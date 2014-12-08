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
	if (isset($question_index)) {
		$pass = '/'.$question_index;
		$title = 'Editing Question';
	} else {
		$pass = '';
		$title = 'New Question';
	}
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	echo($this->Form->input('Question.id'));
	echo('<br/>Question Prompt<br/>');
	echo($this->Form->input('Question.prompt', array(
	  'label' => '',
	  'type' => 'textArea',
	  'cols' => '70',
	  'rows' => '3',
	)));
	echo('<br/>');
	echo($this->Form->input('Question.type', array(
	  'label' => 'Type',
	  'options'=>array(
	    SHORT_ANSWER => 'Short Answer',
	    SCALE_ONE_T0_TEN => 'Scale 1 to 10',
	    YES_OR_NO => 'Yes or No',
	    TRUE_OR_FALSE => 'True or False',
	    CHOOSE_ONE => 'Choose One',
	    MULTI_SELECT => 'Multi-Select',
	  )
	)));
	echo('<br/>');
	echo('<br/>Answer Choices<br/>');
	echo($this->Form->input('Question.Choice.value', array(
	  'label' => '',
	  'type' => 'textarea',
	  'cols' => '40',
	  'rows' => '5'
	)
	));
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#questionSpace',
	  'name' => $this->Html->url(array('action' => 'surveyQuestion'.$pass, 'controller' => 'surveys')),
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
</div>
