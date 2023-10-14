<br />
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
        $conn = $this->getDatabaseConnection();
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$heqcMeetingPassed = "";
	$this->getHEQCMeetingTableTop($heqc_meeting_id);
	$HEQC_Meeting_date = $this->getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meeting_id, "heqc_start_date");
	$SQL = <<<ACCESSDAYS
                SELECT s_value FROM `settings`
                WHERE s_key = 'heqc_meeting_days_access'
ACCESSDAYS;
        $rs = mysqli_query($conn, $SQL);
        $rs->data_seek(0);
        $dataRow = $rs->fetch_array();
        $accessDays = $dataRow[0];
        
	//$accessDays = mysqli_result(mysqli_query($SQL),0);
	$HEQC_Meeting_date = date('Y-m-d', strtotime('+'.$accessDays.' days', strtotime($HEQC_Meeting_date)));
	$HEQC_Meeting_venue = $this->getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meeting_id, "heqc_meeting_venue");
	$heqcMeetingPassed = $this->checkMeetingPassed($HEQC_Meeting_date);

	echo "<hr>";

	$userid = $this->currentUserID;
	$doc = new octoDocGen ("HEQC_Meeting_document", "user=".$userid."&meet_id=".$heqc_meeting_id);
?>

<br>

<?php 
	//canUploadMinutes variable stays 0 until meeting has passed
	echo '<input type="hidden" name="canUploadMins" value="0">';

/*------If the meeting has already happened, force it to go to upload minutes page. -----*/
	if ($heqcMeetingPassed)
	{
		echo "The HEQC meeting has passed. Please press 'Next' to continue.";
		$this->createAction ("next", "Next", "href", "javascript:canUploadMinutes('1');moveto('next');", "ico_next.gif");
	}
/*------END: If the meeting has already happened -----*/

	else {
		$app_rows = "";
	
		$this->createAction ("previous", "Previous", "href", "javascript:moveto('previous');", "ico_prev.gif");

		echo "Please note that you will not be able to close this meeting until the day after the HEQC meeting has been held. The meeting will be held on ".$HEQC_Meeting_date.". You may resend the email with the list of applications as many times as you want to before then. You may want to send the email each time you update the list of applications assigned to this specific meeting - you may edit the email as necessary.";
		echo "<br><br>";
		echo "The following applications have been assigned to this HEQC Meeting:";
		echo "<br><br>";
	//select all those that have been assigned to this specific AC meeting
		$SQL = <<<APP
			SELECT * 
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.heqc_meeting_ref= ?
			LEFT JOIN lkp_proceedings ON p.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
APP;
		//$rs = mysqli_query($conn, $SQL);
                $stmt = $conn->prepare($SQL);
                $stmt->bind_param("s", $heqc_meeting_id);
                $stmt->execute();
                $rs = $stmt->get_result();
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array($rs)) {
				$inst = $this->getValueFromTable("HEInstitution", "HEI_id", $row['institution_id'], "HEI_name");
				$app_rows .= <<<ASSIGNSITE
					<tr class="onblue">
					<td>{$row["lkp_proceedings_desc"]}</td>
					<td>{$inst}</td>
					<td>{$row["CHE_reference_code"]}</td>
					<td>{$row["program_name"]}</td>
					</tr>
ASSIGNSITE;
			}
		}
		
		$sSQL = <<<SITE
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND inst_site_app_proceedings.heqc_meeting_ref= ?
			ORDER BY lkp_site_proceedings.lkp_site_proceedings_desc, HEI_name
SITE;
                $stmt = $conn->prepare($sSQL);
                $stmt->bind_param("s", $heqc_meeting_id);
                $stmt->execute();
                $sRs = $stmt->get_result();
                
		//$sRs = mysqli_query($sSQL);			
		while ($sRow = mysqli_fetch_array($sRs)) {
			$site_arr = $this->getSiteVisitsForApp($sRow["inst_site_app_proc_id"]);
			$sites = "&nbsp;";
			foreach($site_arr as $s){
				$sites .= $s["site_name"] . "-" . $s["location"] . "<br />";
			}

			$app_rows .= <<<ASSIGNSITE
				<tr class="onblue">
					<td>{$sRow["lkp_site_proceedings_desc"]}</td>
					<td>{$sRow["HEI_name"]}</td>
					<td>{$sRow["site_application_no"]}</td>
					<td>{$sites}</td>
				</tr>
ASSIGNSITE;
		}			

		if ($app_rows == ""){
			$app_rows = <<<NOAPPS
				<tr class="onblue" align="center">
				<td colspan="4">No applications have been assigned to this HEQC Meeting. Please click "Previous" to select applications to assign.</td>
				</tr>
NOAPPS;
		}
		$html = <<<APPS
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
			<tr class="oncolourb">
				<td>Proceeding</td>
				<td>Institution</td>
				<td>Reference number</td>
				<td>Name</td>
			</tr>
			{$app_rows}
			</table>
			<br />
			<br />
APPS;
		echo $html;
		
		echo "<br><br>";

		echo "The following HEQC members are attending the HEQC Meeting:";
		echo "<br><br>";
	//select all those that have been assigned to this specific AC meeting
		$iSQL = "SELECT * FROM heqc_meeting_members, users WHERE user_ref = user_id AND heqc_meeting_ref = ?";
		$stmt = $conn->prepare($iSQL);
                $stmt->bind_param("s", $heqc_meeting_id);
                $stmt->execute();
                $irs = $stmt->get_result();
		//$irs = mysqli_query($iSQL);

		echo "<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>";

		if (mysqli_num_rows($irs) > 0) {
			echo "<tr class='oncolourb'>";
			echo "<td>Name</td>";
			echo "<td>Email address</td>";
			echo "<td>Email below sent on:</td>";
			echo "</tr>";
				while ($irow = mysqli_fetch_array($irs)) {
					echo "<tr class='onblue'>";
					echo "<td>".$irow['name']." ".$irow['surname']."</td>";
					echo "<td>".$irow['email']."</td>";
					echo "<td>".$irow['email_notification_date']."</td>";
					echo "</tr>";
				}
			echo "</table>";
		}
		echo "<br>";
		echo "<table border='0'><tr><td>";
		echo "Click the \"Send email\" button to send the following email to the above HEQC members: ";
		echo "<br><br>";
		$this->formFields['memberNotificationOfApps']->fieldValue = $this->getTextContent("scheduleHEQCMeeting", "Confirm HEQC applications");
		$this->showfield('memberNotificationOfApps');
		echo "</td><td valign='top'>";
		echo '<br><br><input type="button" class="btn" value="Send Email" onClick="moveto(\'next\')"><br>';
		if (isset($_POST["memberNotificationOfApps"]) && ($_POST["memberNotificationOfApps"])) {
			echo "<br><span class='visi'>The email has been sent to all HEQC members.<br><br></span>";
		}
		echo "</td></tr></table>";
	}



?>


</td></tr>
</table>
<br>

<script>
function canUploadMinutes(num) {
	document.defaultFrm.canUploadMins.value = num;
}
</script>
