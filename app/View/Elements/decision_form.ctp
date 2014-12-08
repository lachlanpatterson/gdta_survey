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
	if (isset($decision_index)) {
		$pass = '/'.$indexObjective.'/'.$decision_index;
		$title = 'Edit Decision';
	} else {
		$pass ='/'.$indexObjective;
		$title = 'New Decision';
	}
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	/*echo($this->Form->input('Decision.id', array(
		'type' => 'hidden',
	)));*/
	echo($this->Form->input('Decision.decision', array(
	  'label' => 'Decision',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#decisionSpace'.$indexObjective,
	  'name' => $this->Html->url(array('action' => 'decision'.$pass, 'controller' => 'surveys')),
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
