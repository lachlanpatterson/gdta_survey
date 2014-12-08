<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>Goal Directed Task Analysis Survey Application</title>
<?php
echo($this->element('gdtaajax'));
?>
</head>
<body>
	<div class="jqmWindow" id="modWin"></div>
	<div id="container">
		<table cellpadding="0" style="width: 1000px">
			<tr>
				<td id="topBar"><span id="middlecontainer" style="width: 800px; float: left">
						<h1 style="margin-top: 20px">
							<?php echo($this->Html->link('Goal Directed Task Analysis Survey', '/pages/home'));?>
						</h1>
				</span> <span id="rightcorner" style="float: right"> &nbsp; <?php
				$curUser = $this->Session->read('Auth.User');
				if (is_null($curUser)) {
				 echo('Not Logged In');
				 echo('<br/>');
				} else {
				 echo($curUser['username']);
				 echo("<br/>".$this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('style' => 'font-size:80%')));
				}
				?>
				</span>
				</td>
			</tr>
		</table>
		<div id="contentCell">
			<div id="loading-div" style="display: none; width: 100%">
				<?php echo($this->Html->image('ajax_loading2.gif')); ?>
				&nbsp;LOADING <br />
			</div>
			<span style="float: left; padding-bottom: .8em"> <?php
			if (!is_null($curUser)) {
				echo $this->Html->getCrumbs(' > ',
				  array(
				    'text' => $this->Html->image('home.png', array('style' => 'vertical-align:bottom;border:0')),
				    'url' => array('controller' => 'pages', 'action' => 'display', 'menu'),
				    'escape' => false
				  )
				);
			}
			?>
			</span>
			<div id="messages">
				<?php
				echo($this->element('messages'));
				?>
			</div>
			<div id="content" style="clear: both">
				<?php
				echo($content_for_layout);
				if ($isadmin) {
				 echo($this->element('sql_dump'));
				}
				?>
			</div>
		</div>
	</div>
</body>
<?php
echo($this->Js->writeBuffer());
?>