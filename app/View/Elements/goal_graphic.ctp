<script type="image/svg+xml">
<?php echo('<svg xmlns="http://www.w3.org/2000/svg" width="'.$totalWidth.'" height="'.$totalHeight.'" version="1.1" id="embeddedSVG">'); ?>
<defs>

</defs>
<?php

$xStart = 50; //starting point for the left hand side
$x = $xStart; //working cursor x position
$y = 351; // starting point for the top of the row of decision rects, also our working vertical cursor
$decStartX = 0; // marker for the start of the decision lead line
$decEndX = 0; // marker for the end of the decision lead line

foreach ($curGoal as $Objective) {  // loop through each subgoal (i.e. objective) in the goal being drawn
	$xObjStart = $x; // a reference to the horizontal position at the start of drawing each objective
	$decCount = count($Objective['Decision']); // the number of decision boxes in the objective
	foreach ($Objective['Decision'] as $Dec) {  // loop through each decision for the objective
		echo('<rect stroke="rgb(0,0,0)" fill="#99ffcc" stroke-width="2" x="'.$x.'" y="'.$y.'" width="175" height="100" />');  // draw the rectangle for the decision x=left y=top
		write_box_text($x+88, $y+20, $Dec['decision']);  // write the decision in the box
		echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.($x+88).'" y1="'.$y.'" x2="'.($x+88).'" y2="'.($y-25).'"/>'); // draw a vertical line at the halfway point on the box
		$boxHeight = count($Dec['infoLines']) * 16; // calculate the height of the info box
		$boxHeight += 35; // add some for the top line and margin space 
		echo('<rect stroke="rgb(0,0,0)" fill="#aaccff" stroke-width="2" x="'.$x.'" y="'.($y+125).'" width="175" height="'.$boxHeight.'" />');  // draw the rectangle for the information x=left y=top
		write_infobox_text($x+8, $x+88, $y+145, $Dec['infoLines']);  // write the information in the box
		echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.($x+88).'" y1="'.($y+100).'" x2="'.($x+88).'" y2="'.($y+125).'"/>'); // draw a vertical line at the halfway point on the box
		$x += 225;  // move the cursor to the right
	}
	$x += 15; // add some distance between decision boxes for each subgoal
	$midlen = $xObjStart+88; // default for $midlen
	if ($decCount > 1) {
		//  only need this part if we have more than one decision box
		$crosslen = (($decCount-1) * 225)+88;  // the length of the horizontal line connecting the vertical lines from the decision rectangles
		echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.($xObjStart+88).'" y1="'.($y-25).'" x2="'.($xObjStart+$crosslen).'" y2="'.($y-25).'"/>'); // draw a horizontal line connecting the vertical lines
		$midlen = round(($crosslen/2)+$xObjStart+44,0);  // find the middle of the horizontal line
		echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.($midlen).'" y1="'.($y-25).'" x2="'.($midlen).'" y2="'.($y-50).'"/>'); // draw a vertical line up to the decision box
	} elseif ($decCount == 1) {
		echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.$midlen.'" y1="'.($y-25).'" x2="'.$midlen.'" y2="'.($y-50).'"/>'); // draw a vertical line up to the subgoal box
	} else {
		$x += 225;  // move the cursor to the right if we had no decision objects
	}
	echo('<rect stroke="rgb(0,0,0)" fill="#ffeebb" stroke-width="2" x="'.($midlen-88).'" y="'.($y-150).'" width="175" height="100" />');  // draw the rectangle for the subgoal x=left y=top
	echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.($midlen).'" y1="'.($y-150).'" x2="'.($midlen).'" y2="'.($y-175).'"/>'); // draw a vertical line at the halfway point on the box
	if ($decStartX == 0) {  // set the leftmost vertical vertex for a subgoal
		$decStartX = $midlen;
	} 
	$decEndX = $midlen;  // set the farthest right vertical vertex for a subgoal
	write_box_text($midlen, $y-130, $Objective['objective']);  // write the subgoal in the box
}
$subCount = count($curGoal); // count of the number of subgoal boxes

if ($subCount > 1) {
	//  only need this part if we have more than one subgoal box
	echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.$decStartX.'" y1="'.($y-175).'" x2="'.$decEndX.'" y2="'.($y-175).'"/>'); // draw a horizontal line connecting the vertical lines
	$cen = round(($decStartX+$decEndX)/2,0);  // the center point of the horizontal line connecting the vertical lines from the dec rectangles
	$cen -= ($subCount % 2 == 0) ? ((($subCount / 2) - 1) * 8) : 0; // shift the goal over to center it with the vertical line of the nearest adjacent decision
	echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.$cen.'" y1="'.($y-175).'" x2="'.$cen.'" y2="'.($y-200).'"/>'); // draw a vertical line up to the goal box
} elseif ($subCount == 1) {
	$cen = $decStartX;  //  the center if we had only one subgoal
	echo('<line stroke="rgb(0,0,0)" stroke-width="2" x1="'.$cen.'" y1="'.($y-175).'" x2="'.$cen.'" y2="'.($y-200).'"/>'); // draw a vertical line up to the goal box
} else {
	$cen = 88 + $xStart;  // the center if we had no decisions
}
echo('<rect stroke="rgb(0,0,0)" fill="#dd0022" stroke-width="2" x="'.($cen-88).'" y="'.($y-300).'" width="175" height="100"/>');  // draw the rectangle for the goal x=left y=top
write_box_text($cen, $y-280, $mainGoal);  // write the goal in the box
echo('</svg>');
echo('</script>');
?>