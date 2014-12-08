<?php
App::uses('Sanitize', 'Utility');

class SurveysController extends AppController {

 var $name = 'Surveys';

 var $paginate = array(
   'Survey' => array(
     'limit' => 12,
     'page' => 1,
     'group' => 'Survey.id',
     'order'=>array('surveyname'=>'asc'),
     'joins' => array(
       array('table' => 'questions',
         'alias' => 'Question',
         'type' => 'LEFT',
         'conditions' => array(
           'Question.survey_id = Survey.id',
         )
       ),
     ),
   ),
 );
 var $uses = array('Survey', 'Respondent');

 function beforeFilter () {
  parent::beforeFilter();
  $this->Auth->allow(array('getmessages','surveyQuestion','startSurvey','pastRights','pastIntro','answerQuestions','runSurvey','reeditSurvey','createSvgResult','goal','deleteGoal','decision','deleteDecision','information','deleteInformation','saveUserSurvey','userSurvey','stopSurvey','enterGdta'));
 }

 function index() {

  $this->paginate = array('Survey' => array('limit' => 12,'page' => 1,'order'=>array('surveyname'=>'asc'),'recursive' => -1));
  $this->set('surveys', $this->paginate('Survey'));
 }

 function view($id = null) {

  if (!$id) {
   $this->Session->setFlash(__('Invalid survey', true));
   $this->redirect(array('action' => 'index'));
  }
  $this->request->data = $this->Survey->read(null, $id);
  $this->set('survey', $this->request->data);
 }

 private function __clearEditSession ($type = CLEAR_ALL) {
  /*
   * PRIVATE FUNCTION __clearEditSession
  * -----
  * Clears any set session variables that are used for temporary information when
  * editing a survey
  * -----
  * Parameters:
  * $type -> constant that determines what session data should be cleared
  * -----
  * No return value
  */
  if (($type == CLEAR_ALL | $type == CLEAR_QUESTION)) {
   if ($this->Session->check('SurveyQuestion.new')) $this->Session->delete('SurveyQuestion.new');
   if ($this->Session->check('SurveyQuestion.delete')) $this->Session->delete('SurveyQuestion.delete');
   if ($this->Session->check('Survey.id')) $this->Session->delete('Survey.id');
  }
  if  (($type == CLEAR_ALL | $type == CLEAR_RESPONDENT)) {
   if ($this->Session->check('SurveyRespondent.new')) $this->Session->delete('SurveyRespondent.new');
   if ($this->Session->check('SurveyRespondent.delete')) $this->Session->delete('SurveyRespondent.delete');
  }
  if  (($type == CLEAR_ALL | $type == CLEAR_SURVEY)) {
   if ($this->Session->check('Survey.type')) $this->Session->delete('Survey.type');
   if ($this->Session->check('Survey.original')) $this->Session->delete('Survey.original');
   if ($this->Session->check('Survey.progress')) $this->Session->delete('Survey.progress');
   if ($this->Session->check('Survey.answers')) $this->Session->delete('Survey.answers');
   if ($this->Session->check('Survey.hierarcy')) $this->Session->delete('Survey.hierarcy');
   if ($this->Session->check('Survey.mainGoal')) $this->Session->delete('Survey.mainGoal');
   if ($this->Session->check('Survey.respondent')) $this->Session->delete('Survey.respondent');
   if ($this->Session->check('Survey.started')) $this->Session->delete('Survey.started');
   if ($this->Session->check('Survey.id')) $this->Session->delete('Survey.id');
  }
 }

 function add() {
  /*
   * function add
  * -----
  * Ajax only
  * Adds a new survey
  * -----
  * No parameters
  */
  $this->autoRender = false;
  $this->layout = 'ajax';
  if($this->request->is('ajax')) {
   if ($this->request->is('post')) {
    $this->Survey->set($this->request->data);
    if ($this->Survey->save($this->request->data)) {
     $this->Session->setFlash('Survey saved', true, null, 'confirm');
     $this->Survey->recursive = 0;
     $this->set('surveys', $this->paginate());
     $this->render('/Surveys/index');
    } else {
     $err = array_values($this->Survey->invalidFields());
     $this->Session->setFlash('Invalid survey: '.implode($err[0],", "), true, null, 'error');
     $this->Survey->recursive = 0;
     $this->set('surveys', $this->paginate());
     $this->render('/Surveys/index');
    }
   } else {
    $this->render();
   }
  }
 }

