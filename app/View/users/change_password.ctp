<?php
$this->Html->addCrumb('Change Password', '/users/change_password/'.$this->Form->data['User']['id'], array('class'=>'crumbText'));
?>
<div class="form">
	<h2>Change Password</h2>
	<?php
	echo($this->Form->create('User'));
	echo($this->Form->input('id'));
	echo($this->Form->input('username', array('type' => 'hidden')));
	echo('<dl class="editlist">');
	echo($this->Form->input('passwd', array('type' => 'password', 'size' => '80','label' => 'New Password','before'=>'<dt>','between'=>'</dt><dd>','after'=>'</dd>')));
	echo($this->Form->input('checkpassword', array('type' => 'password', 'size' => '80', 'label' => 'Confirm','before'=>'<dt>','between'=>'</dt><dd>','after'=>'</dd>')));
	echo('</dl>');
	echo('<br/>');
	echo($this->Form->end('Save', true));
	?>
</div>
