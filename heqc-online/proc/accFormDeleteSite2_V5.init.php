<?php

	function delete_data($dsql,$app){
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }

		$errorMail = false;
		mysqli_query($conn, $dsql) or $errorMail = true;
		$app->writeLogInfo(10, "SQL-DELREC", $dsql."  --> ".mysqli_error($conn), $errorMail);
	}
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

	$app_id  = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$ia_site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	if ($app_id > 0  and $ia_site_id > 0){

		// get sites id in order to identify lkp_sites record to delete.	
		$sql = "select institutional_profile_sites_ref from ia_criteria_per_site where ia_criteria_per_site_id = $ia_site_id";
		$rs = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($rs);
		$inst_prof_site_id = $row["institutional_profile_sites_ref"];	

		if ($inst_prof_site_id > 0){							
			$del_lkp_sites_rec = "delete from lkp_sites where application_ref = $app_id and sites_ref = $inst_prof_site_id";
			delete_data($del_lkp_sites_rec,$this);

			$del_ia_criteria_per_site = "delete from ia_criteria_per_site where ia_criteria_per_site_id = $ia_site_id";
			delete_data($del_ia_criteria_per_site,$this);

			$del_ias_staff_details = "delete from ias_staff_details where ia_criteria_per_site_ref =  $ia_site_id";
			delete_data($del_ias_staff_details,$this);

			$this->writeAuditTrail($this->active_processes_id, "REMOVE SITE", "Site deleted for application-".$del_lkp_sites_rec);

		}
	}
?>
