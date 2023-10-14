<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT documentation FROM screening WHERE screening_id=".$this->dbTableInfoArray["screening"]->dbTableCurrentID;
	$RS = mysqli_query($conn, $SQL);
	$documentation = "";
	if ($RS && ($row=mysqli_fetch_array($RS))) {
		$documentation = $row["documentation"];
	}
?>
