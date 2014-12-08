<table class="index">
	<?php
	$this->Paginator->options(array(
	  'update' => '#content',
	  'evalScripts' => true,
	  'before' => $this->Js->get('#loading-div')->effect('fadeIn', array('buffer' => false)),
	  'complete' => $this->Js->get('#loading-div')->effect('fadeOut', array('buffer' => false)),
	));
	switch($modelUse) {
		case 'user':
			echo($this->Html->tableHeaders(array(
			'&nbsp;',
			$this->Paginator->sort('username','User Name'),
			)));
			foreach ($users as $user) {
				echo($this->Html->tableCells(array(
				  ($isadmin ? $this->Html->link($this->Html->image("trash-24x24.png", array(
				    "alt" => "Delete",
				    "title" => "Delete",
				    'border' => 0
				  )),
				    array('action'=>'delete','controller'=>'users',$user['User']['id']),
				    array('escape'=>false),
				    "Really delete user: ".$user['User']['username']."?"
				  ) : ''),
				  $user['User']['username']
				),
				  array(
				    'class' => 'altrow'
				  ),
				  null,
				  true
				));
			}
			break;
		case 'survey':
			echo($this->Html->tableHeaders(array(
			'&nbsp;',
			$this->Paginator->sort('surveyname','Survey Name'),
			)));
			foreach ($surveys as $survey) {
				echo($this->Html->tableCells(array(
				  ($this->Html->link($this->Html->image("arrow.png", array(
				    "alt" => "Test Survey",
				    "title" => "Test Survey",
				    'border' => 0
						)),array('action'=>'testSurvey','controller'=>'surveys',$survey['Survey']['id']),	array('escape'=>false
						))).'&nbsp'.
				  ($this->Html->image("edit2.png", array(
				    "alt" => "Edit",
				    "title" => "Edit",
				    'url' => array('action'=>'edit','controller'=>'surveys',$survey['Survey']['id']),
				    'border' => 0
				  ))).'&nbsp'.
				  ($this->Html->link($this->Html->image("trash-24x24.png", array(
				    "alt" => "Delete",
				    "title" => "Delete",
				    'border' => 0
						)),
				    array('action'=>'delete','controller'=>'surveys',$survey['Survey']['id']),
				    array('escape'=>false),
				    "Really delete survey: ".$survey['Survey']['surveyname']."?"
				  )),
				  $this->Html->link($survey['Survey']['surveyname'], array('action'=>'view','controller'=>'surveys',$survey['Survey']['id']), array('escape'=>false))
				),
				  array(
				    'class' => 'altrow'
				  ),
				  null,
				  true
				));
			}
			break;
	}?>
</table>
<div class="pagecounter">
	<?php
	echo($this->Paginator->counter(array('format' => 'Page %page% of %pages%')));
	echo("<br/><br/>");
	echo($this->Paginator->prev('<< ' . 'previous - ', array(), null, array('class'=>'disabled')));
	echo($this->Paginator->numbers(array('separator'=>' - ')));
	echo($this->Paginator->next(' - next page' . ' >>', array(), null, array('class' => 'disabled')));
?>
</div>
