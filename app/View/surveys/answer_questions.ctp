<div class="form">
	<h2>Please Answer the Following Questions</h2>
	<?php
	echo($this->Form->create('Answer', array('url' => $this->Html->url(array('controller' => 'surveys', 'action' => 'answerQuestions')))));
	echo('<div style="margin-left: .8em;margin-top: 1.8em;margin-bottom: .8em">');
	foreach ($questions as $key => $question) {
		echo("<div class='borderbox'>");
		echo($this->Form->input($key.'.question_id', array('type' => 'hidden', 'value' => $question['id'])));
		switch ($question['type']) {
			case SHORT_ANSWER:
				echo($this->Form->input($key.'.answer', array('type'=>'text', 'value' => isset($question['answer']) ? $question['answer'] : '', 'size' => '80','label' => $question['prompt'].'<br/>','before'=>'<div class="questionlist">','between'=>'<br/>','after'=>'</div>')));
				break;
			case SCALE_ONE_T0_TEN:
				echo($this->Form->input($key.'.answer', array('type'=>'radio', 'value' => isset($question['answer']) ? $question['answer'] : '', 'options' => array(''=>'Not Applicable or No Answer<br/>',1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10) ,'fieldset' =>false, 'legend' => false, 'before' => '<label class="toplabel">'.$question['prompt'].'</label><br/><br/>','after' => '')));
				break;
			case TRUE_OR_FALSE:
				echo($this->Form->input($key.'.answer', array('type'=>'select', 'value' => isset($question['answer']) ? $question['answer'] : '', 'options' => array(''=>'Not Applicable or No Answer','1' => 'True','0' => 'False'),'label' => $question['prompt'].'<br/>','before'=>'<div class="questionlist">','between'=>'<br/>','after'=>'</div>')));
				break;
			case YES_OR_NO:
				echo($this->Form->input($key.'.answer', array('type'=>'select', 'value' => isset($question['answer']) ? $question['answer'] : '', 'options' => array(''=>'Not Applicable or No Answer','1' => 'Yes','0' => 'No'),'label' => $question['prompt'].'<br/>','before'=>'<div class="questionlist">','between'=>'<br/>','after'=>'</div>')));
				break;
			case CHOOSE_ONE:
				echo($this->Form->input($key.'.answer', array('type'=>'select', 'value' => isset($question['answer']) ? $question['answer'][0]['id'] : '', 'options' => $question['Choice'],'label' => $question['prompt'].'<br/>','before'=>'<div class="questionlist">','between'=>'<br/>','after'=>'</div>')));
				break;
			case MULTI_SELECT:
				echo($this->Form->input($key.'.answer', array('type'=>'select', 'value' => isset($question['answer']) ? $question['answer'] : '', 'multiple' => 'checkbox', 'size' => array_count_values($choices[$question['id']]), 'options' => $question['Choice'],'label' => $question['prompt'].'<br/>','before'=>'<div class="questionlist">','between'=>'<br/>','after'=>'</div>')));
				break;
		}
		echo("</div>");
	}
	echo('</div>');
	echo "<div class='actions'>";
	echo($this->Form->submit('Next',
			array(
			  'type' => 'submit',
			  'div' => false,
			  'style' => 'display: inline-block;'
			)
	));
	echo($this->Form->button('Cancel',
			array(
			  'type' => 'button',
			  'div' => false,
			  'style' => 'display: inline-block;',
			  'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'stopSurvey'))."'",
			))
	);
	echo('</div>');
	echo($this->Form->end());
	?>
</div>
