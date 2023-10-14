<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	//closeMeeting variable stays 0 until button clicked
	echo '<input type="hidden" name="closeMeeting" value="0">';

	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	 $this->getHEQCMeetingTableTop($heqc_meeting_id);
	
	
	//echo $heqc_meeting_id;
	
	$doc = new octoDocGen ("heqc_meeting_minutes", "heqc_meet_id=".$heqc_meeting_id);
	$doc->url ("Click here to generate the HEQC Meeting minutes for these applications");
	
	echo "<br><br>The HEQC Meeting has passed. Please upload the minutes of this meeting.";
	echo "<br><br>";
	$this->makeLink('minutes_doc');
	echo "<br><br>";

	if ($this->getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meeting_id, "minutes_doc") != 0)
	{
		$this->createAction ("next", "Close HEQC Meeting", "href", "javascript:setCloseMeeting('1');moveto('next');", "ico_next.gif");
	}
	echo "Once you have uploaded the minutes, you will be able to close the HEQC Meeting by clicking on the \"Close Meeting\" button. The meeting will be marked as a 'Past HEQC meeting', and you will be able to view all documentation, applications and outcomes relevant to it.";
	echo "<br><br>";

	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$this->displayHEQCMeetingDocs($heqc_meeting_id);
?>

</td></tr>
</table>
<br>

<script>
function setCloseMeeting(num) {
	document.defaultFrm.closeMeeting.value = num;
}
</script>
