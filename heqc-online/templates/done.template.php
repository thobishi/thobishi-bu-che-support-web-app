<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "done";
$this->bodyFooter	= "formFoot";

$this->setFormDBinfo("Institutions_application", "application_id");

//$this->formStatus = FLD_STATUS_DISABLED;

$this->createField("CHE_reference_code", "TEXT");

$this->formOnSubmit = " return checkSenate ();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkSenate () {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (parseInt(printed) == 1) {\n";
$this->scriptTail .= "			return true;\n";
$this->scriptTail .= "		}else {\n";
$this->scriptTail .= "			alert('Please print the application before submitting.');\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n\n";
?>
