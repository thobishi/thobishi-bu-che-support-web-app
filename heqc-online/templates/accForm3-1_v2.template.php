<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm3-1_v2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Programme design</span>";

$this->formOnSubmit = "return checkDocumentsSelected(1, ".$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ").");";

$this->formHidden["DELETE_RECORD"] = "";
?>
