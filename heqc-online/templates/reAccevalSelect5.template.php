<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reAccevalSelect5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Upload evaluator report</span>";

$this->formHidden["DELETE_RECORD"] = "";
/* 2014-10-07 Robin: Commenting out because the field is not on the page and is preventing users from closing the page.
$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	var anyChecked = 0;\n";
$this->scriptTail .= "	if (obj.MOVETO.value == 'next')  {\n";
$this->scriptTail .= "	    if (!document.defaultFrm.readyForApproval.checked){ \n";
$this->scriptTail .= "		    alert('The application needs to be marked as ready for management approval before continuing.');\n";
$this->scriptTail .= "	        return false;\n";
$this->scriptTail .= "	    }\n";
$this->scriptTail .= "  }\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";
*/
?>
