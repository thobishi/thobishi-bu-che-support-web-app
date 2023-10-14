<?php
	$this->title			= "CHE National Reviews";
	$this->bodyHeader		= "formHead";
	$this->body				= "ser_prelim_upload_additional_docs";
	$this->bodyFooter		= "formFoot";
	$this->NavigationBar	= array('Self-evaluation report', 'Manage preliminary analysis and panel members', 'Process site visit - Upload additional information');
	// $this->formFields["nr_programme_id"]->fieldValue = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->formHidden["DELETE_RECORD"] = "";
?>