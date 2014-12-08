<?php
$this->Html->addCrumb('Surveys', '/surveys', array('class'=>'crumbText'));
if (isset($response[0])) {
 $this->Html->addCrumb('View Survey', '/surveys/view/'.$response[0]['Respondent']['survey_id'], array('class'=>'crumbText'));
 $this->Html->addCrumb('View Responses', '/responses/view/'.$response[0]['Response']['respondent_id'], array('class'=>'crumbText'));
 ?>
<div class="view">
	<h1>
		<?php  echo 'Responses from '.$response[0]['Respondent']['key'];?>
	</h1>
	<br />
	<?php
	foreach ($response as $i => $r) {
		echo $this->element('response', array('response' => $response[$i]));
	}
	?>
</div>
<?php 
} else {
 ?>
<div class="view">
	<h1>
		<?php  echo 'No Responses For The Selected Respondent';?>
	</h1>
	<br />
</div>
<?php 
}
?>
