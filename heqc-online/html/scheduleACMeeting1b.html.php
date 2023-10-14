<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$ac_start_date = $this->getValueFromTable("AC_Meeting","ac_id",$ac_meeting_id,"ac_start_date");
	$this->getACMeetingTableTop($ac_meeting_id);

	//SQL to get previous meeting's minutes
	$SQL = "SELECT ac_id FROM AC_Meeting WHERE ac_start_date <= '".$this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_start_date")."' AND ac_id != ".$ac_meeting_id." GROUP BY ac_start_date ORDER BY ac_start_date DESC";
	$rs = mysqli_query($conn,$SQL);
	
	//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	$row = mysqli_fetch_array($rs);
	$prev_ac_id = $row[0];
	$prev_minutes_ref = $this->getValueFromTable("AC_Meeting", "ac_id", $prev_ac_id, "minutes_doc");
	$prev_minutes_doc = new octoDoc($this->getValueFromTable("AC_Meeting", "ac_id", $prev_ac_id, "minutes_doc"));
	$prev_meeting_str  = "<a href='".$prev_minutes_doc->url()."' target='_blank'>".$prev_minutes_doc->getFilename()."</a>";
	$prev_minutes_bool = ($prev_minutes_ref == 0) ? "false" : 'true';


	echo "Upload the relevant documents for this AC meeting and enter the date until which AC members will be able to access information for this meeting:<hr>";
	echo '<table width="100%" border=0 align="center" cellpadding="10" cellspacing="2">';

	echo '<tr>';
	echo '<td valign="top" width="30%">';
	echo "AC members will have access to this meeting until date: ";
	echo '</td>';
	echo '<td>';
		if ($this->formFields["ac_member_access_date"]->fieldValue == '1000-01-01') {
			$date = date("Y-m-d", strtotime(" +2 days",strtotime($ac_start_date)));
			$this->formFields["ac_member_access_date"]->fieldValue = $date;
		}
		$this->showField("ac_member_access_date");
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td valign="top" width="30%">';
	echo "Agenda: ";
	echo '</td>';
	echo '<td>';
		$this->makeLink("agenda_doc");
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td valign="top">';
	echo "Minutes from previous meeting held on <br>".$this->getValueFromTable("AC_Meeting", "ac_id", $prev_ac_id, "ac_start_date")." (".$this->getValueFromTable("AC_Meeting", "ac_id", $prev_ac_id, "ac_meeting_venue")."):";
	echo '</td>';
	echo '<td valign="top">';


	//set prev_minutes_doc value to be previous meeting's minutes. Eventually might need to put in overriding function
	$this->setValueInTable("AC_Meeting", "ac_id", $ac_meeting_id, "prev_minutes_doc", $prev_minutes_ref);
	//set is_last_meeting value to ac_id of previous meeting
	$this->setValueInTable("AC_Meeting", "ac_id", $ac_meeting_id, "is_last_meeting", $prev_ac_id);

	echo ($prev_minutes_bool == 'true') ? $this->formFields['prev_minutes_doc']->fieldValue = $prev_meeting_str : "The minutes for the previous meeting have not been uploaded yet. Please go to 'AC_Meeting' > 'Manage AC Meetings' and upload the minutes of the meeting.";
	echo '</td>';
	echo '</tr>';

	echo '</table>';



?>

</td></tr>
</table>
<br>

<!--<script>
try {
	if ((document.defaultFrm.FLD_override_prev_minutes.checked) && (document.all.override_div.style.display = "none")) {
		showHide (document.all.override_div);
	}
}catch(e){}
</script>
-->