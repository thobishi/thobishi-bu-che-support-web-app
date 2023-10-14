<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td class="loud"><br>List of Institutional Administrators</td>
	</tr>
	<tr><td>
<?php 
/* All institution users */
	//$SQL = "SELECT * FROM users, sec_UserGroups WHERE user_id=sec_user_ref AND sec_group_ref=4 ORDER BY institution_ref";
/*	$SQL =<<<TXT
			SELECT
			HEI_name,
			lkp_title_desc,
			name,
			surname,
			email,
			contact_nr,
			sec_group_desc,
			login_number,
			last_login_date
			FROM (users,  HEInstitution) LEFT JOIN sec_UserGroups ON user_id = sec_user_ref
			LEFT JOIN sec_Groups ON sec_group_id = sec_group_ref
			LEFT JOIN lkp_title ON users.title_ref = lkp_title.lkp_title_id
			WHERE `active` = 1
			AND (sec_group_ref = 4 OR sec_group_ref is NULL)
			AND institution_ref != 0
			AND institution_ref = HEI_id
			AND institution_ref NOT IN (1,2)
			ORDER BY HEI_name
TXT;
*/
/* Institution Administrators only */
	$SQL = <<<DATA
		SELECT 
		HEI_id,
		HEI_code,
		HEI_name,
		lkp_title_desc,
		name,
		surname,
		email, 
		contact_nr, 
		contact_cell_nr,
		sec_group_desc,
		login_number, 
		last_login_date
		FROM (users,  HEInstitution, sec_UserGroups)
		LEFT JOIN sec_Groups ON sec_group_id = sec_group_ref
		LEFT JOIN lkp_title ON users.title_ref = lkp_title.lkp_title_id 
		WHERE `active` = 1 
		AND user_id = sec_user_ref
		AND (sec_group_ref = 4)
		AND institution_ref not in (0,1,2) 
		AND institution_ref = HEI_id
		ORDER BY HEI_name
DATA;
	$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
	$num_administrators = mysqli_num_rows($RS);
?>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="oncolourb" align="right">
		<td colspan="7">
		<?php echo "Number of Administrators: " . $num_administrators; ?>
		</td>
	</tr>
	<tr class="oncolourb">
		<td>Institution</td>
		<td>Name</td>
		<td>Tel number</td>
		<td>Cell number</td>
		<td>Email address</td>
		<td>No. of times<br>logged on</td>
		<td>last login date</td>
	</tr>
<?php 
	while ($row = mysqli_fetch_array($RS)) {

		$tmpSettings = "DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_institutional_profile___institution_ref=".$row["HEI_id"];
		$institution = $row["HEI_name"] . " (" .$row["HEI_code"] . ")";
		$link1 = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["HEI_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$institution."</a>";
		$name = $row["lkp_title_desc"]." ".$row["name"]." ".$row["surname"];
		$contact_tel = $row["contact_nr"];
		$contact_cell = $row["contact_cell_nr"];
		$contact_email = $row["email"];
		$login_no = $row["login_number"];
		$last_login = $row["last_login_date"];

		$userList =<<<TXT
			<tr class='onblue'>
				<td>$link1</td>
				<td>$name</td>
				<td>$contact_tel</td>
				<td>$contact_cell</td>
				<td>$contact_email</td>
				<td>$login_no</td>
				<td>$last_login</td>
			</tr>
TXT;
		echo $userList;
	}
?>
	</table>
	</td></tr>
</table>
