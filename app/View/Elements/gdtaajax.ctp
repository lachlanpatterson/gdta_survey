<?php
echo($this->Html->script('jquery'));
echo($this->Html->script('jquery-ui'));
echo($this->Html->script('jqModal'));
echo($this->Html->script('jquery.form'));
echo($this->Html->css('styles'));
echo($this->Html->css('jqModal'));
/*
 * Set scripts for various ajax classes used by the application
* -----
* .clickedit
* This class shows the master DOM element used to do modal overlays for
* all modal forms (#modWin) and is attached to an element that calls the modal form.
* The element's "name" attribute represents the URL to be called and displayed in the
* modal form.  The modal form must contain the necessary classes to perform other
* jqmModal functions which are the .savemodal and .jqmClose classes
* -----
* .ajaxchangeselect
* This class is attached to a form select element and when the element is changed it will
* pass its new value (as a Cake-type URL value) to a URL and then update a container with
* the result with the "linkloc" attribute of the element as the URL and the "container"
* attribute as the DOM element to update with the returned information
* -----
* .ajaxlink
* This class is attached to a form element and when the element is clicked it will
* request a URL and then update a container with the result; the "name" attribute
* of the element is the URL and the "container" attribute is the DOM element to
* update with the returned information
* -----
* .savemodal
* This class is attached to a form element in a modal form only and provides a
* method to submit the form to a URL specified in the "name" attribute and then
* update a resulting DOM element (specified by the "container" attribute with the
  * results.  There are several other element classes needed: .mainModal is the
  * DIV element that contains the entire form and will be hidden before the form
  * loads and while the form is submitting; .modalform is the class of the form
  * that contains the data being submitted, and if a .loading-modal form is present
  * then that element will be shown while the form is in the process of submitting
  * and after the form is submitted the .jqmClose class will be clicked by trigger
  * and this form also acts as a .dropForm class to collect data from any .includelist
  * elements that might be in the modal form
  * -----
  * .ajaxsubmit
  * This class is attached to a clickable element and will perform an ajax submittal
  * of a form.  The form that will be submitted is listed in the "actionform" attribute
  * of the element, the "container" attribute holds the DOM pointer for the element to
  * update with the returned contents, and the "name" attribute holds the URL to
  * submit the form to.  If the "confirm" attribute is set then the text in that
  * attribute will be displayed in a confirm dialog box.
  * -----
  * .formsubmit
  * This class can be attached to an element to cause that element to make a form
  * submit synchronously when clicked.  The DOM id for the form to submit must
  * be in the "actionform" element of the .formsubmit DOM object that is clicked, and
  * the "actionloc" attribute will become the form action value.  If the "tarwin" attribute
  * is set then that value will be used as the target when the form is submitted.
  * -----
  * .dropForm
  * This class is attached to a form that will include data provided by elements
  * from a sortable drag-and-drop list when it is submitted.  The list that provides
  * the data must be the immediate parent of that items that represent the values
  * and must have class .includelist attached to them.  The .includelist items must
  * also have the attribute "sendIn" set to a DOM element that is a hidden form value
  * which will carry the data in the form.  The values returned will be the "name"
  * attribute of each element in the .includelist.  Values will be in a comma delimited
  * string that can be parsed into an array in the controller.
  * -----
  * .sortable
  * This class is attached to a "ul" element that contains sortable items.  By setting
  * the "friend" attribute to the DOM id of another list the lists will become connected
  * for drag-and-drop actions.
  * -----
  * .includelist
  * This is a "ul" element class that is attached to a list that should send data when
  * a form is submitted.  The "sendin" attribute specifies the data array keys that the
  * data will be sent in.  The data is sent to the controller as a comma-delimited list
  * where the actual data is the name of each child element in the .includelist
  * -----
  * .crossadd
  * This class is attached to an element that will append a copy of itself into the
  * element indicated in its "copyover" attribute and will apply a .clickout class
  * to that copy
  * -----
  * .changesize
  * This class is attached to a select element that will change a text field indicated
  * in the "valueheld" attribute of the element with this class.  If the select box has
  * value set to 0 then it will change it from inches to CM, and if value is set to 1
  * then it will change from CM to inches.
  * -----
  * .changeweight
  * Works just like .changesize except switches between KG for 0 and LBs for 1
  * -----
  * .clickout
  * This class will make an element delete itself when clicked
  */
