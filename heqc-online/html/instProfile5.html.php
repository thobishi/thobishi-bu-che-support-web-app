<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<?php 	echo $program;?>
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