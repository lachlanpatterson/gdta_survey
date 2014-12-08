<fieldset id=technical>
	<legend id=sqldump>Database Information for This Page <i>Click to Expand</i></legend>
	<div id=sqldumpset style="display:none">
	<?php
	$this->Js->get('#sqldump')->event('click', "$(\"#sqldumpset\").toggle()");
if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	return false;
}

$sources = ConnectionManager::sourceList();
if (!isset($logs)):
	$logs = array();
	foreach ($sources as $source):
		$db =& ConnectionManager::getDataSource($source);
		//if (!$db->isInterfaceSupported('getLog')):
		//	continue;
		//endif;
		$logs[$source] = $db->getLog();
	endforeach;
endif;

foreach ($logs as $source => $logInfo):
	$text = $logInfo['count'] > 1 ? 'queries' : 'query';
	printf(
		'<table class="sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log">',
		preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
	);
	printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
?>
<thead>
	<tr><th>Nr</th><th>Query</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
</thead>
<tbody>
<?php
	foreach ($logInfo['log'] as $k => $i) :
		echo "<tr><td>" . ($k + 1) . "</td><td>" . h($i['query']) . "</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
	endforeach;
?>
</tbody></table>
<?php endforeach; ?>
	</div>
</fieldset>

