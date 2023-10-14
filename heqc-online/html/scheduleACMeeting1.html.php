<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
//	if ($this->dbTableInfoArray['AC_Meeting']->dbTableCurrentID != 'NEW') {
//		$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
//		$this->getACMeetingTableTop($ac_meeting_id);
//	}
//	else {

	echo "Enter the date and venue of the AC meeting:<hr>";
	echo '<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">';
	echo '<tr>';
	echo '<td>';
	echo "Start date: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("ac_start_date");
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo "End date: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("ac_to_date");
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo "Venue: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("ac_meeting_venue");
	echo '</td>';
	echo '</tr>';

	echo '</table>';
//}



?>

</td></tr>
</table>
<br>