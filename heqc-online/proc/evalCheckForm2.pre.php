<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$set_eval_id = 0;
	if (isset($_POST["eval_id"]) && ($_POST["eval_id"] > 0)) {
		$set_eval_id = $_POST["eval_id"];
		$SQL = "INSERT INTO `evalReport` (application_ref, Persnr_ref) VALUES ('".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."', '".$set_eval_id."')";
		$RS = mysqli_query($conn, $SQL);
	}

	$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr=Persnr_ref";
	$RS = mysqli_query($conn, $SQL);
	$count = $num_rows = mysqli_num_rows($RS);
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if (($row["Spent_time_Management"] > 0) && ($row["eval_change_status"] == 0)) {
			$this->setValueInTable("evalReport", "Persnr_ref", $row["Persnr"], "is_manager", 1);
			$count--;
		}
	}
	$no_manager = false;
	if ($count == $num_rows) {
		if ($set_eval_id > 0) {
			$SQL = "DELETE FROM `evalReport` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID. " AND Persnr_ref=".$set_eval_id;
			$RS = mysqli_query($conn, $SQL);
		}
		$no_manager = true;
	}
?>
