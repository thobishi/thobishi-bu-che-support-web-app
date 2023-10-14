<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table><tr>
	<td>Expenditure patterns 2003</td>
</tr></table>
<br><br>
<?php echo $table?>
<br><br>
</td></tr></table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
	function changeID (newID) {
		document.defaultFrm.id.value = newID;
	}
	function makeReport() {
		document.defaultFrm.report.value = '1';
	}
</script>