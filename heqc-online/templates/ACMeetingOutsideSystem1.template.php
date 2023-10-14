<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "ACMeetingOutsideSystem1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting</span>";

$this->formOnSubmit = "return checkConditions();";

$this->formHidden["DELETE_RECORD"] = "";
?>
