<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>Survey</title>
<?php
echo($this->element('gdtaajax'));
?>
</head>
<body>
	<div class="jqmWindow" id="modWin"></div>
	<div id="container">
		<table cellpadding="0" style="width: 1000px">
			<tr>
				<td id="topBar"><span id="middlecontainer" style="width: 100%">
						<h1 style="margin: .5em">
							<?php echo('Goal Directed Task Analysis Survey');?>
						</h1>
				</span>
				</td>
			</tr>
		</table>
		<div id="contentCell">
			<div id="loading-div" style="display: none; width: 100%">
				<?php echo($this->Html->image('ajax_loading2.gif')); ?>
				&nbsp;LOADING <br />
			</div>
			<div id="messages">
				<?php
				echo($this->element('messages'));
				?>
			</div>
			<div id="content">
				<?php
				echo($content_for_layout);
				if ($isadmin) {
				 echo($this->element('sql_dump'));
				}
				?>
			</div>
		</div>
	</div>
	<?php
echo $this->Js->writeBuffer();
?>
</body>
