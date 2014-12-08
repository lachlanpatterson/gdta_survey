<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
$this->Html->addCrumb('View Survey', '/surveys/view/'.$data['Question']['survey_id'], array('class'=>'crumbText'));
$this->Html->addCrumb('View Question & Answers', '/questions/listAnswers/'.$data['Question']['id'], array('class'=>'crumbText'));
?>
<div class="view">
	<h2>
		<?php  echo 'Question: '.$data['Question']['prompt'];?>
	</h2>
	<br />
	<fieldset class="detailframe">
		<legend class="detaillegend">Answers Given</legend>
		<table class="index">
			<?php
			echo($this->Html->tableHeaders(array(
			  'Answer',
			  'Respondent',
			  'Submitted On'
			)));
			foreach ($data['Answer'] as $answer) {
				echo($this->Html->tableCells(array(
				  isset($answer['readableAnswer']) ? nl2br($answer['readableAnswer']) : nl2br($answer['answer']),
				  $this->Html->link($answer['Response']['Respondent']['key'], array('action'=>'viewByResponseId','controller'=>'responses',$answer['response_id'])),
				  $answer['Response']['finished']
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
</div>
