<br />
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$acMeetingPassed = "";
	$this->getACMeetingTableTop($ac_meeting_id);
	$ac_meeting_date = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_start_date");
	$ac_meeting_venue = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_meeting_venue");
	$SQL = <<<ACCESSDAYS
                SELECT s_value FROM `settings`
                WHERE s_key = 'ac_meeting_days_access'
ACCESSDAYS;

                // echo $SQL;
        $accessDays = mysqli_result(mysqli_query($conn, $SQL),0);

        $ac_meeting_date = date('Y-m-d', strtotime('+'.$accessDays.' days', strtotime($ac_meeting_date)));
	$acMeetingPassed = $this->checkMeetingPassed($ac_meeting_date );

	echo "<hr>";

	$userid = $this->currentUserID;
	$doc = new octoDocGen ("ac_meeting_document", "user=".$userid."&meet_id=".$ac_meeting_id);


function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

	
?>

<br />




<?php


			$conn = $this->getDatabaseConnection();
			//canUploadMinutes variable stays 0 until meeting has passed
	echo '<input type="hidden" name="canUploadMins" value="0">';

/*------If the meeting has already happened, force it to go to upload minutes page. -----*/
	if ($acMeetingPassed)
	{
		echo "The AC meeting has passed. Please press 'Next' to continue.";
		$this->createAction ("next", "Next", "href", "javascript:canUploadMinutes('1');moveto('next');", "ico_next.gif");
	}
/*------END: If the meeting has already happened -----*/

	else {
		$this->createAction ("previous", "Previous", "href", "javascript:moveto('previous');", "ico_prev.gif");

		echo "Please note that you will not be able to close this meeting until the day after the AC meeting has been held. The meeting will be held on ".$ac_meeting_date.". You may resend the email with the list of applications as many times as you want to before then. You may want to send the email each time you update the list of applications assigned to this specific meeting - you may edit the email as necessary.";
		echo "<br /><br />";
		echo "The following applications have been assigned to this AC Meeting:";
		echo "<br /><br />";
		$doc->url ("Click here to generate the AC Meeting Documentation for these applications");
		echo "<br /><br />"; 
	//select all those that have been assigned to this specific AC meeting
/*		$SQL = <<<APP
			SELECT lkp_proceedings.lkp_proceedings_desc,
				a.program_name,
				a.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.ac_meeting_ref= $ac_meeting_id
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			WHERE p.ac_meeting_ref= $ac_meeting_id
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
APP;*/
		//2017-09-13: Richard - Added AC agenda type
		$SQL = <<<APP
			SELECT lkp_AC_agenda_type.lkp_AC_agenda_type_desc,
				lkp_proceedings.lkp_proceedings_desc,
				a.program_name,
				a.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.ac_meeting_ref= ?
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = p.lkp_AC_agenda_type_ref
			WHERE p.ac_meeting_ref= ?
			ORDER BY lkp_AC_agenda_type.lkp_AC_agenda_type_desc, lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
APP;
//			WHERE a.application_status = 2 


                $stmt = $conn->prepare($SQL);

             $stmt->bind_param("ss", $ac_meeting_id, $ac_meeting_id);

             $stmt->execute();

            $rs = $stmt->get_result();
//        $rs = mysqli_query($conn, $SQL); // or die(mysqli_error());
		$app_rows = "";
		if (mysqli_num_rows($rs) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_array($rs)) {
/*				$app_rows .= <<<APPROW
					<tr class="onblue">
						<td>{$i}</td>
						<td>{$row['lkp_proceedings_desc']}</td>
						<td>{$row['HEI_name']}</td>
						<td>{$row['CHE_reference_code']}</td>
						<td>{$row['program_name']}</td>
					</tr>
APPROW;*/
				//2017-09-13: Richard - Added AC agenda type
				$app_rows .= <<<APPROW
					<tr class="onblue">
						<td>{$i}</td>
						<td>{$row['lkp_AC_agenda_type_desc']}</td>
						<td>{$row['lkp_proceedings_desc']}</td>
						<td>{$row['HEI_name']}</td>
						<td>{$row['CHE_reference_code']}</td>
						<td>{$row['program_name']}</td>
					</tr>
APPROW;
				$i++;	
			}
		}
/*		$sSQL = <<<SITE
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
			AND inst_site_app_proceedings.ac_meeting_ref= $ac_meeting_id
			ORDER BY lkp_site_proceedings.lkp_site_proceedings_desc, HEI_name
SITE;*/
		//2017-09-13: Richard - Added AC agenda type
		$sSQL = <<<SITE
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc,
				lkp_AC_agenda_type.lkp_AC_agenda_type_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = inst_site_app_proceedings.lkp_AC_agenda_type_ref
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND inst_site_app_proceedings.ac_meeting_ref= $ac_meeting_id
			ORDER BY lkp_AC_agenda_type.lkp_AC_agenda_type_desc, lkp_site_proceedings.lkp_site_proceedings_desc, HEI_name
