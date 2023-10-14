<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reAccnewSubmission";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > New Reaccreditation Submission</span>";

$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
$che_ref_no = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reaccred_id,"referenceNumber");
$app_id = $this->getValueFromTable("Institutions_application","CHE_reference_code",$che_ref_no,"application_id");
$this->setFormDBinfo("Institutions_application", "application_id",$app_id);

?>
