<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>Rights</title>
<?php
echo($this->element('gdtaajax'));
?>
</head>
<body>
	<div class="jqmWindow" id="modWin"></div>
	<div id="container">
		<table cellpadding="0" style="height: 900px">
			<tr style="height: 70px">
				<td>
					<h1>
						<?php echo('Goal Directed Task Analysis Survey');?>
					</h1>
				</td>
			</tr>
			<tr style="height: auto">
				<td id="contentCell">
					<div id="content">
						<?php
						echo($content_for_layout);
						echo "<div class='actions'>";
						echo($this->Form->button('Continue',
						  array(
						    'type' => 'button',
						    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'pastRights'))."'",
						  ))
						);
						echo($this->Form->button('Decline',
						  array(
						    'type' => 'button',
						    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'stopSurvey'))."'",
						  ))
						);
						?>
					</div> <?php
					if ($isadmin) {
						echo($this->element('sql_dump'));
					}
					?>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?php
echo $this->Js->writeBuffer();
?>
</body>
