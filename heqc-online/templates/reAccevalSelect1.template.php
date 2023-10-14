<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reAccevalSelect1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Type</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	if (obj.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (document.defaultFrm.FLD_evaluationType.value == '0') {\n";
$this->scriptTail .= "			alert('Please select the type of evaluation this re-accreditation application will undergo.');\n";
$this->scriptTail .= "		    return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		return true;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>
