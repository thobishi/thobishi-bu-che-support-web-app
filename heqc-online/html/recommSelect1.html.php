<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$ru_id = "";

	$ru = $this->getSelectedRecommUserForAppProceeding($app_proc_id);
	$html_users = <<<MESSAGE
		<b>No user has been assigned to the Directorate Recommendation Portal for this application.  
		Please indicate the required user below by clicking on the required radio button in Assign to application.</b>
		<br>
		<br>
MESSAGE;

	if (count($ru) > 0){
		$ru_id = $ru["user_id"];
		$html_users = <<<USERS
			<b>The following user has been assigned to the Directorate Recommendation Portal for this application.</b>
			<table align="center" width="90%" border=0  cellpadding="2" cellspacing="0">
				<tr class="oncolour"><td>Name</td><td>Email</td><td>Phone numbers</td></tr>
USERS;
		$html_users .= <<<USERS
			<tr><td class="saphireframe">$ru[user_name]</td><td class="saphireframe">$ru[email]</td><td class="saphireframe">$ru[contact_nr] $ru[contact_cell_nr]</td></tr>
USERS;
		$html_users .= <<<USERS
			</table>
USERS;
	}
	
	$dir_users = $this->getRecommendationUsers($ru_id);
/*
	$SQL = <<<recommUsers
		SELECT users.user_id, CONCAT( users.name, ' ', users.surname ) AS user_name, 
			users.email, users.contact_nr, users.contact_cell_nr
		FROM users, sec_UserGroups
		WHERE users.user_id = sec_UserGroups.sec_user_ref
		AND sec_UserGroups.sec_group_ref = 19
		AND users.active = 1
		ORDER BY users.surname, users.name
recommUsers;
	$rs = mysqli_query($SQL);
	$n_rows = mysqli_num_rows($rs);

	if ($n_rows == 0):
		$dir_users = <<<USERS
		<table  class="saphireframe" border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
		<tr class="oncolourb">
			<th align="left">List of available Directorate Recommendation users</th>
		</tr>
		<tr>
			<td><i>No users have been assigned to the Directorate Recommendation security group.</i></td>
		</tr>
		</table>
USERS;
	endif;

	if ($n_rows > 0):
		$dir_users = <<<USERS
					<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
					<tr>
						<td align="left" colspan="5"><span class="visi">Note: You may change the user responsible for the directorate recommendation by clicking on another user and Next.</span></td>
					</tr>
					<tr class="oncolourb">
						<td>Name</td>
						<td>Email address</td>
						<td>Tel number</td>
						<td>Cell number</td>
						<td>
							Assign to<br>application
						</td>
					</tr>
USERS;
		while ($row = mysqli_fetch_array($rs)) {
			$sel = "";
			$bgcolor = "onblue";
			$user_id = $row["user_id"];
			if ($user_id == $ru_id){
				$bgcolor = "#d6e0eb";
				$sel = "CHECKED";
			}
			$dir_users .= <<<USERS
				<tr class="$bgcolor">
					<td>$row[user_name]</td>
					<td>$row[email]</td>
					<td>$row[contact_nr]</td>
					<td>$row[contact_cell_nr]</td>
					<td><input type="radio" name="recomm_user_id" value="$user_id" $sel /></td>
				</tr>
USERS;
		}
		$dir_users .= '</table>';
	endif;
*/
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<b>List of previous evaluations for this application</b>
		<?php 
			echo $this->displayListofEvaluations($app_id);
		?>
	</td>
</tr>
</table>
<br/>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
				Please select the user that will complete the directorate recommendation for this application. A user must be an active HEQC-online user 
				that belongs to the Directorate Recommendation security group.  Please contact the HEQC-online administrator if the user you require does not appear in the list
				and request them to add the user as a recommendation user.<br><br>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $html_users; ?>				
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $dir_users; ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
