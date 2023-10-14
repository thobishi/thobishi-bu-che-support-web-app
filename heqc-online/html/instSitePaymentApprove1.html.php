
<?php

	$payment_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	//echo $payment_id ;
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

	$inst_site_app_ref=$this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id", $site_proc_id,"inst_site_app_ref");
	

	
	

$payment_inst_site_app_ref=$this->getValueFromTable("payment","payment_id", $payment_id,"inst_site_app_ref");
	

if (!($payment_inst_site_app_ref > 0 || $payment_inst_site_app_ref = null)){
	$this->formFields["inst_site_app_ref"]->fieldValue = $inst_site_app_ref;
}
$this->showField("inst_site_app_ref"); 
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
		Prepare for invoicing approval
		<br>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		
		
	</td>
</tr>


<tr>
	<td align="left">
	<span><i>	The site application number and document containing the details of the application are available in the header.  Check that the invoicing spreadsheet matches the application above before proceeding. 	</i>
</span>	
</td>
</tr>
</table>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Approve the invoicing spreadsheet for this site application: 		
	</td>
	<td>
		<?php $this->makeLink("invoicing_doc"); ?>
	</td>
</tr>
<tr>
	<td>
	Enter the total amount to be invoiced (if known): 		
	</td>
	<td>
		<?php $this->showField("invoice_total"); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">Please check this box to indicate that the invoicing spreadsheet has been approved for this application. <?php $this->showField("invoicing_approval");?></span>
	
	</td>
</tr>
</table>
<br>
