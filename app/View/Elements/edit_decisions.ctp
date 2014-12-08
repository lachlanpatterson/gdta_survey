<ul>
	<?php
	foreach ($decisions as $decisionIndex => $decision) {
	 echo('<div class="decisionbox">');
	 echo("<li class='nobullet'>");
	 echo("<span class='gdtaheader'>".'Decision: '."</span>");
	 echo($this->Html->link($decision['decision'],'',array('class' => 'clickedit ajaxlinkstyle handover cleanlink',
				'container' => '#hierarchySpace',
				'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'decision/'.$goalIndex.'/'.$decisionIndex.'/')))));
	 echo('<br/>');
	 echo($this->Html->link('[Delete]','',array(
				'class' => 'ajaxlink small-link',
				'container' => '#hierarchySpace',
				'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'deleteDecision/'.$goalIndex.'/'.$decisionIndex))
	 )));
	 echo("</li>");
	 echo($this->Html->div('managecontainer2', $this->element('edit_information', array(
				'information' => isset($decision['Information']) ? $decision['Information'] : array(),
				'goalIndex' => $goalIndex,
				'decisionIndex' => $decisionIndex,
	 )), array('id' => 'informationSpace'.$goalIndex.'-'.$decisionIndex)));
	 echo('</div>');
	}
	echo($this->Form->button('Add Decision',
			array(
			  'type' => 'button',
			  'div' => false,
			  'class' => 'clickedit ajaxlinkstyle handover',
			  'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'decision/'.$goalIndex))
			))
	);
	?>
</ul>
