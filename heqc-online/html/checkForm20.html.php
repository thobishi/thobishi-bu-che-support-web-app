<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

</td>
</tr><tr>
<td>
Have you received a reply from DoE about the programme being part of the institution's approved PQM?
</td>
</tr><tr>
<td>
<?php $this->showField("prog_part_pqm")?>
<br><br>
<div id="yes1" style="display:none">
	Indicate in the comments of the workflow the approximate date for the outcome of the programme being part of the institution's approved PQM and continue with the process
</div>
<div id="no" style="display:none">
<?php 
	$text = "";
	if (isset($_POST["resend"]) && ($_POST["resend"] == 1)) {
		$private_public = ($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"), "priv_publ") == 1)?($this->getDBsettingsValue("current_doe_priv_user_id")):($this->getDBsettingsValue("current_doe_publ_user_id"));
		$to = $this->getValueFromTable("users", "user_id", $private_public, "email");
		$message = $this->getTextContent ("checkForm19", "PublicProvProgNotPartPQM");
		$this->misMailByName($to, "Programme part of PQM", $message);
		$text = "The email was successfully re-sent.";
	}else {
		$text = 'Resend your e-mail to DoE as a reminder. Click <a href="javascript:resendEmail();moveto(\'stay\');">here</a>';
	}
	echo $text;
?>
</div>
<div id="yes2" style="display:none">
<?php $this->showField("prog_part_pqm_comments")?>
</div>
</td>
</tr></table>
</td></tr></table>
<input type="hidden" name="resend" value="0">
<script>
	function checkConfirm () {
		obj = document.all;
		for (i=0; i<obj.length; i++) {
			if ((obj[i].name=="FLD_prog_part_pqm") && (obj[i].value == 2) && (obj[i].checked)) {
				obj.no.style.display = "none";
				obj.yes1.style.display = "Block";
				obj.yes2.style.display = "Block";
			}
			if ((obj[i].name=="FLD_prog_part_pqm") && (obj[i].value == 1) && (obj[i].checked)) {
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
