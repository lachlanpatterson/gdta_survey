<?php

//variables and functions to handle calculating character display sizes

function get_str_width($str){
	//get the display width (in pixels) of a string
	global $fontwidth,$char_relwidth;
	$result = 0;
	for ($i=0;$i<strlen($str);$i++){
		$result += $char_relwidth[ord($str[$i])];
	}
	$result = $result * $fontwidth;
	return $result;
}

function return_text_array($str, $width){
	global $fontwidth,$char_relwidth;
	$strClip = $str;
	$newArr = array();  // array of return strings
	if (is_null($fontwidth)) {
		$fontwidth = 10;
	}
	if (is_null($char_relwidth = null)) {
		//each chargroup has char-ords that have the same proportional displaying width
		$chargroup[0] = array(64);
		$chargroup[1] = array(37,87,119);
		$chargroup[2] = array(65,71,77,79,81,86,89,109);
		$chargroup[3] = array(38,66,67,68,72,75,78,82,83,85,88,90);
		$chargroup[4] = array(35,36,43,48,49,50,51,52,53,54,55,56,57,60,61,62,63, 69,70,76,80,84,95,97,98,99,100,101,103,104,110,111,112, 113,115,117,118,120,121,122,126);
		$chargroup[5] = array(74,94,107);
		$chargroup[6] = array(34,40,41,42,45,96,102,114,123,125);
		$chargroup[7] = array(44,46,47,58,59,91,92,93,116);
		$chargroup[8] = array(33,39,73,105,106,108,124);

		//how the displaying widths are compared to the biggest char width
		$chargroup_relwidth[0] = 1; //is char @
		$chargroup_relwidth[1] = 0.909413854;
		$chargroup_relwidth[2] = 0.728241563;
		$chargroup_relwidth[3] = 0.637655417;
		$chargroup_relwidth[4] = 0.547069272;
		$chargroup_relwidth[5] = 0.456483126;
		$chargroup_relwidth[6] = 0.36589698;
		$chargroup_relwidth[7] = 0.275310835;
		$chargroup_relwidth[8] = 0.184724689;

		//build fast array
		$char_relwidth = null;
		for ($i=0;$i<count($chargroup);$i++){
			for ($j=0;$j<count($chargroup[$i]);$j++){
				$char_relwidth[$chargroup[$i][$j]] = $chargroup_relwidth[$i];
			}
		}
	}
	$width = $width/$fontwidth; // relative character width
	for ($n=0;$n<6;$n++) {
		if (strlen($strClip) <= 0) {
			break;
		}
		$w = 0; // substring width
		for ($i=0;$i<strlen($strClip);$i++){
			// step through the string and stop when we are over the max width
			$z = ord($strClip[$i]);
			array_key_exists($z, $char_relwidth) ? $w += $char_relwidth[$z] : $w += 1;
			if ($w >= $width) {
				break;
			}
		}
		if ($n==5) {
			$addThis = substr($strClip,0,$i-3).'...';
		} else {
			$subSlice = substr($strClip,$i-2,4);
			$posCheck = strrpos($subSlice, " ");
			$addThis = '';
			if ($posCheck === 0) {
				$i--;
			} elseif ($posCheck === 1) {
				$i--;
			} elseif ($posCheck === 2) {
				$i=$i;
			} elseif ($posCheck === 3) {
				$i++;
			} elseif ($posCheck === false) {
				(strlen($strClip) > ($i+1)) ? $addThis = "-" : $i++;
			}
			$addThis = substr($strClip,0,$i).$addThis;
		}
		$newArr[] = $addThis;
		$strClip = substr($strClip,$i);
	}
	return $newArr;
}

function write_box_text($center = null, $yFirst = null, $fullString = null) {
	/**
	 * function write_box_text
	 *
	 * Writes text in a goal, subgoal, or decision box
	 *
	 * @param int $center -> the center x position of the box, used for centering text
	 * $yFirst -> the y position of the first line of text in the box
	 * $fullString -> the full string of text to put in the box
	 */
	echo('<g font-size="10pt" font-family="arial">');  // enclose the text in a group
	$writeMe = return_text_array($fullString,132);  // get the text split into an array of lines of the correct length and hyphenation
	foreach ($writeMe as $writeThis) {
		echo('<text text-anchor="middle" x="'.($center).'" y="'.$yFirst.'">'.str_replace("&","&amp;",strip_tags($writeThis)).'</text>');  //  print each line of text
		$yFirst += 14;  // move the vertical cursor for each line of text
	}
	echo('</g>');
}

function write_infobox_text($left = null, $center = null, $yFirst = null, $info = null) {
	/**
	 * function write_infobox_text
	 *
	 * Writes text in a information box
	 *
	 * @param int $left -> the left x position of the box, used for left justifying text
	 * @param int $center -> the center x position of the box, used for centering text
	 * $yFirst -> the y position of the first line of text in the box
	 * $info -> the array of info text to put in the box
	 */
	echo('<g font-size="10pt" font-family="arial">');  // enclose the text in a group
	echo('<text text-anchor="middle" text-decoration="underline" x="'.($center).'" y="'.$yFirst.'">'.'Information Needs:'.'</text>');
	$yFirst += 20;
	foreach ($info as $writeThis) {
		echo('<text text-anchor="left" x="'.($left).'" y="'.$yFirst.'">'.$writeThis.'</text>');
		$yFirst += 16;
	}
	echo('</g>');
}

function setup_info_items(&$hArr = null) {
	/**
	 * function setup_info_lines
	 * -----
	 * Iterates through decisions and organizes information into an array of text lines
	 * and returns the maximum number of lines used for information among all of the decisions
	 * -----
	 * $hArr -> array of the hierarchy, passed by reference
	 */
	$maxLines = 1; // start with one line, which is just the header of the box
	foreach ($hArr as &$curGoal) {
		foreach ($curGoal['Decision'] as &$curDec) {
			$curDec['infoLines'] = array(); //start with a blank array of lines
			$countline = 0; // start line count at 0
			foreach ($curDec['Information'] as $curInfo) {
				$lineArr =  return_text_array($curInfo['information'],148); // get an array that composes this piece of information broken into lines
				if (count($lineArr) > 0) {
					$lineArr[0]='&#x2022;&#x0020;'.str_replace("&","&amp;",strip_tags($lineArr[0]));
				}
				$countline += count($lineArr); // start the line count at the number of complete lines
				foreach ($lineArr as $line) {
					// add each line on to the array of lines for this decision
					array_push($curDec['infoLines'], $line); // add a special character at the first line, like a bullet list
				}
				if ($countline > $maxLines) {
					// if this list of info is longer than any so far, increase the max line count
					$maxLines = $countline;
				}
			}
		}
	}
	return $maxLines;
}

function unichr($u) {
	/**
	 * Return unicode char by its code
	 *
	 * @param int $u
	 * @return char
	 */
	return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
}
?>