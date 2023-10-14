<?php
	$this->formFields["search_name"]->fieldValue = readPost('search_name');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["search_active"]->fieldValue = readPost('search_active');
	$this->showField("search_name");
	$this->showField("search_institution");
	$this->showField("search_active");

	$user_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("users","user_id",$user_id,"institution_ref");
	if ($inst_id == 2):  // add dropdown having the CHE specific groups for CHE users
		$grp_sql = <<<CHEGROUP
			SELECT sec_group_id, CONCAT(sec_group_type,': ',sec_group_desc) as sec_group_desc
			FROM sec_Groups
			WHERE sec_group_type = 'CHE'
CHEGROUP;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$sm = $conn->prepare($grp_sql);
		$sm->execute();
		$grp_rs = $sm->get_result();


		//$grp_rs = mysqli_query($grp_sql);
		while ($row = mysqli_fetch_array($grp_rs)){
			//$arr_che[$row["sec_group_id"]] = $row["sec_group_desc"];
			$this->formFields["sec_group_ref"]->fieldValuesArray[$row["sec_group_id"]] = $row["sec_group_desc"];
		}
	endif;

	$user_name = $this->getUserName($user_id);

	if (!($this->formFields["sec_user_ref"]->fieldValue > 0)):
 		$this->formFields["sec_user_ref"]->fieldValue= $user_id;
	endif;
	$this->showField("sec_user_ref");

?>
<br>
<table border='0'>
<tr>
	<td class="loud">Assign group for user: <?php echo $user_name; ?></td>
</tr>
<tr>
	<td>
	<br>
	<table border='0'>
	<tr>
		<td>Select Group:</td>
		<td><?php echo $this->showField("sec_group_ref");?></td>
	</tr>
	</table>

	</td>
</tr>
</table>
