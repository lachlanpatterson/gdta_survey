<?php
class Information extends AppModel {
 var $name = 'Information';

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
   'information' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'Information cannot be blank',
       'allowEmpty' => false,
       'required' => true,
     ),
   ),
   'decisions_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Decision ID is invalid',
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Decision' => array(
     'className' => 'Decision',
     'foreignKey' => 'decisions_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 function beforeValidate() {
  $this->data['Information']['information'] = trim($this->data['Information']['information']);
 }
}
