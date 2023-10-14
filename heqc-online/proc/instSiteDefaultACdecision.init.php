<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	
	// Directorate recommendation has obtained final approval. Default the AC decision o the recommendation. It will then be edited in 
	// the AC Meeting.
	$this->defaultSiteOutcome("AC",$site_proc_id);	

	// Flag the application as ready for an AC meeting
	$this->setValueInTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"application_status_ref",1);
	
	// Generate a rtf copy of the Directorate recommendation that will be viewed in an AC Meeting by AC members.
	$fileName = "site_recomm_" . $site_proc_id . ".rtf";

	$this->generateDocument($site_proc_id,"dir_recomm_document_site",$fileName,"inst_site_app_proceedings","inst_site_app_proc_id","recomm_doc");

?>
