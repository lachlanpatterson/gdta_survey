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
	if (isset($goal_index)) {
		$pass = '/'.$goal_index;
		$title = 'Edit Sub-Goal';
	} else {
		$pass = '';
		$title = 'New Sub-Goal';
	}
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	/*echo($this->Form->input('Objective.id', array(
		'type' => 'hidden',
	)));*/
	echo($this->Form->input('Objective.objective', array(
	  'label' => 'Sub-Goal',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#hierarchySpace',
	  'name' => $this->Html->url(array('action' => 'goal'.$pass, 'controller' => 'surveys')),
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
	<?php echo('</div>');?>
</div>
