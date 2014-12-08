<h1>Menu</h1><br/>
<?php echo $this->Html->link(__('Manage Surveys', true), array('controller' => 'surveys', 'action' => 'index')); ?><br/><br/>
<?php echo($isadmin ? $this->Html->link(__('Manage Users', true), array('controller' => 'users', 'action' => 'index')).'<br/><br/>' : '');?>
<?php echo($this->Html->link(__('Change Password', true), array('controller' => 'users', 'action' => 'changePassword')).'<br/><br/>');?>