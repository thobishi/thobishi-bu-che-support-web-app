<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$due_arr = array();
	
	$duep = readPost('FLD_condition_prior_due_date');
	//$duep = $this->update_conditions_due_date($app_proc_id,'FLD_condition_prior_due_date','p');
	if ($duep > ''){
		array_push($due_arr, $duep);
	}

	$dues = readPost('FLD_condition_short_due_date');
	//$dues = $this->update_conditions_due_date($app_proc_id,'FLD_condition_short_due_date','s');
	if ($dues > ''){
		array_push($due_arr, $dues);
	}
	
	$duel = readPost('FLD_condition_long_due_date');
	//$duel = $this->update_conditions_due_date($app_proc_id,'FLD_condition_long_due_date','l');
	if ($duel > ''){
		array_push($due_arr, $duel);
	}	

	sort($due_arr);
	
	if ($due_arr[0] > ''){
		$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_decision_due_date",$due_arr[0]);
	}
	
	/* No longer storing conditions due date on ia_proceedings_heqc_decision
	function update_conditions_due_date($app_proc_id, $due_date_field, $condition_term){
		$due_date = readPost($due_date_field);
		if ($due_date > ''){
			$upd = <<<UPDSQL
				UPDATE ia_proceedings_heqc_decision 
				SET condition_due_date = '{$due_date}'
				WHERE ia_proceedings_ref = {$app_proc_id}
				AND condition_term_ref = '{$condition_term}'
UPDSQL;
			$errorMail = false;
			mysqli_query($upd) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $upd."  --> ".mysqli_error(), $errorMail);
			
		}
		return $due_date;
	}	
	*/
?>
