<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>';
echo ' <br><br><center><b>Please, click "Next" to finish this report.</b></center><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td></tr>';
$this->setValueInTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "evalReport_date_screen", date("Y-m-d"));
echo '</table>';

$avg = 0;
$SQL = "SELECT evalReport_q1_comp,evalReport_q2_comp,evalReport_q3_comp,evalReport_q4_comp,evalReport_q5_comp,evalReport_q6_comp,evalReport_q7_comp,evalReport_q8_comp, evalReport_q9_comp FROM evalReport WHERE evalReport_id = ?";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();


//$rs = mysqli_query($SQL);
$row = mysqli_fetch_assoc($rs);
foreach ($row as $key => $value){
	$avg += $value;
}
$avg = round($avg/sizeof($row),2);
$this->updateField("evalReport","evalReport_id","evalReport_comp",$avg,$this->dbTableInfoArray["evalReport"]->dbTableCurrentID);
$this->updateField("evalReport","evalReport_id","evalReport_accept",1,$this->dbTableInfoArray["evalReport"]->dbTableCurrentID);

$this->formFields["evalReport_date_completed"]->fieldValue = date("Y-m-d");
$this->showField('evalReport_date_completed');

$this->formFields["evalReport_completed"]->fieldValue = "1";
$this->showField('evalReport_completed');
?>
