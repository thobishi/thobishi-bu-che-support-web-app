
<?php

	$payment_ids=  $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	//$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_site_app_ref=$this->getValueFromTable("payment","payment_id", $payment_ids,"inst_site_app_ref"); 
	$site_proc_id=$this->getValueFromTable("inst_site_app_proceedings","inst_site_app_ref", $inst_site_app_ref,"inst_site_app_proc_id"); 
	$this->formFields["email_template"]->fieldValue = $this->getTextContent ("instSiteInvoice1", "sendinvoicetoinstitution");


	
?>
<?php


		$sDoc = new octoDoc($this->getValueFromTable("payment","payment_id", $payment_ids,"invoicing_doc"));
		$doc_id =$this->getValueFromTable("payment","payment_id", $payment_ids,"invoicing_doc"); 
		if ($sDoc->isDoc()) {

			$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");
			//echo $doc_name;
			$doc_link = '<a href="'.$sDoc->url().'" target="_blank">'.$doc_name.'</a>';
			//array_push ($docs_arr, $doc_link);
		}
		
		
	

	
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id); ?>
	</td>
</tr>

<tr>
	<td class="specialh">
		<br>
		Invoice the Institution
		<br>
	</td>
</tr>

<tr>
	<td>
	<span><i>	The site application number and document containing the details of the application are available in the header.   
	</i>
</span>	
</td>
</tr>
<tr>
	<td>
	<span>	Download the approved invoicing spreadsheet for this site application:    
	
</span>	
</td>
</tr>
<tr>
	<td  align="left">
	<span><?php echo $doc_link; ?>
	
</span>	
</td>
</tr>
</table>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Enter the total amount to be invoiced : 		
	</td>
	<td>
		<?php $this->showField("invoice_total"); ?>
	</td>
</tr>
<tr>
	<td>
	Upload the invoice to be emailed to the institution
		
	</td>
	<td>
		<?php $this->makeLink("finance_invoice_doc"); ?>
	</td>
</tr>

</table>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	
<tr>
	
	<td>
		<?php echo	"<br /><br >The message below will be emailed to the institution.  Kindly edit the email as required. <br /><br /> "; ?>
		
	</td>
</tr>
<tr>
	
	<td>
		<?php $this->showField("email_template"); ?>
		
	</td>
</tr>

</table>
<br>




