<?php
class Objective extends AppModel {
 var $name = 'Objective';

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
   'objective' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'Goals cannot be blank',
       'allowEmpty' => false,
       'required' => true,
     ),
   ),
   'response_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Response ID is invalid',
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Response' => array(
     'className' => 'Response',
     'foreignKey' => 'response_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 var $hasMany = array(
   'Decision' => array(
     'className' => 'Decision',
     'foreignKey' => 'objectives_id',
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
  $this->data['Objective']['objective'] = trim($this->data['Objective']['objective']);
 }
}
