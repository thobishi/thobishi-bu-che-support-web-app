<?php 
$newemp = $this->getValueFromTable("Eval_Auditors", "Persnr", $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID, "employer");
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
if ($newemp > ""){
	$SQL = "SELECT lkp_employer_id FROM `lkp_employer` WHERE lkp_employer_name ='" .$newemp. "'";
	$RS = mysqli_query($conn, $SQL);
	$num_rows = mysqli_num_rows($RS);
	if ($num_rows == 0){
		$SQL = "INSERT INTO `lkp_employer` (lkp_employer_name) VALUES ('". $newemp ."')";
		$RS = mysqli_query($conn, $SQL);
		$id = mysqli_insert_id($conn);
		if ($id > ""){
			$SQL = "UPDATE Eval_Auditors SET employer = '', employer_ref = " . $id . " WHERE Persnr = ". $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;
			if (! mysqli_query ($conn, $SQL) ) {
				$this->writeLogInfo(10, "SQL-EvaluatorForm4-Pre", $SQL."  --> ".mysqli_error($conn), true);
			}
		}
	}
}
?>