 function edit($id = null) {
  /*
   * function edit
  * -----
  * Renders the edit survey view and handles the results
  * -----
  * Parameters:
  * $id -> the database id of the survey to edit
  */
  if (is_null($id) && !empty($this->request->data)) {  // check for an id as long as no form data has been submitted
   $this->Session->setFlash('Invalid survey', true, null, 'error'); // display an error when no valid survey id is given
   $this->redirect(array('action' => 'index'));  // return to the index view
  }
  if (!empty($this->request->data)) {  // check to see if form data has been submitted
   // first assemble the complete survey data including information from the edit session values
   if ($this->Session->check('SurveyQuestion.new')) {  // check for a session for the survey questions
    $tempQuestions = $this->Session->read('SurveyQuestion.new'); // retrieve the questions that have been stored in the session
    //go through each question and set its order value to the same as the current index in the array
    foreach ($tempQuestions as $index => &$quest) {
     $quest['order'] = $index;
    }
    $this->request->data['Question'] = $tempQuestions; // update the form data with the current questions
   }
   if ($this->Session->check('SurveyRespondent.new')) { // check the session for the respondents
    $this->request->data['Respondent'] = $this->Session->read('SurveyRespondent.new'); // update the form data with the current respondents
   }
   $delrespondent = null; // variable to hold respondents to delete (database records only)
   if ($this->Session->check('SurveyRespondent.delete')) { // check the session for respondents to delete
    $delrespondent = $this->Session->read('SurveyRespondent.delete'); // retrieve the respondents to delete
   }
   $delquestion = null; // variable to hold questions to delete (database records only)
   if ($this->Session->check('SurveyQuestion.delete')) { // check the session for questions to delete
    $delquestion = $this->Session->read('SurveyQuestion.delete'); // retrieve the questions to delete
   }
   // now save the survey and return the results
   $errReturn = $this->Survey->complexSave($this->request->data, $delquestion, $delrespondent); // save the combined data, including deletion of survey and respondents that have been dropped
   if (is_null($errReturn)) { // if no errors are returned
    $this->__clearEditSession(); // empty the session variables used for the edit session now that it is complete
    $this->Session->setFlash('The survey has been saved', true, null, 'confirm'); // send a confirmation message that the survey was saved
    $this->redirect(array('action' => 'index')); // redirect to the index view
   } else {
    $this->Session->setFlash($errReturn['message'], true, null, $errReturn['type']); // send error messages received from the model during the save to the view for display
   }
  } else { // if there is no form data, and therefore the edit session is just starting
   $this->Survey->contain(array('Question' => 'Choice', 'Respondent' => 'Response'));
   $this->request->data = $this->Survey->findById($id); // find the survey being edited
   if(!$this->request->data) {
    $this->Session->setFlash('Invalid ID for survey.', true, null, 'error'); // send an error message
    $this->redirect(array('action' => 'index')); // redirect to the index view
   }
   $this->__clearEditSession(); // make sure the session edit variables are empty
   $this->Session->write('Survey.id', $id); // put the survey id in to the session
   $this->Session->write('SurveyQuestion.new', $this->request->data['Question']); // put the original survey questions in to the session
   $this->Session->write('SurveyRespondent.new', $this->request->data['Respondent']); // put the original survey respondents in to the session
  }
 }

 function surveyQuestion($id = null) {
  /*
   * function surveyQuestion
  * -----
  * Ajax only
  * Renders the survey question form and handles the results;
  * adds a new question to the temporary session variable storing questions
  * -----
  * Parameters:
  * $id -> the index in the session array to modify, or if null then a new
  * 	question will be created instead
  */
  $this->autoRender = false;  // turn off autoRender because there is no view named surveyQuestion
  $this->layout = 'ajax';  // use the blank ajax layout
  if($this->request->is('ajax')) {  // only proceed if this is an ajax request
   if (!$this->request->is('post')) {
    if ($id != null) {  // existing question being edited so retrieve it from the session
     if ($this->Session->check('SurveyQuestion.new')) {
      $tempData = $this->Session->read('SurveyQuestion.new');
      $this->set('question_index', $id);
      $question = $tempData[$id];
      $question['Choice']['value'] = $this->Survey->Question->Choice->CombineChoices($question['Choice']);
      $this->request->data['Question'] = $question;  // send the existing question to the view
     }
    }
    $this->render('/Elements/question_form');
   } else {  // returning with data from the form here
    $tempArr = null;
    if ($this->Session->check('SurveyQuestion.new')) {
     $tempArr = $this->Session->read('SurveyQuestion.new');
    }
    $this->request->data['Question']['Choice'] = $this->Survey->Question->Choice->SplitChoices($this->request->data['Question']['Choice']['value']);
    $this->Survey->Question->set($this->request->data);
    $checkfieldsArr = $this->Survey->Question->schema();
    unset($checkfieldsArr['id']);
    unset($checkfieldsArr['survey_id']);
    unset($checkfieldsArr['order']);
    $checkfields = array_keys($checkfieldsArr);
    if ($this->Survey->Question->validates(array('fieldList' => $checkfields))) {
     if (is_null($id)) {
      $tempArr[] = $this->request->data['Question'];
     } else {
      $tempArr[$id] = $this->request->data['Question'];
     }
     $this->Session->write('SurveyQuestion.new',$tempArr);
    } else {
     $errors = $this->Survey->Question->invalidFields();
     $this->Session->setFlash('Invalid question: '.$errors['question'][0], true, null, 'error');
    }
    $this->set('questions', $tempArr);
    $this->layout = 'ajax';
    $this->render('/Elements/manage_questions');
   }
  }
 }

 function deleteQuestion($idQuestion = null)
 {
  /*
   * function deleteQuestion
  * -----
  * Ajax only
  * Deletes a question from the temporary session data and if
  * the question exists then retreives its real database id and
  * stores it in the session array for question deletion
  * -----
  * Parameters:
  * $idQuestion -> the index in the session array to remove
  */
  $this->autoRender = false;  // turn off autoRender because there is no deleteQuestions view
  if($this->request->is('ajax')) {  // only process ajax requests
   $tempArr = null;
   if ($this->Session->check('SurveyQuestion.new')) {  //check for the session variable containing questions for this survey
    $tempArr = $this->Session->read('SurveyQuestion.new');  // get temp working copy of questions
    if (isset($tempArr[$idQuestion]['id']) && ($tempArr[$idQuestion]['id'] != '')) {  // questions that have been written to the database will have an id set
     $delArr = array();
     if ($this->Session->check('SurveyQuestion.delete')) {  // get a copy of any questions already marked for deletion
      $delArr = $this->Session->read('SurveyQuestion.delete');
     }
     $delArr[] = $tempArr[$idQuestion]['id'];  //  add our question to the array for deletion from the database
     $this->Session->write('SurveyQuestion.delete',$delArr);  // write to the session
    }
    unset($tempArr[$idQuestion]);  // remove the question from the temporary question array
   }
   $this->Session->write('SurveyQuestion.new',$tempArr);  // write the new question array to the session
   $this->set('questions', $tempArr);  // send the questions to the view
   $this->layout = 'ajax';  // use the blank ajax layout
   $this->render('/Elements/manage_questions');  // send the element to the receiving element in the view
  }
 }

