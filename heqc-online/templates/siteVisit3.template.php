<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteVisit3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Site Visit > </span>";

$this->scriptTail .= "\n\n showHideAction('next', false); \n\n checkEvaluators(); \n\n";
?>
