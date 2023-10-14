<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT paper_eval_complete FROM `evalReport` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND eval_change_status=1";
	$RS = mysqli_query($conn, $SQL);
	$paper_status = 0;
	if ($RS && ($row=mysqli_fetch_array($RS))) {
		$paper_status = $row["paper_eval_complete"];
	}
	
	$SQL = "UPDATE `evalReport` SET paper_eval_complete=".$paper_status." WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$RS = mysqli_query($conn, $SQL);
	
	$SQL = "UPDATE `evalReport` SET eval_change_status=0, evalReport_status_confirm=0, do_sitevisit_checkbox=1 WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND eval_change_status=1";
	$RS = mysqli_query($conn, $SQL);
	
	
	
	if (isset($_POST["eval_id"]) && ($_POST["eval_id"] > 0)) {
		$SQL = "SELECT evalReport_id FROM `evalReport` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$_POST["eval_id"];
		$RS = mysqli_query($conn, $SQL);
		if ($RS && ($row=mysqli_fetch_array($RS))) {
			$_POST["rec_id"] = $row["evalReport_id"];
		}
	}
	
	mysqli_query($conn, "DELETE FROM evalReport_nominees WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
?>
