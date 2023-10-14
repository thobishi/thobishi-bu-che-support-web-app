<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
	<?php $this->showInstitutionTableTop ()?>
	<br>

	<?php 
	$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS_evalReport = $sm->get_result();

	//$RS_evalReport = mysqli_query($SQL);
	$num_rows = mysqli_num_rows($RS_evalReport);
	$str = "( ";
	for ($i=0; $i < $num_rows; $i++) {
		if ($row = mysqli_fetch_array($RS_evalReport)) {
			$str .= "Persnr_ref='".$row["Persnr_ref"]."'";
			if ($i < ($num_rows - 1)) {
				$str .= " OR ";
			}
		}
	}
	$str .= " )";
	$this->evaluatorStats(array("CONCAT(Names, ' ', Surname)", "CHE_reference_code", "evalReport_date_sent"), array("`Eval_Auditors`", "`Institutions_application`", "`evalReport`"), array("application_id=application_ref", "Persnr_ref=Persnr", "evalReport_completed=0", "evalReport_status_confirm=1", $str), " AND ", "", "# Evaluations per Evaluator", array("Name", "Reference Number", "Date Sent"), array("CONCAT(Names, ' ', Surname)", "CHE_reference_code"), "None of these evaluators are currently busy with applications");
	?>
	
	<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td><b>The following evaluators have been chosen:</b></td>
	<td><b>The following chairperson has been nominated:</b></td>
	</tr>
	<tr>
	<td>
	<ul>
<?php 

	$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr";

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS_evalReport = $sm->get_result();

	//$RS_evalReport = mysqli_query($SQL);
	while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
		echo "<li>".$row["Surname"].", ".$row["Names"]."</li><br>";
	}
?>
	</ul>
	</td>
	<td><ul><li><?php echo $chair?></li></ul></td>
</tr>
<tr>
	<br><br>
	<td>If this list is correct, click "Next" to continue this process.
	<br>
	If not, click "Choose Evaluators" to select other evaluators.
	</td>
</tr>
</table>

</td>
</tr>
</table>