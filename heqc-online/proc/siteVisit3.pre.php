<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT Persnr_ref, Names, Surname, E_mail, Title_ref FROM `evalReport`, `Eval_Auditors` WHERE application_ref=? AND evalReport_status_confirm=1 AND Persnr=Persnr_ref ORDER BY Surname";
	
	$sm = $conn->prepare($SQL);
        $sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
        $sm->execute();
        $RS = $sm->get_result();
        
	//$RS = mysqli_query($SQL);

	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if ($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "email_sent_members") != 1) {
			$to = $this->getValueFromTable("users", "user_id", $this->checkUserInDatabase($row["Title_ref"], $row["E_mail"], $row["Surname"], $row["Names"]), "email");
			$message = $this->getTextContent ("siteVisit2", "sitevisit confirmation display");
			$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "email_sent_members", 1);
			$this->misMailByName($to, "letter of appointment", $message);
		}
	}

	if ($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "object_sitevisit_evals") == 2) {
		$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "letterToInstitutionChanged", 1);
		$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "object_sitevisit_evals", 0);
		$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "inst_confirm_visit", 0);
	}

?>
