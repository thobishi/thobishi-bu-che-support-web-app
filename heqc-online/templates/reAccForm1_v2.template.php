<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "reAccForm1_v2";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Apply for Re-accreditation</span>";

	$this->formOnSubmit = "return checkFrm(this);";
	
	$this->scriptTail .= "\n\n";
	$this->scriptTail .= "function checkFrm(obj) {\n";
	$this->scriptTail .= "	var flag = false;\n";
	$this->scriptTail .= "	var count = 0;\n";
	$this->scriptTail .= "	if (obj.MOVETO.value == 'next') {\n";
	$this->scriptTail .= "		if (obj.FLD_referenceNumber.value == '0') {\n";
	$this->scriptTail .= "			alert('Please select a HEQC reference code before continuing.');\n";
	$this->scriptTail .= "			obj.FLD_referenceNumber.focus();\n";
	$this->scriptTail .= "			obj.MOVETO.value = '';\n";
	$this->scriptTail .= "			return false;\n";
	$this->scriptTail .= "		}\n";
	$this->scriptTail .= "	}\n";
	$this->scriptTail .= "	return true;\n";
	$this->scriptTail .= "}\n";
	$this->scriptTail .= "\n\n";

?>
