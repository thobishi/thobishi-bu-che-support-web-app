<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	//closeMeeting variable stays 0 until button clicked
	echo '<input type="hidden" name="closeMeeting" value="0">';

	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;

	//echo $ac_meeting_id;
	$this->getACMeetingTableTop($ac_meeting_id);

	$doc = new octoDocGen ("ac_meeting_minutes", "meet_id=".$ac_meeting_id);
	$doc->url ("Click here to generate the AC Meeting minutes for these applications");

	echo "<br><br>The AC Meeting has passed. Please upload the minutes of this meeting.";

	echo "<br><br>";
	$this->makeLink('minutes_doc');
	echo "<br><br>";

	if ($this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "minutes_doc") != 0)
	{
		$this->createAction ("next", "Close AC Meeting", "href", "javascript:setCloseMeeting('1');moveto('next');", "ico_next.gif");
	}
	echo "Once you have uploaded the minutes, you will be able to close the AC Meeting by clicking on the \"Close Meeting\" button. The meeting will be marked as a 'Past AC meeting', and you will be able to view all documentation, applications and outcomes relevant to it.";
	echo "<br><br>To add/edit outcomes of applications that went through this meeting, please use the <b>\"Tools\" > \"Add/edit outcomes\"</b> menu option.";
	echo "<br><br>";

	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$this->displayMeetingDocs($ac_meeting_id);

?>

</td></tr>
</table>
<br>

<script>
function setCloseMeeting(num) {
	document.defaultFrm.closeMeeting.value = num;
}
</script>
