<?php
	$this->cleanTempActiveProccesses ();

	$this->title		= "CHE National Reviews";
	$this->bodyHeader	= "formHead";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= array('Home');

	$activeProcesses = $this->getActiveProcess();

	if (Settings::get('userInterface') != 2) {
		$this->body = "workInternal";
		if (count($activeProcesses) == 1) {
			//BUG: I think this is where the active process is set when you return to home page and you only have 1 process left.
			//$this->setActiveWorkFlow ($activeProcesses[0]["active_processes_id"]);
		}
	} else {
		$this->body = "home";
		if ($this->sec_userInGroup("Institution")) {
			$this->createAction ("application_v2", "New Accreditation Application", "href", "?goto=114", "ico_register.gif");
		}

		if ($this->sec_userInGroup("Evaluator")) {
			$persnr = $this->db->getValueFromTable("Eval_Auditors", "user_ref", Settings::get('currentUserID'), "Persnr");
			if ($persnr > 0){
				$edit_link = $this->scriptGetForm ('Eval_Auditors', $persnr, '_lblEvaluatorForm2');
				$edit_link = str_replace('"',"'",$edit_link);

				$this->createAction ("application_v2", "Update evaluator profile", "href", "$edit_link", "ico_eval.gif");
			}
		}
	}

/*
	Need to add an action category to cater for the different actions sections
*/
	
// $this->createAction ("inst", "Accreditation Information", "href", "?goto=115", "ico_info.gif");
// $this->createAction ("chpasswd", "Change Password", "href", "?goto=9", "ico_pass.gif");
?>

