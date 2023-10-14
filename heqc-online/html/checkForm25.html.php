<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<?php 
	if (isset($_POST["FLD_email_rcpt"]) && ($_POST["FLD_email_rcpt"] > "")) {
		$message = $this->getTextContent ("checkForm25", "TeachersEduProgramme");
		$this->misMailByName ($_POST["FLD_email_rcpt"], "", $message);
	}

	if ($this->checkEmail_DoE_Profboard ()) {
		$this->formActions["stay"]->actionMayShow = false;
?>
	<table><tr>
		<td>The email was already sent to <?php echo $this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "email_rcpt");?> on the following date: <?php echo $this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "email_sent_date");?></td>
	</tr><tr>
		<td>&nbsp;</td>
	</tr><tr>
		<td>Did you get any results back from the DoE? <?php $this->showField("results_back");?> <i>(Tick for yes)</i></td>
	</tr><tr>
		<td>When you get the results back, please tick it off <i>(above)</i> and click "Next" to continue.</td>
	</tr></table>
	<script>
		function check_results_back () {
			if (document.defaultFrm.FLD_results_back.checked) {
				showHideAction("next", true);
			}else {
				showHideAction("next", false);
			}
		}
	</script>
<?php 
	}else{
?>
	<table><tr>
		<td>Please type the email address to whom the email must go in the following box:</td>
		<td><?php 
					if ( !($this->formFields["email_rcpt"]->fieldValue > "") ) {
						$this->formFields["email_rcpt"]->fieldValue = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
					}
					$this->showField("email_rcpt");
				?></td>
	</tr><tr>
		<td align="right">Date:</td>
		<td><?php 
						if ((! ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "email_sent_date") > "") ) || (! ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "email_rcpt") > "") )) {
							$this->formFields["email_sent_date"]->fieldValue = $this->getCurrentDate();
						}
						$this->showField("email_sent_date");
				?>
		</td>
	</tr>
		<td colspan="2">
<?php 
		$this->showEmailAsHTML("checkForm25", "TeachersEduProgramme");

		$this->formFields["email_sent"]->fieldValue = 1;
		$this->showField("email_sent");
?>
		</td>
	</tr></table>
<?php 
	}
?>
</td></tr></table>
