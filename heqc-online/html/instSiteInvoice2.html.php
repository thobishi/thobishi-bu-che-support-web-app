<?php
	$payment_id=  $this->dbTableInfoArray["payment"]->dbTableCurrentID;
    $site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$template = $this->template;

	// Do not display next if the invoice has not been sent
	if ($this->formFields["date_invoice"]->fieldValue == '1000-01-01') {
		$this->formActions["next"]->actionMayShow = false;
	}
	if ($this->formFields["invoice_sent"]->fieldValue == 1) {
		//$this->formActions["previous"]->actionMayShow = false;
	}
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php echo $this->getSiteApplicationTableTop($site_proc_id); ?>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="loud">Email the pro-forma or tax invoice to the institution </span>
		<br>
	</td>
</tr>

<tr>
<td>
		<br>
<?php
		/*$this->formFields['email_template']->fieldValue = $this->getTextContent("instSiteInvoice2", "sendinvoicetoinstitution");
		$this->showField('email_template');*/
		?>
			<br>
	</td>
</tr>



<?php

$sql =<<<SQL
SELECT *
FROM payment
WHERE payment_id = $payment_id
SQL;
$rs = mysqli_query($this->getDatabaseConnection(), $sql);
if ($rs){
	while ($row = mysqli_fetch_array($rs)){

		$sDoc = new octoDoc($row['finance_invoice_doc']);
		$doc_id = $row['finance_invoice_doc'];
		if ($sDoc->isDoc()) {

			$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");

			$doc_link = '<a href="'.$sDoc->url().'" target="_blank">'.$doc_name.'</a>';

		}		
	}
}
	
?>

</tr>
<br>

<tr>
	<td>
<?php

//echo $this->formFields["date_invoice"]->fieldValue;
	//echo $this->formFields["invoice_sent_count"]->fieldValue;
	//echo $this->formFields["invoice_sent"]->fieldValue;
		//$inst_name = $this->table_field_info($this->active_processes_id, "InstitutionName");
		$sendmessage="";
		//$sentmessage="";
		$this->showField("invoice_total");
		$this->showField("invoice_sent");
		$this->formFields["invoice"]->fieldValue = nl2br ($this->getTextContent ($template, "payment_invoice"));


	
		//$this->formFields["email_template"]->fieldValue = $this->getTextContent ("instSiteInvoice2", "sendinvoicetoinstitution");
		$this->showField("invoice");
		$this->showField("invoice_sent_count");
		$this->showField("date_invoice");
		$this->showField("invoice_sent_user_ref");

		$invoice_sent = $this->getFieldValue("invoice_sent");
		$invoice_date = $this->getFieldValue("date_invoice");

		$today = date('Y-m-d');
		
		/*$usr_to_copy = $this->getUsersInGroup(34); //usr_finance_emails group

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
		$user_arr = $this->getInstitutionAdministrator(0,40);
		if ($user_arr[0]==0){
			echo $user_arr[1];
			die();
		}
		$usr_email = $this->getValueFromTable("users", "user_id", $user_arr[0], "email");
		*/
?>
		<table width="95%" border="0" align="left" cellpadding="2" cellspacing="2">
		<tr>
			<td>
<?php
			if (($invoice_sent == 0) && (empty($_POST["send_invoice"]))) {
				echo	"<br /><br >The message below will be emailed to the institution.  Kindly edit the email as required. <br /><br /> ";
				//$this->showEmailAsHTML($template, "sendinvoicetoinstitution");
				$this->showField("email_template");
			
				echo	"<br /><br ><span> The following document will be sent as an attachment:</span> <br /> ";
				echo	"<span>{$doc_link} </span> <br /> ";
				echo	"<br /><span> If you'd like to send this invoice, click the checkbox below:</span> <br /> ";

				echo "Send invoice? &nbsp; " . $this->showField("send_invoice");
				$instr = <<<INSTR
					&nbsp;<input class="btn" type="button" value="Send" onClick="firstEmailMsg('{$today}');checkSendInvoice(document.defaultFrm.send_invoice);">
INSTR;
				echo $instr;
			}

			if (($invoice_sent == 1) && (empty($_POST["send_invoice"]))) {
				echo	"<br /><br >The message below will be emailed to the institution.  Kindly edit the email as required. <br /><br /> ";
			
				//$this->showEmailAsHTML($template, "sendinvoicetoinstitution");
				$this->showField("email_template");

				echo	"<br /><br ><span> The following document will be sent as an attachment:</span> <br /><br /> ";
				echo	"<span>{$doc_link} </span> <br /><br /> ";

				

				echo	"<br/><br/><span> If you'd like to resend  this invoice, click the checkbox below:</span> <br /> ";
				
				$this->showField("send_invoice");
				$instr = <<<INSTR
					<input class="btn" type="button" value="Resend" onClick="resendEmailMsg();checkSendInvoice(document.defaultFrm.send_invoice);">
INSTR;
				echo $instr;
			}
			
			if ( ($invoice_sent == 1) && isset($_POST["send_invoice"]) ) {
			
				

				
		$ins_id =$this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id", $site_proc_id,"institution_ref");
		$inst_name=$this->getValueFromTable("HEInstitution","HEI_id", $ins_id,"HEI_name");
		
		$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
			if ($user_arr[0]==0):
				echo $user_arr[1];
				die();
			endif;
			$new_user = $user_arr[0];
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			$message = $this->getValueFromTable("payment", "payment_id", $payment_id, "email_template"); //$this->getTextContent("instSiteInvoice2", "sendinvoicetoinstitution");
			
			$cc = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
			
			$files = "";
		
			$doc_id  = $this->getValueFromTable("payment", "payment_id", $payment_id, "finance_invoice_doc");
			if ($doc_id > ""){

			$doc_url = $this->getValueFromTable("documents", "document_id", $doc_id,"document_url");
			$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");

			$files = array();
			array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
			
			
			}
			
			
		// 2010/05/12 Robin: Invoice date was not being set. 
		$this->formFields["date_invoice"]->fieldValue = date('Y-m-d');
		$this->showField("date_invoice");


		$this->misMailByName($to, "  Invoice to the institution ", $message, $cc,true,$files);

		$sendmessage = " Invoice  sent to ". $to ."(".$inst_name ." on ". $this->formFields["date_invoice"]->fieldValue ." (invoice sent date)  Click Next. Capture amounts. Click Next.";
		

		//$sentmessage = "This invoice was already sent to". $to ."(".$inst_name ." on ". $this->formFields["date_invoice"]->fieldValue ." (invoice sent date) ";
		

				$this->formFields["invoice_sent_user_ref"]->fieldValue = $this->currentUserID;
				$this->showField("invoice_sent_user_ref");

				$this->formFields["date_invoice"]->fieldValue = date('Y-m-d');
				$this->showField("date_invoice");

				if ($this->getFieldValue("invoice_sent_count") > 1) {
					$instr = $sendmessage;
				}
				if ($this->getFieldValue("invoice_sent_count") == 1) {
					$instr =$sendmessage;
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