 function upQuestion($id = null) {
  /*
   * function upQuestion
  * -----
  * Ajax only
  * moves a question up in the list of questions
  * -----
  * Parameters:
  * $id -> the index in the session array to modify
  */
  $this->autoRender = false;  // turn off autoRender because there is no deleteQuestions view
  if($this->request->is('ajax')) {
   // only process ajax requests
   $tempArr = null;
   if ($this->Session->check('SurveyQuestion.new')) {
    //check for the session variable containing questions for this survey
    $tempArr = $this->Session->read('SurveyQuestion.new');  // get temp working copy of questions
    $swapone = $tempArr[$id];
    $swaptwo = $tempArr[$id-1];
    $tempArr[$id-1] = $swapone;
    $tempArr[$id] = $swaptwo;
   }
   $this->Session->write('SurveyQuestion.new',$tempArr);  // write the new question array to the session
   $this->set('questions', $tempArr);  // send the questions to the view
   $this->layout = 'ajax';  // use the blank ajax layout
   $this->render('/Elements/manage_questions');  // send the element to the receiving element in the view
  }
 }

 function downQuestion($id = null) {
  /*
   * function downQuestion
  * -----
  * Ajax only
  * moves a question down in the list of questions
  * -----
  * Parameters:
  * $id -> the index in the session array to modify
  */
  $this->autoRender = false;  // turn off autoRender because there is no deleteQuestions view
  if($this->request->is('ajax')) {
   // only process ajax requests
   $tempArr = null;
   if ($this->Session->check('SurveyQuestion.new')) {
    //check for the session variable containing questions for this survey
    $tempArr = $this->Session->read('SurveyQuestion.new');  // get temp working copy of questions
    $swapone = $tempArr[$id];
    $swaptwo = $tempArr[$id+1];
    $tempArr[$id+1] = $swapone;
    $tempArr[$id] = $swaptwo;
   }
   $this->Session->write('SurveyQuestion.new',$tempArr);  // write the new question array to the session
   $this->set('questions', $tempArr);  // send the questions to the view
   $this->layout = 'ajax';  // use the blank ajax layout
   $this->render('/Elements/manage_questions');  // send the element to the receiving element in the view
  }
 }

 function surveyRespondent($id = null) {
  /*
   * function surveyRespondent
  * -----
  * Ajax only
  * Renders the survey respondent form and handles the results;
  * adds a new respondent to the temporary session variable storing respondents
  * -----
  * Parameters:
  * $id -> the index in the session array to modify, or if null then a new
  * 	question will be created instead
  */
  $this->autoRender = false;  // turn off autoRender because there is no view named surveyRespondent
  $this->layout = 'ajax';  // use the blank ajax layout
  if($this->request->is('ajax')) {
   // only proceed if this is an ajax request
   if (!$this->request->is('post')) {  // check to see if there is any form data
    if ($id != null) { // if there is no form data, check if an id was passed (if there is an id, then the user is editing and not creating)
     if ($this->Session->check('SurveyRespondent.new')) {  // look for the session record of the respondents
      $tempData = $this->Session->read('SurveyRespondent.new'); // retrieve the session record of respondents
      $this->set('respondent_index', $id); // include the session array index for the respondent in the view
      $this->request->data['Respondent'] = $tempData[$id]; // send the values of the respondent being edited to the view
     }
    }
    $this->render('/Elements/respondent_form');  // render the ajax form element
   } else {  // handle form data
    $tempArr = null; // temporary array to hold session values
    if ($this->Session->check('SurveyRespondent.new')) { // check for existing session values
     $tempArr = $this->Session->read('SurveyRespondent.new'); // read the respondent data from the session
    }
    if (!isset($this->request->data['Respondent']['survey_id'])) {  // check to see if the respondent's foreign key is set
     $this->request->data['Respondent']['survey_id'] = $this->Session->read('Survey.id'); // put the survey id in the foreign key of the respondent
    }
    /*if (!isset($this->request->data['Respondent']['started'])) {	// check to see if the respondent has a started datetime set
     $this->request->data['Respondent']['started'] = null; // put the empty "started" key in place
    }
    if (!isset($this->request->data['Respondent']['finished'])) {  // check to see if the respondent has a finished datetime set
    $this->request->data['Respondent']['finished'] = null; // put the empty "finished" key in place
    }*/
    $this->Survey->Respondent->set($this->request->data);  // set the model data to the current form data
    $checkfieldsArr = $this->Survey->Respondent->schema();  // read the model schema to use for setting custom validation procedures
    unset($checkfieldsArr['id']);  // remove the id field for validation
    unset($checkfieldsArr['survey_id']);  // remove the survey_id from the validation criteria
    unset($checkfieldsArr['guid']);  // remove the guid from the validation criteria
    $checkfields = array_keys($checkfieldsArr);  // pull the keys only from the modified schema to create an array of validation fields
    if ($this->Survey->Respondent->validates(array('fieldList' => $checkfields))) {  // perform modified validation
     if (is_null($id)) {
      $this->request->data['Respondent']['url'] = 'Not available until after changes saved';
      $tempArr[] = $this->request->data['Respondent'];  // new respondent
     } else {
      $tempArr[$id] = $this->request->data['Respondent']; // editing an existing respondent
     }
     $this->Session->write('SurveyRespondent.new',$tempArr);
    } else {
     $this->Session->setFlash('Invalid respondent: '.implode($this->Survey->Respondent->invalidFields(),", "), true, null, 'error');
    }
    $this->set('respondents', $tempArr);
    $this->layout = 'ajax';
    $this->render('/Elements/manage_respondents');
   }
  }
 }

