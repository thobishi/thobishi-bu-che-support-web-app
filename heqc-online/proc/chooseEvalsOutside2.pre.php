<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$SQL = "DELETE FROM `lkp_evaluation_evals_outside` WHERE evaluation_outside_system_ref = '".$this->dbTableInfoArray["evaluation_outside_system"]->dbTableCurrentID."'";
$RS = mysqli_query($conn, $SQL);

$SQL_evalReport = "SELECT Persnr_ref FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr";
$RS_evalReport = mysqli_query($conn, $SQL_evalReport);
while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
	$SQL = "INSERT INTO `lkp_evaluation_evals_outside` VALUES (NULL, '".$this->dbTableInfoArray["evaluation_outside_system"]->dbTableCurrentID."', '".$row["Persnr_ref"]."')";
	$RS = mysqli_query($conn, $SQL);
}
?>
