<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalSelect3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Checklist</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	var anyChecked = 0;\n";
$this->scriptTail .= "	var count = 0;\n";
$this->scriptTail .= "	if (obj.MOVETO.value == 'next') {\n";

//Checking radio buttons for evaluators to accept
$this->scriptTail .= "    for(i=0; i<document.defaultFrm.elements.length;i++){ \n";

/*
//Taken out: Rebecca, 10 October 2007
//setting value to NULL if evaluator declined/not answered
$this->scriptTail .= "	    if ((document.defaultFrm.elements[i].checked) && ((document.defaultFrm.elements[i].value == 2) || (document.defaultFrm.elements[i].value == 3))){ \n";
$this->scriptTail .= "	    	document.defaultFrm.elements[i].value = '';\n";
$this->scriptTail .= "	    } \n";
*/

$this->scriptTail .= "	    if ((document.defaultFrm.elements[i].checked) && (document.defaultFrm.elements[i].name.match('evalReport_id')) && (document.defaultFrm.elements[i].value == 1)){ \n";
$this->scriptTail .= "		    anyChecked++;\n";
$this->scriptTail .= "	    }\n";
$this->scriptTail .= "	  }\n";
$this->scriptTail .= "    if (anyChecked == 0){ \n";
$this->scriptTail .= "	    alert('At least one evaluator needs to accept in order to continue');\n";
$this->scriptTail .= "	    obj.MOVETO.value = '';\n";
$this->scriptTail .= "	    return false;\n";
$this->scriptTail .= "	  }\n";

//Checking that "access until" date has been entered
$this->scriptTail .= "	if ((document.defaultFrm.FLD_evaluator_access_end_date.value == '1000-01-01') || (document.defaultFrm.FLD_evaluator_access_end_date.value == '')) {\n";
$this->scriptTail .= "		alert('Please enter a deadline date for evaluator access.');\n";
$this->scriptTail .= "	    return false;\n";
$this->scriptTail .= "	}\n";

$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>
