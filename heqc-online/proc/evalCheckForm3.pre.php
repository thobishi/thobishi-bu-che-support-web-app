<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT * FROM `Eval_Auditors`, evalReport_nominees WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr=Persnr_ref";
	$RS = mysqli_query($conn, $SQL);
	$files = array();
	array_push($files, WRK_DOCUMENTS."/Code_of_Ethics_and_declaration_of_conflict_of_interest.doc");
	array_push($files, WRK_DOCUMENTS."/GJA_Lubbe.doc");
	
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if ($row["lop_isSent"] == 0) {
			$to = $row["E_mail"];
			$message = nl2br($this->getTextContent ("evalCheckForm2", "Letter of appointment"));
			$this->mimemail ($to, "", "Letter of appointment", $message, $files);
			if ($row["do_summary"] > 0) {
				$message = nl2br($this->getTextContent ("evalCheckForm2", "Letter of appointment to chair person"));
				$this->mimemail ($to, "", "Letter of appointment", $message, $files);
			}
//		$this->misEvalMail($to, "Letter of appointment", $message);
			$SQL = "UPDATE `evalReport` SET lop_isSent=1 WHERE application_ref='".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."' AND Persnr_ref='".$row["Persnr_ref"]."'";
			$updateRS = mysqli_query($conn, $SQL);
			$SQL = "UPDATE `evalReport_nominees` SET lop_isSent=1 WHERE evalReport_nominees_id='".$row["evalReport_nominees_id"]."'";
			$updateRS = mysqli_query($conn, $SQL);
		}
	}
	
	$only_1_eval = 0;
	if (isset($_POST["eval_id"]) && ($_POST["eval_id"] > 0)) {
		$only_1_eval = $_POST["eval_id"];
	}
?>
