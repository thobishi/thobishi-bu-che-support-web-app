<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Insert sites if they have been selected but don't already exist.

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
//echo "<br><br>The application ID is:  ".$app_id."<br><br>";

	$sel = <<<selectSQL
	SELECT * FROM lkp_sites
	WHERE application_ref = $app_id;
selectSQL;

	$selrs = mysqli_query($conn, $sel);
	if ($selrs){
		while ($row = mysqli_fetch_array($selrs)){
			$chk = <<<chkSQL
				SELECT institutional_profile_sites_ref 
				FROM ia_criteria_per_site 
				WHERE application_ref = $app_id 
				AND institutional_profile_sites_ref = $row[sites_ref]
chkSQL;
			$chkrs = mysqli_query($conn, $chk);
			$n = mysqli_num_rows($chkrs);
			if ($n == 0){
				$sql = <<<insertSQL
					INSERT IGNORE INTO ia_criteria_per_site (application_ref, institutional_profile_sites_ref)
					VALUES ($row[application_ref], $row[sites_ref]);
insertSQL;
//echo $sql . "<br><br>";
				$sqlrs = mysqli_query($conn, $sql);
//echo "Rows inserted: ".mysqli_affected_rows()."<br><br>";
			}
		}
	}

?>
