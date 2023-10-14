<br>

<?php 
	$currentUserID = $this->currentUserID;
	
	$ac_mem_id = $this->getValueFromTable("AC_Members", "user_ref", $currentUserID, "ac_mem_id");
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "You have confirmed your attendance at the following meetings. You will be granted viewing access to programmes up until the day of the AC Meeting.";
	echo "<br>Each row displays a meeting - for each meeting, you will see: ";
	echo "<ul>";
	echo "<li>The meeting date (please note you will only be able to view applications until the day before the meeting).</li>";
	echo "<li>The venue of the meeting.</li>";
	echo "<li>The list of applications that will be discussed at this meeting. If you click on this link, you will be able to view the following:";
		echo "<ul>";
		echo "<li>Application submission (reference number, programme name)</li>";
		echo "<li>Institutional profile</li>";
		echo "<li>Evaluator report <b><i>(not any they should be excluded from however)</i></b></li>";
		echo "<li>Directorate recommendations <b><i>(as above)</i></b></li>";
		//echo "<li>Representations (letter of appeal -> for next phase)</li>";
		echo "</ul>";
	echo "</li>";
	echo "<li>The minutes of the previous AC meeting.</li>";
	echo "<li>The meeting agenda</li>";
	echo "</ul>";
?>

</td></tr>
</table>

<!---------------------------------------->
<?php 
	// You must be an AC member to view the applications assigned to an AC meeting
	if ($ac_mem_id > ""){
?>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
			<?php
				echo "<tr class='oncolourb'>";
				echo "<td>AC meeting date</td>";
				echo "<td>AC meeting venue</td>";
				echo "<td>Applications assigned to meeting</td>";
				echo "<td>Meeting agenda</td>";
				echo "<td>Minutes of previous meeting</td>";
				if ($this->sec_partOfGroup(23)) { // AC Meeting minutes - Only from June 2011 after proceedings implemented.
					echo "<td>Take minutes</td>";
					$SQL = <<<SQL
						SELECT ac_id, ac_start_date, ac_meeting_venue, agenda_doc, prev_minutes_doc 
						FROM AC_Meeting
						WHERE ac_id >= 15
						ORDER BY ac_start_date
SQL;
				} else {  // AC members must only see meetings that they are assigned to while the meeting is open.
					$SQL  = "SELECT * FROM lnk_ACMembers_ACMeeting, AC_Meeting";
					$SQL .= " WHERE lnk_ACMembers_ACMeeting.ac_member_ref=".$ac_mem_id;
					$SQL .= " AND lnk_ACMembers_ACMeeting.ac_meeting_ref=ac_id ";
					$SQL .= " AND AC_Meeting.ac_member_access_date >= '".date("Y-m-d")."'";
					$SQL .= " ORDER BY ac_start_date";
				}
				echo "</tr>";
                                 
                                 
                                 $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
				$rs = mysqli_query($conn, $SQL);
				if (!$rs){
					$this->writeLogInfo(10, "SQL-GETVAL", "Current user is: *" . $currentUserID . "*<br><br>" . $SQL."  --> ".mysqli_error($conn), true);
				}
				if (mysqli_num_rows($rs) > 0) {
					while ($row = mysqli_fetch_array($rs))
					{
						$agendaDoc = new octoDoc($row['agenda_doc']);
						$prev_minutesDoc = new octoDoc($row['prev_minutes_doc']);

						echo "<tr class='onblue'>";
						echo "<td>".$row["ac_start_date"]."</td>";
						echo "<td>".$row["ac_meeting_venue"]."</td>";
						echo "<td><a href='pages/applicationList.php?ac_ref=".base64_encode($row['ac_id'])."&member_id=".base64_encode($ac_mem_id)."' target='_blank'>Click to view application list</a></td>";
						echo "<td><a href='".$agendaDoc->url()."' target='_blank'>".$agendaDoc->getFilename()."</a></td>";
						echo "<td><a href='".$prev_minutesDoc->url()."' target='_blank'>".$prev_minutesDoc->getFilename()."</a></td>";
						if ($this->sec_userInGroup("AC Meeting minutes")) {
							$link1 = $this->scriptGetForm ('AC_Meeting', $row['ac_id'], '_label_ACminute_edit');
							echo "<td><a href='$link1'>Display list for edit</a></td>";
						}
						echo "</tr>";
					}
				}
				else {
					echo "<tr>";
					echo "<td colspan='10' align='center'>";
					echo "-- You have no AC Meetings assigned to you at the moment. --";
					echo "</td></tr>";
				}
			?>
		</table>
<?php 
	} else {
		echo '<span class="speciali"> You need to be an AC member in order to view AC meeting information.  Please request your system administrator to add you as an AC member</span>';
	}
?>
<br>

<script>
function setID(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='evalReport|'+val;
}
</script>



