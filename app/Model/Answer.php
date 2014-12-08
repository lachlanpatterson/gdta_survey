<?php
class Answer extends AppModel {
 var $name = 'Answer';

 var $validate = array(
   'question_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Question ID is invalid',
     ),
   ),
   'response_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Response ID is invalid',
     ),
   ),
   'id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Invalid value - manual entry of ID not allowed',
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Question' => array(
     'className' => 'Question',
     'foreignKey' => 'question_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   ),
   'Response' => array(
     'className' => 'Response',
     'foreignKey' => 'response_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 public $hasAndBelongsToMany = array(
   'Choice' =>	array(
     'className'              => 'Choice',
     'joinTable'              => 'choices_answers',
     'foreignKey'             => 'answers_id',
     'associationForeignKey'  => 'choices_id',
   )
 );

 public function afterFind($results, $primary=false) {
  foreach ($results as $key => $val) {
   $questionType = isset($val['Answer']['question_id']) ? $this->Question->field('type', array('id' => $val['Answer']['question_id'])) : null;
   switch($questionType) {
    case YES_OR_NO:
     if (isset($val['Answer']['answer'])) {
      switch ($val['Answer']['answer']) {
       case '1':
        $results[$key]['Answer']['readableAnswer'] = 'Yes';
        break;
       case '0':
        $results[$key]['Answer']['readableAnswer'] = 'No';
        break;
       case '':
        $results[$key]['Answer']['readableAnswer'] = 'No Answer';
      }
     }
     break;
    case TRUE_OR_FALSE:
     if (isset($val['Answer']['answer'])) {
      switch ($val['Answer']['answer']) {
       case '1':
        $results[$key]['Answer']['readableAnswer'] = 'True';
        break;
       case '0':
        $results[$key]['Answer']['readableAnswer'] = 'False';
        break;
       case '':
        $results[$key]['Answer']['readableAnswer'] = 'No Answer';
        break;
       default:
        $results[$key]['Answer']['readableAnswer'] = $val['Answer']['answer'];
      }
     }
     break;
    case SCALE_ONE_T0_TEN:
     if (isset($val['Answer']['answer'])) {
      switch ($val['Answer']['answer']) {
       case '':
        $results[$key]['Answer']['readableAnswer'] = 'No Answer';
        break;
      }
     }
     break;
    case CHOOSE_ONE:
     if (isset($val['Answer']['id'])) {
      $results[$key]['Answer']['readableAnswer'] = $this->getSingleChoiceByAnswerId($val['Answer']['id']);
     }
     break;
    case MULTI_SELECT:
     if (isset($val['Answer']['id'])) {
      $results[$key]['Answer']['readableAnswer'] = $this->getMultiChoiceByAnswerId($val['Answer']['id']);
     }
     break;
    case SHORT_ANSWER:
     	
    default:
     if (isset($val['Answer']['answer'])) {
      $results[$key]['Answer']['readableAnswer'] = $val['Answer']['answer'];
     }
   }
  }
  return $results;
 }

 function getSingleChoiceByAnswerId ($id = null) {
  if (is_null($id)) {
   return null;
  }
  $choices = $this->Choice->Answer->find('first',array('contain' => array('Choice' => array('order'=>'Choice.id','fields' => array('Choice.id','Choice.value'))),'fields' => array('Answer.id'),'conditions' => array('Answer.id' => $id)));
  if (isset($choices['Choice'][0]['value'])) {
   return $choices['Choice'][0]['value'];
  } else {
   return null;
  }
 }

 function getMultiChoiceByAnswerId ($id = null) {
  if (is_null($id)) {
   return null;
  }
  $choices = $this->Choice->Answer->find('first',array('contain' => array('Choice' => array('order'=>'Choice.id','fields' => array('Choice.id','Choice.value'))),'fields' => array('Answer.id'),'conditions' => array('Answer.id' => $id)));
  $combineChoicesArr = Set::extract('/value',$choices['Choice']);
  $combineChoicesStr = implode(chr(10),$combineChoicesArr);
  return $combineChoicesStr;
 }
}
