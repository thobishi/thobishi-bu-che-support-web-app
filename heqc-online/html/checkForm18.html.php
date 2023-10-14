<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

</td>
</tr><tr>
<td>
Have you received a reply from SAQA about the status of the above programme’s registration on the NQF?
</td>
</tr><tr>
<td>
<?php $this->showField("confirm_prog_status_SAQA")?>
<br><br>
<div id="yes1" style="display:none">
	Indicate in the comments of the workflow the approximate date for the outcome of the programme registration on the NQF and continue with the process
</div>
<div id="no" style="display:none">
	<?php 
	$text = "";
	if (isset($_POST["resend"]) && ($_POST["resend"] == 1)) {
		$to = $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("current_saqa_user_id"), "email");
		$message = $this->getTextContent ("checkForm17", "PublicProvProgPendingSAQA");
		$this->misMailByName($to, "Programme registration on NQF", $message);
		$text = "The email was successfully re-sent.";
	}else {
		$text = 'Resend your e-mail to SAQA as a reminder. Click <a href="javascript:resendEmail();moveto(\'stay\');">here</a>';
	}
	echo $text;
?>
</div>
<div id="yes2" style="display:none">
<?php $this->showField("prog_status_SAQA_comments")?>
</div>
</td>
</tr></table>
</td></tr></table>
<input type="hidden" name="resend" value="0">
<script>
	function checkConfirm () {
		obj = document.all;
		for (i=0; i<obj.length; i++) {
			if ((obj[i].name=="FLD_confirm_prog_status_SAQA") && (obj[i].value == 2) && (obj[i].checked)) {
				obj.no.style.display = "none";
				obj.yes1.style.display = "Block";
				obj.yes2.style.display = "Block";
			}
			if ((obj[i].name=="FLD_confirm_prog_status_SAQA") && (obj[i].value == 1) && (obj[i].checked)) {
				obj.no.style.display = "Block";
				obj.yes1.style.display = "none";
				obj.yes2.style.display = "none";
			}
		}
	}
	checkConfirm ();
	
	function resendEmail() {
		document.defaultFrm.resend.value = 1;
	}
</script>
