<?php
require("svgtools.php");
echo('<div style="width:100%;text-align: center">');
echo($this->Form->button('Go Back to Make Changes',
  array(
    'type' => 'button',
    'div' => false,
    'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'reeditSurvey'))."'",
    'style' => 'display: inline-block;'
  ))
);
switch ($surveyType) {
 case 'user':
  echo($this->Form->button('Accept and Save Results',
  array(
  'type' => 'button',
  'div' => false,
  'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'saveUserSurvey'))."'",
  'style' => 'display: inline-block;'
  ))
  );
  break;
 case 'test':
  echo($this->Form->button('Return to Surveys Menu',
  array(
  'type' => 'button',
  'div' => false,
  'onClick' => "location.href='".$this->Html->url(array('controller' => 'surveys', 'action' => 'stopSurvey'))."'",
  'style' => 'display: inline-block;'
  ))
  );
  break;
}?>
<div>
	<p class="tip">This graphic represent the information you entered.</p>
	<p class="tip">If you see anything that you think needs to be changed click on
		"go back and make changes."</p>
	<p class="tip">If everything looks OK then click "accept and save results" to
		save your answers and end the survey.</p>
</div>
<?php
echo('</div>');
$totalWidth = 88; // width of the SVG graphic, defaults to 88px
$totalHeight = 529; // height of the SVG graphic, defaults to 500px
$countUp = 1; // variable to hold the number of information boxes
foreach ($gdtaGoals as $checkit) {
 if (empty($checkit['Decision'])) {
  $countUp++;
 } else {
  $countUp += count($checkit['Decision'], COUNT_NORMAL);
 }
 $totalWidth += 15;
}
$totalWidth+=225*$countUp; // add to the width of the SVG based on the number of decision boxes, which is the widest part of the graphic (minimum of one per goal)
$totalHeight+=(16*setup_info_items($gdtaGoals)); // add to the height of the SVG based on the number of lines in the tallest information box that will be displayed
echo('<div style="margin-bottom: 50px">');
echo($this->element('goal_graphic', array (
  'curGoal' => $gdtaGoals,
  'mainGoal' => $gdtaMainGoal,
  'totalWidth' => $totalWidth,
  'totalHeight' => $totalHeight,
)));
echo('</div>');
?>