 function deleteRespondent($idRespondent = null)
 {
  /*
   * function deleteRespondent
  * -----
  * Ajax only
  * Deletes a respondent from the temporary session data and if
  * the respondent exists then retreives its real database id and
  * stores it in the session array for respondent deletion
  * -----
  * Parameters:
  * $idRespondent -> the index in the session array to remove
  */
  $this->autoRender = false;  // turn off autoRender because there is no deleteRespondent view
  if($this->request->is('ajax')) {
   // only process ajax requests
   $tempArr = null;
   if ($this->Session->check('SurveyRespondent.new')) {
    //check for the session variable containing respondents for this survey
    $tempArr = $this->Session->read('SurveyRespondent.new');  // get temp working copy of respondents
    if (isset($tempArr[$idRespondent]['id']) && ($tempArr[$idRespondent]['id'] != '')) {
     // respondents that have been written to the database will have an id set
     $delArr = array();
     if ($this->Session->check('SurveyRespondent.delete')) {
      // get a copy of any respondents already marked for deletion
      $delArr = $this->Session->read('SurveyRespondent.delete');
     }
     $delArr[] = $tempArr[$idRespondent]['id'];  //  add our respondent to the array for deletion from the database
     $this->Session->write('SurveyRespondent.delete',$delArr);  // write to the session
    }
    unset($tempArr[$idRespondent]);  // remove the respondent from the temporary respondent array
   }
   $this->Session->write('SurveyRespondent.new',$tempArr);  // write the new respondent array to the session
   $this->set('respondents', $tempArr);  // send the respondents to the view
   $this->layout = 'ajax';  // use the blank ajax layout
   $this->render('/Elements/manage_respondents');  // send the element to the receiving element in the view
  }
 }

 function delete($id = null) {
  /*
   * function delete
  * -----
  * Deletes a survey
  * -----
  * Parameters:
  * $id -> the id of the survey to delete
  */
  if (is_null($id)) {  // check for a value in the id variable
   $this->Session->setFlash('Invalid id for survey', true, null, "error");  //  send an error message
   $this->redirect(array('action'=>'index'));  // go back to the index action
  }
  $i = $this->Survey->Respondent->Response->find('count', array('conditions' => array('survey_id' => $id)));
  if ($i > 0) {
   $this->Session->setFlash('A survey that has responses cannot be deleted.  Please delete all survey responses first.', true, null, "error");  //  send an error message
   $this->redirect(array('action'=>'index'));  // go back to the index action
  }
  if ($this->Survey->delete($id)) {  // delete the survey and return true  if successful
   $this->Session->setFlash('Survey deleted', true, null, "confirm");  // send a confirmation message
   $this->redirect(array('action'=>'index'));  // go back to the index view
  }
  // continue when the delete function returns false
  $this->Session->setFlash('Survey was not deleted', true, null, "error");  // send an error message
  $this->redirect(array('action' => 'index'));  //  go back to the index view
 }

 function pastIntro() {
  /*
   * function pastIntro
  * -----
  * Runs when a user continues past the intro page
  * -----
  * No parameters
  */
  // move progress forward and redirect to the runSurvey action
  $this->autoRender = false;  // turn off autoRender
  $this->Session->write('Survey.progress', RIGHTS);
  $this->redirect(array('action' => 'runSurvey'));

 }

 function pastRights() {
  /*
   * function pastRights
  * -----
  * Handles a response to the survey rights page
  * -----
  * No parameters
  */
  // move progress forward and redirect to the runSurvey action
  $this->autoRender = false;  // turn off autoRender
  $this->Session->write('Survey.progress', QUESTIONS);
  $this->redirect(array('controller' => 'surveys', 'action' => 'runSurvey'));
 }

