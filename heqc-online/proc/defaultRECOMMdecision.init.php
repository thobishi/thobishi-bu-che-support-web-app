<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "lkp_proceedings_ref");

	// User has been appointed to do the Directorate recommendation. 
	// For a conditional proceedings it is only determined if conditions are met or not.  Thus the recommendation must be preset and contain the conditions
	// from the previous outcome.
	// For the rest of the proceeding types the Recommendation writer will capture the recommendation from scratch.

	//if ($proc_type == 4){ 
	//2017-10-17 Richard: Included conditional re-accreditation
	if (($proc_type == 4) || ($proc_type == 6)){ 
		$this->defaultOutcome("RECOMM",$app_proc_id);	
	}
?>
