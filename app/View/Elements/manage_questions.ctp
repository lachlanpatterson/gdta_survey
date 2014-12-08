<fieldset class="detailframe" id="manageQuestions">
	<legend class="detaillegend">Questions</legend>
	<div class="scrollList">
		<table class="index">
			<?php
			if (!empty($questions)) {
				echo(($this->Html->tableHeaders(array(
				  '',
				  'Question',
				  'Type',
				))));
				$maxkey = count($questions) - 1;
				foreach ($questions as $key => $question) {
					echo($this->Html->tableCells(array(
					  $this->Html->image("edit2.png", array(
					    "alt" => "Edit",
					    "title" => "Edit",
					    'class' => 'clickedit ajaxlinkstyle handover',
					    'border' => 0,
					    'container' => '#questionSpace',
					    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'surveyQuestion/'.$key.'/'))
					  )).
					  $this->Html->image("trash-24x24.png", array(
					    "alt" => "Delete",
					    "title" => "Delete",
					    'class' => 'ajaxlink ajaxlinkstyle handover',
					    'border' => 0,
					    'container' => '#questionSpace',
					    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'deleteQuestion/'.$key.'/'))
					  )).
					  $this->Html->image($key != 0 ? "up.png" : "24space.png", array(
					    "alt" => "Up",
					    "title" => "Up",
					    'class' => $key != 0 ? 'ajaxlink ajaxlinkstyle handover' : '',
					    'border' => 0,
					    'container' => '#questionSpace',
					    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'upQuestion/'.$key.'/'))
					  )).
					  $this->Html->image($key != $maxkey ? "down.png" : "24space.png", array(
					    "alt" => "Down",
					    "title" => "Down",
					    'class' => $key != $maxkey ? 'ajaxlink ajaxlinkstyle handover' : '',
					    'border' => 0,
					    'container' => '#questionSpace',
					    'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'downQuestion/'.$key.'/'))
					  )),
					  nl2br($question['prompt']),
					  $this->Format->displayQuestionType($question['type']),
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
		echo($this->Form->button('New Question',array(
				'type' => 'button',
				'class' => 'clickedit',
				'name' => $this->Html->url(array('controller' => 'surveys', 'action' => 'surveyQuestion')),
			))
		);
	?>
	</div>
</fieldset>
