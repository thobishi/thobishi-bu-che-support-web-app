<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "evalStats1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Evaluator Statistics</span>";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function statType (sType) {\n";
$this->scriptTail .= "	document.defaultFrm.sType.value=sType;\n";
$this->scriptTail .= "}\n\n";

?>
