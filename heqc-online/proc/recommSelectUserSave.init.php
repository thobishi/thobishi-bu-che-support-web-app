<?php
// Update the user selected to do the directorate recommendation
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$recomm_user_id = readPost('recomm_user_id');
	$usql = <<<UPD
		UPDATE ia_proceedings  
		SET recomm_user_ref = $recomm_user_id
		WHERE ia_proceedings_id = $app_proc_id
		LIMIT 1
UPD;
	$errorMail = false;
	mysqli_query($conn, $usql) or $errorMail = true;
	$this->writeLogInfo(10, "SQL-UPDREC", $usql."  --> ".mysqli_error($conn), $errorMail);
?>