 function goal($indexObjective = null) {
  /*
   * function goal
  * -----
  * Ajax only
  * Adds a new sub-goal
  * -----
  * $indexObjective -> the index in the session of the goal that is being edited, or null if it is new
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {  // only proceed if this is an ajax request
   if (!$this->request->is('post')) {  // no data, so this is a fresh form
    $this->set("goal_index", $indexObjective); // send the index of the goal to the view}
    if (!is_null($indexObjective)) {
     $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
     $this->request->data['Objective'] = $tempArr[$indexObjective];
    }
    $this->render('/Elements/goal_form');
   } else {
    $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
    $this->request->data['Objective']['objective'] = strip_tags($this->request->data['Objective']['objective']); // remove any HTML tags that the user might have entered
    $this->Survey->Respondent->Response->Objective->set($this->request->data);  //put data in to an Objective object
    if ($this->Survey->Respondent->Response->Objective->validates()) {  //use the method of the Objective object to validate the data
     if (is_null($indexObjective)) {
      // new goal
      $this->request->data['Objective']['Decision'] = array();  //  create an empty array for decisions
      $tempArr[] = $this->request->data['Objective']; // add the new goal
     } else {
      $tempArr[$indexObjective]['objective'] = $this->request->data['Objective']['objective']; // update the decision
     }
    } else {
     $errors = $this->Survey->Respondent->Response->Objective->invalidFields();
     $this->Session->setFlash('Invalid goal: '.$errors['objective'][0], true, null, 'error');  // send an error to the view
    }
    $this->Session->write('Survey.hierarcy', $tempArr);  // write the new hierarchy to the session
    $this->set("hierarchy", $tempArr); // send the hierarchy to the view
    $this->render('/Elements/edit_hierarchy');  // re-display the hierarchy view
   }
  }
 }

 function deleteGoal($id = null) {
  /*
   * function deleteGoal
  * -----
  * Ajax only
  * Deletes a sub-goal from the hierarchy
  * -----
  * $id -> id of the goal to delete
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {
   $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
   if (!is_null($id)) {
    unset($tempArr[$id]);
    $this->Session->write('Survey.hierarcy', $tempArr);  // write the new hierarchy to the session
   }
   $this->set("hierarchy", $tempArr); // send the hierarchy to the view
   $this->render('/Elements/edit_hierarchy');  // re-display the hierarchy view
  }
 }

 function decision($indexObjective = null, $indexDecision = null) {
  /*
   * function decision
  * -----
  * Ajax only
  * Adds a new decision to a goal
  * -----
  * $indexObjective -> the index in the session of the goal that the objective is being added to
  * $indexDecision -> the index in the session of the decision that is being edited or null for a new one
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {
   // only proceed if this is an ajax request
   if (!$this->request->is('post')) {
    // no data, so this is a fresh form
    $this->set("indexObjective", $indexObjective); // send the index of the goal to the view
    if (!is_null($indexDecision)) {
     $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
     $this->request->data['Decision'] = $tempArr[$indexObjective]['Decision'][$indexDecision];
    }
    $this->set("decision_index", $indexDecision);  // send the index of the decision to the view
    $this->render('/Elements/decision_form');
   } else {
    $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
    $this->request->data['Decision']['decision'] = strip_tags($this->request->data['Decision']['decision']); // remove any HTML tags that the user might have entered
    $this->Survey->Respondent->Response->Objective->Decision->set($this->request->data);
    if ($this->Survey->Respondent->Response->Objective->Decision->validates()) {
     if (is_null($indexDecision)) {
      // new decision
      $this->request->data['Decision']['Information'] = array();
      $tempArr[$indexObjective]['Decision'][] = $this->request->data['Decision']; // add the new decision
     } else {
      $tempArr[$indexObjective]['Decision'][$indexDecision]['decision'] = $this->request->data['Decision']['decision']; // update the decision
     }
    } else {
     $errors = $this->Survey->Respondent->Response->Objective->Decision->invalidFields();
     $this->Session->setFlash('Invalid decision: '.$errors['decision'][0], true, null, 'error');  // send an error to the view
    }
    $this->Session->write('Survey.hierarcy', $tempArr);
    $this->set("goalIndex", $indexObjective);
    $this->set("decisions", $tempArr[$indexObjective]['Decision']); // send the hierarchy to the view
    $this->render('/Elements/edit_decisions');  // re-display the hierarchy view
   }
  }
 }

 function deleteDecision($idGoal = null, $idDec = null) {
  /*
   * function deleteDecision
  * -----
  * Ajax only
  * Deletes a decision from the hierarchy
  * -----
  * $idGoal -> if of the goal holding the decision
  * $idDec -> id of the decision to delete
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {
   $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
   if (!is_null($idGoal) && !is_null($idDec)) {
    unset($tempArr[$idGoal]['Decision'][$idDec]);
    $this->Session->write('Survey.hierarcy', $tempArr);  // write the new hierarchy to the session
   }
   $this->set("hierarchy", $tempArr); // send the hierarchy to the view
   $this->render('/Elements/edit_hierarchy');  // re-display the hierarchy view
  }
 }

 function information($indexObjective = null, $indexDecision = null, $indexInfo = null) {
  /*
   * function information
  * -----
  * Ajax only
  * Adds a new piece of information to a decision
  * -----
  * $indexObjective -> the index in the session of the goal that the objective to which the information is being added to belongs
  * $indexDecision -> the index in the session of the decision to which the information is being added
  * $indexInfo -> the index in the session of the information being edited (this is null if creating a new piece of information)
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {
   // only proceed if this is an ajax request
   if (!$this->request->is('post')) {
    // no data, so this is a fresh form
    $this->set("indexObjective", $indexObjective); // send the index of the goal to the view
    $this->set("indexDecision", $indexDecision); // send the index of the decision to the view
    if (!is_null($indexInfo)) {
     $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy // get the current hierarchy
     $this->request->data['Information'] = $tempArr[$indexObjective]['Decision'][$indexDecision]['Information'][$indexInfo];
    }
    $this->set("information_index", $indexInfo); // send the index of the decision to the view
    $this->render('/Elements/information_form');
   } else {
    $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
    $this->request->data['Information']['information'] = strip_tags($this->request->data['Information']['information']); // remove any HTML tags that the user might have entered
    $this->Survey->Respondent->Response->Objective->Decision->Information->set($this->request->data);
    if ($this->Survey->Respondent->Response->Objective->Decision->Information->validates()) {
     if (is_null($indexInfo)) { // new information
      $tempArr[$indexObjective]['Decision'][$indexDecision]['Information'][] = $this->request->data['Information']; // add the new information
     } else {
      $tempArr[$indexObjective]['Decision'][$indexDecision]['Information'][$indexInfo] = $this->request->data['Information']; // add the new information
     }
    } else {
     $errors = $this->Survey->Respondent->Response->Objective->Decision->Information->invalidFields();
     $this->Session->setFlash('Invalid information: '.$errors['information'][0], true, null, 'error');  // send an error to the view
    }
    $this->Session->write('Survey.hierarcy', $tempArr);
    $this->set("goalIndex", $indexObjective);
    $this->set("decisionIndex", $indexDecision);
    $this->set("information", $tempArr[$indexObjective]['Decision'][$indexDecision]['Information']); // send the hierarchy to the view
    $this->render('/Elements/edit_information');  // re-display the hierarchy view
   }
  }
 }

 function deleteInformation($idGoal = null, $idDec = null, $idInfo = null) {
  /*
   * function deleteInformation
  * -----
  * Ajax only
  * Deletes a piece of information from the test hierarchy
  * -----
  * $idGoal -> if of the goal holding the decision and information
  * $idDec -> id of the decision holding the information
  * $idInfo -> id of the information
  */
  $this->autoRender = false;  // turn off autoRender
  $this->layout = 'ajax';  // use the empty ajax layout
  if($this->request->is('ajax')) {
   $tempArr = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // temporary array to use for the hierarchy
   if (!is_null($idGoal) && !is_null($idDec) && !is_null($idInfo)) {
    unset($tempArr[$idGoal]['Decision'][$idDec]['Information'][$idInfo]);
    $this->Session->write('Survey.hierarcy', $tempArr);  // write the new hierarchy to the session
   }
   $this->set("hierarchy", $tempArr); // send the hierarchy to the view
   $this->render('/Elements/edit_hierarchy');  // re-display the hierarchy view
  }
 }

