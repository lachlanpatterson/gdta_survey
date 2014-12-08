<?php
class Respondent extends AppModel {
 var $name = 'Respondent';

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

   'key' => array(
     'special' => array(
       'rule' => 'uniqueBySurvey',
       'message' => 'Respondent keys must be unique and cannot be blank',
     ),
   ),
   'survey_id' => array(
     'numeric' => array(
       'rule' => 'numeric',
       'message' => 'Survey ID is invalid',
     ),
   ),
 );

 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Survey' => array(
     'className' => 'Survey',
     'foreignKey' => 'survey_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 var $hasMany = array(
   'Response' => array(
     'className' => 'Response',
     'foreignKey' => 'respondent_id',
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

  $this->data['Respondent']['key'] = trim($this->data['Respondent']['key']); // remove unnecessary spaces from the respondent key
 }

 function afterFind(array $results, $primary = false) {
  parent::afterFind($results, $primary);
  foreach ($results as $key => $val)
  {
   if (isset($val['Respondent']['id'])) {
    $results[$key]['Respondent']['responses'] = $this->Response->find('count', array('conditions' => array('respondent_id' => $results[$key]['Respondent']['id'])));
   }
   if (isset($val['Respondent']['guid'])) {
    $results[$key]['Respondent']['url'] = FULL_BASE_URL.'/surveys/startSurvey/'.urlencode($results[$key]['Respondent']['guid']);
   }
  }
  return $results;
 }

 function uniqueBySurvey($check) {
  /*
   * function uniqueBySurvey
  * -----
  * Custom validation function for the key field
  * checks that the key is unique in the specific survey
  * and checks to see if the key is blank
  * -----
  * Parameters:
  * $check -> the value of the key field
  */
  App::import('Validation');
  if (Validation::blank($check['key'])) {  // check to see if the value is blank
   return false;  // return false for invalid (blank) values
  }
  if (isset($this->data['Respondent']['id']) && ($this->data['Respondent']['id'] != '')) {  // check if the id is set and not blank
   $check['id'] = '!='.$this->data['Respondent']['id'];  // if the id is not blank then exclude it from the query because we don't want to count the record we are validating
  }
  $check['survey_id'] = $this->data['Respondent']['survey_id'];  //  limit the search to the survey in question
  $totalCount = $this->find('count', array('conditions' => $check, 'recursive' => -1));  // get the count of records that have duplicate keys
  return !($totalCount > 0);  // return false if we have an invalid count (greater than zero)
 }

 function verifyRespondentToSurvey($respondentKey, $surveyId) {
  /*
   * function verifyRespondentToSurvey
  * -----
  * Returns true if a respondent is connected to a particular survey
  * -----
  * Parameters:
  * $respondentKey -> the respondent being checked
  * $surveyId -> the survey to check to see if the respondent is connected to
  */
  $check['survey_id'] = $surveyId;
  $check['key'] = $respondentKey;
  $totalCount = $this->find('count', array('conditions' => $check, 'recursive' => -1));  // get the count of records that have the respondent key and survey
  return ($totalCount == 1);  // return true if we have a count of one
 }

 function getLastResponse ($respondentId = null) {
  if (is_null($respondentId)) {
   return null;
  }
  $results = $this->find('all', array('conditions' => array('Respondent.id' => $respondentId), 'recursive' => -1, 'fields' => array ('MAX(Response.finished) as finished')));
  return $results;
 }

 function getIdByGuid ($guid = null) {
  // return the respondent ID associated with a respondent's guid
  if (is_null($guid)) {
   return null;
  }
  $results = $this->field('id', array('guid' => $guid));
  return $results;
 }
}
