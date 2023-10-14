<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
	$isSent = 0;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	$this->showField("doe_isSent");
	$this->showField("doe_email");
	$this->showField("doe_email_count");
	$this->showField("doe_isSent_date");
	
	$isSent = $this->getFieldValue("doe_isSent");
	
	if (($isSent == 1) && (empty($_POST["send_message"]))) {
?>
<tr>
	<td><b>The message was already sent to PQM - DoE on the following date: <?php echo $this->getFieldValue("doe_isSent_date");?></b></td>
</tr><tr>
	<td>
	<br>If you'd like to resend the message, click the checkbox below:</td>
</tr><tr>
	<td>
		<script>
			function resendEmailMsg() {
				var obj = document.defaultFrm;
				var intCount = 0;
				intCount = parseInt(obj.FLD_doe_email_count.value) + parseInt(1);
				obj.FLD_doe_email_count.value = intCount;
				obj.FLD_doe_isSent_date.value = "<?php echo $this->getCurrentDate()?>";
			}
		</script>
	</td>
</tr><tr>
	<td>Force a resend of the message? <?php $this->showField("send_message") ?> &nbsp; <input type="button" class="btn" value="Resend" onClick="resendEmailMsg();checkSendMessage(document.defaultFrm.send_message);"></td>
</tr>
<?php 
	}
	if (($isSent == 1) && isset($_POST["send_message"])) {
		$re = "";
		if ($this->getFieldValue("doe_email_count") > 1) {
			$re = "RE: ";
?>
<tr>
	<td>The message was resent successfully!</td>
</tr>
<?php 
		}
		
		if ($this->getFieldValue("doe_email_count") == 1) {
?>
<tr>
	<td>Your message was sent sucessfully to DoE.</td>
</tr>
<?php 
		}
		if (empty($_POST["comments"]) || (!($_POST["comments"] > ""))) {
			$this->formFields["doe_email"]->fieldValue = str_replace("Comments:<br>", "", $this->getFieldValue("doe_email"));
		}
		/* Changed $email_message so that it gets the value from the database 
		   before it checks to see if it is a resend.
		   If it is not a resend, it will still be sent via e-mail.
		*/
		$email_message = str_replace("<br />", "", $this->getFieldValue("doe_email"));
		if ($re > "") {
			$email_message = str_replace("Application", $re."Application", $email_message);
		}
		$private_public = ($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"), "priv_publ") == 1)?($this->getDBsettingsValue("current_doe_priv_user_id")):($this->getDBsettingsValue("current_doe_publ_user_id"));
		$this->misMail($private_public, "Inclusion of programme in institutional PQM", $email_message, $this->getDBsettingsValue("che_registry_email"), true);
	}

	if (($isSent == 0) && (empty($_POST["send_message"]))) {
?>
<tr>
	<td><br><br><b>To continue the process you need to send the following e-mail to the DoE to confirm that the programme submitted for accreditation is included in the institution's PQM. Make sure that the information in the e-mail is correct and that you have included comments if necessary.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>
<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class='oncoloursoft'>
	<fieldset>
	<legend><span class="specialb"><i>Message</i></span></legend>

<?php 
			$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
			$messageTop = $this->getTextContent ($this->template, "EmailDOE_part1");
			$messageTop = nl2br ($messageTop);
			echo $messageTop;
			echo '<a href="javascript:showComments(document.all.commentsDiv)">Comments</a>:'."<br />";
			echo '<div id="commentsDiv" style="display:none">';
			$messageComments = $this->getTextContent ($this->template, "EmailDOE_part2");
			$messageComments = nl2br ($messageComments);
			echo $messageComments;
			echo '</div>';
			$messageEnd = $this->getTextContent ($this->template, "EmailDOE_part3");
			$messageEnd = nl2br ($messageEnd);
			echo $messageEnd;
?>
	<script>
		function firstEmailMsg() {
			var obj = document.defaultFrm;
			obj.FLD_doe_email.value = '<?php echo ((isset($messageTop))?($this->newGenerationAddcslashes($messageTop)):(""))?>';
			obj.FLD_doe_email.value += obj.comments.value;
			obj.FLD_doe_email.value += '<?php echo ((isset($messageEnd))?($this->newGenerationAddcslashes($messageEnd)):(""))?>';
			obj.FLD_doe_isSent.value = 1;
			obj.FLD_doe_email_count.value = 1;
		}
		
		function showComments(obj) {
			if (obj.style.display == "none") {
				obj.style.display = "Block";
			}else{
				obj.style.display = "none";
			}
		}
	</script>
</fieldset>
</td>
</tr>
<tr>
	<td>Send message? &nbsp; <?php $this->showField("send_message") ?> &nbsp; <input type="button" class="btn" value="Send" onClick="firstEmailMsg();checkSendMessage(document.defaultFrm.send_message);"></td>
</tr>
</table>
<?php 
	}
?>
</td></tr></table>
