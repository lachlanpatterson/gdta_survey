<?php
class Choice extends AppModel {
 var $name = 'Choice';
 public $actsAs = array('Containable');
 var $displayField = 'value';
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
   'order' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Invalid choice order',
       'allowEmpty' => false,
     ),
   ),
   'questions_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Question ID is invalid',
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Question' => array(
     'className' => 'Question',
     'foreignKey' => 'questions_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 public $hasAndBelongsToMany = array(
   'Answer' =>	array(
     'className'              => 'Answer',
     'joinTable'              => 'choices_answers',
     'foreignKey'             => 'choices_id',
     'associationForeignKey'  => 'answers_id',
   )
 );

 function splitChoices ($start = null) {
  // combines choices that are a string seperated by NL into an array
  $usechr = 10;
  $retdat = null;
  if (is_null($start)) {
   return null;
  }
  $retdat = explode(chr($usechr),$start);
  foreach ($retdat as $key => $val) { // trim all of the values
   $retdat[$key] = trim($val);
   if ($retdat[$key] == '') { // unset any blank values
    unset($retdat[$key]);
   }
  }
  foreach ($retdat as $key => &$val) { // now reconfigure the array in to a normal array of choice objects
   if (!is_array($val)) {
    $val = array('value' => $val);
   }
   $val['order'] = $key;
  }
  return array_values($retdat);
 }

 function combineChoices ($start = null) {
  // breaks choices into a string with NL seperating each
  $usechr = 10;
  $retdat = null;
  if (is_null($start)) {
   return null;
  }
  if (isset($start[0]) && is_array($start[0])) {
   $start = $this->breakDownValues($start);
  }
  $retdat = implode(chr($usechr), $start);
  return $retdat;
 }

 function breakDownValues($arr) {
  $retarr = array();
  foreach ($arr as $check) {
   $retarr[] = $check['value'];
  }
  return $retarr;
 }

}