 function runSurvey() {
  // base branch function for a survey
  $this->autoRender = false;  // turn off autoRender
  $this->response->disableCache(); // do not cache the page
  $type = $this->Session->read('Survey.type');
  switch ($type) {
   case 'user':
    if (array_key_exists('respondentGUID', $this->request->named)) { // check to see if this is a new survey
     $respondentGUID = $this->request->named['respondentGUID'];
     if (!$this->Session->check('Survey.progress')) {
      $respondentId = $this->Survey->Respondent->getIdByGuid($respondentGUID);
      $i = $this->Survey->Respondent->Response->find('count', array('conditions' => array('respondent_id' => $respondentId))); // check to see if a responds has already been provided
      if ($i > 0) {
       $currentResults = $this->Survey->Respondent->Response->getLatestResponse($respondentId);
       if (isset($currentResults['Hierarchy'])) {
        $this->Session->write('Survey.hierarcy', $currentResults['Hierarchy']);
       }
       if (isset($currentResults['Response']['maingoal'])) {
        $this->Session->write('Survey.mainGoal', $currentResults['Response']['maingoal']);
       }
       if (isset($currentResults['Answer'])) {
        $this->Session->write('Survey.answers', $currentResults['Answer']);
       }
      }
      $this->Session->write('Survey.type', 'user');
      $this->Session->write('Survey.started', date("Y-m-d H:i:s"));
      // need to read the user information here, verify that it is a valid user for this survey, and add the info to the session
      $this->request->data = $this->Survey->Respondent->read(null, $respondentId); // read the respondent
      $id = $this->request->data['Respondent']['survey_id']; // get the ID of the survey being taken
      $this->Session->write('Survey.respondent', $respondentId);
      // id will only be set when a survey is just starting, so check for id
      $this->Survey->contain(array('Question' => 'Choice'));
      $this->Session->write('Survey.original', $this->Survey->getFullSurvey($id));
      $this->Session->write('Survey.progress', INTRO); // set the initial progress phase here
      $this->Session->write('Survey.id', $id); // set the survey id
     }
    }
    break;
   case 'test':
    if ($this->Session->check('Auth.User')) {
     if (array_key_exists('surveyId', $this->request->named)) { // check to see if this is a new survey
      $surveyId = $this->request->named['surveyId'];
      if (!is_null($surveyId) && !$this->Session->check('Survey.progress')) {
       $this->Survey->contain(array('Question' => 'Choice')); // get the survey data
       $this->Session->write('Survey.original', $this->Survey->getFullSurvey($surveyId));  // write the survey data to the session
       $this->Session->write('Survey.progress', INTRO); // set the initial progress phase here
       $this->Session->write('Survey.id', $surveyId); // set the survey id in the session
      }
     }
     break;
    }
   default:
    die('Error - survey type not in session.  Session may have timed out.  Reuse your original link to start a new session.');
    break;
  }
  if (!$this->Session->check('Survey.progress')) { // check the current survey progress
   //make sure the correct session variables are present in order to continue
   $this->Session->setFlash(__('Error with survey', true, null, "warning"));  // send a warning
   if ($this->referer() != '/') {
    // if we have a reference that isn't root
    $this->redirect($this->referer());  // go to that original page
   } else {
    $this->redirect(array('action' => 'index'));  // otherwise go back to the survey index
   }
  }
  $progress = $this->Session->read('Survey.progress');  // get the current progress from the session
  // direct to the correct step based on the progress variable in the session
  switch ($progress) {
   case INTRO:
    array_key_exists('intropage', $this->request->named) ? $introstep = $this->request->named['intropage'] : $introstep = 0;
    if ($introstep < 4) {
     $introstep++;
     $this->layout = 'intro';  // use the intro layout
     $this->set('intropage', $introstep);
     $this->render('/Elements/intro');
     break;
    }
   case RIGHTS:
    $this->layout = 'rights';  // use the rights layout
    $id = $this->Session->read('Survey.id');  // get the survey id the session
    $consent = $this->Survey->field('consent', array('id' => $id)); // retrieve the informed consent information
    $this->set('consent', $consent); // send the custome consent form to the view
    $this->render('/Elements/rights');
    break;
   case QUESTIONS:
    $this->redirect(array('controller' => 'surveys', 'action' => 'answerQuestions')); // send to the question action
    break;
   case GDTA_ENTRY:
    $this->redirect(array('action' => 'enterGdta')); // send to the GDTA portion
    break;
   case SUMMARY:
    $this->redirect(array('action' => 'createSvgResult')); // send to the GDTA summary
    break;
  }
 }

