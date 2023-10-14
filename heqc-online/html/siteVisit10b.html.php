<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<?php 
			$this->showField("sent_transport_program");
			$this->showField("dosent");

			if (isset($_POST["dosent"]) && ($_POST["dosent"] > 0)) {
						
				$file = array();
				
				$fieldsArr = array();
				$fieldsArr["siteVisit_program_time_from_timeFLD"] = "Start Time";
				$fieldsArr["siteVisit_program_time_to_timeFLD"] = "End Time";
				$fieldsArr["siteVisit_program_activity"] = "Activity";
				$fieldsArr["siteVisit_program_venue"] = "Venue";
				$fieldsArr["siteVisit_program_purpose_textFLD"] = "Purpose";
				$fieldsArr["siteVisit_program_comments_textFLD"] = "Comments";
				
				$programFile = $this->generateReport("generateSiteProgram(".$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID.", \"siteVisit_program\", \"siteVisit_program_id\", ".var_export($fieldsArr, true).", 5, 1)");
				$ext = strrchr($programFile,".");
				copy($programFile, $this->TmpDir."siteVisit-Programme".$ext);
				unlink($programFile);
				$programFile = $this->TmpDir."siteVisit-Programme".$ext;
				array_push($file, $programFile);
				
				$tableHeading = array();
				$tableHeading["PANEL MEMBER"] = 1;
				$tableHeading["AIRFARE"] = 5;
				$tableHeading["SHUTTLE"] = 5;
				$tableHeading["CAR HIRE"] = 2;
			
				$fieldsArr = array();
				$fieldsArr["Persnr_ref"] = "";
				$fieldsArr["airfare_date"] = "Date";
				$fieldsArr["airfare_from"] = "From";
				$fieldsArr["airfare_to"] = "To";
				$fieldsArr["airfare_time"] = "Time";
				$fieldsArr["airfare_reference"] = "REF";
				$fieldsArr["shuttle_date"] = "Date";
				$fieldsArr["shuttle_from"] = "From";
				$fieldsArr["shuttle_to"] = "To";
				$fieldsArr["shuttle_time"] = "Time";
				$fieldsArr["shuttle_reference"] = "REF";
				$fieldsArr["car_hire_date"] = "Date";
				$fieldsArr["car_hire_reference"] = "REF";

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
				$number = mysqli_num_rows($RS);
				$evalsArr = array();
				while ($row = mysqli_fetch_object($RS)) {
					array_push($evalsArr, $row->Persnr_ref."|".$row->Surname.", ".$row->Names);
				}
				$transportFile = $this->generateReport("generateTransportProgram(".$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID.", \"siteVisit_transport\", \"siteVisit_transport_id\", ".var_export($fieldsArr, true).", 5, 1, ".var_export($tableHeading, true).", ".$number.", ".var_export($evalsArr, true).")");
				$ext = strrchr($transportFile,".");
				copy($transportFile, $this->TmpDir."siteVisit-TransportArragements".$ext);
				unlink($transportFile);
				$transportFile = $this->TmpDir."siteVisit-TransportArragements".$ext;
				array_push($file, $transportFile);
				
				$message = nl2br ($this->getTextContent ($this->template, "siteVisitProgramTransportMembers"));
				$subject = "Site visit program";
				mysqli_data_seek($RS, 0);
				echo "<b>Your programme has been sent to the following members:</b><br><br>";
				while ($row = mysqli_fetch_object($RS)) {
					$to = $row->E_mail;
					$this->mimemail ($to, "", $subject, $message, $file);
					echo $row->Names." ".$row->Surname;
					echo "<br>";
				}
			}else {

?>
<table width="75%" border=0  cellpadding="2" cellspacing="2"><tr>
	<td>Once the programme is confirmed, e-mail the programme as well as the arrangements to each panel member. To send these attachments click <a href="javascript:changeValue();moveto('stay');">here</a>
	</td>
</tr></table>
<?php 
			}
?>
</td></tr></table>
<script>
	function changeValue () {
		document.all.dosent.value = 1;
		document.defaultFrm.FLD_sent_transport_program.value = "1";
	}
</script>
