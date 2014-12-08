<div class="loading-modal"
	style="display: none; text-align: center; padding: 20px"
>
	Updating...<br />
	<br />
	<?php
	echo($this->Html->image('ajax_loading2.gif'));
	?>
</div>
<div class="mainModal">
	<?php
	if (isset($respondent_index)) {
		$pass = '/'.$respondent_index;
		$title = 'Editing Respondent';
	} else {
		$pass = '';
		$title = 'New Respondent';
	}
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	echo($this->Form->input('Respondent.id'));
	echo($this->Form->input('Respondent.key', array(
	  'label' => 'Respondent Key',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#respondentSpace',
	  'name' => $this->Html->url(array('action' => 'surveyRespondent'.$pass, 'controller' => 'surveys')),
	  'div' => false,
	  'style' => 'float: left'
	)));
	echo($this->Form->button('Cancel', array(
	  'type' => 'button',
	  'class' => 'jqmClose',
	  'div' => false,
	  'style' => 'float: left'
	)));
	?>
	</span>
</div>
