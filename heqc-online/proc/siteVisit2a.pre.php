<?php 
	$SQL = "SELECT Persnr_ref, Names, Surname FROM `evalReport`, `Eval_Auditors` WHERE do_sitevisit_checkbox=1 AND application_ref='".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."' AND evalReport_status_confirm=1 AND Persnr=Persnr_ref ORDER BY Surname";
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	
	$RS = mysqli_query($conn, $SQL);
	$pers_arr = array();
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		$pers_arr[$row["Surname"].", ".$row["Names"]] = $row["Persnr_ref"];
	}
	$response_pers = $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "responsible_person_ref");
?>
