<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "lkp_proceedings_ref");

	// Directorate recommendation has obtained final approval. Default the AC decision o the recommendation. It will then be edited in 
	// the AC Meeting.
	$this->defaultOutcome("AC",$app_proc_id);	

//	if ($proc_type != 4){ // no recommendation for conditional proceedings
		// Generate a rtf copy of the Directorate recommendation that will be viewed in an AC Meeting by AC members.
		$fileName = "recomm_" . $app_proc_id . ".rtf";

		$this->generateDocument($app_proc_id,"dir_recomm_document",$fileName,"ia_proceedings","ia_proceedings_id","recomm_doc");

		file_put_contents('php://stderr', print_r($fileName, TRUE));


//	}
?>


