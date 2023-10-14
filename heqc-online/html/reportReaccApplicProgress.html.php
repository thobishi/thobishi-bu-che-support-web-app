<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<br>
<?php 
	$is_CHE = false;
//echo 'Hello';
    $cuser = $this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref");
	if (( $cuser == 2) || ($cuser == 1)) {  //CHE or Octoplus
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Reaccreditation Application Status Report:</span></td>
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
	$showTable = false;
    if (!$is_CHE || ($institution != 0)) {
		$showTable = true;
	}



	if ($showTable) {

$this->reportReaccApplicProgress ($institution, $is_CHE);

//echo $is_CHE;
//echo $institution;
	}

?>
</td>
</tr></table>
<script>
try {
	document.defaultFrm.HEI_id.value = '<?php echo $institution ?>';
}catch(e){}
</script>
