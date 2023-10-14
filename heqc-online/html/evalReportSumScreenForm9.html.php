<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>';
$this->showInstitutionTableTop();
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td>Please click "Next" to finish the report.</td></tr>';
echo '<tr><td>';
$SQL = "UPDATE evalReport SET paper_eval_complete=1, summary_done=1, application_sum_ref=? WHERE application_ref=? AND evalReport_status_confirm=1";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("ss", $this->dbTableInfoArray["application_summery_comments"].dbTableCurrentID, $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_affected_rows() == 1)	{
	$this->addActiveProcesses (32, $this->currentUserID);
}
$avg = 0;
$SQL = "SELECT avg(evalReport_comp) FROM evalReport WHERE application_ref=? AND evalReport_status_confirm=1";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$avg += $row[0];
$avg = round($avg,2);
$this->formFields["application_comp_all"]->fieldValue = $avg;
$this->showField('application_comp_all');
	//echo "<br>The paper based evaluation has been completed, to send the following email to the person responsible for making decisions regarding the site visit, click 'Next'.";
	//$this->showEmailAsHTML("evalReportSumScreenForm9", "SitevisitInform");
	echo '</td></tr>';
	echo '</table>';
	echo '</table>';
?>
