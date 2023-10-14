<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$template = $this->template;

	// Do not display next if the invoice has not been sent
	if ($this->formFields["date_invoice"]->fieldValue == '1970-01-01') {
		$this->formActions["next"]->actionMayShow = false;
	}
	if ($this->formFields["invoice_sent"]->fieldValue == 1) {
		$this->formActions["previous"]->actionMayShow = false;
	}
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">Email the pro-forma invoice to the institution</span>
		<br>
	</td>
</tr>
<tr>
	<td>
<?php

echo $this->formFields["date_invoice"]->fieldValue;
	echo $this->formFields["invoice_sent_count"]->fieldValue;
	echo $this->formFields["invoice_sent"]->fieldValue;
		$inst_name = $this->table_field_info($this->active_processes_id, "InstitutionName");
		$this->showField("invoice_total");
		$this->showField("invoice_sent");
		$this->formFields["invoice"]->fieldValue = nl2br ($this->getTextContent ($template, "payment_invoice"));
		$this->showField("invoice");
		$this->showField("invoice_sent_count");
		$this->showField("date_invoice");
		$this->showField("invoice_sent_user_ref");

		$invoice_sent = $this->getFieldValue("invoice_sent");
		$invoice_date = $this->getFieldValue("date_invoice");
		$usr_to_copy = $this->getUsersInGroup(34); //usr_finance_emails group

		$usr_copy = "<i>None currently specified</i>";
		$cc = "";
		if (count($usr_to_copy) > 0){
			$cc = array();
			foreach($usr_to_copy as $u){
				array_push($cc, $u[1]);
			}
			$usr_copy = implode(",", $cc);
		}
		
		$today = date('Y-m-d');
		$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
		if ($user_arr[0]==0){
			echo $user_arr[1];
			die();
		}
		$usr_email = $this->getValueFromTable("users", "user_id", $user_arr[0], "email");
?>
		<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
<?php
			if (($invoice_sent == 0) && (empty($_POST["send_invoice"]))) {
				$instr = <<<INSTR
					The following <em>pro forma</em> invoice will be sent to <b>{$usr_email} at {$inst_name}</b> and copied to {$usr_copy} (specified in the Finance emails group).
					<br><br><br>
INSTR;
				echo $instr;
				$this->showEmailAsHTML($template, "payment_invoice");
				echo "Send invoice? &nbsp; " . $this->showField("send_invoice");
				$instr = <<<INSTR
					&nbsp;<input class="btn" type="button" value="Send" onClick="firstEmailMsg('{$today}');checkSendInvoice(document.defaultFrm.send_invoice);">
INSTR;
				echo $instr;
			}

			if (($invoice_sent == 1) && (empty($_POST["send_invoice"]))) {
				$instr = <<<INSTR
					<b>This invoice was already sent to <b>{$usr_email} at {$inst_name} on {$invoice_date}</b>.</b>
					<br /><br />
INSTR;
				echo $instr;
				$this->showEmailAsHTML($template, "payment_invoice");
				$instr = <<<INSTR
					If you'd like to resend this invoice, click the checkbox below:<br />
					Force a resend of the invoice?
INSTR;
				echo $instr;
				$this->showField("send_invoice");
				$instr = <<<INSTR
					<input class="btn" type="button" value="Resend" onClick="resendEmailMsg();checkSendInvoice(document.defaultFrm.send_invoice);">
INSTR;
				echo $instr;
			}
			
			if ( ($invoice_sent == 1) && isset($_POST["send_invoice"]) ) {
				$message = $this->getTextContent ($template, "payment_invoice");
				
				// Email payment administrator, institutional administrator and current user (if different to institutional administrator)
				$pay_adm = $this->getDBsettingsValue("usr_registry_payment");
				$this->misMail($user_arr[0], "Invoice", $message); // Institutional administrator
				$this->misMail($pay_adm, "Invoice", $message, $cc);

				$curr_usr_email = $this->getValueFromTable("users","user_id",$this->currentUserID,"email");
				if ($curr_usr_email != $pay_adm){
					$this->misMailByName ($curr_usr_email, "Invoice", $message);
				}
				
				$this->formFields["invoice_sent_user_ref"]->fieldValue = $this->currentUserID;
				$this->showField("invoice_sent_user_ref");

				$this->formFields["date_invoice"]->fieldValue = date('Y-m-d');
				$this->showField("date_invoice");

				if ($this->getFieldValue("invoice_sent_count") > 1) {
					$instr = "The invoice was resent successfully  to <b>{$usr_email} at {$inst_name}</b> and copied to: {$usr_copy}";
				}
				if ($this->getFieldValue("invoice_sent_count") == 1) {
					$instr = "Your invoice was sent successfully to <b>{$usr_email} at {$inst_name}</b> and copied to: {$usr_copy}";
				}
				echo $instr;
			}
?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

