<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
$this->Html->addCrumb('Edit Survey', '/surveys/edit/'.$this->Form->data['Survey']['id'], array('class'=>'crumbText'));
?>
<div class="form">
	<h2>Edit Survey</h2>
	<?php
	echo('<h4>');
	echo($this->Html->link($this->Html->image("arrow.png", array(
	  "alt" => "Test Survey",
	  "title" => "Test Survey",
	  'border' => 0
	)),array('action'=>'testSurvey','controller'=>'surveys',$this->Form->data['Survey']['id']),
	  array('escape'=>false)));
	echo('&nbsp');
	echo($this->Html->link($this->Html->image("trash-24x24.png", array(
	  "alt" => "Delete",
	  "title" => "Delete",
	  'border' => 0
	)),array('action'=>'delete','controller'=>'surveys',$this->Form->data['Survey']['id']),
	  array('escape'=>false),
	  "Really delete survey: ".$this->Form->data['Survey']['surveyname']."?"));
	echo('</h4>');
	echo($this->Form->create('Survey'));
	echo($this->Form->input('id'));
	echo('<dl class="editlist">');
	echo($this->Form->input('surveyname', array('size' => '80','label' => 'Survey Name','before'=>'<dt>','between'=>'</dt><dd>','after'=>'</dd>')));
	echo($this->Form->input('Survey.consent', array(
			'label' => 'Participant Informed Consent',
			'between'=>'<br/>',
			'rows' => '20',
			'cols' => '103',
			'escape' => false,
	)));
	echo('</dl>');
	echo($this->Html->div('managecontainer', $this->element('manage_questions', array(
	  'questions' => $this->Form->data['Question'],
	  'surveyId' => $this->Form->data['Survey']['id']
	)), array('id' => 'questionSpace')));
	echo($this->Html->div('managecontainer', $this->element('manage_respondents', array(
	  'respondents' => $this->Form->data['Respondent'],
	  'surveyId' => $this->Form->data['Survey']['id']
	)), array('id' => 'respondentSpace')));
	echo "<div class='actions'>";
	echo($this->Form->end('Save', true));
	echo($this->Form->button('Cancel',
	  array(
	    'type' => 'button',
	    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'index'))."'",
	  ))
	);
	?>
</div>
</div>
