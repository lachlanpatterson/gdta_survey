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
	$title='New User';
	echo($this->Html->div('title', $title, array('id' => 'dialogtitle')));
	echo('<div class="modal">');
	echo($this->Form->create(null, array('url' => false, 'class' => 'modalform', 'default' => false)));
	//echo($this->Form->input('User.id'));
	echo($this->Form->input('User.username', array(
	  'label' => 'User Name',
	  'type' => 'text',
	  'size'=>'50'
	)));
	echo($this->Form->input('User.passwd', array(
	  'label' => 'Password',
	  'type' => 'password',
	  'size'=>'50'
	)));
	echo($this->Form->input('User.checkpassword', array(
	  'label' => 'Verify Password',
	  'type' => 'password',
	  'size'=>'50'
	)));
	echo('<br/>');
	?>
	<span> <?php
	echo($this->Form->end(array(
	  'label' => 'Save',
	  'class' => 'savemodal',
	  'container' => '#content',
	  'name' => $this->Html->url(array('action' => 'add', 'controller' => 'users')),
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
	<?php 
	echo('</div>');
	?>
</div>
