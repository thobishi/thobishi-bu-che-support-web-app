<?php 
$this->showField("doCancelProc");
$this->showField("gotoInst");
if ($this->formFields["doCancelProc"]->fieldValue==1) {
	$heading = "Cancellation of application";
	$bodytext = "This application will be cancelled if you continue.  The institution will be notified that the application is cancelled.  
	This application will not be processed any further and any active processes to it will be closed. ";
}
if ($this->formFields["gotoInst"]->fieldValue==1) {
	$heading = "Return application to institution";
	$bodytext = "This application will be returned to the institution if you continue.  The institution will be notified that the application has been returned.  
	Your active processes to this application will be closed.  The institution will be able to access it from their list of processes.  They may modify it and re-submit it to you.
	It will return to screening on re-submission.";
}
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<span class="loud"><?php echo $heading; ?></span>
	
<?php 
$this->showInstitutionTableTop ();
?>
<br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td><?php echo $bodytext; ?></td>
	</tr>
	<tr>
		<td><b>Please enter a comment, or add to the existing comments, that will be sent to the institution.</b></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><?php $this->showField("comment_on_applicationForm");?></td>
	</tr>
	</table>
	<br><br>
	</td>
</tr>
</table>
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