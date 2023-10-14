<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$cond_met = readPost('FLD_condition_complete_ind');

	if ($cond_met == 1){
		$evalReport_id = $this->getValueFromTable("evalReport","ia_proceedings_ref",$app_proc_id, "evalReport_id");
		if ($evalReport_id > 0){
			$this->setValueInTable("evalReport", "evalReport_id", $evalReport_id, "evalReport_completed", 2);
			$this->setValueInTable("evalReport", "evalReport_id", $evalReport_id, "evalReport_date_completed", date("Y-m-d"));
		}
//	2013-12-23 Robin: Recommendation will be done the same as representations. ia_proceedings_recoom, ac and heqc tables will be used.
//ia_conditions_proceedings table will only be used for evaluation of the conditions.
		// Default confirmation to evaluators recommendation
		// $upd = <<<UPDATE
			// UPDATE ia_conditions_proceedings
			// SET recomm_condition_met_yn_ref = eval_condition_met_yn_ref
			// WHERE ia_proceedings_ref = {$app_proc_id}
			// AND ia_proceedings_ref <> 0
// UPDATE;
		// $errorMail = false;
		// mysqli_query($upd) or $errorMail = true;
		// $this->writeLogInfo(10, "SQL-UPDREC", $upd."  --> ".mysqli_error(), $errorMail);
//		
	}	
?>
