<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body	= "RefineACMeetingForm0";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>AC Meeting > Meeting</span>";
	$this->formOnSubmit = "return checkMembers();";
	$this->scriptTail .= "showHideAction('next', false);\n\n";
	$this->scriptTail .= "checkMembers();\n\n";
?>

