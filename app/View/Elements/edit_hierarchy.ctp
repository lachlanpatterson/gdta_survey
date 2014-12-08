<div>
	<ul>
		<?php
		foreach ($hierarchy as $goalIndex => $goal) {
			echo('<div class="hierarchybox">');
			echo("<li class='nobullet' name='".$goalIndex.".'>");
			echo("<span class='gdtaheader'>".'Sub-Goal: '."</span>");
			echo($this->Html->link($goal['objective'],'',array('class' => 'clickedit ajaxlinkstyle handover cleanlink',
			  'container' => '#hierarchySpace',
			  'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'goal/'.$goalIndex.'/')))));
			echo('<br/>');
			echo($this->Html->link('[Delete]','',array(
			  'class' => 'ajaxlink small-link',
			  'container' => '#hierarchySpace',
			  'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'deleteGoal/'.$goalIndex)))));
			echo("</li>");
			echo($this->Html->div('managecontainer2', $this->element('edit_decisions', array(
					'decisions' => isset($goal['Decision']) ? $goal['Decision'] : array(),
					'goalIndex' => $goalIndex,
			)), array('id' => 'decisionSpace'.$goalIndex)));
			echo('</div>');
		}
		echo($this->Form->button('Add Sub-Goal',
		  array(
		    'type' => 'button',
		    'div' => false,
		    'class' => 'clickedit ajaxlinkstyle handover',
		    'container' => '#hierarchySpace',
		    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'goal/'))
		  ))
		);
		?>
	</ul>
</div>
