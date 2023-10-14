<?php 
// Inserts a lookup value to lkp_employer_name if theuser selected other because the employer was not in the drop down list.
$newemp = $this->getValueFromTable("Eval_Auditors", "Persnr", $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID, "employer");

if ($newemp > ""){
	$SQL = "SELECT lkp_employer_id FROM `lkp_employer` WHERE lkp_employer_name ='" .$newemp. "'";
	$RS = mysqli_query($SQL);
	$num_rows = mysqli_num_rows($RS);
	if ($num_rows == 0){
		$SQL = "INSERT INTO `lkp_employer` (lkp_employer_name) VALUES ('". $newemp ."')";
		$RS = mysqli_query($SQL);
		$id = mysqli_insert_id();
		if ($id > ""){
			$SQL = "UPDATE Eval_Auditors SET employer = '', employer_ref = " . $id . " WHERE Persnr = ". $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;
			if (! mysqli_query ($SQL) ) {
				$this->writeLogInfo(10, "SQL-EvaluatorForm2-Pre", $SQL."  --> ".mysqli_error(), true);
			}
		}
	}
}
?>
