<?php
class ResponsesController extends AppController {

 var $name = 'Responses';

 var $paginate = array(
   'Response' => array(
     'limit' => 12,
     'page' => 1,
     'order'=>array('key'=>'asc'),
   ),
 );
 var $uses = array('Response');

 function beforeFilter () {
  parent::beforeFilter();

 }

 function index($surveyId = null) {

  $this->paginate = array('Response' => array('limit' => 12,'page' => 1,'order'=>array('key'=>'asc'),'conditions' => array('survey_id' => $surveyId),'recursive' => -1));
  $this->set('responses', $this->paginate('Response'));
 }

 function view($respondentId = null) {

  if (is_null($respondentId)) {
   $this->Session->setFlash(__('Invalid respondent', true));
   $this->redirect(array('action' => 'index', 'controller' => 'surveys'));
  }
  $this->Response->contain(array('Answer', 'Answer.Question', 'Respondent'));
  $response = $this->Response->find('all', array('conditions' => array('respondent_id' => $respondentId), 'order' => 'finished DESC'));
  $this->set('response', $response);
 }

 /*function viewByRespondentId($id = null) {
  $response = $this->Response->find('first',array('fields' => array('Response.id'), 'conditions' => array('respondent_id' => $id), 'contains' => ''));
 $this->redirect(array('action' => 'view', $response['Response']['id']));
 }*/

 function viewByResponseId($id = null) {
  $response = $this->Response->find('first',array('fields' => array('Response.respondent_id'), 'conditions' => array('Response.id' => $id), 'recursive' => '-1'));
  $this->redirect(array('action' => 'view', $response['Response']['respondent_id']));
 }

 function showGdta($id = null) {
  $this->layout = 'svglayout';  // use the svg layout
  $this->Response->contain(array('Objective', 'Objective.Decision', 'Objective.Decision.Information'));
  $response = $this->Response->find('first',array('fields' => array('Response.id','Response.maingoal', 'Response.respondent_id'), 'conditions' => array('Response.id' => $id)));
  $this->set('respondentId', $response['Response']['respondent_id']);
  $this->set('gdtaGoals', $response['Objective']);
  $this->set('gdtaMainGoal', $response['Response']['maingoal']);
 }
}
?>