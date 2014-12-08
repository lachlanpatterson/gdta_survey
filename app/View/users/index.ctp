<?php
$this->Html->addCrumb('Users', '/users', array('class'=>'crumbText'));
?>
<div class="index">
	<h2>
		<?php __('Users');?>
	</h2>
	<div id="paging">
		<?php
		echo($this->element('paging', array('modelUse' => 'user')));
		?>
	</div>
</div>
<br />
<div class="actions">
	<?php 
	echo($this->Form->button('New User',
	  array(
	    'type' => 'button',
	    'id' => 'add',
	    'class' => 'clickedit',
	    'name' => $this->Html->url(array('controller' => 'users', 'action' => 'add'))
	  ))
	);

	?>
</div>
<br />
