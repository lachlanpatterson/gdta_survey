<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
$this->Html->addCrumb('View Survey', '/surveys/view/'.$survey['Survey']['id'], array('class'=>'crumbText'));
?>
<div class="view">
	<h1>
		<?php  echo $survey['Survey']['surveyname'];?>
	</h1>
	<br />
	<?php
	echo('<h4>');
	echo($this->Html->link($this->Html->image("arrow.png", array(
			"alt" => "Test Survey",
			"title" => "Test Survey",
			'border' => 0
	)),array('action'=>'testSurvey','controller'=>'surveys',$survey['Survey']['id']),
	  array('escape'=>false)));
	echo('&nbsp');
	echo($this->Html->image("edit2.png", array(
			"alt" => "Edit",
			"title" => "Edit",
			'url' => array('action'=>'edit','controller'=>'surveys',$survey['Survey']['id']),
			'border' => 0
	)));
	echo('</h4>');
	?>
	<fieldset class="detailframe">
		<legend class="detaillegend">Questions</legend>
		<table class="index">
			<?php
			echo($this->Html->tableHeaders(array(
			  'Question',
			  'Type',
			  'Answers'
			)));
			foreach ($survey['Question'] as $question) {
				echo($this->Html->tableCells(array(
				  nl2br($question['prompt']),
				  $this->Format->displayQuestionType($question['type']),
				  $this->Html->link('View Answers', array('action'=>'listAnswers','controller'=>'questions',$question['id']))
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
	<fieldset class="detailframe">
		<legend class="detaillegend">Respondents</legend>
		<table class="index">
			<?php
			echo($this->Html->tableHeaders(array(
			  'Respondent Key',
			  'URL',
			  'Response'
			)));
			foreach ($survey['Respondent'] as $respondent) {
				echo($this->Html->tableCells(array(
				  $respondent['key'],
				  "<span style='font-size: 70%'>".$respondent['url']."</span>",
				  $respondent['responses'] < 1 ? 'None' : $this->Html->link('View Responses ('.$respondent['responses'].')', array('action'=>'view','controller'=>'responses',$respondent['id']))
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
