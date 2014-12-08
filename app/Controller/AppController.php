<?php
/**
 * GDTA Survey AppController.php
 * 
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

App::import('Sanitize');

class AppController extends Controller {

 var $helpers = array('Html', 'Form', 'Session', 'Js' => array('Jquery'), 'Paginator', 'Format')	;
 var $components = array('Auth' => array(
   'loginRedirect' => array('controller' => 'surveys', 'action' => 'index'),
   'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
   //'authorize' => array('Controller'),
   'ajaxLogin' => 'messages',
   'authenticate' => array(
     'Form' => array(
       'userModel' => 'User',
       'fields' => array(
         'username' => 'username',
         'password' => 'password'
       )
     )
   )
 ),
   'Security',
   'Session'
 );
 var $isAdmin = false;

 function success () {
  header("HTTP/1.0 200 Success", null, 200);
  exit;
 }

 function failure () {
  header("HTTP/1.0 404 Failure", null, 404);
  exit;
 }

 function beforeFilter () {
  $this->disableCache();  //some problems with the security component, ajax, and Internet Explorer = turn off cache for all pages to be safe
  if ($this->Auth->user('username') == 'admin') {
   $this->set('isadmin', true);
   $this->isAdmin = true;
  } else {
   $this->isAdmin = false;
   $this->set('isadmin', false);
  }
  $this->Auth->allow('getmessages');
 }

 Public function getmessages () {
  $this->autoRender = false;
  $this->layout = 'ajax';
  if($this->request->is('ajax')) {
   $this->render('/Elements/messages');
  }
 }
}
