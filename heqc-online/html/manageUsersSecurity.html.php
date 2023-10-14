<?php
	$s_name = readPost('search_name');
	$s_email = readPost('search_email');
	$s_inst = readPost('search_institution');
	$s_active = readPost('search_active');

	$fc_arr = array();
	
	if ($s_name > ''){
		array_push($fc_arr,"( name like '".$s_name."%' OR surname like '".$s_name."%')");
		$this->formFields["search_name"]->fieldValue = $s_name;
	}

	if ($s_surname > ''){
		array_push($fc_arr,"( surname like '".$s_surname."%')");
		$this->formFields["search_surname"]->fieldValue = $s_surname;
	}

	if ($s_email > ''){
		array_push($fc_arr,"( email like '".$s_email."%')");
		$this->formFields["search_email"]->fieldValue = $s_email;
	}
	
	if ($s_inst > 0){
		array_push($fc_arr," institution_ref = ".$s_inst);
		$this->formFields["search_institution"]->fieldValue = $s_inst;
	}


	// Display all active users by default
	if ($s_active == '' || $s_active == '1') {
		$s_active_str = " active = 1 ";
	} else {
		$s_active_str = " active <> 1 ";
	}
	array_push($fc_arr,$s_active_str);
	$this->formFields["search_active"]->fieldValue = $s_active;


	$filter_criteria = (count($fc_arr) > 0) ? ' WHERE ' . implode(' AND ',$fc_arr) : "";
?>

	<br>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="loud">User administration:</td>
		</tr>
		<tr>
			<td>
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td align="right">
					Name or surname:  
					</td>
					<td>
					<?php $this->showField('search_name');	?>
					</td>
					<td rowspan="3" valign="bottom" align="left">
						<input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_startUserManagement');">
					</td>
				</tr>
				<tr>
					<td align="right">
					Email:  
					</td>
					<td>
					<?php $this->showField('search_email');	?>
					</td>
				</tr>
				<tr>
					<td align="right">
					Institution:  
					</td>
					<td>
					<?php $this->showField('search_institution');	?>
					</td>
				</tr>
				<tr>
					<td align="right">
					Login status:<br><span class="specials"><i>(whether user can login or not)</i></span>
					</td>
					<td>

							<?php $this->showField('search_active');	?>

					</td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
	<hr>
<?php
if ($filter_criteria > "" OR isset($_POST['submitButton'])){

	$html = "";
	$SQL = <<<SQL
		SELECT users.user_id, users.name, users.surname, users.email, users.contact_nr,  institution_ref, HEInstitution.HEI_name, users.login_number, users.last_login_date, lkp_active.lkp_active_desc
		FROM users
		LEFT JOIN HEInstitution ON HEInstitution.HEI_id = users.institution_ref
		LEFT JOIN lkp_active ON lkp_active.lkp_active_id = users.active
			$filter_criteria
		ORDER BY users.surname, users.name
SQL;
//echo $SQL;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$rs = mysqli_query($conn, $SQL);
	$n_usr = mysqli_num_rows($rs);
?>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="Loud">
		<td class="Loud">
			List of HEQConline users
		</td>
		<td class="Loud" align="right"><?php echo "Number of users: " . $n_usr; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
				<tr class="doveblox">
					<td class="doveblox">Edit</td>
					<td class="doveblox">Manage<br>roles</td>
					<td class="doveblox">Surname</td>
					<td class="doveblox">Name</td>
					<td class="doveblox">Email</td>
					<td class="doveblox">Telephone no.</td>
					<td class="doveblox">Institution</td>
					<td class="doveblox">Login<br>number</td>
					<td class="doveblox">Last login<br>date</td>
					<td class="doveblox">Login<br>Status</td>
				</tr>
<?php

				if ($n_usr > 0){
					while ($row = mysqli_fetch_array($rs)){
					
					$user_id = $row["user_id"];
					$edit_link = $this->scriptGetForm ('users', $user_id, 'next');
					$group_link = $this->scriptGetForm ('users', $user_id, '_startUserManageGroups');
					
					$adm = "";
					$admarr = $this->getInstitutionAdministrator("", $row["institution_ref"]);
					if ($user_id === $admarr[0]) $adm = '<span class="specials">(administrator)</span>';
					
					$html .= <<<HTML
							<tr>
							<td class="saphireframe"><a href='$edit_link'><img src="images/ico_change.gif"></a></td>
							<td class="saphireframe"><a href='$group_link'><img src="images/ico_eval.gif"></a></td>
							<td class="saphireframe">$row[surname]</td>
							<td class="saphireframe">$row[name] $adm</td>
							<td class="saphireframe">$row[email]</td>
							<td class="saphireframe">$row[contact_nr]</td>
							<td class="saphireframe">$row[HEI_name]</td>
							<td class="saphireframe">$row[login_number]</td>
							<td class="saphireframe">$row[last_login_date]</td>
							<td class="saphireframe">$row[lkp_active_desc]</td>
							</tr>
HTML;
					}
				}
				
				echo $html
?>

			</table>
		</td>
	</tr>
	</table>
<?php
}
?>

