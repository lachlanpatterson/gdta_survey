<div class="loading-modal"
	style="display: none; text-align: center; padding: 20px">
	Updating...<br />
	<br />
	<?php
	echo($this->Html->image('ajax_loading2.gif'));
	?>
</div>
<div class="mainModal">
	<?php
	$pass='/'.$indexObjective.'/'.$indexDecision;
	if (isset($information_index)) {
		$pass.= '/'.$information_index;
		$title = 'Edit Information';
	} else {
		$title = 'New Information';
	}
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	/*echo($this->Form->input('Information.id', array(
		'type' => 'hidden',
	)));*/
	echo($this->Form->input('Information.information', array(
	  'label' => 'Information',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#informationSpace'.$indexObjective.'-'.$indexDecision,
	  'name' => $this->Html->url(array('action' => 'information'.$pass, 'controller' => 'surveys')),
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
