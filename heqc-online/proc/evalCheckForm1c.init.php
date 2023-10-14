<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr";
echo "<br><br>".$SQL;
	$RS = mysqli_query($conn, $SQL);
	
	$upd1 = "UPDATE `evalReport` SET do_summary=0 WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
echo "<br><br>".$upd1;
	mysqli_query($conn, $upd1);
	
	$upd2 = "UPDATE `evalReport_nominees` SET processed=0 WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
echo "<br><br>".$upd2;
	mysqli_query($conn, $upd2);

	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if ($row["pre_chosen_checkbox"] > 0) {
			//mysqli_query("DELETE FROM `evalReport_nominees` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"]);
			$nom_sql = "SELECT lop_isSent, evalReport_status_confirm, processed FROM evalReport_nominees WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"];
			$nom_rs = mysqli_query($conn, $nom_sql);
			$lop = 0;
			$status = -1;
			$processed = 1;
			if ($nom_row = mysqli_fetch_array($nom_rs)) {
				$lop = $nom_row["lop_isSent"];
				if ($nom_row["evalReport_status_confirm"] != 0)	$status = $nom_row["evalReport_status_confirm"];
			}
			$do_sum = (((isset($_POST["do_summary"]) && ($_POST["do_summary"] > 0)) && ($row["evalReport_id"] == $_POST["do_summary"])))?(1):(0);

			$rpl1 = "REPLACE INTO `evalReport_nominees` (application_ref, Persnr_ref, do_summary, lop_isSent, evalReport_status_confirm, processed) VALUES (".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.", ".$row["Persnr_ref"].", ".$do_sum.", ".$lop.", ".$status.", ".$processed.")";
echo "<br><br>".$rpl1;
			mysqli_query($conn, $rpl1);

			$upd3 = "UPDATE `evalReport` SET evalReport_status_confirm=".$status.", lop_isSent=".$lop." WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"];
echo "<br><br>".$upd3;
			mysqli_query($conn, $upd3);

			if ($do_sum) {
				$upd4 = "UPDATE `evalReport` SET do_summary=1 WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"];
echo "<br><br>".$upd4;
				mysqli_query($conn, $upd4);
			}
			}else {
				$del1 = "DELETE FROM `evalReport` WHERE evalReport_id=".$row["evalReport_id"];
echo "<br><br>".$del1;
				mysqli_query($conn, $del1);
			}
		}
		
		$del2 = "DELETE FROM `evalReport_nominees` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND processed=0";
echo "<br><br>".$del2;
		mysqli_query($conn, $del2);
?>