 function startSurvey($respondent = null) {
  /*
   * function startSurvey
  * -----
  * Start a user survey
  * -----
  * Parameters:
  * $respondent -> the guid of the survey respondent
  */
  $this->autoRender = false;  // turn off autoRender because we need total control over what view to render here
  $this->__clearEditSession(CLEAR_SURVEY); // clear any previous session variables
  $this->Session->write('Survey.type', 'user'); // write the survey type to the session
  $this->redirect(array('action' => 'runSurvey', 'respondentGUID' => $respondent)); // send to the main survey routine
 }

 function testSurvey($id = null) {
  /*
   * function testSurvey
  * -----
  * Start a test survey
  * -----
  * Parameters:
  * $id -> the id of the survey to test
  */
  $this->autoRender = false;  // turn off autoRender because we need total control over what view to render here
  $this->__clearEditSession(CLEAR_SURVEY); // clear any previous session variables
  $this->Session->write('Survey.type', 'test'); // write the survey type to the session
  $this->redirect(array('action' => 'runSurvey', 'surveyId' => $id)); // send to the main survey routine
 }

 function userSurvey() {
  /*
   * function userSurvey
  * -----
  * Manages a run through a survey
  * -----
  * Parameters:
  * none
  */
  $this->autoRender = false;  // turn off autoRender because we need total control over what view to render here
  if (!$this->Session->check('Survey.progress')) {
   //make sure the correct session variables are present in order to continue
   $this->Session->setFlash(__('Error with survey', true, null, "warning"));  // send a warning
   if ($this->referer() != '/') {
    // if we have a reference that isn't root
    $this->redirect($this->referer());  // go to that original page
   } else {
    $this->redirect(array('action' => 'index'));  // otherwise go back to the survey index
   }
  }
  $progress = $this->Session->read('Survey.progress');  // get the current progress from the session
  // logic for the survey process starts here
  switch ($progress) {
   case INTRO:
    $this->layout = 'intro';  // use the intro layout
    $this->render('/Elements/intro');
    break;
   case RIGHTS:
    $this->layout = 'rights';  // use the more rights layout
    $id = $this->Session->read('Survey.id');  // get the survey id the session
    $consent = $this->Survey->field('consent', array('id' => $id)); // retrieve the informed consent information
    $this->set('consent', $consent);
    $this->render('/Elements/rights');
    break;
   case QUESTIONS:
    $this->redirect(array('action' => 'answerQuestions')); // send to the question action
    break;
   case GDTA_ENTRY:
    $this->redirect(array('action' => 'enterGdta')); // send to the GDTA portion
    break;
   case SUMMARY:
    $this->redirect(array('action' => 'createSvgResult')); // send to the GDTA summary
    break;
  }
 }

 function answerQuestions() {
  /*
   * function answerQuestions
  * -----
  * Operates the static question portion of the survey
  * -----
  * No parameters
  */
  $this->layout = 'survey';  // use the more basic survey layout
  if (!$this->request->is('post')) {
   // if there is no data then show the initial view
   $survey = $this->Session->read('Survey.original');  // get the survey being used
   if (empty($survey['Question'])) {  // if there are no questions then we don't need to show the question form at all
    $this->Session->write('Survey.progress', GDTA_ENTRY);  // move progress forward and redirect to the runSurvey action
    $this->redirect(array('action' => 'runSurvey'));
   }
   $questions = $survey['Question'];
   $answers = ($this->Session->check('Survey.answers')) ?  $this->Session->read('Survey.answers') : array(); // check to see if there are already answers in the session
   $choices = array(); // gather choices here keyed to each question id
   foreach ($questions as &$q) {  // go through each question and look to see if there is an answer for it
    $checkId = $q['id'];
    $choices[$checkId] = array();
    if (isset($q['Choice'])) {
     foreach ($q['Choice'] as $choice) {
      $choices[$checkId][$choice['id']] = $choice['value'];
     }
    }
    foreach ($answers as $a) {
     if ($a['question_id'] == $checkId) {
      if ($q['type'] == MULTI_SELECT) {
       $q['answer'] = Set::extract('/id',$a['answer']);
      } else {
       $q['answer'] = $a['answer'];
      }
      break;
     }
    }
   }
   $this->set('questions', $questions);  // send questions to the view
   $this->set('choices', $choices);  // send choices for questions to the view, ordered for form elements
  } else {  // we have form data so process it here
   if (isset($this->request->data['Answer'])) {
    // make sure we have answers in the data set
    $this->Session->write('Survey.answers', $this->request->data['Answer']);  // write the answers to the session
   }
   $this->Session->write('Survey.progress', GDTA_ENTRY);  // move progress forward and redirect to the runSurvey action
   $this->redirect(array('action' => 'runSurvey'));
  }
 }

