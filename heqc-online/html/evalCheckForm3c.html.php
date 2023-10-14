<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="60%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="center" colspan="2">Please choose an evaluator to do the summary of evaluations:</td>
</tr><tr>
	<td align="center" colspan="2">&nbsp;</td>
</tr>
	<?php 
	$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr AND evalReport_status_confirm=1";
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS = $sm->get_result();
	//$RS = mysqli_query($SQL);
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		echo '<tr><td align="right"><input type="radio" name="do_summary" value="'.$row["evalReport_id"].'"></td><td> '.$row["Names"]." ".$row["Surname"].'</td></tr>';
	}
	?>
	</td>
</tr></table>
</td></tr></table>
