<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Site Information</span>";

$this->formHidden["FLD_HEI_ref"] = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;

$this->formHidden["DELETE_RECORD"] = "";

$this->createAction ("cancel", "Cancel", "href", "javascript:document.defaultFrm.DELETE_RECORD.value='".$this->dbTableCurrent."|".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField."|".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID."';moveto(165);", "");
?>
