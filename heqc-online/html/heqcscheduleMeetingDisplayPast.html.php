<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$this->getHEQCMeetingTableTop($heqc_meeting_id);
	$this->displayHEQCMeetingDocs($heqc_meeting_id);
?>

</td></tr>
</table>
<br>