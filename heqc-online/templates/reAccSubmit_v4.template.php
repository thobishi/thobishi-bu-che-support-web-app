<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reAccSubmit_v4";
$this->bodyFooter	= "formFoot";

$this->setFormDBinfo("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id");

$this->createField("referenceNumber", "TEXT");

$this->formOnSubmit = " return checkSenate ();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkSenate () {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (parseInt(printed) == 1) {\n";
$this->scriptTail .= "			return true;\n";
$this->scriptTail .= "		}else {\n";
$this->scriptTail .= "			alert('Please print the reaccreditation application before submitting.');\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n\n";


$user_id = $this->currentUserID;
$app_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

?>
