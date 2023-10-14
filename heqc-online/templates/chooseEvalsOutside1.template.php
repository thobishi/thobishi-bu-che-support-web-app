<?php
$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "chooseEvalsOutside1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation</span>";

$this->formHidden["FLD_application_ref"] = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

?>
