
<?php
	$payment_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	
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
		Payment received notification
		<br>
	</td>
</tr>

<tr>
	<td>
	The payment has been received for the following:
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
<tr>
	<td>
	Click Continue to Next process and user to continue processing this application.
</td>
</tr>
</table>
<br>