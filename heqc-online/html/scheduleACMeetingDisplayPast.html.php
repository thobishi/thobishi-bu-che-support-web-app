<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$this->getACMeetingTableTop($ac_meeting_id);
	$this->displayMeetingDocs($ac_meeting_id);
?>

</td></tr>
</table>
<br>