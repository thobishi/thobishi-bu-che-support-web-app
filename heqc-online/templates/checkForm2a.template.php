<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm2a";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->formOnSubmit = "return checkDOCSChecked();";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function checkDOCRADIO() {\n";
$this->scriptHead .= "	var checkBoxes = document.defaultFrm;\n";
$this->scriptHead .= "	checkBoxes.FLD_documentation.value = '';\n";
$this->scriptHead .= "	if (checkBoxes.length > 0) {\n";
$this->scriptHead .= "		for (i=0; i<checkBoxes.length; i++) {\n";
$this->scriptHead .= "			if (((checkBoxes.elements[i].name.substr(0, 8)) == 'DOCRADIO') && (checkBoxes.elements[i].checked)) {\n";
$this->scriptHead .= "				checkBoxes.FLD_documentation.value += checkBoxes.elements[i].name.substr(9, checkBoxes.elements[i].name.length)+'|';\n";
$this->scriptHead .= "			}\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	checkBoxes.FLD_documentation.value = checkBoxes.FLD_documentation.value.substr(0,(checkBoxes.FLD_documentation.value.length-1));\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "\n\n";

$this->scriptHead .= "function checkDOCSChecked() {\n";
$this->scriptHead .= "	var flag = checkDOCSChecked_pre();\n";
$this->scriptHead .= "	if (flag == false) {\n";
$this->scriptHead .= "		alert('Please check all documentation before continuing.');\n";
$this->scriptHead .= "		document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "		return chk_submit;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return chk_submit;\n";
$this->scriptHead .= "}\n";


$this->scriptHead .= "function checkDOCSChecked_pre() {\n";
$this->scriptHead .= "	checkDOCRADIO();\n";
$this->scriptHead .= "	var checkBoxes = document.defaultFrm;\n";
$this->scriptHead .= "	chk_submit = true;\n";
$this->scriptHead .= "	if ((document.defaultFrm.MOVETO.value == 'next') && ((document.defaultFrm.FLD_changeTo.value != 1) || (document.defaultFrm.FLD_changeTo.value != 2))) {\n";
$this->scriptHead .= "		if (checkBoxes.length > 0) {\n";
$this->scriptHead .= "			for (i=0; i<checkBoxes.length; i++) {\n";
$this->scriptHead .= "				if (((checkBoxes.elements[i].name.substr(0, 8)) == 'DOCRADIO') && (checkBoxes.elements[i].checked == false)) {\n";
$this->scriptHead .= "					chk_submit = false;\n";
$this->scriptHead .= "					return chk_submit;\n";
$this->scriptHead .= "				}\n";
$this->scriptHead .= "			}\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return chk_submit;\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "\n\n";
?>
