<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteForm4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Site Information</span>";

$this->setFormDBinfo("Sites", "sites_id");

$this->formHidden["DELETE_RECORD"] = "";

$this->createAction ("cancel", "Cancel", "href", "javascript:document.defaultFrm.DELETE_RECORD.value='".$this->dbTableCurrent."|".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField."|".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID."';moveto(165);", "");
?>
