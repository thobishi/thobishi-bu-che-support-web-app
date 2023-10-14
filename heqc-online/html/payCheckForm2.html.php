<?php 
// Do not display next if the invoice has not been sent

if ($this->formFields["date_invoice"]->fieldValue == '1970-01-01') {
	$this->formActions["next"]->actionMayShow = false;
}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 	if (($invoice_sent == 1) && (empty($_POST["send_invoice"]))) {?>
<tr>
	<td><b>To continue the process you need to send the provider the invoice indicating the fee they need to pay for the accreditation process</b></td>
</tr>
<?php 	}?>
<tr>
	<td>&nbsp;</td>
</tr>
<?php 
	$this->showField("invoice_total");
	$this->showField("invoice_sent");
	$this->formFields["invoice"]->fieldValue = nl2br ($this->getTextContent ("payCheckForm2", "payment_invoice"));
	$this->showField("invoice");
	$this->showField("invoice_sent_count");
	$this->showField("date_invoice");

	$invoice_sent = $this->getFieldValue("invoice_sent");

	if (($invoice_sent == 1) && (empty($_POST["send_invoice"]))) {
?>
<tr>
	<td>This invoice was already sent to <b><?php echo $this->table_field_info($this->active_processes_id, "InstitutionName") ?></b></td>
</tr><tr>
	<td>If you'd like to resend this invoice, click the checkbox below:</td>
</tr><tr>
	<td>
		<script>
			function resendEmailMsg() {
				var obj = document.defaultFrm;
				var intCount = 0;
				intCount = parseInt(obj.FLD_invoice_sent_count.value) + parseInt(1);
				obj.FLD_invoice_sent_count.value = intCount;
			}
		</script>
	</td>
</tr><tr>
	<td>Force a resend of the invoice? <?php $this->showField("send_invoice") ?> &nbsp; <input class="btn" type="button" value="Resend" onClick="resendEmailMsg();checkSendInvoice(document.defaultFrm.send_invoice);"></td>
</tr>
<?php 
	}
	if (($invoice_sent == 1) && isset($_POST["send_invoice"])) {
		$message = $this->getTextContent ("payCheckForm2", "payment_invoice");

		$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
		if ($user_arr[0]==0){
			echo $user_arr[1];
			die();
		}
		
//  2009-09-01 I'm replacing user from application record with current active institutional administrator because the administrator may
//  have changed since the application was started.
//	$this->misMail($this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref"), "Invoice", $message);

		$this->misMail($user_arr[0], "Invoice", $message);
		$this->misMail($this->getDBsettingsValue("usr_registry_payment"), "Invoice", $message);
		$this->misMail($this->getDBsettingsValue("usr_registry_payment_query"), "HEQC Invoice", $message);
		
		// 2010/05/12 Robin: Invoice date was not being set. 
		$this->formFields["date_invoice"]->fieldValue = date('Y-m-d');
		$this->showField("date_invoice");

		if ($this->getFieldValue("invoice_sent_count") > 1) {
?>
<tr>
	<td>The invoice was resent successfully!</td>
</tr>
<?php 
		}

		if ($this->getFieldValue("invoice_sent_count") == 1) {
?>
<tr>
	<td>Your invoice was sent successfully to <b><?php echo $this->getValueFromTable("users", "user_id", $user_arr[0], "email") . " at " . $this->table_field_info($this->active_processes_id, "InstitutionName") ?></b> and the CHE finances (<?php 
	echo $this->getValueFromTable("users", "user_id", $this->getValueFromTable("settings", "s_key", "usr_registry_payment_query", "s_value"), "email"); ?>).</td>
</tr>
<?php 
		}
	}

	if (($invoice_sent == 0) && (empty($_POST["send_invoice"]))) {
?>
<tr>
	<td>The following letter and the attached pro-forma invoice will be sent to the provider.
			<br><br><br>The following <em>pro forma</em> invoice will be sent to <b><?php echo $this->table_field_info($this->active_processes_id, "InstitutionName") ?></b> and CHE finances:</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
<?php 
	$this->showEmailAsHTML("payCheckForm2", "payment_invoice");
?>
	</td>
</tr>
<tr><td>
	<script>
		function firstEmailMsg(invdate) {
			var obj = document.defaultFrm;
			obj.FLD_invoice_sent.value = 1;
			obj.FLD_invoice_sent_count.value = 1;
			obj.FLD_date_invoice.value = invdate;
		}
	</script>
</td></tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>Send invoice? &nbsp; <?php $this->showField("send_invoice") ?> &nbsp; <input class="btn" type="button" value="Send" onClick="firstEmailMsg(<?php echo "'".date('Y-m-d')."'" ?>);checkSendInvoice(document.defaultFrm.send_invoice)"></td>
</tr>
<?php 
	}
?>
</table>
</td></tr></table>
