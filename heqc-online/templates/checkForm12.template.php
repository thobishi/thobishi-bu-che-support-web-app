<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body		= "checkForm12";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

	if ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "override_prog_status_SAQA")) {
		$this->skipThisFlow ();
	}
?>
