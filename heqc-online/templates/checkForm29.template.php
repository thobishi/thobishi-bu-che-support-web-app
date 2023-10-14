<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm29";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";


$this->formOnSubmit = "return checkFeedback ();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "	function checkFeedback () {\n";
$this->scriptTail .= "		q1_checked = q2_checked = false;\n";
$this->scriptTail .= "		if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "			if (document.defaultFrm.FLD_doe_feedback_on_registration.value == '') {\n";
$this->scriptTail .= "				alert('Please fill in the information from DoE');\n";
$this->scriptTail .= "				document.defaultFrm.FLD_doe_feedback_on_registration.focus();\n";
$this->scriptTail .= "				document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "				return false;\n";
$this->scriptTail .= "			}\n";

$this->scriptTail .= "			for (i=0; i < document.defaultFrm.FLD_inst_have_application_registration.length; i++) {\n";
$this->scriptTail .= "				if (document.defaultFrm.FLD_inst_have_application_registration[i].checked) q1_checked = true;\n";
$this->scriptTail .= "			}\n";

$this->scriptTail .= "			for (i=0; i < document.defaultFrm.FLD_inst_have_application_pending.length; i++) {\n";
$this->scriptTail .= "				if (document.defaultFrm.FLD_inst_have_application_pending[i].checked) q2_checked = true;\n";
$this->scriptTail .= "			}\n";

$this->scriptTail .= "			if (!(q1_checked) && !(q2_checked)) {\n";
$this->scriptTail .= "				alert('Please fill in one of the questions regarding the institution.');\n";
$this->scriptTail .= "				document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "				return false;\n";
$this->scriptTail .= "			}\n";

$this->scriptTail .= "		}\n";
$this->scriptTail .= "		return true;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "\n\n";

$this->scriptTail .= "	function checkInstitutionType (obj, other) {\n";
$this->scriptTail .= "		obj_check = other_check = false;\n";
$this->scriptTail .= "		for (i=0; i < obj.length; i++) {\n";
$this->scriptTail .= "			if (obj[i].checked) {\n";
$this->scriptTail .= "				obj_check = true;\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		for (i=0; i < other.length; i++) {\n";
$this->scriptTail .= "			if (other[i].checked) {\n";
$this->scriptTail .= "				other_check = true;\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if (other_check) {\n";
$this->scriptTail .= "			alert('You can only fill in one of the questions.');\n";
$this->scriptTail .= "			for (i=0; i < other.length; i++) {\n";
$this->scriptTail .= "				other[i].checked = false;\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "\n\n";
?>
