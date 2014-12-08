<fieldset class="detailframe" style="margin-bottom: 30px">
	<legend class="detaillegend">
		<?php echo("Response Started ".$response['Response']['started']." duration ".$response['Response']['timediff']); ?>
	</legend>
	<span class='standalone'><?php echo($this->Html->link('Display GDTA Results', array('controller' => 'responses', 'action' => 'showGdta', $response['Response']['id'])));?>
	</span>
	<table class="index">
		<?php
		echo($this->Html->tableHeaders(array(
				'Question',
				'Answer'
		)));
		foreach ($response['Answer'] as $answer) {
		 echo($this->Html->tableCells(array(
					$this->Html->link(nl2br($answer['Question']['prompt']), array('action'=>'listAnswers','controller'=>'questions',$answer['question_id'])),
					isset($answer['readableAnswer']) ? nl2br($answer['readableAnswer']) : nl2br($answer['answer']),
		 ),
		   array(
		     'class' => 'altrow'
		   ),
		   null
			));
		}
		?>
	</table>
</fieldset>
