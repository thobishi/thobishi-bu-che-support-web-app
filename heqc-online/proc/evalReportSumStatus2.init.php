<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$SQL = "SELECT * FROM evalReport WHERE application_ref =".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." and evalReport_status_confirm=1";
	$RS = mysqli_query($conn, $SQL);
	$decline = false;
	$eval_arr = array();
	$active_proc = "";
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if ($row["accept_summary"] == 1) {
			$decline = true;
		}
		array_push($eval_arr, $row["active_process_ref"]);
		if ($row["evalReport_id"] == $this->dbTableInfoArray["evalReport"]->dbTableCurrentID) {
			$active_proc = "73"."|".$this->getValueFromTable("users", "email", $this->getValueFromTable("Eval_Auditors", "Persnr", $row["Persnr_ref"], "E_mail"), "user_id")."|"."0"."|"."0"."|"."false"."|".$this->makeWorkFlowStringFromCurrent ("evalReport", "evalReport_id", $row["evalReport_id"]);
		}
	}
	if ($decline) {
		$active_proc_arr = explode("|", $active_proc);
		$this->addActiveProcesses ($active_proc_arr[0], $active_proc_arr[1], $active_proc_arr[2], $active_proc_arr[3], $active_proc_arr[4], $active_proc_arr[5]);
		$this->setValueInTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "summary_done", 0);
		foreach ($eval_arr AS $value) {
			mysqli_query($conn, "UPDATE `active_processes` SET work_flow_ref=0, status=0 WHERE active_processes_id=".$value);
		}
	}else {
		$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
		$new_user = $this->getDBsettingsValue($usr);
		$this->addActiveProcesses (75, $new_user);
	}
?>
