<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reAccevalSelect";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Checklist</span>";

$this->formAction = "html/evalSelect.iframe.html.php";
$this->formTarget = "resultsFrame";
$this->formName = "searchFrm";

$this->scriptHead .= "function doSearch() {\n";
$this->scriptHead .= "	document.searchFrm.submit();\n";
$this->scriptHead .= "}\n";

?>
