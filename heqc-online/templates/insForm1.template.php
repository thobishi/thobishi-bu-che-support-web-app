<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "insForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution details > Type of Institution</span>";

$this->setFormDBinfo("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "creation_date", "last_updated");

?>
