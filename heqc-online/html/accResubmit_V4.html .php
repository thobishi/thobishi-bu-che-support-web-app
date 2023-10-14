<?php 
 	$this->showField("application_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ();
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Your application was been returned to you with reasons specified in an email.
	Please will you make the requested changes and re-submit your application to the CHE.
	When you re-submit please enter a comment, or add to the existing comments, that will be sent to the HEQC.</b></td>
</tr>  
<tr>
	<td>&nbsp;</td></td>   
</tr>
<tr>
	<td><?php $this->showField("comment_on_resubmission");?></td>
</tr>
</table>
<br><br>
</td></tr></table>
<script>
	function checkComment () {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (document.defaultFrm.FLD_comment_on_resubmission.value == '') {
				alert('Please enter the comment.');
				document.defaultFrm.FLD_comment_on_resubmission.focus();
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
</script>
