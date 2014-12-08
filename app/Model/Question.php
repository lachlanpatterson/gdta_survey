<?php
class Question extends AppModel {
 var $name = 'Question';
 public $actsAs = array('Containable');
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
   'prompt' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'Question prompt cannot be blank',
       'allowEmpty' => false,
       'required' => true,
     ),
   ),
   'type' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Invalid question type',
       'allowEmpty' => false,
     ),
   ),
   'order' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Invalid question order',
       'allowEmpty' => false,
     ),
   ),
   'survey_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
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
   'Answer' => array(
     'className' => 'Answer',
     'foreignKey' => 'question_id',
     'dependent' => true,
     'conditions' => '',
     'fields' => '',
     'order' => '',
     'limit' => '',
     'offset' => '',
     'exclusive' => '',
     'finderQuery' => '',
     'counterQuery' => ''
   ),
   'Choice' => array(
     'className' => 'Choice',
     'foreignKey' => 'questions_id',
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
  $this->data['Question']['prompt'] = trim($this->data['Question']['prompt']);
 }

 function afterFind($results, $primary = false) {
  parent::afterFind($results, $primary);
  foreach ($results as $key => $val) {
   if (isset($val['Question']['id'])) {
    $results[$key]['Question']['answerCount'] = $this->countAnswersByQuestionId($results[$key]['Question']['id']);
   }
  }
  return $results;
 }

 function countAnswersByQuestionId($questionId = null) {
  if (is_null($questionId)) {
   return null;
  }
  return $this->Answer->find('count', array('conditions' => array('question_id' => $questionId)));
 }

 function reconcileChoices() {
  if (!isset($this->data['Choice']) || is_null($this->data['Choice'])) { // if there are no choices, then simply return false
   return false;
  }
  $retval = false; // defaults to false, which means no changes have been made to the choices for this question
  $questionId = $this->data['Question']['id'];
  $originalChoices = $this->Choice->find('list', array('conditions' => array('questions_id' => $questionId), 'fields' => array('Choice.value'), 'recursive' => -1)); //retrieve all of the current choices for the question
  $originalCounts = array_count_values($originalChoices);
  $newvals = array(); // drop in our new choices as we go for comparison
  // normalize the data
  foreach ($this->data['Choice'] as $key => &$val) {
   if (!is_array($val)) {
    $val = array('value' => $val);
    $val['questions_id'] =  $questionId;
   }
   if (!isset($originalCounts[$val['value']])){ //we have added a new value that wasn't there before
    $retval = true;
   }
   $newvals[] = $val['value'];
   $val['order'] = $key;
  }
  $newcounts = array_count_values($newvals);
  foreach ($originalCounts as $key => $val) {
   switch (true) {
    case (!isset($newcounts[$key])): // a choice was deleted
     $retval = true;
     break;
    case $newcounts[$key] > $val: // the count of a certian choice increased (choice added)
     $retval = true;
     break;
    case $newcounts[$key] > $val: // the count of a certain choice decreased (choice deleted)
     $retval = true;
     break;
   }
  }
  return $retval;
 }

 function formatAnswersForSave($start) {
  foreach ($start as &$answer) {
   $getType = $this->field('type', array('Question.id' => $answer['question_id']));
   switch ($getType) {
    case CHOOSE_ONE:
     $answer['Choice']['id'] = $answer['answer'];
     unset($answer['answer']);
     break;
    case MULTI_SELECT:
     foreach ($answer['answer'] as $multianswer) {
      $answer['Choice'][] = $multianswer;
     }
     unset($answer['answer']);
     break;
   }
  }
  return $start;
 }
}