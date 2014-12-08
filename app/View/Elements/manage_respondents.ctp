<fieldset class="detailframe" id="manageRespondents">
	<legend class="detaillegend">Respondents</legend>
	<div class="scrollList">
		<table class="index">
			<?php
			if (!empty($respondents)) {
				echo(($this->Html->tableHeaders(array(
				  '',
				  'Respondent Key',
				  'URL for Survey',
				  'Response'
				))));
				foreach ($respondents as $key => $respondent) {
					switch (true) {
						case (!array_key_exists('responses', $respondent)):
							$restext = 'None';
							break;
						case ($respondent['responses'] == 1):
							$restext = '1 response';
							break;
						case ($respondent['responses'] < 1):
							$restext = 'None';
							break;
						default:
							$restext = $respondent['responses'].' responses';
					}
					echo($this->Html->tableCells(array(
					  $this->Html->image("trash-24x24.png", array(
					    "alt" => "Delete",
					    "title" => "Delete",
					    'class' => 'ajaxlink ajaxlinkstyle handover',
					    'border' => 0,
					    'container' => '#respondentSpace',
					    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'deleteRespondent', $key))
					  )),
					  $respondent['key'],
					  "<span style='font-size: 70%'>".$respondent['url']."</span>",
					  $restext
					),
					  array(
					    'class' => 'altrow'
					  ),
					  null,
					  true
					));
				}
			}
			?>
		</table>
	</div>
	<div class="alignright">
		<?php
		echo($this->Form->button('New Respondent',array(
		  'type' => 'button',
		  'class' => 'clickedit',
		  'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'surveyRespondent')),
		))
		);
		?>
	</div>
</fieldset>
