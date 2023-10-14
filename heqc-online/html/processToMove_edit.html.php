<LINK REL=StyleSheet HREF="css/chosen.min.css" TYPE="text/css">
<?php
	$user_id = readPost('data');
	$processes_id = $this->dbTableInfoArray["processes"]->dbTableCurrentID;
	$this->formFields["data"]->fieldValue = $user_id;
	$this->showField("data");
?>
	<br>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="loud">Move active processes to another user:</td>
	</tr>
	<?php
	if ($user_id > 0 && $processes_id > 0){
		$sqlap = <<<SQL
		SELECT processes_desc, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName, count(*) AS no_open
		FROM `active_processes`, processes, users, HEInstitution
		WHERE active_processes.`processes_ref` = processes.processes_id
		AND active_processes.`user_ref` = users.user_id
		AND HEI_id = users.institution_ref
		AND users.institution_ref = 2
		AND status = 0
		AND user_id = {$user_id}
		AND processes_ref = {$processes_id}
		GROUP BY processes_desc, processes_id, HEI_name, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')'), users.user_id
		ORDER BY processes_desc, processes_id, HEI_name, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')'), users.user_id
SQL;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
                
		$rsap = mysqli_query($conn, $sqlap);
		$totalRowsap = mysqli_num_rows($rsap);
		if($totalRowsap > 0){		
?>
			<tr>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>You have selected to move the following processes with this user to the user you select.
				<ul>
					<li>Click on Cancel in the Actions menu to cancel this transaction.</li>
					<li>Select a user and click Save in the Actions menu to proceed to move the processes.</li>
				</ul>
			</td>
			</tr>
			<tr>
			<td>
				<table width="50%" border="1" align="center" cellpadding="2" cellspacing="2">
				<tr class="doveblox"><td>Process</td><td>User</td><td>No. to move</td></tr>
				<?php
				while ($row = mysqli_fetch_array($rsap)){
					$row = <<<ROW
					<tr>
						<td>{$row["processes_desc"]}</td>
						<td>{$row["userName"]}</td>
						<td>{$row["no_open"]}</td>
					</tr>
ROW;
					echo $row;
				}
				?>
				</table>
			</td>
			</tr>
			<tr>
			<td>
			<table class= "saphireframe" width="50%" border="0" align="center" cellpadding="2" cellspacing="2">
<?php
				echo '<tr class="doveblox">';
				echo '<td align= "center">';
				echo "Please select the user to move the processes to:";
				$sql = <<<SQL
				SELECT  `user_id`, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName
				FROM   `users`
				WHERE `active` = 1
				AND `institution_ref` = 2
				ORDER BY userName
SQL;
                $rs = mysqli_query($conn, $sql);
				$totalRows = mysqli_num_rows($rs);
				if ($totalRows > 0){
					echo '<select name="user_ref" data-placeholder="Select..." style="width:350px; height: 30px;"  class="chzn-select">';
					echo'<option value="0" selected></option>';
					while ($row = mysqli_fetch_array($rs)){
						echo "<option value=\"". $row['user_id']."\">" . $row['userName'] . "</option>";
					}
					echo '</select>';
				} else {
					echo 'No users were found.  Please contact support.';
				}
?>
				</td>
				</tr>
				</table>
			</td>	
			</tr>
<?php
		}
	} else {
		// Process and user has not been set
?>
		<tr>
			<td>The process and user from whom to move proceses has not been found.  Please cancel and try again.</td>
		</tr>
<?php
	}
?>
</table>
