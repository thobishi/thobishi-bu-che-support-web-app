<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body		= "MakeACReportForm1";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>AC Meeting > Meeting</span>";
	$this->formOnSubmit = "return checkFiles();";
	
	$this->formHidden["DELETE_RECORD"] = "";
?>

