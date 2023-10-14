<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$ac_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
$SQL = "SELECT distinct(institution_id) FROM Institutions_application WHERE AC_Meeting_ref=".$ac_id;
//echo $SQL;
$rs = mysqli_query($conn, $SQL);
while ($row = mysqli_fetch_array($rs)){
	$S = "SELECT * FROM AC_Meeting_reports WHERE ins_ref=".$row["institution_id"]." AND ac_ref=".$ac_id;
	$r = mysqli_query($conn, $S);
	if (mysqli_num_rows($r) == 0){
		$S = "INSERT INTO AC_Meeting_reports (ins_ref,ac_ref) VALUES (".$row["institution_id"].",".$ac_id.")";
		$r = mysqli_query($conn, $S);
		$this->addActiveProcesses (42, $this->getValueFromTable("settings","s_key","user_make_ac_reports","s_value"), 0, 0, false,$this->makeWorkFlowStringFromCurrent ("AC_Meeting_reports", "report_id", mysqli_insert_id($conn)));	
	}
}
?>
