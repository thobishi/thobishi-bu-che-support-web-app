<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

</td>
</tr><tr>
<td>
<b>Have you received a reply from DoE about the status of the above institution’s registration?</b>
</td>
</tr><tr>
<td>
<?php $this->showField("status_doe_registration")?>
<br><br>
<div id="yes1" style="display:none">
	Indicate in the comments of the workflow the approximate date for the outcome of the institution’s registration with the DoE and continue with the process
</div>
<div id="no" style="display:none">
<?php 
	$text = "";
	if (isset($_POST["resend"]) && ($_POST["resend"] == 1)) {
		$private_public = ($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"), "priv_publ") == 1)?($this->getDBsettingsValue("current_doe_priv_user_id")):($this->getDBsettingsValue("current_doe_publ_user_id"));
		$to = $this->getValueFromTable("users", "user_id", $private_public, "email");
		$message = $this->getTextContent ("checkForm13", "PrivateProvRegPendingDOE");
		$this->misMailByName($to, "Status of application for registration", $message);
		$text = "The email was successfully re-sent.";
	}else {
		$text = 'Resend your e-mail to DoE as a reminder. Click <a href="javascript:resendEmail();moveto(\'stay\');">here</a>';
	}
	echo $text;
?>
</div>
<div id="yes2" style="display:none">
<?php $this->showField("status_doe_registration_comments")?>
</div>
</td>
</tr></table>
</td></tr></table>
<input type="hidden" name="resend" value="0">
<script>
	function checkConfirm () {
		obj = document.all;
		for (i=0; i<obj.length; i++) {
			if ((obj[i].name=="FLD_status_doe_registration") && (obj[i].value == 2) && (obj[i].checked)) {
				obj.no.style.display = "none";
				obj.yes1.style.display = "Block";
				obj.yes2.style.display = "Block";
			}
			if ((obj[i].name=="FLD_status_doe_registration") && (obj[i].value == 1) && (obj[i].checked)) {
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
