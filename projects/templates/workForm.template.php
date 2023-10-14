<?

$this->cleanTempActiveProccesses ();

$this->title		= "CHE Project Register";
$this->bodyHeader	= "formHead";
$this->bodyFooter	= "formFoot";

/*
	$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id and user_id = ".$this->currentUserID." AND status = 0 ORDER BY last_updated DESC";
	$rs = mysqli_query($SQL);
*/

	if ($this->userInterface != 2) {
		$this->body = "workInternal";
/*
		if (mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
		if (mysqli_num_rows($rs) == 1) {
			if ($row = mysqli_fetch_array ($rs)) {
				//BUG: I think this is where the active process is set when you return to home page and you only have 1 process left.
				//$this->setActiveWorkFlow ($row["active_processes_id"]);
			}
		}
*/
	} else {

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


			// only show this action if the user is part of the
			// Institution groups (thus an institutional administrator - normal users have no group)
			if ($this->sec_userInGroup("Institution")) {
				$this->createAction ("inst", "Information", "href", "?goto=14", "ico_info.gif");
				$this->createAction ("application", "New Application", "href", "?goto=13", "ico_register.gif");
			}
/*
		}
*/
	}


$this->createAction ("chpasswd", "Change Password", "href", "?goto=9", "ico_pass.gif");
?>

