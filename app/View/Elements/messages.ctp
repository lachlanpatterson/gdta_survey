<?php
$quickflash = '&nbsp';
$useid = 'flashcell-empty';
$testflash = $this->Session->flash('confirm');
if($testflash != '') {
	$quickflash = $testflash;
	$useid = 'flashcell-full';
}
$testflash = $this->Session->flash('warning');
if($testflash != '') {
	$quickflash = $testflash;
	$useid = 'flashcell-warn';
}
$testflash = $this->Session->flash('error');
if($testflash != '') {
	$quickflash = $testflash;
	$useid = 'flashcell-error';
}
$testflash = $this->Session->flash('auth');
if ($testflash != '') {
	$quickflash = $testflash;
	$useid = 'flashcell-auth';
}
echo("<div id='".$useid."'>");
echo($quickflash);
echo('</div>');
?>