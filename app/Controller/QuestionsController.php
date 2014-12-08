<?php
class QuestionsController extends AppController {

 var $name = 'Questions';

 var $paginate = array(
   'Question' => array(
     'limit' => 12,
     'page' => 1,
     'order'=>array('order'=>'asc'),
     'joins' => array(
       array('table' => 'answers',
         'alias' => 'Answer',
         'type' => 'LEFT',
         'conditions' => array(
           'Answer.question_id = Question.id',
         )
       ),
     ),
   ),
 );
 var $uses = array('Question', 'Answer');

 function beforeFilter () {
  parent::beforeFilter();
 }

 function listAnswers($questionId = null) {
  if (is_null($questionId)) {
   $this->Session->setFlash('No question selected', true, null, 'error'); // display an error when no valid id is given
   $this->response->redirect(array('contoller' => 'surveys', 'action' => 'index'));
  }
  $this->Question->contain(array(
    'Answer',
    'Answer.Response' => array('order' => 'Response.started ASC', 'fields' => 'DISTINCT Respondent.key'),
    'Answer.Response.Respondent',
  ));
  $data = $this->Question->find('first', array('conditions' => array('Question.id' => $questionId)));
  //$this->Question->contain(array('Answer', 'Answer.Response' => array('order' => 'Response.started ASC'), 'Answer.Response.Respondent' => array('fields' => array('Respondent.key'))));
  //$data = $this->Question->find('first', array('conditions' => array('Question.id' => $questionId)));
  //$dbname=$this->Question->getDataSource()->config['database'];
  //$sql = "SELECT 'questions'.'id' FROM ".$dbname." JOIN 'answers' ON ('answers'.'questions_id'='questions'.'id') WHERE 'questions'.'id' = ".$questionId."";
  //$sql = "SELECT 'questions'.'id' FROM '".$dbname."'.'questions' WHERE 'questions'.'id' = ".$questionId."";
  //$sql = 	"SELECT `Question`.`id`, `Question`.`prompt`, `Question`.`type`, `Question`.`survey_id`, `Question`.`order` FROM `".$dbname."`.`questions` AS `Question` WHERE `Question`.`id` = 1 LIMIT 1";
  //$data['Question'] = $this->Question->query($sql);
  //$sql = "SELECT `Answer`.`answer`, `Answer`.`question_id`, `Answer`.`id`, `Answer`.`response_id` FROM `".$dbname."`.`answers` AS `Answer` WHERE `Answer`.`question_id` = (1)";
  //debug($sql,true);
  //$data['Answer'] = $this->Question->query($sql);
  //$data['Question'] = $this->Question->find('first', array('recursive' => 2, 'conditions' => array('Question.id' => $questionId)));
  $this->set('data', $data);
 }
}