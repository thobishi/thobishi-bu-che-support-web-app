<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "requestInfoDisplay";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc></span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "	function checkFrm(obj) {\n";
$this->scriptTail .= "	if (obj.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (obj.FLD_response_text.value == '') {\n";
$this->scriptTail .= "			alert('Please enter a response before sending. Please upload a document if required.');\n";
$this->scriptTail .= "			obj.FLD_response_text.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if (obj.FLD_response_date.value <= '1970-01-01') {\n";
$this->scriptTail .= "			alert('Please enter a response date before sending.');\n";
$this->scriptTail .= "			obj.FLD_response_date.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		return true;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>
