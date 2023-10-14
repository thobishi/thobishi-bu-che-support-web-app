<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "rptForm3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution details > Report</span>";

$this->setFormDBinfo("Institutions_application", "application_id");

$this->createField("application_id", "TEXT");


?>
