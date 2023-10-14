<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteVisit11";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Site Visit > </span>";


$this->scriptTail .= "\n\n";
$this->scriptTail .= "function genRep() {\n";
$this->scriptTail .= "	document.all.generate.value = 1;\n";
$this->scriptTail .= "}\n";
?>
