<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$addApplic = readPost('addApplic');
	$removeApplic = readPost('removeApplic');

	if (is_array($removeApplic)):
		foreach ($removeApplic as $svp_id):
			$del = "DELETE FROM inst_site_visit_progs 
				WHERE inst_site_visit_progs_id = $svp_id";

			if (! mysqli_query ($conn, $del) ):
					$this->writeLogInfo(10, "SQL-instSite_insert_programmes-PROC", $del."  --> ".mysqli_error($conn), true);
			endif;
		endforeach;
	endif;	
	
	if (is_array($addApplic)):
		foreach ($addApplic as $app_id):
			$ins = "INSERT INTO inst_site_visit_progs (site_visit_ref, application_ref)
				VALUES ($site_visit_id, $app_id)";
			if (! mysqli_query ($conn, $ins) ):
					$this->writeLogInfo(10, "SQL-instSite_insert_programmes-PROC", $ins."  --> ".mysqli_error($conn), true);
			endif;
		endforeach;
	endif;
?>
