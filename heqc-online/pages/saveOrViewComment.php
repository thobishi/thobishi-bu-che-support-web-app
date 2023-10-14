<?php
	require_once ("/var/www/html/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	
					$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
    }

	$app = new HEQConline (1);
	if(isset($_POST['command'])){
		$command = $_POST['command'];
		$application_ref = mysqli_real_escape_string($conn, $_POST['application_ref']);
		switch ($command){			
			case 'save':
				if(isset($_POST['comment']) && !empty($_POST['comment'])){
					$comment = mysqli_real_escape_string($conn,$_POST['comment']);
					$coment_date =  mysqli_real_escape_string($conn, $_POST['coment_date']);
					$user_ref = mysqli_real_escape_string($conn, $_POST['user_ref']);		
					$currentProcess =  mysqli_real_escape_string($conn, $_POST['currentProcess']);		
					
					
					$SQL = "INSERT INTO ia_comments  VALUES "."(NULL,'$application_ref','$coment_date','$user_ref','$comment','. $currentProcess')";
					 echo $SQL;		
					$rs = mysqli_query($conn,$SQL);			
		
				}
			break;
			case 'view':
				if($application_ref > ''){
					$html = '';
					$sql = "SELECT *
						FROM (SELECT ia_comments_id, application_ref, comment_date ,user_ref, comment, currentProcess FROM ia_comments WHERE application_ref = ". $application_ref ." ORDER BY comment_date DESC) t ORDER BY ia_comments_id DESC ";
					$rs = mysqli_query($conn, $sql);
					$commentsCount = mysqli_num_rows($rs);
					if($commentsCount > 0){				
						while($row = mysqli_fetch_array($rs)){
							$html .= "<li>" . $row['comment'] . "<br><small>". $app->getUserName($row['user_ref'], 2) . ", " .$row['currentProcess'] . ", " . $row['comment_date']  . "</small></li><br>";
						}								
					}	
					echo $html;
				}
			break;
		}
	}
?>