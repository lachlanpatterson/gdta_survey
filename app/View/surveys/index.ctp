<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
?>
<div class="index">
	<h2>
		<?php __('Surveys');?>
	</h2>
	<div id="paging">
		<?php
		echo($this->element('paging', array('modelUse' => 'survey')));
		?>
	</div>
</div>
<br />
<div class="actions">
	<?php 
	echo($this->Form->button('New Survey',
	  array(
	    'type' => 'button',
	    'id' => 'add',
	    'class' => 'clickedit',
	    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'add'))
	  ))
	);
	?>
</div>
<br />
