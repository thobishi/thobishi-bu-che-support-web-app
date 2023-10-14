<?php 
	//$this->formActions["changeUser"]->actionMayShow = false;
	//$this->formActions["gotoInstitution"]->actionMayShow = false;
//	$this->formActions["cancelProc"]->actionMayShow = false;
	//$this->formActions["continueNorm"]->actionMayShow = false;

	$is_at_manager = 0;
	$is_at_manager = $this->getValueFromTable("screening", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "proc_to_manager");
	$did_payment = 0;

	

	$prov_type = $this->checkAppPrivPubl($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	//if public HEI, we do not need to pay - therefore will have no value for payment. give it one, else it will not execute script!
	$did_payment = ($prov_type == 1) ? $this->getValueFromTable("payment", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "payment_id") : "don't need to pay";

	
	if (!$is_at_manager && !$did_payment) {
		$this->formActions["changeUser"]->actionMayShow = true;
	}
	echo '<input type="hidden" name="screening" value="0">';
		

	if ($is_at_manager) {
		//$this->formActions["next"]->actionMayShow = false;
		//$this->formActions["gotoInstitution"]->actionMayShow = true;
		echo '<input type="hidden" name="gotoInst" value="0">';
		echo '<input type="hidden" name="doCancelProc" value="0">';
		//$this->formActions["cancelProc"]->actionMayShow = true;
		//$this->formActions["continueNorm"]->actionMayShow = true;
	}

?>