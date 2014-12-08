<?php
class Response extends AppModel {
 var $name = 'Response';

 var $validate = array(
   'id' => array(
     'blank' => array(
       'rule' => 'blank',
       'message' => 'Invalid value - manual entry of ID not allowed',
       'allowEmpty' => true,
       'required' => false,
       'on' => 'create',
     ),
   ),
   'survey_id' => array(
     'numeric' => array(
       'rule' => 'numeric',
       'message' => 'Survey ID is invalid',
     ),
   ),
   'respondent_id' => array(
     'numeric' => array(
       'rule' => 'numeric',
       'message' => 'Respondent ID is invalid',
     ),
   ),
 );

 var $virtualFields = array(
   'timediff' => 'TIMEDIFF(Response.finished,Response.started)'
 );

 var $belongsTo = array(
   'Respondent' => array(
     'className' => 'Respondent',
     'foreignKey' => 'respondent_id',
   )
 );

 var $hasMany = array(
   'Answer' => array(
     'className' => 'Answer',
     'foreignKey' => 'response_id',
     'dependent' => true,
   ),
   'Objective' => array(
     'className' => 'Objective',
     'foreignKey' => 'response_id',
     'dependent' => true,
   )
 );

 function getLatestResponse($respondent = null) {
  /*
   * function getLatestResponse
  * -----
  * Returns the most recent of responses from the respondent
  * -----
  * Parameters:
  * $respondent -> database id of the respondent
  * -----
  * Returns:
  * an array from the database or a null array if there are no responses
  */
  if (is_null($respondent)) return null;
  $response = $this->find('all', array('conditions' => array('respondent_id' => $respondent), 'order' => 'finished DESC', 'limit' => 1, 'contain' => 'Answer'));
  $results['Response'] = isset($response[0]['Response']) ? $response[0]['Response'] : null; // use this one response to send back
  $responseId = $response[0]['Response']['id'];
  $hierarchy = $this->Objective->find('all', array('conditions' => array('response_id' => $responseId),'contain' => array('Decision' => array('Information'))));
  foreach ($hierarchy as $key => $val) {  // format the results for use in the session variable format
   if (isset($hierarchy[$key]['Decision'])) {
    $hierarchy[$key]['Objective']['Decision'] = $hierarchy[$key]['Decision'];
   }
   $results['Hierarchy'][] = $hierarchy[$key]['Objective'];
  }
  $results['Answer'] = isset($response[0]['Answer']) ? $response[0]['Answer'] : null; // we are only retrieving one response (the latest) so just use those answers
  foreach ($results['Answer'] as &$answer) { // go through answers and get multi select and single choice answers in associated choice table
   $choices = $this->Answer->Choice->Answer->find('first',array('contain' => array('Choice' => array('order'=>'Choice.id','fields' => array('Choice.id','Choice.value'))),'fields' => array('Answer.id'),'conditions' => array('Answer.id' => $answer['id'])));
   if (isset($choices['Choice'][0])) { // if we have linked choices they will be enumerated here
    $answer['answer'] =  $choices['Choice'];
   }
  }
  return $results;
 }
}
