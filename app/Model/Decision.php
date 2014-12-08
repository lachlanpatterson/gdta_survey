<?php
class Decision extends AppModel {
 var $name = 'Decision';

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
   'decision' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'Decision cannot be blank',
       'allowEmpty' => false,
       'required' => true,
     ),
   ),
   'objectives_id' => array(
     'numeric' => array(
       'rule' => array('numeric'),
       'message' => 'Survey ID is invalid',
     ),
   ),
 );
 //The Associations below have been created with all possible keys, those that are not needed can be removed

 var $belongsTo = array(
   'Objective' => array(
     'className' => 'Objective',
     'foreignKey' => 'objectives_id',
     'conditions' => '',
     'fields' => '',
     'order' => ''
   )
 );

 var $hasMany = array(
   'Information' => array(
     'className' => 'Information',
     'foreignKey' => 'decisions_id',
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
  $this->data['Decision']['decision'] = trim($this->data['Decision']['decision']);
 }
}
