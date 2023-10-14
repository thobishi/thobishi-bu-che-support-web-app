<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalSelect7";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Request additional information</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "	function checkFrm(obj) {\n";
$this->scriptTail .= "	if (obj.MOVETO.value != '_startRequests') {\n";
$this->scriptTail .= "		if (obj.FLD_request_text.value == '') {\n";
$this->scriptTail .= "			alert('Please enter a request before saving or sending it.');\n";
$this->scriptTail .= "			obj.FLD_request_text.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if (obj.FLD_request_date.value <= '1970-01-01') {\n";
$this->scriptTail .= "			alert('Please enter a request date before saving or sending it.');\n";
$this->scriptTail .= "			obj.FLD_request_date.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		return true;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "  return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>
