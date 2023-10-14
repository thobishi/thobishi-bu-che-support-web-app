<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br>
<?php 
	if (isset($_POST["evaluators"]) && ($_POST["evaluators"] > "")) {
		$this->updateMultiplePipedValuesInTable ("Persnr_ref", $_POST["evaluators"], "evalReport", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "eval_site_visit_status", 1, 0);
	}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="center" colspan="3">The following table shows the names of the suggested panel members:</td>
</tr></table>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Subject Specialist 1</b></td>
	<td><b>Subject Specialist 2</b></td>
	<td><b>QA manager</b></td>
</tr><tr>
<?php 
	$this->showField("email_sent_members");
	$SQL = "SELECT Names, Surname, E_mail, Title_ref, evalReport_id FROM Eval_Auditors, evalReport WHERE active=1 AND eval_site_visit_status=1 AND Persnr_ref=Persnr AND application_ref=? ORDER BY Surname, Names";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$rs = $sm->get_result();

	//$rs = mysqli_query($SQL);
	while ($row = mysqli_fetch_array($rs)) {
		echo '<td>'.$row["Surname"].', '.$row["Names"].'</td>'."\n";
		if ($this->formFields["email_sent_members"]->fieldValue < 1) {
			$eval_user_id = $this->checkUserInDatabase($row["Title_ref"], $row["E_mail"], $row["Surname"], $row["Names"]);
			$message = $this->getTextContent($this->template, "sitevisit confirmation");
			$this->misMail($eval_user_id, "Site Visit", $message);
		}
	}

	$SQL = "SELECT Persnr_ref, Names, Surname, E_mail, Title_ref, is_manager FROM Eval_Auditors, evalReport WHERE evalReport_status_confirm=1 AND Persnr=Persnr_ref AND application_ref=?";
	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS = $sm->get_result();

	//$RS = mysqli_query($SQL);
	while ($row = mysqli_fetch_array($RS)) {
		if ($row["is_manager"] == 1) {
			echo '<td>'.$row["Surname"].', '.$row["Names"].'</td>';
			if ($this->formFields["email_sent_members"]->fieldValue < 1) {
				$eval_user_id = $this->checkUserInDatabase($row["Title_ref"], $row["E_mail"], $row["Surname"], $row["Names"]);
				$message = $this->getTextContent($this->template, "sitevisit confirmation");
				$this->misMail($eval_user_id, "Site Visit", $message);
				echo '<script>';
				echo 'document.all.FLD_email_sent_members.value = 1;';
				echo '</script>';
			}
		}
	}
?>
</tr></table>
</td></tr></table>
