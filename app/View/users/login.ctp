<div class="form">
	<h2>Login</h2>
	<br />
	<?php
	echo $this->Form->create('User', array('action' => 'login'));
	echo "<dl class='editlist'>";
	echo $this->Form->input('username', array('size' => '40','label' => 'User Name','before'=>'<dt>','between'=>'</dt><dd>','after'=>'</dd>'));
	echo $this->Form->input('password', array('size' => '40','label' => 'Password','before'=>'<dt>','between'=>'</dt><dd>','after'=>'</dd>'));
	echo "</dl>";
	echo $this->Form->end('Login');
	?>
</div>
