<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm1a";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->setFormDBinfo("Institutions_application", "application_id");

$this->createInput("create_registry", "CHECKBOX");

// $this->scriptHead .= "function emailRegistry(){\n";
// $this->scriptHead .= "	document.defaultFrm.createRegistry.value='1';\n";
// $this->scriptHead .= "}\n";

?>
