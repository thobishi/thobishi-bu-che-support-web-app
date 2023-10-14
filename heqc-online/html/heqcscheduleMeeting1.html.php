<?php
//print_r($_POST);
//echo "<br><br><br>";
//print_r($this->workFlow_settings);
//echo "<br><br><br>";
//print_r($this->dbTableInfoArray);?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
	echo "Enter the date and venue of the HEQC meeting:<hr>";
	echo '<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">';
	echo '<tr>';
	echo '<td>';
	echo "Start date: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("heqc_start_date");
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo "End date: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("heqc_to_date");
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo "Venue: ";
	echo '</td>';
	echo '<td>';
	$this->showfield("heqc_meeting_venue");
	echo '</td>';
	echo '</tr>';

	echo '</table>';
?>

</td></tr>
</table>
<br>