$this->Js->get("document")->event('ready',"\$('#modWin').jqm({ajax: '@name', trigger: '.clickedit', modal: 'true',ajaxText: '".$this->Html->image('ajax_loading2.gif')."'});$('body').append($('.jqmOverlay'));\$('.clickedit').live('click', function(){ \$('#modWin').jqmShow(this);return false;});" .
  "reBind();" .
  "\$('.ajaxlink').live('click', function (event) {flagvar=\$(this).attr('container');\$.ajax({async:true, beforeSend:function (XMLHttpRequest) {\$('#loading-div').fadeIn();}, complete:function (XMLHttpRequest, textStatus) {\$('#loading-div').fadeOut();}, dataType:'html', evalScripts:true, success:function (data, textStatus) {;\$(flagvar).html(data);flashmessage()}, type:'get', url:\$(this).attr('name')});return false});" .
  "\$('.savemodal').live('click', function (event) {\$('.includelist').each(function(index){holder=\$(this);sendIn=\$(holder).attr('sendIn');\$(sendIn).val(function (index, value) {newval='';\$(holder).children().each(function(i,v){newval = newval + ',' + v.name});return newval;});});flagvar=\$(this).attr('container');\$('.modalform').ajaxSubmit({target: flagvar, replace: true, beforeSubmit: function () {\$('.mainModal').hide();\$('.loading-modal').show(); }, success: function () {\$('#modWin').jqmHide();flashmessage()}, url:\$(this).attr('name')});return false});" .
  "\$('.ajaxsubmit').live('click', function (event) {\$('.includelist').each(function(index){holder=\$(this);sendIn=\$(holder).attr('sendIn');\$(sendIn).val(function (index, value) {newval='';\$(holder).children().each(function(i,v){newval = newval + ',' + v.name});return newval;});});diagtxt=\$(this).attr('confirm');if (diagtxt != '') {verify=confirm(diagtxt)} else {verify=true};if (verify) {formvar=\$(this).attr('actionform');flagvar=\$(this).attr('container');\$(formvar).ajaxSubmit({target: flagvar, replace: true, beforeSubmit: function () {alert(flagvar);\$('#loading-div').fadeIn();}, success: function () {\$('#loading-div').fadeOut();flashmessage()}, url:\$(this).attr('name')})};return false});" .
  "\$('.formsubmit').live('click', function (event) {formvar=\$(this).attr('actionform');tarvar=\$(this).attr('tarwin');if (tarvar != undefined && tarvar != '') {\$(formvar).get(0).setAttribute('target',tarvar);} else {\$(formvar).removeAttr('target');}actionvar=\$(this).attr('actionloc');\$(formvar).get(0).setAttribute('action',actionvar);\$(formvar).submit();return false});".
  "\$('.clickedit').live('blur', function (event) {reBind();return false});" .
  "\$('.crossadd').live('click', function (event) {flagvar=\$(this).attr('copyover');cln=\$(this).clone(false);cln.removeClass('crossadd');cln.addClass('clickout');cln.appendTo(flagvar);});" .
  "\$('.clickout').live('click', function (event) {\$(this).remove();return false})"
);
$this->Js->get("document")->event('ajaxStart',"\$('.ajaxchangeselect').attr('disabled', 'disabled')");
$this->Js->get("document")->event('ajaxComplete',"reBind();\$('.ajaxchangeselect').removeAttr('disabled')");
$this->Js->buffer("function reBind () {" .
  "\$('.ajaxchangeselect').unbind();\$('.ajaxchangeselect').bind('change', function (event) {quicklook='#'+\$(this).attr('id')+' option:selected';flagvar=\$(this).attr('container');\$.ajax({async:true, beforeSend:function (XMLHttpRequest) {\$('#loading-div').fadeIn();}, complete:function (XMLHttpRequest, textStatus) {\$('#loading-div').fadeOut();}, dataType:'html', evalScripts:true, success:function (data, textStatus) {\$(flagvar).html(data);reBind()}, type:'post', url:\$(this).attr('linkloc')+'/'+\$(quicklook).val()});});" .
  "\$('.changesize').unbind();\$('.changesize').bind('change', function (event) {txtchg=\$(this).attr('valueheld');if (\$(this).val()==0) {\$(txtchg).val(function() {return (Math.round((\$(txtchg).val()/0.3937007874)*100)/100)});} else {\$(txtchg).val(function() {return (Math.round((\$(txtchg).val()*0.3937007874)*100)/100)});}});" .
  "\$('.changeweight').unbind();\$('.changeweight').bind('change', function (event) {txtchg=\$(this).attr('valueheld');if (\$(this).val()==0) {\$(txtchg).val(function() {return (Math.round((\$(txtchg).val()/2.20462262)*100)/100)});} else {\$(txtchg).val(function() {return (Math.round((\$(txtchg).val()*2.20462262)*100)/100)});}});" .
  "\$('.dropForm').unbind();\$('.dropForm').bind('submit', function (event) {\$('.includelist').each(function(index){holder=\$(this);sendIn=\$(holder).attr('sendIn');\$(sendIn).val(function (index, value) {newval='';\$(holder).children().each(function(i,v){newval = newval + ',' + v.name});return newval;});});return true;});" .
  "\$('.sortable').each(function (index) {\$(this).sortable({appendTo:'body', connectWith:\$(this).attr('friend'), container:'#container', fx:300, ghosting:true, helper:'clone', opacity:0.50000000000})});" .
  "if(typeof(displayhelp) == 'function') { displayhelp();};" .
  "}");
$this->Js->buffer("function flashmessage () {\$.ajax({url:'".$this->Html->url(array("action"=>'getmessages'))."', async:true, dataType:'html', evalScripts:true, success:function (data, textStatus) {\$('#messages').html(data)}, type:'get'})};");
?>