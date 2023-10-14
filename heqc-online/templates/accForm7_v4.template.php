<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm7_v4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Report</span>";

$this->setFormDBinfo("Institutions_application", "application_id");

// If the application does not have a CHE reference number, give one.
if (! ($this->getValueFromCurrentTable ("CHE_reference_code") > "") ) {
	$inst_id = $this->getValueFromCurrentTable ("institution_id");
	$inst_code = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_code");
	$che_ref = $this->createCHE_reference ("H", $inst_code, "E", "CAN");
	$this->setValueInCurrentTable ("CHE_reference_code", $che_ref);
}

$this->createField("application_id", "TEXT");
?>
