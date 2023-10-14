<?php

$this->cleanTempActiveProccesses ();

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->bodyFooter	= "formFoot";

	$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id and user_id = ".$this->currentUserID." AND status = 0 ORDER BY last_updated DESC";
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	
	if ($this->userInterface != 2) {
		$this->body = "workInternal";
		if (mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
		if (mysqli_num_rows($rs) == 1) {
			if ($row = mysqli_fetch_array ($rs)) {
				//BUG: I think this is where the active process is set when you return to home page and you only have 1 process left.
				//$this->setActiveWorkFlow ($row["active_processes_id"]);
			}
		}
	} else {
		/**
		 * EB & RTN
		 * Date: 2014-08-04 
		 * Note: all users are set to $this->userInterface = 1 on Class.Security.php so never come into here
		 */

		$this->body = "workExternal";

		// mysqli_data_seek($rs, 0);

/*
		if (! (mysqli_num_rows($rs) > 1) ) {
			$this->startFlow (13);
			$this->readTemplate ();
		}else if (mysqli_num_rows($rs) == 1) {
			if ($row = mysqli_fetch_array ($rs)) {
				$this->setActiveWorkFlow ($row["active_processes_id"]);
			}
		}else {
*/

			// only show this action if the user is part of the
			// Institution groups (thus an institutional administrator - normal users have no group)
			$SQLVersion = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = Database() AND TABLE_NAME = 'Institutions_application'  AND COLUMN_NAME='app_version' ";
			$rsVersion = mysqli_query($this->getDatabaseConnection(), $SQLVersion);
			//$rs = mysqli_query($conn, $sql);
			
			while ($row = mysqli_fetch_array($rsVersion)) {
			
				$AVersion = $row['COLUMN_DEFAULT'];		
			
			}
			

			if ($this->sec_userInGroup("Institution")) {
				//$this->createAction ("application", "New Accreditation Application", "href", "?goto=13", "ico_register.gif");
				if ($AVersion == 4){
				$this->createAction ("application_v2", "New Accreditation Application", "href", "?goto=114", "ico_register.gif");
				}else if ($AVersion == 5){
				$this->createAction ("application_v4", "New Accreditation Application v5", "href", "?goto=220", "ico_register.gif");
				//$this->createAction ("application_v4", "New Accreditation Application v5", "href", "?goto=2", "ico_register.gif");
			}
			}

			if ($this->sec_userInGroup("Evaluator")) {
				$persnr = $this->getValueFromTable("Eval_Auditors", "user_ref", $this->currentUserID, "Persnr");
				if ($persnr > 0){
					$edit_link = $this->scriptGetForm ('Eval_Auditors', $persnr, '_lblEvaluatorForm2');
					$edit_link = str_replace('"',"'",$edit_link);

					$this->createAction ("application_v2", "Update evaluator profile", "href", "$edit_link", "ico_eval.gif");
				}


			}
/*
		}
*/
	}

$this->createAction ("inst", "Accreditation Information", "href", "?goto=115", "ico_info.gif");
$this->createAction ("chpasswd", "Change Password", "href", "?goto=9", "ico_pass.gif");
if ($this->sec_userInGroup("Institution")) {
	if ($AVersion == 4){
	$this->createAction ("application_v2", "New Accreditation Application", "href", "?goto=114", "ico_register.gif");
	}else 	if ($AVersion == 5){
    //$this->createAction ("application_v4", "New Accreditation Application v5", "href", "?goto=2", "ico_register.gif");
	$this->createAction ("application_v4", "New Accreditation Application v5", "href", "?goto=220", "ico_register.gif");
	
	}
	$this->createAction ("deleteProgBtn", "Delete selected programmes", "href", "#deleteProgBtn", "ico_info.gif");
}
?>
