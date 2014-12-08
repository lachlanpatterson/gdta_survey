<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">
<script type="text/javascript"
	src="/js/svg.js"
></script>
<head>
<title>Introduction</title>
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
						<?php echo('Introduction');?>
					</h1>
					<h3>
						Page
						<?php echo($intropage);?>
						of 4
					</h3>
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
						    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'runSurvey/', 'intropage' => $intropage))."'",
						  ))
						);
						echo($this->Form->button('Cancel',
						  array(
						    'type' => 'button',
						    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'stopSurvey'))."'",
						  ))
						);
						?>
					</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?php
echo $this->Js->writeBuffer();
?>
</body>
