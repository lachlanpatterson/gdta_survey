<?php
class Survey extends AppModel {
 var $name = 'Survey';
 var $validate = array(
   'id' => array(
     'blank' => array(
       'rule' => array('blank'),
       'message' => 'Invalid value - manual entry of ID not allowed',
       'allowEmpty' => true,
       'required' => false,
       'on' => 'create',
     ),
   ),
   'surveyname' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'Survey name cannot be blank',
       'allowEmpty' => false,
       'required' => true,
     ),
     'unique' => array(
       'rule' => 'isUnique',
       'message' => 'Duplicate survey names are not allowed'
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $hasMany = array(
   'Question' => array(
     'className' => 'Question',
     'foreignKey' => 'survey_id',
     'dependent' => true,
     'conditions' => '',
     'fields' => '',
     'order' => 'Question.order',
     'limit' => '',
     'offset' => '',
     'exclusive' => '',
     'finderQuery' => '',
     'counterQuery' => ''
   ),
   'Respondent' => array(
     'className' => 'Respondent',
     'foreignKey' => 'survey_id',
     'dependent' => true,
     'conditions' => '',
     'fields' => '',
     'order' => '',
     'limit' => '',
     'offset' => '',
     'exclusive' => '',
     'finderQuery' => '',
     'counterQuery' => ''
   )
 );

 function beforeValidate() {

  $this->data['Survey']['surveyname'] = trim($this->data['Survey']['surveyname']); // remove unnecessary spaces from the survey name
 }

 function complexSave ($allData = null, $questionDelete = null, $repondentDelete = null) {
  /*
   * function complexSave
  * -----
  * Processes the survey, questions, and respondents deletions
  * 	for a survey save action
  * -----
  * Parameters:
  * $allData -> The survey data in the format:
  * 		array (
    * 			['Survey'] => array (
      * 				[field1] => [value1],
      * 				...
      * 			),
    * 			[Question] => array (
      *				[0] => array (
        *					[field1] => [value1],
        *					...
        *					['Choice'] => array (
          *						[field1] => [value1],
          * 						...
          * 					),
        *				),
      *				...
      * 			),
    * 			[Respondent] => array (
      * 				[0] => array (
        * 					[field1] => [value1],
        * 					...
        * 				),
      * 				...
      * 			)
    * 		)
  * $questionDelete -> simple array of database Id values to delete
  * 	from the question table e.g. array(54, 654, 2, 543 ...)
  * $respondentDelete -> simple array of database Id values to delete
  * 	from the respondent table e.g. array(54, 654, 2, 543 ...)
  * -----
  * Returns:
  * Null if there is no error
  * An array with key 'messsage' with an error message and key 'type' with
  * 	a type of either warning or error to indicate an error occured
  */
  if (!is_null($allData)) {
  // only proceed if there is data
  $errors = null; // variable to collect any errors that occur
  if ($this->save($allData)) {
   // save the base survey data
   $err = false; // boolean value to flag if there are errors from this point on
   $errors['message'] = ''; // set an empty key in the errors array
   if (!is_null($questionDelete)) {
    // check to see if there are any questions to delete from the database
    foreach ($questionDelete as $questionDeleteId) {
     // go through each question to delete and attempt to delete it
     if ($this->Question->countAnswersByQuestionId($questionDeleteId)) {
      $err = true; // there are answers for this question, so it shouldn't be deleted
      $errors['message'] .= 'Error deleting question. Cannot delete a question that has answers stored. ';
     } else {
      if (!$this->Question->delete($questionDeleteId, true)) {
       // delete the question
       $err = true; // if there was an error during deletion, turn on the flag
       $errors['message'] .= 'Error deleting question. '; // send an error message
      }
     }
    }
   }
   if (isset($allData['Question'])) {
    // look to see if any questions were included in the survey data
    foreach ($allData['Question'] as $question) {
     // iterate through the questions
     if (!isset($question['survey_id'])) {
      // check to see if the question's foreign key is set for the survey
      $question['survey_id'] = $this->id; // set the foreign key if it is not set
     }
     if (!isset($question['answerCount'])) {
      $question['answerCount'] = 0;
     }
     if (isset($question['id'])) {
      // id will not be set if it is a new question
      $this->Question->set($question); // use "set" for existing questions
     } else {
      $this->Question->create($question); // create new questions in order to get id for new questions
     }
     // reformat choices in to an appropriate array and return true if changes have been made
     $choiceChange = $this->Question->reconcileChoices(); // determine if any choices have changed for the question
     //$i = $this->Question->Answer->find('count', array('conditions' => array('question_id' => $question['id']))); // get the number of answers supplied for a question
     if ($question['answerCount'] > 0) { // if a question has associated answer check to see if the question has been modified
      $this->Question->contain(array('Choice')); // also get choices to see if a question's choices have changed
      $compare =  $this->Question->findById($question['id']); // get the original to compare to the new
      $compare['Question']['Choice'] = $compare['Choice']; // reformat the choice array to match how it will look in the new data array
      unset($compare['Choice']);
      //$compare =  $this->Question->find('first', array('recursive' => -1, 'conditions' => array('id' => $question['id']))); // get the original to compare to the new
      $compare['Question']['order'] = (int) $compare['Question']['order']; // make sure we can compare the result array exactly
      if (($compare['Question'] !== $question) || ($choiceChange)) {  //  see if they are different at all
       $err = true; // there are answers for this question, so it shouldn't be deleted
       $errors['message'] .= 'Error saving question. Cannot change a question that has answers stored. ';
      }
     } else {
      $this->Question->data['Choice'] = $question['Choice'];
      if ($choiceChange) { // if choices have changed then delete the old choices
       $this->Question->Choice->deleteAll(array('questions_id' => $question['id']));
       $saveres = $this->Question->saveAll($this->Question->data);
      } else {
       $saveres = $this->Question->save();
      }
      if (!$saveres){
       // save the question and return any errors
       $err = true; // flag an error
       $add = $this->Question->invalidFields();
       while (is_array($add)):
       $add = end($add);
       endwhile;
       $errors['message'] .= $add;
      }
     }
    }
   }
   if (!is_null($repondentDelete)) {
    // check to see if there are any respondents to delete from the database
    foreach ($repondentDelete as $respondent) {
     // go through each respondent to delete and attempt to delete it
     if (!$this->Respondent->delete($respondent)) {
      // delete the respondent
      $err = true; // if there was an error during deletion, turn on the flag
      $errors['message'] .= 'Error deleting respondent. '; // send an error message
     }
    }
   }
   if (isset($allData['Respondent'])) {
    // look to see if any respondents were included in the survey data
    foreach ($allData['Respondent'] as $respondent) {
     // iterate through the respondents
     if (!isset($respondent['survey_id'])) {
      // check to see if the respondent's foreign key is set for the survey
      $respondent['survey_id'] = $this->id; // set the foreign key if it is not set
     }
     if (!isset($respondent['guid'])) {
      // check to see if the respondent's unique id is set
      $respondent['guid'] = $this->guid(); // set the unique id
     }
     if (isset($respondent['id'])) {
      // id will not be set if it is a new respondent
      $this->Respondent->set($respondent); // use "set" for existing respondent
     } else {
      $this->Respondent->create($respondent); // create new respondent in order to get id for new respondent
     }
     if (!$this->Respondent->save()){
      // save the respondent and return any errors
      $err = true; // flag an error
      $add = $this->Respondent->invalidFields();
      while (is_array($add)):
      $add = end($add);
      endwhile;
      $errors['message'] .= $add;
     }
    }
   }
   if ($err) {
    // look for error flag
    $errors['type'] = 'warning'; // these errors are only warnings, because the base survey was saved
   } else {
    $errors = null;  // there were no errors
   }
  } else {  // error saving the base survey
   $errors['message'] = 'The survey could not be saved. Please, try again.';  // send an error message
   $errors['type'] = 'error'; // this is a hard error because no data at all was saved
  }
 } else { // if there is no data
  $errors['message'] = 'Save not completed.  No data provided. '; // send an error message
  $errors['type'] = 'error'; // this is a hard error because no data at all was saved
 }
 return $errors; // return any errors collected
 }

 function guid(){
  // create a guid to use for a respondent ID
  if (function_exists('com_create_guid')){
   return com_create_guid();
  }else{
   mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
   $charid = strtoupper(md5(uniqid(rand(), true)));
   $hyphen = chr(45);// "-"
   $uuid = chr(123)// "{"
   .substr($charid, 0, 8).$hyphen
   .substr($charid, 8, 4).$hyphen
   .substr($charid,12, 4).$hyphen
   .substr($charid,16, 4).$hyphen
   .substr($charid,20,12)
   .chr(125);// "}"
   return $uuid;
  }
 }

 function getFullSurvey($id = null) {
  if (is_null($id)) {
   return null;
  }
  $this->contain(array('Question'));
  $fullSurvey = $this->findById($id);
  foreach ($fullSurvey['Question'] as &$question) {
   $question['Choice'] = $this->Question->Choice->find('list', array('conditions' => array('questions_id' => $question['id'])));
  }
  return $fullSurvey;
 }
}
