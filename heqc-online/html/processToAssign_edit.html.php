<LINK REL=StyleSheet HREF="css/chosen.min.css" TYPE="text/css">

<?php
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
    if ($conn->connect_errno) {
        $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
        printf("Error: %s\n".$conn->error);
        exit();
    }
        
	$user_ref = isset($_POST["user_ref"]) ? $_POST["user_ref"] : "";
	$sql = "SELECT  `user_id`, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName
			FROM   `users`
			WHERE `active` = '1'";

	$rs = mysqli_query($conn, $sql);
	$totalRows = mysqli_num_rows($rs);

	$s_key = $this->dbTableInfoArray['settings']->dbTableCurrentID;
	$descriptionSql = "SELECT `s_key`,`s_description`, CONCAT(`name`, ' ', `surname`, ' (', `email`, ')') AS userName
						FROM  `settings`
						LEFT JOIN `users` ON `users`.`user_id` = CAST(`settings`.`s_value` AS SIGNED)
						WHERE `s_key` = ?";
	

	$sm = $conn->prepare($descriptionSql);
    $sm->bind_param("s", $s_key);
    $sm->execute();
    $descriptionRs = $sm->get_result();

	//$descriptionRs = mysqli_query($conn, $descriptionSql);
	$totalDescriptionRows = mysqli_num_rows($descriptionRs);
	if($totalRows > 0 && $totalDescriptionRows > 0){
		$currentSettingDetail = mysqli_fetch_array($descriptionRs);
?>
<br><br>
	<table class= "saphireframe" width="50%" border="0" align="center" cellpadding="2" cellspacing="2">
			<tr class="doveblox">	
				<td align= "center" class="doveblox">
					<h3>Your are about to change the user in charge of receiving "<em><?php echo $currentSettingDetail['s_description']; ?></em>" currently assigned to "<em><?php echo $currentSettingDetail['userName']; ?></em>.If you wish to go ahead then"
						select a user from the list below to replace the current user and click on "<em>Save</em>" under "<em>Actions</em>" </h3>
				</td>
			</tr>
<?php
		echo '<tr class="doveblox">';
		echo '<td align= "center">';
		echo '<select name="user_ref" data-placeholder="Select..." style="width:350px; height: 30px;"  class="chzn-select">';
			echo'<option value="0" selected></option>';
		while ($row = mysqli_fetch_array($rs)){
			$selected = ($row['user_id'] == $user_ref) ? "selected" : "";
			echo "<option value=\"". $row['user_id']."\"" . $selected . ">" . $row['userName'] . "</option>";
		}

		echo '</select>';
		echo '</td>';
		echo '</tr>';
	echo '</table>';
	}

?>