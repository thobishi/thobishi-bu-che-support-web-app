<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information > Prerequisites</span>";

$this->formOnSubmit = "return checkPrerequisites();";

$ins_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function checkPrerequisites(){\n";
$this->scriptHead .= "	if (document.defaultFrm.FLD_prerequisites['0'].checked) {\n";
$this->scriptHead .= "		alert('Please get the information required before you can go on.');\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n\n";


?>
