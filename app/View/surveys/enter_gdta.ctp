<div>
	<?php
	echo($this->Form->create(null, array('div' => false, 'url' => $this->Html->url(array('controller' => 'surveys', 'action' => 'enterGdta')))));
	echo('<br/><br/>');
	//echo('<div id="helpsidebar">');

	//echo('</div>');
	echo('<div id="gdtacontainer">');
	echo($this->Form->input('mainGoal', array('size' => '80','label' => 'Main Goal')));
	echo($this->Html->div('managecontainer3', $this->element('edit_hierarchy', array(
	  'hierarchy' => $hierarchy,
	)), array('id' => 'hierarchySpace')));
	echo($this->element('gdta-help'));
	echo('</div>');
	echo("<div class='actions'>");
	echo($this->Form->submit('Done',
	  array(
	    'type' => 'submit',
	    'div' => false,
	    'style' => 'display: inline-block;'
	  )
	));
	echo($this->Form->button('Cancel',
	  array(
	    'type' => 'button',
	    'div' => false,
	    'style' => 'display: inline-block;',
	    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'stopSurvey'))."'",
	  ))
	);
	echo('</div>');
	echo($this->Form->end());
	?>
</div>
