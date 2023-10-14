<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteApplicPanel1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Assign panel members to site visit</span>";

$this->formAction = "html/evalSelect.iframe.html.php";
$this->formTarget = "resultsFrame";
$this->formName = "searchFrm";

$this->scriptHead .= "function doSearch() {\n";
$this->scriptHead .= "	document.searchFrm.submit();\n";
$this->scriptHead .= "}\n";

?>
