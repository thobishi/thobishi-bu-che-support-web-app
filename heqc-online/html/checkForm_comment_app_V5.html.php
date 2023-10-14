<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<?php
$this->showField("doCancelProc");
$this->showField("gotoInst");
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please enter a comment, or add to the existing comments, that will be sent to the institution.</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->showField("comment_on_applicationForm");?></td>
</tr></table>
<br><br>
</td></tr></table>   
<script>
	function checkComment () {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (document.defaultFrm.FLD_comment_on_applicationForm.value == '') {
				alert('Please enter the comment.');
				document.defaultFrm.FLD_comment_on_applicationForm.focus();
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
</script>