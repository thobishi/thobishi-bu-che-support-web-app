<?php
$SQL = "SELECT * FROM HEInstitution WHERE HEI_code=''";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
                
$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		$priv = "PR";
		if ($row["priv_publ"] == 2) $priv = "H";
		if ($row["inst_user_role_ref"] == 2) $priv = "S";  // Institution that is not applying for accreditation but just for users to view information.
		$S = "UPDATE HEInstitution SET HEI_code='".$this->getLastHEIcode($priv)."' WHERE HEI_id=?";
		$errorMail = false;
		
		$sm = $conn->prepare($S);
                $sm->bind_param("s", $row["HEI_id"]);
                $sm->execute();
                $rs = $sm->get_result();
                
                if(!$rs) $errorMail = true;
		//mysqli_query($S) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $S."  --> ".mysqli_error($conn), $errorMail);
	}
}
?>
