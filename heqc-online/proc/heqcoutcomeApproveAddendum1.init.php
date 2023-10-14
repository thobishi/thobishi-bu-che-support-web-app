<?php
	$current_user_id = $this->currentUserID;
	// echo $current_user_id;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$this->setValueInTable('ia_proceedings','ia_proceedings_id',$app_proc_id,'heqc_outcome_approved_user_ref',$current_user_id);
	$this->setValueInTable('ia_proceedings','ia_proceedings_id',$app_proc_id,'heqc_outcome_approved_date',date('Y-m-d'));
	
	$this->defaultOutcome("CONDITIONS",$app_proc_id);
?>