<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
?>
<div class="loading-modal"
	style="display: none; text-align: center; padding: 20px"
>
	Updating...<br /> <br />
	<?php
	echo($this->Html->image('ajax_loading2.gif'));
	?>
</div>
<div class="mainModal">
	<?php
	$title='New Survey';
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	echo($this->Form->input('Survey.id'));
	echo($this->Form->input('Survey.surveyname', array(
	  'label' => 'Survey Name',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#content',
	  'name' => $this->Html->url(array('action' => 'add', 'controller' => 'surveys')),
	  'div' => false
	)));
	echo($this->Form->button('Cancel', array(
	  'type' => 'button',
	  'class' => 'jqmClose',
	  'div' => false
	)));
	?>
	</span>
</div>
</div>
