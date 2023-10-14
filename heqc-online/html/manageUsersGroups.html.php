<?php
	$this->formFields["search_name"]->fieldValue = readPost('search_name');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["search_active"]->fieldValue = readPost('search_active');
	$this->showField("search_name");
	$this->showField("search_institution");
	$this->showField("search_active");

	$user_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
	$user_name = $this->getUserName($user_id);

	$SQL = <<<USERGROUP
			SELECT * 
			FROM sec_UserGroups
			INNER JOIN sec_Groups ON sec_UserGroups.sec_group_ref = sec_Groups.sec_group_id
			WHERE sec_user_ref = ?
USERGROUP;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $user_id);
		$sm->execute();
		$rs = $sm->get_result();

		//$rs = mysqli_query($SQL);
		$rowcount = mysqli_num_rows($rs);
?>

<br>
<table width="95%" border="0" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Manage groups for user: <?php echo $user_name; ?></td>
</tr>
<tr>
	<td>
	<br>
	Roles are implemented through assigning specific groups to users.  Each group has menu items assigned to it.  When a user is assigned to a group 
	then that user may access the menu items and functionality associated with that group.  The functionality per group may be viewed from the 
	'Manage security groups' menu option.  The above user has been assigned to the following groups.</td>
</tr>
<tr>
	<td>
	<br>
	<?php
		$html = <<<HTML
			<table width="60%" align="center" cellpadding="2" cellspacing="2" class="doveblox">
				<tr class="oncolourb">
					<td>Edit</td><td>Group name</td><td>Group Type</td>
				</tr>
HTML;

		if ($rowcount == 0):
			$html .= <<<HTML
				<tr>
					<td colspan="3">No groups have been assigned to this user.</td>
				</tr>
HTML;
		endif;
		while ($row = mysqli_fetch_array($rs)){
			$edit_link = $this->scriptGetForm ('sec_UserGroups', $row["sec_UserGroups_id"], 'next');
			$html .= <<<HTML
				<tr>
					<td><a href='$edit_link'><img src="images/ico_change.gif"></a></td><td>$row[sec_group_desc]</td><td>$row[sec_group_type]</td>
				</tr>
HTML;
		}
		$html .= <<<HTML
			</table>
HTML;
		echo $html;
	?>

	</td>
</tr>
</table>
