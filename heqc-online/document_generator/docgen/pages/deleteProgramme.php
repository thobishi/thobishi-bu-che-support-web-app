<?php
	require_once ("/var/www/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	if(isset($_POST)){
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	
		if(!empty($_POST['progArr'])){
			$active_processes_ids = implode(", ", $_POST['progArr']);
			$id = mysqli_real_escape_string($conn, $active_processes_ids);
			$SQL = "UPDATE active_processes  SET status = 1 WHERE  active_processes_id IN ($id)";
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);

if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
			$rs = mysqli_query($conn, $SQL);
			if ($rs){
				echo true;
			}else{
				echo false;
			}
	
		}else{
			echo false;
		}
	}

?>