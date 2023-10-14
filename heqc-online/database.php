 <?php
	function connect(){
		$Hostname = "localhost";
		$Username = "root";
		$Password = "H@ppyusers@123";
		$DBName   = "heqsupport_1";  
		$charset='utf8'; // specify the character set
        $collation='utf8_general_ci'; //specify what collation you wish to use
	
		$conn = mysqli_connect($Hostname, $Username, $Password); //connect to the database
		if (!$conn) {
			echo 'Could not connect to mysql';//die('Could not connect: ' . mysqli_error());
		}

		if (!mysqli_select_db($DBName, $conn)) {		//select correct database		
			echo 'Could not select database';
			//exit;
		}
		
			mysqli_set_charset($con,"utf8");	
		$result=mysqli_query('show tables') or die("Mysql could not execute the command 'show tables' " . mysqli_error());
	while($tables = mysqli_fetch_array($result)) {
foreach ($tables as $key => $value) {
mysqli_query("ALTER TABLE $value CONVERT TO CHARACTER SET $charset COLLATE $collation") or die("Could not convert the table " . mysql_error());
}}

mysqli_query("ALTER DATABASE $dbname DEFAULT CHARACTER SET $charset COLLATE $collation") or die("could not alter the collation of the databse " . mysql_error());
echo "The collation of your database has been successfully changed!";
		
		return $conn;
	}
	
	

	
	function pr($array){
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	function disconnect(){
		mysqli_close();
	}
	
	function reinstate($app_ref){
	
		$apps = explode(",",$app_ref);
		//var_dump($apps);
		$app_list = "'" . implode("','",$apps) . "'";

		$sql = <<<SQL
			SELECT application_id, program_name, ia_proceedings_id, lkp_proceedings_ref, proceeding_status_ind, proceeding_status_date
			FROM `Institutions_application` , ia_proceedings
			WHERE `CHE_reference_code` IN ({$app_list})
			AND ia_proceedings.application_ref = Institutions_application.application_id
SQL;
		echo "<br />" . $sql . "<br />";
		$rs = mysqli_query($sql) or die(mysqli_error());
		while ($row = mysqli_fetch_array($rs)){
			 pr($row);
		}

		$sql1 = <<<SQL
			SELECT application_id, MAX(ia_proceedings_id) AS ia_proceedings_id
			FROM `Institutions_application` , ia_proceedings
			WHERE `CHE_reference_code` IN ({$app_list})
			AND ia_proceedings.application_ref = Institutions_application.application_id
			GROUP BY application_id
			ORDER BY application_id
SQL;
		echo "<br />" . $sql1 . "<br />";
		$rs1 = mysqli_query($sql1) or die(mysqli_error());
		$proc_arr = array();
		$app_arr = array();
		while ($row1 = mysqli_fetch_array($rs1)){
			 array_push($proc_arr, $row1['ia_proceedings_id']);
			 array_push($app_arr, $row1['application_id']);
		}
		$proc_list = implode(",",$proc_arr);
		$app_list = implode(",",$app_arr);


		if ($app_list > "" && $proc_list > ""){

			$ap_all_arr = array();
			foreach ($proc_arr as $p){
				$sql3 = <<<SQL
					SELECT active_processes_id 
					FROM active_processes 
					WHERE processes_ref = 170 
					AND workflow_settings LIKE '%ia_proceedings_id={$p}%'
SQL;
				$rs3 = mysqli_query($sql3) or die($sql3 . ": " . mysqli_error());
				$ap_arr = array();
				while ($row3 = mysqli_fetch_array($rs3)){
					array_push($ap_arr, $row3['active_processes_id']);
					array_push($ap_all_arr, $row3['active_processes_id']);
				}
				$ap_list = implode(", ",$ap_arr);
				echo "<br />Active processes for $p: " . $ap_list . "<br />";
			}

			$ap_all_list = implode(", ",$ap_all_arr);

			$sqlap = <<<SQL
				INSERT INTO `CHE_heqconline`.`active_processes` 
					(`active_processes_id`, `processes_ref`, `work_flow_ref`, `user_ref`, `workflow_settings`, 
					`status`, `last_updated`, `active_date`, `due_date`, `expiry_date`) 
				SELECT NULL, '170', '11090', '284', `workflow_settings`, 
					0, '2013-05-02 10:23:16', `active_date`, `due_date`, `expiry_date`
				FROM `CHE_heqconline`.`active_processes`
				WHERE active_processes_id IN ($ap_all_list);
SQL;
			echo "<br /><br />1. " . $sqlap;
			
			$sql2 = <<<SQL
				UPDATE ia_proceedings 
				SET `proceeding_status_ind` = '0',
					`proceeding_status_date` = '1970-01-01' 
				WHERE `ia_proceedings`.`ia_proceedings_id` in ({$proc_list});

SQL;
			echo "<br />2. " . $sql2 . "<br />";
			
			echo "<br><br> RERUN TO PICK UP THE NEW ACTIVE active processes to edit workflow_settings and then export.";
			//$rs2 = mysqli_query($sql2) or die(mysqli_error());	

			$act_arr = array();
			foreach ($proc_arr as $p){
				$sql4 = <<<SQL
					SELECT active_processes_id 
					FROM active_processes
					WHERE status = 0 
					AND workflow_settings LIKE '%ia_proceedings_id={$p}%'
SQL;
				$rs4 = mysqli_query($sql4) or die($sql4 . ": " . mysqli_error());
				while ($row4 = mysqli_fetch_array($rs4)){
					array_push($act_arr, $row4['active_processes_id']);
				}
			}
			$act_list = implode(", ",$act_arr);
			echo "<br /><br />3. You will need to edit and update the workflow_settings of these active processes: Remove logic_settings and delete ACTPROC value<br>";
			echo "<br />Active processes to export: " . $act_list . "<br />";
		}
	}
	
	function accept_outcomes($conn){
	
		$app_arr = array();

		$reset = <<<ACCEPT
			UPDATE applications
			SET outcome_accepted = 0, notified = 0, outcome_approval_date='1970-01-01'
ACCEPT;
		echo $reset . '<br />';
		$result = mysqli_query($reset, $conn) or die(mysqli_error());
		echo mysqli_affected_rows() . ' initialised<br />';
		
		$sel_id = <<<SELECT
			SELECT applications.id 
				FROM applications, heqf_qualifications 
				WHERE heqf_qualifications.id = applications.heqf_qualification_id 
				AND heqf_qualifications.apx_A = 1
SELECT;
		$rs = mysqli_query($sel_id,$conn) or die(mysqli_error());
		while ($row = mysqli_fetch_array($rs)){
			array_push($app_arr,"'".$row["id"]."'");
		}
		
		$app_ids = implode(", ",$app_arr);
		$accept_upd = <<<ACCEPT
			UPDATE applications
			SET outcome_accepted = '1', notified = '1', outcome_approval_date='2013-03-01'
			WHERE id IN 
				({$app_ids})
ACCEPT;
		echo $accept_upd . '<br />';
		$result = mysqli_query($accept_upd, $conn) or die(mysqli_error());
		echo mysqli_affected_rows() . ' updated<br />';
	}
	
	
	?>
