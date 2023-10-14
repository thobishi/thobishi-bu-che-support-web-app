<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$chair = "";
	$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr";
	$RS_evalReport = mysqli_query($conn, $SQL);
	while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
		$rs2 = mysqli_query($conn, "SELECT do_summary FROM evalReport_nominees WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		if (mysqli_num_rows($rs2) == 0) {
			$rs2 = mysqli_query($conn, "SELECT do_summary FROM evalReport WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		}
		if ($rs2 && ($row2=mysqli_fetch_array($rs2))) {
			if ($row2["do_summary"] > 0) $chair = $row["Surname"].", ".$row["Names"];
		}
	}
?>
