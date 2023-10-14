<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm1b";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->setFormDBinfo("Institutions_application", "application_id");

//$this->formOnSubmit = "return checkCreateRegistry(document.all.defaultFrm.create_registry)";

$this->createField("CHE_reference_code", "TEXT");

?>
