<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="center" colspan="2">&nbsp;</td>
</tr><tr>
	<td align="center" colspan="2"><b>Please select the date that was chosen by the institution</b></td>
</tr><tr>
<?php 
	$this->getDatesForSiteVisit("print");
?>
</tr><tr>
	<td align="center" colspan="2">&nbsp;</td>
</tr></table>
<br><br>
<script>
	function checkConfirm() {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!(document.defaultFrm.FLD_inst_confirm_visit.checked)) {
				alert('You must confirm the reception of the institutional letter before continuing');
				return false;
			}
		}
		return true;
	}
</script>
