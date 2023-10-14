<?php
//print_r($_POST);
//echo "<br><br><br>";
//print_r($this->workFlow_settings);
//echo "<br><br><br>";
//print_r($this->dbTableInfoArray);?>
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
	$meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$start_date = $this->getValueFromTable("HEQC_Meeting","heqc_id",$meeting_id,"heqc_start_date");
	$this->getHEQCMeetingTableTop($meeting_id);
	
	//echo $meeting_id;

	echo "Upload the relevant documents for this HEQC meeting and enter the date until which HEQC members will be able to access information for this meeting:<hr>";
	echo '<table width="100%" border=0 align="center" cellpadding="10" cellspacing="2">';

	echo '<tr>';
	echo '<td valign="top" width="30%">';
	echo "HEQC members will have access to this meeting until date: ";
	echo '</td>';
	echo '<td>';
		if ($this->formFields["heqc_member_access_date"]->fieldValue == '1000-01-01') {
			$date = date("Y-m-d", strtotime(" +2 days",strtotime($start_date)));
			$this->formFields["heqc_member_access_date"]->fieldValue = $date;
		}
		$this->showField("heqc_member_access_date");
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td valign="top" width="30%">';
	echo "Summary of AC recommendation:";
	echo '</td>';
	echo '<td>';
		$this->makeLink("ac_summary_doc");
	echo '</td>';
	echo '</tr>';
	echo '</table>';



?>

</td></tr>
</table>
<br>
