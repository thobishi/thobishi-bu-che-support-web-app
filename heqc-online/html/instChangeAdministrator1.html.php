<?php
	$search = readPost("submitButton");

	$fc_arr = array();

	$inst = readPost('institution');
	$this->formFields["institution"]->fieldValue = $inst;

	if ($inst > 0){
		array_push($fc_arr,'HEI_id = '.$inst);
	}
	
	$filter_criteria = (count($fc_arr) > 0) ? "AND ". implode(' AND ',$fc_arr) : "";
	
	$sql = <<<DATA
		SELECT 
		HEI_id,
		HEI_code,
		HEI_name,
		user_id,
		lkp_title_desc,
		name,
		surname,
		email, 
		contact_nr, 
		contact_cell_nr,
		login_number, 
		last_login_date,
		sec_group_ref
		FROM (users,  HEInstitution)
		LEFT JOIN lkp_title ON users.title_ref = lkp_title.lkp_title_id 
		LEFT JOIN sec_UserGroups ON  sec_user_ref = user_id
		WHERE institution_ref = HEI_id
		$filter_criteria
DATA;
	$active_sql = $sql . " AND active = 1 ";
	$inactive_sql = $sql . " AND active = 0 ";

	$rs = mysqli_query($this->getDatabaseConnection(), $active_sql);
	$n_user = mysqli_num_rows($rs);
	
	$inactive_rs = mysqli_query($this->getDatabaseConnection(), $inactive_sql);
	$n_inactive_user = mysqli_num_rows($inactive_rs);
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2" align="left" class="special1">
			<br>
			<span class="specialb">
			CHANGE INSTITUTIONAL ADMINISTRATOR
			</span>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="30%" align="right">Select institution: </td>
		<td>
			<?php 
			$this->formFields['institution']->fieldValue = $inst;
			$this->showField('institution');
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td><input type="submit" class="btn" id="submitButton" name="submitButton" value="Search" onClick="moveto('stay');"></td>
	</tr>
	<tr>
		<td colspan="2">
		<br>
		<p>All the active users for the selected institution will display below.  Please select the new administrator from the list by placing a check in the box next to 
		the user.  Please note the following:
		<ul>
		<li>An institution may only have one administrator.</li>
		<li>The new administrator must be an active HEQC-online institutional user.  If not, they will need to be added as an institutional user first, in order to 
		display in the user list below.</li>
		<li>All active processes will be transferred to the new administrator.</li>
		<li>The previous administrator will remain an active institutional user.</li>
		<li>The institutional administrator has functionality to manage users within their institution and can set the previous administrator to inactive if required.</li>
		</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
</table>

<?php
if ($inst == 0){
	echo "Please select an institution in order to display the users and administrator for that institution";
}
if ($inst > 0){
	$admarr = $this->getInstitutionAdministrator("", $inst);

	$adm_id = $admarr[0];

	//20110621 Robin: The users must display even if there is no administrator or more than one administrator.
	//if ($adm_id > 0){  // valid administrator
?>
		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
	<?php 		
				$html = <<<HTMLSTR
					<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
					<tr>
						<td class="specialh">Active users</td>
						<td align="right" colspan="8"><b>Number of active institutional users: $n_user</b></td>
					</tr>
					<tr class="oncolourb">
						<td>Institution</td>
						<td>Name</td>
						<td>Tel number</td>
						<td>Cell number</td>
						<td>Email address</td>
						<td>No. of times<br>logged on</td>
						<td>last login date</td>
						<td>Has<br>admin<br>rights</td>
						<td>
							Select<br>Administrator
						</td>
					</tr>
HTMLSTR;

				while($row = mysqli_fetch_array($rs)){
					$sel = "";
					$bgcolor = "onblue";
					
					// identify administrator
					if ($row["user_id"] == $adm_id){
						$bgcolor = "#d6e0eb";
						$sel = "CHECKED";
					}

					$tmpSettings = "DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_institutional_profile___institution_ref=".$row["HEI_id"];
					$institution = $row["HEI_name"] . " (" .$row["HEI_code"] . ")";
					$link1 = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["HEI_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$institution."</a>";
					$name = $row["lkp_title_desc"]." ".$row["name"]." ".$row["surname"];
					$contact_tel = $row["contact_nr"];
					$contact_cell = $row["contact_cell_nr"];
					$contact_email = $row["email"];
					$login_no = $row["login_number"];
					$last_login = $row["last_login_date"];
					$chk_reaccred = '<input type="Radio" name="inst_admin_id" value="'.$row["user_id"].'"' . $sel .'>';
					$is_admin = ($row["sec_group_ref"] == 4) ? "Yes" : "No";

					$html .= <<<HTMLSTR
					<tr class="$bgcolor">
						<td>$link1</td>
						<td>$name</td>
						<td>$contact_tel</td>
						<td>$contact_cell</td>
						<td>$contact_email</td>
						<td>$login_no</td>
						<td>$last_login</td>
						<td>$is_admin</td>
						<td>$chk_reaccred</td>
					</tr>
HTMLSTR;
				}
				$html .= <<<HTMLSTR
						</table>
						<br>
						<br>
						<br>
HTMLSTR;

				$html .= <<<HTMLSTR
					<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
					<tr>
						<td class="specialh" colspan="4">Inactive users (cannot login) - Set to active from user administration</td>
						<td align="right" colspan="4"><b>Number of inactive institutional users: $n_inactive_user</b></td>
					</tr>
					<tr class="oncolourb">
						<td>Institution</td>
						<td>Name</td>
						<td>Tel number</td>
						<td>Cell number</td>
						<td>Email address</td>
						<td>No. of times<br>logged on</td>
						<td>last login date</td>
						<td>Has <br>administrator<br>rights</td>
					</tr>
HTMLSTR;

				while($row = mysqli_fetch_array($inactive_rs)){
					
					$institution = $row["HEI_name"] . " (" .$row["HEI_code"] . ")";
					$name = $row["lkp_title_desc"]." ".$row["name"]." ".$row["surname"];
					$contact_tel = $row["contact_nr"];
					$contact_cell = $row["contact_cell_nr"];
					$contact_email = $row["email"];
					$login_no = $row["login_number"];
					$last_login = $row["last_login_date"];
					$is_admin = ($row["sec_group_ref"] == 4) ? "Yes" : "No";

					$html .= <<<HTMLSTR
					<tr>
						<td>$institution</td>
						<td>$name</td>
						<td>$contact_tel</td>
						<td>$contact_cell</td>
						<td>$contact_email</td>
						<td>$login_no</td>
						<td>$last_login</td>
						<td>$is_admin</td>
					</tr>
HTMLSTR;
				}
				$html .= <<<HTMLSTR
						</table>
HTMLSTR;


				echo $html;
	?>			
			</td>
		</tr>
		</table>
<?php
	//} // end valid administrator
} // end - inst selected
?>