<?php
	$reacc_id = ($this->dbTableInfoArray['Institutions_application_reaccreditation']->dbTableCurrentID);
	
	//echo $reacc_id;

	$table = $this->dbTableCurrent;
	$keyFLD = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField;
	$keyVal = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;

	if(isset($table))
		$_SESSION["ses_table"] = $table;
	if(isset($table))
		$_SESSION["ses_keyFLD"] = $keyFLD;
	if(isset($table))
		$_SESSION["ses_keyVal"] = $keyVal;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reacc_id)?>
		<br>
	</td>
</tr>
<tr>
	<td align="center">
<?php 
//	$AC_history_link = "<a href='pages/acMeetingHistory.php?app_ref=".base64_encode($application_id)."' target='_blank'><i>(View this application's AC meeting history)</i></a>";
?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<table border=0 width="95%" cellpadding="2" cellspacing="2">
		<tr class="onblue">
			<td width="40%">Please select the AC meeting that this application was tabled at:</td>
			<td><?php echo $this->showField("reacc_acmeeting_date")?></td>
		</tr>
	
		<tr class="onblue">
			<td valign="top">Please select the outcome of the application from the above meeting:</td>
			<td><?php $this->showField("reacc_decision_ref");?></td>
		</tr>
		</table>
		<?php  $displayStyle = $this->div_reacc($reacc_id, 'reacc_decision_ref', '2'); ?>
		<div id="condition" style="display:<?php echo $displayStyle?>">
		<table border=0 width="95%" cellpadding="2" cellspacing="2">
		<tr valign="top" class="onblue">
			<td width="40%" valign="top">Please enter the conditions:</td>
			<td><?php $this->showField("reacc_conditions");?></td>
		</tr>
		<tr valign="top" class="onblue">
			<td valign="top">Please enter the due date for conditions:</td>
			<td><?php $this->showField("reacc_conditiondue_date");?></td>
		</tr>
		</table>
		</div>
		
		<?php $displayStyle = $this->div_reacc($reacc_id, 'reacc_decision_ref', '4'); ?>
		<div id="deferral" style="display:<?php echo $displayStyle?>">
		<table border=0 width="95%" cellpadding="2" cellspacing="2">
		<tr valign="top" class="onblue">
			<td width="40%" valign="top">Please enter any relevant comments for the deferral:</td>
			<td><?php $this->showField("reacc_deferral_comment");?></td>
		</tr>
		<tr valign="top" class="onblue">
			<td valign="top">Please enter the due date for the deferral:</td>
			<td><?php $this->showField("reacc_deferdue_date");?></td>
		</tr>
		</table>
		</div>
		
		<table border=0 width="95%" cellpadding="2" cellspacing="2">
		<tr class="onblue">
			<td width="40%">General comments/notes regarding the outcome:</td>
			<td><?php echo $this->showField("reacc_acmeeting_comment")?></td>
		</tr>
	
		<tr class="onblue">
			<td valign="top">Please upload all documentation from the AC meeting:</td>
			<td><?php $this->makeLink("reacc_acmeeting_doc");?></td>
		</tr>
		</table>
	</td>
</tr>

</td></tr>
</table>
<br>

