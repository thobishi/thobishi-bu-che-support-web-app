<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	
	// Directorate recommendation has obtained final approval. Default the AC decision o the recommendation. It will then be edited in 
	// the AC Meeting.
	$this->defaultSiteOutcome("HEQC",$site_proc_id);	

?>
