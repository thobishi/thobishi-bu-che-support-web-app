<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "evaluatorForm4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluators > Evaluator</span>";

$this->formHidden["DELETE_RECORD"] = "";

$this->scriptHead .= "function checkNewEmplValue() {\n";
$this->scriptHead .= "	if (document.defaultFrm.new_employer.checked == false) {\n";
$this->scriptHead .= "		document.defaultFrm.FLD_employer.value = '';\n";
$this->scriptHead .= "	}else {\n";
$this->scriptHead .= "		document.defaultFrm.FLD_employer_ref.selectedIndex = 0;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";
?>
