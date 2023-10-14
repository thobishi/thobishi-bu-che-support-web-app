<script language="JavaScript" src="js/popupcalendar.js"></script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right" valign="top"><b>Site Name:</b> </td>
	<td class="oncolour" valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Preparation of the programme for the site visit</b></td>
</tr></table>
<input type="hidden" name="report" value="0">
<br>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
	
	$addRowText = "another year of study";
	
	
	$fieldsArr = array();
	$fieldsArr["siteVisit_program_time_from_timeFLD"] = "Start Time";
	$fieldsArr["siteVisit_program_time_to_timeFLD"] = "End Time";
	$fieldsArr["siteVisit_program_activity"] = "Activity";
	$fieldsArr["siteVisit_program_venue"] = "Venue";
	$fieldsArr["siteVisit_program_purpose_textFLD"] = "Purpose";
	$fieldsArr["siteVisit_program_comments_textFLD"] = "Comments";
	
	echo $this->gridDisplay("siteVisit", "siteVisit_program", "siteVisit_program_id", "siteVisit_ref", $fieldsArr, 5, "", "", "", "", 1, "siteVisit_program_template");
	
	if (isset($_POST["report"]) && ($_POST["report"] == 1)) {
?>
	<br><br>
	<table align="center"><tr>
		<td align="center">
		<?php
			$programFile = $this->generateReport("generateSiteProgram(".$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID.", \"siteVisit_program\", \"siteVisit_program_id\", ".var_export($fieldsArr, true).", 5, 1)");
			$ext = strrchr($programFile,".");
			copy($programFile, $this->TmpDir."siteVisit-Programme".$ext);
			unlink($programFile);
			$programFile = $this->TmpDir."siteVisit-Programme".$ext;
			$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
			
			$message = $this->getTextContent ($this->template, "siteVisitProgramTransport");
			
			$this->mimemail ($to, "", "Site visit program", $message, $programFile);
			echo "Your programme has been generated and sent to the institution. </b>";
			
		?>
		</td>
	</tr></table>
<?php 
	}else {
?>
<br><br>
<table><tr>
	<td>Consult with the person responsible for logistics at the institution during the preparation of the programme. Once the programme has been finalized, send a copy to the institution for confirmation.<br><br>
	 <b>Note: Before sending the programme, please consult with the three evaluators.</b><br>
	 <i>Click on the names below to enter the conversation with the evaluators.</i><br><br>
<?php 
	$this->showField("contact_eval");
	$SQL = "SELECT Persnr_ref, Names, Surname, E_mail, is_manager FROM Eval_Auditors, evalReport WHERE eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=?";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS = $sm->get_result();

	//$RS = mysqli_query($SQL);
	while ($row = mysqli_fetch_object($RS)) {
		echo '<a href="javascript:makeContact('.$row->Persnr_ref.');moveto(\'next\')">'.$row->Names." ".$row->Surname.'</a><br>';
	}
?>
		<br><br>
	 To send programme click <a href="javascript:if(checkProgramFilledIn()){makeReport();moveto('stay');}">here</a>.
</td>
</tr></table>
<?php 
	}
?>

</td></tr></table>
<script>
	function makeContact (id) {
		document.defaultFrm.contact_eval.value=id;
	}

	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
	function changeID (newID) {
		document.defaultFrm.id.value = newID;
	}
	
	function checkProgramFilledIn () {
		obj = document.defaultFrm;
		for (i=0; i<obj.length; i++) {
			if (obj.elements[i].name.substr(0,4) == "GRID") {
				if ((obj.elements[i].value == "") || (obj.elements[i].value == "00:00:00")) {
					alert('Please complete the programme before sending it to the institution');
					return false;
				}
			}
		}
		return true;
	}
	
	function makeReport() {
		document.defaultFrm.report.value = '1';
	}
</script>
