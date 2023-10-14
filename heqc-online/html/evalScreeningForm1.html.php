<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%">&nbsp;</td>
	<td>&nbsp;</td>
</tr><tr>
	<td width="30%" align="right"><b>INSTITUTION NAME:</b> </td>
	<td class="oncolour"><?php echo $this->table_field_info($this->active_processes_id, "InstitutionName")?></td>
</tr><tr>
	<td width="30%" align="right"><b>PROVIDER TYPE:</b></td>
	<td class="oncolour"><?php echo $this->table_field_info($this->active_processes_id, "InstitutionType")?></td>
</tr><tr>
	<td width="30%" align="right"><b>PROGRAMME NAME:</b></td>
	<td class="oncolour"><?php echo $this->table_field_info($this->active_processes_id, "ProgramName")?></td>
</tr><tr>
	<td width="30%" align="right"><b>NQF Level:</b></td>
	<td class="oncolour"><?php echo $this->getValueFromTable("NQF_level", "NQF_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"NQF_ref"), "NQF_level")?></td>
</tr><tr>
	<td width="30%" align="right"><b>HEQC - Reference number:</b></td>
	<td class="oncolour"><?php echo $this->table_field_info($this->active_processes_id, "HEQC_ref")?></td>
</tr></table>
<br>
<table width="85%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td>The system has notified you that an evaluator's report has arrived.</td>
</tr><tr>
	<td>Read the report and make sure that it is complete. If the report is complete send an e-mail to the evaluator
		acknowledging the reception of the report. To send the e-mail click <a href="javascript:moveto('next')">here</a>
	</td>
</tr></table>
<br><br>
Tick off on the table the reports you received:
<table width="85%" border=1  cellpadding="2" cellspacing="2">
<tr>
	<td>Evaluator Name</td>
	<td>Date Sent</td>
	<td>Date In</td>
	<td>Complete</td>
	<td>Comments</td>
</tr>
<?php 
	$fieldRows = $this->getMultipleFieldsFromTable ($this->dbTableCurrent, "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, " AND evalReport_status_confirm=1");
	foreach ($fieldRows AS $row) {
		foreach ($row AS $key=>$val) {
			echo $key."=>".$val."<br>";
		}
	}
?>
</table>
<br><br>
</td></tr></table>
