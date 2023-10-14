<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalApprove2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Approve evaluator report</span>";

$this->formHidden["DELETE_RECORD"] = "";  //required if a field of type FILE is on the template.

$this->formOnSubmit = "return checkFrm(document.defaultFrm.readyForACMeeting);";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	if (obj.checked == true) {\n";
$this->scriptTail .= "		document.defaultFrm.FLD_application_status.value = 1;\n";
$this->scriptTail .= "	}else {\n";
$this->scriptTail .= "		document.defaultFrm.FLD_application_status.value = 4;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next')  {\n";
$this->scriptTail .= "		if (!obj.checked) {\n";
$this->scriptTail .= "		    alert('The application needs to be marked as approved and ready to be assigned to an AC Meeting  before continuing.');\n";
$this->scriptTail .= "	        return false;\n";
$this->scriptTail .= "	    }\n";
$this->scriptTail .= "  }\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";
$this->scriptTail .= "if (document.defaultFrm.FLD_application_status.value == 1) {\n";
$this->scriptTail .= "	document.defaultFrm.readyForACMeeting.checked=true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";
$this->scriptTail .= "if (document.defaultFrm.FLD_application_status.value == 4) {\n";
$this->scriptTail .= "	document.defaultFrm.readyForACMeeting.checked=false;\n";
$this->scriptTail .= "}\n";
?>
