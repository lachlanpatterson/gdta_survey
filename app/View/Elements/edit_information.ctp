<ul>
	<?php
	echo('<div class="informationbox">');
	echo('<div class="gdtaheader">Information Needs</div><hr/>');
	foreach ($information as $infoIndex => $info) {
	 echo('<div class="infoitem">');
	 echo("<span style=''>");
	 echo($this->Html->link($info['information'],'',array('class' => 'clickedit ajaxlinkstyle handover cleanlink',
				'container' => '#hierarchySpace',
				'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'information/'.$goalIndex.'/'.$decisionIndex.'/'.$infoIndex.'/'))
	 )));
	 echo('<br/>');
	 echo($this->Html->link('[Delete]','',array(
				'class' => 'ajaxlink small-link',
				'container' => '#hierarchySpace',
				'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'deleteInformation/'.$goalIndex.'/'.$decisionIndex.'/'.$infoIndex))
	 )));
	 echo("</span><br/>");
	 echo("<hr/>");
	 echo('</div>');
	}
	echo('<div>');
	echo($this->Html->link('[Add Informaton]','',array(
			'class' => 'clickedit ajaxlinkstyle small-link',
			'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'information/'.$goalIndex.'/'.$decisionIndex))
	)));
	echo('</div>');
	echo('</div>');
	?>
</ul>
