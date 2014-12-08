<?php
class FormatHelper extends AppHelper {
	
	
	function displayQuestionType($val) {
		switch ($val) {
			case SHORT_ANSWER:
				return "Short Answer";
			case SCALE_ONE_T0_TEN:
				return "Scale One to Ten";
			case TRUE_OR_FALSE:
				return "True or False";
			case YES_OR_NO:
				return "Yes or No";
			case CHOOSE_ONE:
				return "Choose One";
			case MULTI_SELECT:
				return "Multi-Select";
			default:
				return "-ERROR-";
		}
	}
}
?>