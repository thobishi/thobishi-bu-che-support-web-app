<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$newemp = $this->getValueFromTable("Eval_Auditors", "Persnr", $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID, "employer");
if ($newemp > ""){
	$SQL = "SELECT lkp_employer_id FROM `lkp_employer` WHERE lkp_employer_name ='" .$newemp. "'";
echo $SQL;
	$RS = mysqli_query($conn, $SQL);
	$num_rows = mysqli_num_rows($RS);
	if ($num_rows == 0){
		$SQL = "INSERT INTO `lkp_employer` (lkp_employer_name) VALUES ('". $newemp ."')";
echo $SQL;
		$RS = mysqli_query($conn, $SQL);
		$id = mysqli_insert_id();
		if ($id > ""){
			$SQL = "UPDATE Eval_Auditors SET employer = '', employer_ref = " . $id . " WHERE Persnr = ". $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;
echo $SQL;
			if (! mysqli_query ($conn, $SQL) ) {
				$this->writeLogInfo(10, "SQL-EvaluatorForm4-Pre", $SQL."  --> ".mysqli_error(), true);
			}
			else {
				$this->formFields["employer_ref"]->fieldValue = $id;
				$this->formFields["employer"]->fieldValue = '';
			}
		}
	}
	print_r( $this->formFields);
}
/*

$SQL = "UPDATE `$table` ".
		 "SET $chField = \"$chValue\" ".
		 "WHERE `$keyField` = \"$keyValue\"";
//echo $SQL."<br>";
if (! mysqli_query ($SQL) ) {
	$this->writeLogInfo(10, "SQL-SETVAL", $SQL."  --> ".mysqli_error(), true);
}

$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, commend, siteVisit_report_areas_ref) VALUES ('".$site_ref."', '".$app_ref."', '".$value."', '".$ref[1]."')";
$RS = mysqli_query($SQL);
$id = mysqli_insert_id();


$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr=Persnr_ref";
$RS = mysqli_query($SQL);
$count = $num_rows = mysqli_num_rows($RS);
while ($RS && ($row=mysqli_fetch_array($RS))) {
	if (($row["Spent_time_Management"] > 0) && ($row["eval_change_status"] == 0)) {
		$this->setValueInTable("evalReport", "Persnr_ref", $row["Persnr"], "is_manager", 1);
		$count--;
	}
}
*/
?>
