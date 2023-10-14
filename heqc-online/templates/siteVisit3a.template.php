<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteVisit3a";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Site Visit > </span>";

$this->formAction = "html/siteVisit3a.iframe.html.php";
$this->formTarget = "resultsFrame";
$this->formName = "searchFrm";

$this->scriptHead .= "function doSearch() {\n";
$this->scriptHead .= "	document.searchFrm.submit();\n";
$this->scriptHead .= "}\n";

?>
