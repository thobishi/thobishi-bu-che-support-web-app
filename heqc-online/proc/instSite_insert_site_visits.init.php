<?php	
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error ($conn) ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id", $site_app_id, "institution_ref");

	$site_visit_arr = readPost('site_id');
	if ($site_visit_arr == '') $site_visit_arr = array();

	$saved_sites = array();

	// Check if inserted
	$sql = <<<CHECK
		SELECT institutional_profile_sites_ref 
		FROM inst_site_visit
		WHERE inst_site_app_proc_ref = $site_app_id
CHECK;
	$rs = mysqli_query($conn, $sql);
	if ($rs){
		while ($row = mysqli_fetch_array($rs)){
			array_push($saved_sites,$row["institutional_profile_sites_ref"]);
		}
		foreach ($site_visit_arr AS $site_id){
			if (!in_array($site_id,$saved_sites)){
				$ins = <<<INS
					INSERT INTO inst_site_visit (inst_site_app_proc_ref, institution_ref, institutional_profile_sites_ref)
					VALUES ($site_app_id, $inst_id, $site_id)
INS;
				if (! mysqli_query ($conn, $ins) ) {
					$this->writeLogInfo(10, "SQL-instSite_insert_site_visits-PROC", $SQL."  --> ".mysqli_error($conn), true);
				}
			}
		}
	}
?>
