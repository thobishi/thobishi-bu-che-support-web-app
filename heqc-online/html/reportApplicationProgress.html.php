<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<?php 
	$is_CHE = false;
//	$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref"), "HEI_name") == "CHE"
	if (($this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref") == 2) || ($this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref") == 1)) {?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Application Status Report:</span></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Select Institution/s:</b>&nbsp;</td><td class="oncolour" colspan="4"><?php $this->showField("HEI_id") ?></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4">
	<input type="button" class="btn" value="Create Report" onClick="moveto('stay');">
	</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr></table>
<?php 
		$is_CHE = true;
	}

	$institution = readPOST("HEI_id");
	$status = readPOST("app_status");
	$last_process = readPOST("last_proc", false);
	$process_number = readPOST("proc_number", 5);
	$showTable = false;
	if (!$is_CHE || ($institution != 0)) {
		$showTable = true;
	}

	if ($showTable) {
		echo $this->applicationProgressReport ($institution, $process_number, $status, $last_process, $is_CHE);
	}
?>
</td>
</tr></table>
<script>
try {
	document.defaultFrm.HEI_id.value = '<?php echo $institution?>';
}catch(e){}
</script>
