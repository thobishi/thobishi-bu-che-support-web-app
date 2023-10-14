<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$avg = 0;
$SQL = "SELECT evalReport_q1_comp,evalReport_q2_comp,evalReport_q3_comp,evalReport_q4_comp,evalReport_q5_comp,evalReport_q6_comp,evalReport_q7_comp,evalReport_q8_comp, evalReport_q9_comp FROM evalReport WHERE evalReport_id = ".$this->dbTableInfoArray["evalReport"]->dbTableCurrentID;
$rs = mysqli_query($conn, $SQL);
$row = mysqli_fetch_assoc($rs);
foreach ($row as $key => $value){
	$avg += $value;
}
$avg = round($avg/sizeof($row),2);
$this->updateField("evalReport","evalReport_id","evalReport_comp",$avg,$this->dbTableInfoArray["evalReport"]->dbTableCurrentID);
?>
