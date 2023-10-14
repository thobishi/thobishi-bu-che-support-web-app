<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<table><tr>
	<td>In the box below, enter the results of your query to the DoE:</td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->showField("feedback_results");?></td>
</tr></table>

</td></tr></table>
<script>
	function checkFeedback() {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (document.defaultFrm.FLD_feedback_results.value == '') {
				alert('Please give feedback before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
</script>
