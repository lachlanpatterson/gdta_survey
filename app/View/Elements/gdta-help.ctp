<div id="gdtatip-maingoal">
	<p class="tip">Tip: Your main goal should be the central objective of your job.</p>
	<p class="tip">For example your objective may be to "complete projects on time,
		stay within budget, and archieve requirements defined by stakeholders."</p>
</div>
<div id="gdtatip-subgoal">
	<p class="tip">Tip: Click the Add Sub-Goal button to add sub-goals.</p>
	<p class="tip">These sub-goals will help you achieve your main goal.</p>
	<p class="tip">For example "stay within budget" might be a sub-goal.</p>
</div>
<div id="gdtatip-decision">
	<p class="tip">To achieve each sub-goal you make important decisions.</p>
	<p class="tip">These are questions that must be answered for you to achieve the
		sub-goal.</p>
	<p class="tip">For example "is the project within budget?" could be a key
		decision for the goal "stay within budget."</p>
</div>
<div id="gdtatip-info">
	<p class="tip">Every decision has key pieces of information required make the
		decision correctly.</p>
	<p class="tip">For example the decision "is the project within budget?" might
		need information like "projected expenditures" and "current funding."</p>
	<p class="tip">Click Add Information to start adding information needs.</p>
</div>
<div id="gdtatip-continue">
	<p class="tip">Keep adding more sub-goals, decisions, and information until you
		can't think of any more.</p>
</div>
<?php 
//$this->Js->get("#SurveyMainGoal")->event('focusin',"if(blankmaingoal()){\$('#gdtatip-maingoal').show()};");
//$this->Js->get("#SurveyMainGoal")->event('focusout',"\$('#gdtatip-maingoal').hide(); displayhelp();");
$this->Js->get("#SurveyMainGoal")->event('keyup', 'displayhelp();');
$this->Js->buffer("function anysubgoals() {if(\$('.hierarchybox')[0]) {return true} else {return false}};");
$this->Js->buffer("function anydecisions() {if(\$('.decisionbox')[0]) {return true} else {return false}};");
$this->Js->buffer("function anyinfo() {if(\$('.infoitem')[0]) {return true} else {return false}};");
$this->Js->buffer("function blankmaingoal() {if(\$('#SurveyMainGoal').val().length < 1) {return true} else {return false}};");
$this->Js->buffer("function displayhelp() {".
  "if(blankmaingoal()){\$('#gdtatip-maingoal').show()};" .
  "if(anysubgoals()) {;".
  "if(anydecisions()) {" .
  "if(anyinfo()) {\$('#gdtatip-continue').show();} else {\$('#gdtatip-info').show()};" .
  "} else {\$('#gdtatip-decision').show();\$('#gdtatip-info').hide();\$('#gdtatip-continue').hide();};" .
  "} else if (!blankmaingoal()) {\$('#gdtatip-subgoal').show();}};"
);
?>