<?php 
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$evals = array();

		$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr";
		$RS = mysqli_query($conn, $SQL);

		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$evals[$row["Persnr_ref"]] = 0;
			if ($row["pre_chosen_checkbox"] > 0) {
				$evals[$row["Persnr_ref"]] = 1;
				$nom_sql = "SELECT lop_isSent, evalReport_status_confirm, processed FROM evalReport_nominees WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"];
				$nom_rs = mysqli_query($conn, $nom_sql);
				$lop = 0;
				$status = -1;
				if ($nom_row = mysqli_fetch_array($nom_rs)) {
					$lop = $nom_row["lop_isSent"];
					if ($nom_row["evalReport_status_confirm"] != 0)	$status = $nom_row["evalReport_status_confirm"];
				}
				mysqli_query($conn, "REPLACE INTO `evalReport_nominees` (application_ref, Persnr_ref, do_summary, lop_isSent, evalReport_status_confirm) VALUES (".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.", ".$row["Persnr_ref"].", 0, ".$lop.", ".$status.")");
				if ((isset($_POST["do_summary"]) && ($_POST["do_summary"] > 0)) && ($row["evalReport_id"] == $_POST["do_summary"])) {
					mysqli_query($conn, "UPDATE `evalReport_nominees` SET do_summary=1 WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"]);
				}
			}else {
				mysqli_query($conn, "DELETE FROM `evalReport` WHERE evalReport_id=".$row["evalReport_id"]);
				mysqli_query($conn, "DELETE FROM `evalReport_nominees` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=".$row["Persnr_ref"]);
			}
		}

		$chosen = $discarded = array();
		foreach ($evals AS $key=>$value) {
			$SQL = "SELECT Names, Surname FROM Eval_Auditors WHERE Persnr=".$key;
			$RS = mysqli_query($conn, $SQL);
			if ($RS && ($row=mysqli_fetch_array($RS))) {
				if ($value == 1) array_push ($chosen, $row["Surname"].", ".$row["Names"]);
				if ($value == 0) array_push ($discarded, $row["Surname"].", ".$row["Names"]);
			}
		}

		$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");

		$message = $this->getTextContent ("evalCheckForm1b", "EmailInternalEvalNominationTop");
		$message .= "The following evaluators were accepted.\n";
		foreach ($chosen AS $val) {
			$message .= $val."\n";
		}
		$message .= "\n\nThe following evaluators were rejected.\n";
		foreach ($discarded AS $val) {
			$message .= $val."\n";
		}
		$message .= $this->getTextContent ("evalCheckForm1b", "EmailInternalEvalNominationBottom");

		$this->misMail ($this->getDBsettingsValue ($usr), "Choosing Evaluators", $message);
?>
