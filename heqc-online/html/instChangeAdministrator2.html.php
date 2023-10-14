<?php
	//$this->formActions["next"]->actionMayShow = false;

	$inst_id = readPost('institution');
	$this->formFields["institution"]->fieldValue = $inst_id;
	$this->showField("institution");
	$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");

	$new_adm_id = readPost('inst_admin_id');
	$this->formFields["new_adm_id"]->fieldValue = $new_adm_id;
	$this->showField("new_adm_id");
	$new_adm = $this->getUserName($new_adm_id);

	$html = "";
	//$val_msg = "";
	$prev_adm_arr = array();

	if ($inst_id == 0 || $new_adm_id == 0) {
		$html .= "Please select an institution and the user who will be the new institutional administrator.";
	}

	if ($inst_id > 0 ){  // valid institution is selected

		//$admarr = $this->getInstitutionAdministrator("", $inst_id);
		//$prev_adm_id = $admarr[0];

		//if ($prev_adm_id == 0){
			//$val_msg = $admarr[1];
		//}
		
		//if ($prev_adm_id == $new_adm_id){
			//$val_msg = "You have not selected a new administrator.  No changes will occur.";
		//}

		$sql = <<<adminSQL
			SELECT user_id, name, surname, email, contact_nr, contact_cell_nr, active
			FROM users, sec_UserGroups
			WHERE user_id = sec_user_ref
			AND sec_group_ref = 4
			AND institution_ref = ?
adminSQL;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$sm = $conn->prepare($sql);
		$sm->bind_param("s", $inst_id);
		$sm->execute();
		$rs = $sm->get_result();

		//$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$n_adm = mysqli_num_rows($rs);
		if ($n_adm == 0){
			$text = '<span class="specialb">This institution does not currently have an administrator.</span>';
		} elseif ($n_adm == 1){
			$text = '<span class="specialb">The current administrator is:</span>';
		} else {
			$text = '<span class="specialb">The current administrators are:</span>';
		}

		$html .= <<<HTML
			You have requested to change the institutional administrator for institution: <b>$inst_name</b>
			<br><br>
			$text
			<table align="center" width="70%">
HTML;
		if ($n_adm > 0){
			while ($row = mysqli_fetch_array($rs)){
				array_push($prev_adm_arr, $row["user_id"]);
				$active = ($row["active"] == 1) ? '(active user)' : '(deactivated user)';
				$html .= <<<HTML
					<tr><td>$row[name] $row[surname]</td><td>$row[email]</td><td>$row[contact_nr] $row[contact_cell_nr]</td><td>$active</td></tr>
HTML;
			}
		} else {
			$html .= <<<HTML
				<tr><td colspan="4">No current administrator</td></tr>
HTML;
		}
		$html .= <<<HTML
			</table>
			<br>
			<br>
			<span class="specialb">The new administrator will be: </span>$new_adm
			<br>
			<br>
			If you click on <span class="specialb">Continue and change administrator</span> in the Actions menu the following will take place:
			<ul>
				<li>All active processes will be transferred from the current administrator or administrators to the new administrator.</li>
				<li>The new administrator will be setup as the institutional administrator.</li>
				<li>The administrator rights will be removed from the current administrator or administrators.</li>
			</ul>
			<br>
			<span class="visi">NOTE: An institution should only have one administrator.</span>
HTML;

			$this->formFields["prev_adm_id"]->fieldValue = implode(",",$prev_adm_arr);
			$this->showField("prev_adm_id");


		/*
		if ($prev_adm_id != $new_adm_id && $prev_adm_id > 0){  // valid administrator

			$this->formFields["prev_adm_id"]->fieldValue = $prev_adm_id;
			$this->formFields["new_adm_id"]->fieldValue = $new_adm_id;
			$this->showField("prev_adm_id");
			$this->showField("new_adm_id");
			
			$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");
			$prev_adm = $this->getUserName($prev_adm_id);
			$new_adm = $this->getUserName($new_adm_id);
			$val_msg = <<<MSG
				<b>Institution: $inst_name</b>
				<br><br>
				You have requested to change the institutional administrator from <b>$prev_adm</b> to <b>$new_adm</b>.
				<br><br>
				Please click on Continue to confirm and proceed with this request.
				<br>
MSG;
			$this->formActions["next"]->actionMayShow = true;
		}
		*/
	}

?>

<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="left" class="special1">
			<br>
			<span class="specialb">
			CHANGE INSTITUTIONAL ADMINISTRATOR
			</span>
		</td>
	</tr>
	<tr>
		<td><br><?php echo $html; ?></td>
	</tr>
</table>
<br>
