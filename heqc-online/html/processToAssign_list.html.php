<?php
	$search_setting_description = readPost('search_setting_description');
	$search_username = readPost('search_username');

	$fc_arr = array();
	
	if ($search_setting_description > ''){
		array_push($fc_arr,"( `s_description` like '%".$search_setting_description."%')");
		$this->formFields["search_setting_description"]->fieldValue = $search_setting_description;
	}

	if ($search_username > ''){
		array_push($fc_arr,"( `name` like '%".$search_username."%' OR `surname` like '%".$search_username."%')");
		$this->formFields["search_username"]->fieldValue = $search_username;
	}

	$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";

	$this->showField("data");
	?>
<br><br>
<table class= "saphireframe" width="50%"  align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="center">Search setting description:</td>
		<td><?php $this->showField('search_setting_description');?></td>
	</tr>
	<tr>
		<td align="center">Search user name or surname:</td>
		<td><?php $this->showField('search_username');?></td>
	</tr>
	<tr>
		<td  align="center" colspan="2">
			<br><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_label_assignProcessToUser');">
			<input type="button" class="btn" name="clear" value="Clear fields" onclick="clearFields(document.defaultFrm);">
		</td>
	</tr>
</table>
<br><br>
<?php

	$sql = "SELECT `s_key`,`s_description`, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName
			FROM  `settings`
			LEFT JOIN `users` ON `users`.`user_id` = CAST(`settings`.`s_value` AS SIGNED)
			WHERE `s_key` LIKE  '%usr%' 
			$filter_criteria
			AND `s_key` <> 'usr_finance_indicator_emails'";
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$rs = mysqli_query($conn, $sql);
	$totalRows = mysqli_num_rows($rs);
	if($totalRows > 0){
?>
		<h3>Current settings which manage the assigning of new processes to users</h3>
		<table class= "saphireframe" width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
			<tr class="doveblox">
				<td class="doveblox">Setting description</td>
				<td class="doveblox">User Assigned</td>	
				<td class="doveblox">Action</td>				
			</tr>

			<?php
				
				while($row = mysqli_fetch_array($rs)){
				?>
					<tr>
						<td class="saphireframe"><?php echo $row['s_description']; ?></td>
						<td class="saphireframe"><?php echo $row['userName']; ?></td>
						<td class="saphireframe"><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='settings|<?php echo $row["s_key"]?>';moveto('_label_assignProcessToUser_edit');">Change User</a></td>
					</tr>
			<?php		
				}
			?>
		</table>

<?php		
	}
	
	$sqlap = <<<SQL
		SELECT processes_desc, processes_id, HEI_name, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName, users.user_id, count(*) AS no_open
		FROM `active_processes`, processes, users, HEInstitution
		WHERE active_processes.`processes_ref` = processes.processes_id
		AND active_processes.`user_ref` = users.user_id
		AND HEI_id = users.institution_ref
		AND users.institution_ref = 2
		AND status = 0
		GROUP BY processes_desc, processes_id, HEI_name, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')'), users.user_id
		ORDER BY processes_desc, processes_id, HEI_name, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')'), users.user_id
SQL;
        
	$rsap = mysqli_query($conn, $sqlap);
	$totalRowsap = mysqli_num_rows($rsap);
	if($totalRowsap > 0){
?>
		<h3>Active processes currently assigned to CHE users</h3>
		<table class= "saphireframe" width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
			<tr class="doveblox">
				<td class="doveblox">Process description</td>
				<td class="doveblox">Users institution</td>	
				<td class="doveblox">User Assigned</td>	
				<td class="doveblox">No. open<br>processes</td>	
				<td class="doveblox">Action</td>				
			</tr>
			<?php
				while($row = mysqli_fetch_array($rsap)){
					$link = $this->scriptGetForm ('processes', $row["processes_id"], '_label_moveProcessToUser_edit',$row["user_id"]);
					$tlink = "<a href='".$link."'>Move processes</a>";
					$row = <<<ROW
					<tr>
						<td class="saphireframe">{$row['processes_desc']}</td>
						<td class="saphireframe">{$row['HEI_name']}</td>
						<td class="saphireframe">{$row['userName']}</td>
						<td class="saphireframe">{$row['no_open']}</td>
						<td class="saphireframe">{$tlink}</td>
					</tr>					
ROW;
					echo $row;
				}
			?>
		</table>

<?php		
	}
?>
