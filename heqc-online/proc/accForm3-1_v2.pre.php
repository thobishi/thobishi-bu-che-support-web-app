<?php 

	$adm = $this->getInstitutionAdministrator($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	if ($this->currentUserID != $adm[0]){
		$this->formActions["previous"]->actionMayShow = false;
	}
	
	// 2010-02-08 Robin: Replaced by assigned administrator and not administrator who started the application.
	// switch the previous oof if we are not the Administrator of the Program
	//if ($this->currentUserID != $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref")) {
	//	$this->formActions["previous"]->actionMayShow = false;
	//}


?>
