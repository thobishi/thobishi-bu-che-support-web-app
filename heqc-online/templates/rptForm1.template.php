<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "rptForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution details > Report</span>";

$this->setFormDBinfo("HEInstitution", "HEI_id");

$this->createField ("HEI_id", "TEXT");

?>
