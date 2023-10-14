<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "evaluatorFormSpecialisation";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluators > Evaluator > Specialisation</span>";

array_push($this->scriptFile, "js/TreeMenu.js");

$this->formOnSubmit = "return selectAll();";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function selectAll() {\n";
$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_Specialisations[]'].length;\n";
$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
$this->scriptHead .= "		document.defaultFrm.elements['FLDS_Specialisations[]'].options[i].selected = true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

?>
