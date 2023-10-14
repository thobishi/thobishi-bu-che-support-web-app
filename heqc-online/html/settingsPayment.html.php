<table width=95% border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Configure settings for payment reminders:</td>
</tr>
<tr>
<td>
	<br>
	<br>
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr class="oncolourb">
		<td>Edit</td>
		<td>Configuration setting</td>
		<td>Current value</td>
		<td>Description</td>
	</tr>
	<?php
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$SQL = <<<SQL
			SELECT * 
			FROM settings 
			WHERE s_key IN ('reminder_method','reminder1_days_from_invoice','reminder2_days_from_reminder1','reminderw_days_from_reminder2')
			ORDER BY s_key
SQL;
		$rs = mysqli_query($conn, $SQL);
		while ($row = mysqli_fetch_array($rs)) {
			$link1 = $this->scriptGetForm ('settings', $row["s_key"], '_label_editSetting');

?>

		<tr valign="top">
			<td class="oncolour">
				&nbsp;<a href='<?php echo $link1; ?>'><img src="images/ico_change.gif"></a>
			</td>
			<td class="oncolour">
				<?php echo $row["s_key"]; ?>
			</td>
			<td class="oncolour"><?php echo $row["s_value"];?></td>
			<td class="oncolour"><?php echo $row["s_description"];?></td>
		</tr>
<?php 
	}
?>
	</table>

	<br><br>
	</td>
</tr>
<tr>
	<td class="loud">Payment users and their roles</td>
</tr>
<tr>
	<td>Contact the HEQC-online User administrator if you need any changes to the following groups and users</td>
</tr>
<tr>
<td>
	<br>
	<br>
	<table width=95% border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="oncolourb">
		<td>Group name</td>
		<td>Function</td>
		<td>Processes</td>
		<td>Users</td>
	</tr>
	<?php
		
		  $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}    
			$SQL = <<<SQL
			SELECT sec_group_id , sec_group_desc , sec_group_function, 
			GROUP_CONCAT(DISTINCT processes_desc ORDER BY processes_desc SEPARATOR '\n' )as processes ,  
			GROUP_CONCAT(distinct concat(users.name , ': ', users.surname, ': ', users.email )ORDER BY surname SEPARATOR '\n' )as user_names  
			FROM (sec_Groups , sec_UserGroups , users ) 
			LEFT JOIN lnk_SecGroup_process ON `secGroup_ref` =sec_group_id  
			LEFT JOIN processes ON `process_ref` =processes_id  
			WHERE sec_group_ref = sec_group_id  
			AND user_id =`sec_user_ref`  
			AND users.active =1  
			AND sec_group_id IN (32,33,34,35)  
			GROUP BY sec_group_desc, sec_group_id  
			ORDER BY `sec_Groups`.`sec_group_id` ASC
SQL;
            
          
               file_put_contents('php://stderr', print_r($SQL, TRUE));
		$rs = mysqli_query($conn, $SQL);// or die($SQL . "<br><br>" . mysqli_error());
		while ($row = mysqli_fetch_array($rs)) {

?>

		<tr valign="top">
			<td class="oncolour"><?php echo $row["sec_group_desc"]; ?></td>
			<td class="oncolour"><?php echo $row["sec_group_function"];?></td>
			<td class="oncolour"><?php echo $row["processes"];?></td>
			<td class="oncolour"><?php echo $row["user_names"];?></td>
		</tr>
<?php 
	}
?>
	</table>

	<br><br>
	</td>
</tr>
</table>
