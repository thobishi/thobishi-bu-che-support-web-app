<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "authForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>User Information</span>";

$this->formOnSubmit = "return checkForm();";
$this->scriptHead .= "function checkForm() {\n";
$this->scriptHead .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptHead .= "		if (!document.defaultFrm.new_inst.checked && document.defaultFrm.FLD_institution_ref.value == 0) {\n";
$this->scriptHead .= "			alert('An institution needs to be selected or a new institution must be indicated.');\n";
$this->scriptHead .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "		if (!document.defaultFrm.doAccept[0].checked && !document.defaultFrm.doAccept[1].checked) {\n";
$this->scriptHead .= "			alert('You need to accept or decline the registration.');\n";
$this->scriptHead .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "		if (document.defaultFrm.doAccept[1].checked && document.defaultFrm.FLD_declineReason.value == '') {\n";
$this->scriptHead .= "			alert('A decline reason is required if you are declining the registration application.');\n";
$this->scriptHead .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= " return true;\n";
$this->scriptHead .= "}\n\n";

?>