SITE;

//echo $sSQL;
              //  $stmt = $conn->prepare($sSQL);

               // $stmt->bind_param("s", $ac_meeting_id);

               // $stmt->execute();

               // $sRs = $stmt->get_result();
		$sRs = mysqli_query($conn, $sSQL); // or die(mysqli_error());			
		if (mysqli_num_rows($sRs) > 0){
			$i = 1;
			while ($sRow = mysqli_fetch_array($sRs)) {
				$site_arr = $this->getSiteVisitsForApp($sRow["inst_site_app_proc_id"]);
				$sites = "&nbsp;";
				foreach($site_arr as $s){
					$sites .= $s["site_name"] . "-" . $s["location"] . "<br />";
				}

/*				$app_rows .= <<<ASSIGNSITE
					<tr class="onblue">
						<td>{$i}</td>
						<td>{$sRow["lkp_site_proceedings_desc"]}</td>
						<td>{$sRow["HEI_name"]}</td>
						<td>{$sRow["site_application_no"]}</td>
						<td>{$sites}</td>
					</tr>
ASSIGNSITE;*/
				//2017-09-13: Richard - Added AC agenda type
				$app_rows .= <<<ASSIGNSITE
					<tr class="onblue">
						<td>{$i}</td>
						<td>{$sRow["lkp_AC_agenda_type_desc"]}</td>
						<td>{$sRow["lkp_site_proceedings_desc"]}</td>
						<td>{$sRow["HEI_name"]}</td>
						<td>{$sRow["site_application_no"]}</td>
						<td>{$sites}</td>
					</tr>
ASSIGNSITE;
				$i++;
			}			
		}
		if ($app_rows == ""){
			$app_rows = <<<NOAPPS
				<tr class="onblue" align="center">
					<td colspan="4">No applications have been assigned to this AC Meeting. Please click \"Previous\" to select applications to assign.</td>
				<tr>
NOAPPS;
		}
/*		$html = <<<APPS
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
			<tr class="oncolourb">
				<td>No.</td>
				<td>Proceeding</td>
				<td>Institution</td>
				<td>HEQC reference number</td>
				<td>Programme name</td>
			</tr>
			$app_rows
			</table>
			<br />
			<br />
APPS;*/
		//2017-09-13: Richard - Added AC agenda type
		$html = <<<APPS
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
			<tr class="oncolourb">
				<td>No.</td>
				<td>Type</td>
				<td>Proceeding</td>
				<td>Institution</td>
				<td>HEQC reference number</td>
				<td>Programme name</td>
			</tr>
			$app_rows
			</table>
			<br />
			<br />
APPS;
		echo $html;

		echo "The following AC members are attending the AC Meeting:";
		echo "<br /><br />";
		//select all those that have been assigned to this specific AC meeting
		$iSQL = "SELECT * FROM lnk_ACMembers_ACMeeting, AC_Members WHERE ac_member_ref=ac_mem_id AND ac_meeting_ref=?";
		$stmt = $conn->prepare($iSQL);

                $stmt->bind_param("s", $ac_meeting_id);

                $stmt->execute();

                $irs = $stmt->get_result();
		//$irs = mysqli_query($this->getDatabaseConnection(), $iSQL);

		echo "<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>";

		if (mysqli_num_rows($irs) > 0) {
			echo "<tr class='oncolourb'>";
			echo "<td>Name</td>";
			echo "<td>Email address</td>";
			echo "<td>Email below sent on:</td>";
			echo "</tr>";
				while ($irow = mysqli_fetch_array($irs)) {
					echo "<tr class='onblue'>";
					echo "<td>".$irow['ac_mem_name']." ".$irow['ac_mem_surname']."</td>";
					echo "<td>".$irow['ac_mem_email']."</td>";
					echo "<td>".$irow['email_notification_date']."</td>";
					echo "</tr>";
				}
			echo "</table>";
		}
		echo "<br />";
		echo "<table border='0'><tr><td>";
		echo "Click the \"Send email\" button to send the following email to the above AC members: ";
		echo "<br /><br />";
		$this->formFields['ACmemberNotificationOfApps']->fieldValue = $this->getTextContent("scheduleACMeeting", "Confirm AC applications");
		$this->showfield('ACmemberNotificationOfApps');
		echo "</td><td valign='top'>";
		echo '<br /><br /><input type="button" class="btn" value="Send Email" onClick="moveto(\'next\')"><br />';
		if (isset($_POST["ACmemberNotificationOfApps"]) && ($_POST["ACmemberNotificationOfApps"])) {
			echo "<br /><span class='visi'>The email has been sent to all AC members.<br /><br /></span>";
		}
		echo "</td></tr></table>";

	}

?>


</td></tr>
</table>
<br />

<script>
function canUploadMinutes(num) {
	document.defaultFrm.canUploadMins.value = num;
}
</script>
