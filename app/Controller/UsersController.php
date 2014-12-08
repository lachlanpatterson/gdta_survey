<?php

/**
 * SurveyApp by Lachlan Patterson
 *
 * Users Controller
 *
 * Contains methods or handling users, inluding login, logout, and CRUD functions
 *
 */

class UsersController extends AppController {

 var $name = 'Users';

 function beforeFilter () {
  parent::beforeFilter();
  $this->Auth->allow(array('getmessages','login'));
  if ($this->isAdmin != true) {
   $this->Auth->deny('add','delete','index');
  }
 }

 /*function isAuthorized() {
  if ($this->isAdmin) {
 return true;
 }
 }*/

 function index() {
  /**
   * function index
   * Lists users
   *
   * no parameters
   */

  $this->User->recursive = 0;
  $this->set('users', $this->paginate());
 }

 public function login() {
  /*
   * function login
  * Processes the user login view to allow user login
  *
  * no parameters
  */

  if ($this->request->is('post')) { // handling form data
   if ($this->Auth->login()) { //user Auth component authentication method
    return $this->redirect($this->Auth->redirect()); //send user to their original page before they were redirected to the login view
   } else {
    $this->Session->setFlash('Username or password is incorrect', 'default', array(), 'auth');
   }
  } else {
   $this->User->checkForBlank();
  }

 }

 function logout() {
  /*
   * function logout
  * Logs out the current user
  *
  * no parameters
  */

  $this->Auth->logout();
  return $this->redirect($this->Auth->redirect());
 }

 function add() {
  /*
   * function add
  * -----
  * Ajax only
  * Adds a new user
  * -----
  * No parameters:
  */
  $this->autoRender = false;
  $this->layout = 'ajax';
  if($this->request->is('ajax')) {
   if (!empty($this->data)) {
    $this->User->set($this->data);
    if ($this->User->save($this->data)) {
     $this->Session->setFlash('User saved', true, null, 'confirm');
     $this->User->recursive = 0;
     $this->set('users', $this->paginate());
     $this->render('/Users/index');
    } else {
     $errors = array_values($this->User->invalidFields());
     $this->Session->setFlash('Invalid user: '.$errors[0][0], true, null, 'error');
     $this->User->recursive = 0;
     $this->set('users', $this->paginate());
     $this->render('/Users/index');
    }
   } else {
    $this->render('/Users/add');
   }
  }
 }

 function changePassword() {
  /*
   * function changePassword
  * -----
  * Changes a user's password
  * -----
  * No Parameters
  */
  if (!empty($this->data)) {
   $this->User->set($this->data);
   if ($this->User->save($this->data)) {
    $this->Session->setFlash('Password changed', true, null, 'confirm');
    $this->Auth->logout();
    $this->redirect(array('controller' => 'users', 'action' => 'login'));
   } else {
    $errors = $this->User->invalidFields();
    $this->Session->setFlash('Invalid user: '.$errors['user'][0], true, null, 'error');
    $this->redirect(array('controller' => 'pages', 'action' => 'display', 'menu'));
   }
  } else {
   $this->data = $this->User->read(null, $this->Auth->User('id'));
  }
 }

 function delete($id = null) {
  if (!$id) {
   $this->Session->setFlash(__('Invalid id for user', true));
   $this->redirect(array('action'=>'index'));
  }
  if ($this->User->delete($id)) {
   $this->Session->setFlash(__('User deleted', true));
   $this->redirect(array('action'=>'index'));
  }
  $this->Session->setFlash(__('User was not deleted', true));
  $this->redirect(array('action' => 'index'));
 }
}
