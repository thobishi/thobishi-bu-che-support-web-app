<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
	// Indicate that the application can be assigned to an AC Meeting
	//if ($proc_type == 4){
	//2017-10-17 Richard: Included conditional re-accreditation
	if (($proc_type == 4) || ($proc_type == 6)){ 
		$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_status_ref",1);
	}
?>
