<?php
	require_once ("/var/www/html/common/_systems/heqc-online.php");
	//$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	if(isset($_POST['userEmail'])){
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$escapedEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
		if(!empty($escapedEmail)){
			$sql = "SELECT COUNT(`E_mail`)
				FROM `Eval_Auditors`
				WHERE `E_mail` = '$escapedEmail'";
			$rs = mysqli_query($conn, $sql);
		
			$emailResult =  mysqli_fetch_row($rs);			
			if ($emailResult[0] == '0') {
				echo 'true';
			} else {
				echo 'false';
			}
		}
	}
?>
