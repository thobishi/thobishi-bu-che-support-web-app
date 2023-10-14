<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<table align="center" width="95%" border=0  cellpadding="2" cellspacing="2">
<tr><td>
<?php 
	$this->showField("email_sent_institution");
	$sent = $this->formFields["email_sent_institution"]->fieldValue;

	if (isset($_POST["is_sent"]) && ($_POST["is_sent"] > "")) {
		$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
		$message = $this->getTextContent ($this->template, "letter to institution");
		$this->misMailByName($to, "Site Visit", $message);
		$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "letterToInstitutionChanged", 0);
		echo "The letter was sent successfully.</td>";
	}
	
	if (($sent == 0) || ($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "letterToInstitutionChanged") == 1)) {
		$messageBody = $this->showEmailAsHTML($this->template, "letter to institution");
		$this->formFields["is_sent"]->fieldValue = $messageBody;
		$this->showField("is_sent");
?>
</td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td>To send the letter, click <a href="javascript:checkMail();moveto('stay');">here</a></td>
<?php 
	}

	if (($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "letterToInstitutionChanged") == 0) && ($sent > 0) && (!(isset($_POST["is_sent"])))) {
?>
	The letter was already sent.</td>
<?php 
	}
?>
</tr></table>
</td></tr></table>
<script>
	function checkMail() {
		document.defaultFrm.FLD_email_sent_institution.value = 1;
	}
</script>
