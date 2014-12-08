<h1>Survey Administration</h1><br/>
<div>
<?php
$curUser = $this->Session->read('Auth.User');
if (is_null($curUser)) {
	echo('Not logged in.  Click below to login to the administration system.');
	echo("<br/><br/>".$this->Html->link('Login', array('controller' => 'users', 'action' => 'login'), array('style' => 'font-size:110%')));
} else {
	echo($this->Html->link('Go to Main Menu', array('controller' => 'pages', 'action' => 'display', 'menu'), array('style' => 'font-size:80%')));
}
?>
</div>