 function enterGdta() {
  /*
   * function enterGdta
  * -----
  * Displays the GDTA entry form and handles the results
  * -----
  * No parameters
  */
  $this->layout = 'survey';  // use the survey layout
  if (!$this->request->is('post')) {
   // if there is no data then setup an initial view
   $this->request->data['Survey']['mainGoal'] = $this->Session->check('Survey.mainGoal') ? $this->Session->read('Survey.mainGoal') : ''; // set the main goal if it exists in the session, otherwise make it blank
   $this->set("hierarchy", ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array());  // setup a blank array or use the session variable if it exists
  } else {  // GDTA entry complete so move forward
   $goal = $this->request->data['Survey']['mainGoal'];
   $this->Session->write('Survey.mainGoal', $goal);
   $this->Session->write('Survey.progress', SUMMARY);
   $this->redirect(array('action' => 'runSurvey'));
  }
 }

 function reeditSurvey() {
  /*
   * function reeditUserSurvey
  * -----
  * Returns the user to the survey edit screen
  * -----
  * No parameters
  */
  $this->layout = 'survey';  // use the more basic survey layout
  $this->Session->write('Survey.progress', GDTA_ENTRY);  // change the progress back to GDTA entry
  $this->redirect(array('action' => 'enterGdta'));
 }

 function createSvgResult() {
  /*
   * function createSvgResult
  * -----
  * Creates an SVG file from a session containing GDTA responses for a user
  * -----
  */
  $this->layout = 'svglayout';  // use the svg layout
  $gdtaGoals = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // the hierarchy from the session
  $gdtaMainGoal = ($this->Session->check('Survey.mainGoal')) ? $this->Session->read('Survey.mainGoal') : ''; // the main goal from the session
  $type = ($this->Session->check('Survey.type')) ? $this->Session->read('Survey.type') : ''; // the type of survey being taken 'test' or 'user'
  $this->set('gdtaGoals', $gdtaGoals);
  $this->set('gdtaMainGoal', $gdtaMainGoal);
  $this->set('surveyType', $type);
 }

 function saveUserSurvey() {
  /*
   * function saveUserSurvey
  * -----
  * Saves the users answer to the database as a completed survey
  * -----
  * No parameters
  */
  $result = array(); // an array to hold the survey results
  if (!$this->Session->check('Survey.respondent')) {
   die('No respondent ID');
  } else {
   $respondentid = $this->Session->read('Survey.respondent');
   $i = $this->Survey->Respondent->find('count', array(
     'conditions' => array('Respondent.id' => $respondentid)
   ));
   if ($i !== 1) {
    die('Respondent not valid'.$i.' rid '.$respondentid);
   }
  }
  $data = array('Response' => array());  // a blank array to build our data for saving
  $answers = ($this->Session->check('Survey.answers')) ? $this->Session->read('Survey.answers') : array(); // get the answers to the questions
  $gdta = ($this->Session->check('Survey.hierarcy')) ? $this->Session->read('Survey.hierarcy') : array(); // the hierarchy from the session
  $mainGoal = ($this->Session->check('Survey.mainGoal')) ? $this->Session->read('Survey.mainGoal') : ''; // the main goal from the session
  $started = ($this->Session->check('Survey.started')) ? $this->Session->read('Survey.started') : ''; // the start time from the session
  $finished = date("Y-m-d H:i:s");  // get the time that the survey was finished in MySQL DATETIME format
  $data['Response']['maingoal'] = $mainGoal;
  $data['Response']['respondent_id'] = $respondentid;
  $data['Response']['started'] = $started;
  $data['Response']['finished'] = $finished;
  $data['Answer'] = $this->Survey->Question->formatAnswersForSave($answers);
  $data['Objective'] = $gdta;
  $this->Survey->Respondent->Response->save($data);
  $data['Response']['id'] = $this->Survey->Respondent->Response->id;
  $this->Survey->Respondent->Response->saveAssociated($data,array('deep' => true));
  $this->__clearEditSession(CLEAR_SURVEY);  // delete the temporary session values associated with the survey
  $this->render('/Pages/completed', 'survey');
 }

 function stopSurvey() {
  /*
   * function stopSurvey
  * -----
  * Terminates a survey session
  * -----
  * No parameters
  */
  $this->autoRender = false;
  $type = ($this->Session->check('Survey.type')) ? $this->Session->read('Survey.type') : ''; // the type of survey being taken 'test' or 'user'
  $this->__clearEditSession(CLEAR_SURVEY);  // delete the temporary session values associated with the survey
  switch ($type) {
   case 'test':
    $this->redirect(array('action' => 'index'));
    break;
  }
  $this->render('/Pages/stopped', 'survey');
 }
}
