<?php
require("svgtools.php");
echo($this->Form->button('Go Back to Responses',
  array(
    'type' => 'button',
    'div' => false,
    'onClick' => "location.href='".$this->Html->url(array('controller' => 'responses', 'action' => 'view', $respondentId))."'",
    'style' => 'margin: 10px'
  ))
);
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