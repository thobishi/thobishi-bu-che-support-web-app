
<?php
	
	$payment_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

$this->showField("invoice_total");

	$date_invoice  = $this->getValueFromTable("payment", "payment_id", $payment_id, "date_invoice");
	$invoice_total  = $this->getValueFromTable("payment", "payment_id", $payment_id, "invoice_total");
			
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
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
		Confirmation of payment 
		<br>
	</td>
</tr>

<tr>
	<td>
	<span>This payment is for the following invoicing spreadsheet for this site application: 
</span>	
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

	//echo $row['payment_id'] ;

		$sDoc = new octoDoc($row['invoicing_doc']);
		$doc_id = $row['invoicing_doc'];
		if ($sDoc->isDoc()) {
			//$doc_link = '<a href="'.$sDoc->url().'" target="_blank">invoicing document</a>';

			$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");
			//echo $doc_name;
			$doc_link = '<a href="'.$sDoc->url().'" target="_blank">'.$doc_name.'</a>';
			//array_push ($docs_arr, $doc_link);
			//array_push ($docs_arr, $doc_link);
		}
		
		
		
	
	}
}
	 
?>
<tr>
	<td>
	<span><?php echo $doc_link; ?>
	
</span>	
</td>
</tr>
</table>
<table width="77%" border=0 align="left" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Please enter the payment date : 		
	</td>
	<td>
		<?php $this->showField("paymentdate"); ?>
		
	</td>
</tr>
<tr>
	<td>
	Please enter the amount paid by the institution for this application: 
(to a maximum of the invoice amount) 
		
	</td>
	<td>
		<?php $this->showField("institution_Payment"); ?>
	</td>
</tr>
</table>
	<table width="95%" border=0 align="left" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	<span class="visi">Note: This application will only be considered paid up when the amount paid by the institution equals the invoice amount. 

<br>


	</tr>

	</table>
	<table width="20%" border=0 align="left" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Invoice date	
	</td>
	<td><b>
		<?php
		echo $date_invoice ;
		?></b>
	</td>
	
</tr>
<tr>

	<td>
	Invoice total	
	</td>
	<td> <b>
	<?php
		echo $invoice_total ;
		?>
		</b>
	</td>
	
</tr>


</table>
<br>