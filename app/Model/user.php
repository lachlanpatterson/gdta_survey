<?php
class User extends AppModel {
 var $name = 'User';
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
   'username' => array(
     'notempty' => array(
       'rule' => array('notempty'),
       'message' => 'User name cannot be empty',
       'allowEmpty' => false,
       'required' => true,
     ),
     'unique' => array(
       'rule' => 'isUnique',
       'message' => 'Duplicate user names are not allowed'
     ),
   ),
   'passwd' => array(
     'min' => array(
       'rule' => array('minLength', 6),
       'message' => 'Passwords must be at least 6 characters.'
     ),
     'required' => array(
       'rule' => 'notEmpty',
       'message'=>'Please enter a password.'
     ),
   ),
   'checkpassword' => array(
     'required'=>'notEmpty',
     'match'=>array(
       'rule' => 'validatePasswdConfirm',
       'message' => 'Passwords do not match, try again'
     )
   ),
 );



 function beforeSave()
 {
  if (isset($this->data['User']['passwd']))
  {
   $this->data['User']['password'] = Security::hash($this->data['User']['passwd'], null, true);
   unset($this->data['User']['passwd']);
  }
  if (isset($this->data['User']['checkpassword']))
  {
   unset($this->data['User']['checkpassword']);
  }
  return true;
 }

 function validatePasswdConfirm($data)
 {
  if ($this->data['User']['passwd'] !== $data['checkpassword'])
  {
   return false;
  }
  return true;
 }

 function checkForBlank() {
  /**
   * function checkForBlank
   * Looks in the user table to see if there are any entries.  If there are no
   * entries then add an administrator account with a default password
   *
   * no parameters
   */

  $q = $this->find('count');
  if ($q == 0) {
   $this->create();
   $this->data['User'] = array(
     'username' => 'admin',
     'password' => Security::hash('survey', null, true)
   );
   $this->save();
  }
 }
}
