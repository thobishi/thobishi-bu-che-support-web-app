<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "payCheckForm3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment > Checklist</span>";

$this->formOnSubmit = "return checkConfirmation(document.defaultFrm.receive_confirmation)";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function checkQuery(obj){\n";
$this->scriptHead .= "	if (!(obj.checked == true)) {\n";
$this->scriptHead .= "		alert('Please check the box first.');\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	moveto('stay');\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "function checkConfirmation(obj){\n";
$this->scriptHead .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptHead .= "		if (obj.checked) {\n";
$this->scriptHead .= "			document.defaultFrm.FLD_received_confirmation.value=obj.value;\n";
$this->scriptHead .= "			return true;\n";
$this->scriptHead .= "		}else {\n";
$this->scriptHead .= "			if ((document.defaultFrm.FLD_received_confirmation.value != 2) && (document.defaultFrm.FLD_received_confirmation.value != 4) && (document.defaultFrm.FLD_received_confirmation.value != 10)) {\n";
$this->scriptHead .= "				alert('You must have received confirmation of payment before continuing or save and come back later.');\n";
$this->scriptHead .= "				document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "				return false;\n";
$this->scriptHead .= "			}\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "if (document.defaultFrm.FLD_received_confirmation.value == 1) {\n";
$this->scriptTail .= "	document.defaultFrm.receive_confirmation.checked=true;\n";
$this->scriptTail .= "}\n";

//$this->scriptTail .= "showHideAction('next', false);\n\n";
// robin 2010-03-18: Commented out the rest of the function and the calls to it from the field
// and template because showHideAction was commented out thus obsolete
//$this->scriptTail .= "checkRecieved (document.all.receive_confirmation);\n\n";
?>
