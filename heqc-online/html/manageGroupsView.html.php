<?php 
	$groupID = $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID;

	//$groupID = readPost('groupID');
	$groupName = $this->getValueFromTable("sec_Groups", "sec_group_id", $groupID, "sec_group_desc");
?>
<br>
<input type='hidden' name='groupID' value="<?php echo $groupID?>">
	<table border='0' cellpadding="2" cellspacing="2" align="center" width="95%">
	<tr>
		<td colspan="2">
			<span class="loud">View Security Groups > <?php echo $groupName?></span>
			<hr>
		</td>
	</tr>
	<tr>
		<td valign="top" class='doveblox' colspan="2">Group processes:</td>
	</tr>
	<tr>
		<td class='oncolourcolumn' colspan="2">
<?php 
			$SQL = <<<PROCSQL
				SELECT p.processes_id, p.processes_desc as mainmenu, c.processes_id, c.processes_desc as submenu, c.menu_perant FROM processes p, processes c,lnk_SecGroup_process l 
				WHERE 
				c.menu_perant = p.processes_id
				AND l.process_ref = c.processes_id 
				AND l.secGroup_ref =  '$groupID'
				AND c.menu_perant < 0
PROCSQL;
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
				$prev = '';
				while ($row = mysqli_fetch_array($rs)){
					if ($row['mainmenu'] != $prev){
						echo "<b>".$row["mainmenu"]."</b><br>" ;
					} 
					echo "&nbsp;&nbsp;&nbsp;" . $row["submenu"] . "<br>";
					$prev = $row['mainmenu'];
				}
			} else {
				echo 'No processes have been linked for this group<br>';
			}
	?>
		</td>
	</tr>
	<tr>
		<td valign="top" class="doveblox" colspan="2">Group members:</td>
	</tr>
	<tr>
		<td class='oncolourcolumn'>
		<table width="100%" border="0" cellpadding="2" cellspacing="0">
		<tr>
			<td>
<?php
			// Only display active users.  Inactive users cannot login and therefore belong to no group.
			$SQL = <<<SQLUSER
				SELECT 	u.surname,
						u.name,
						u.email,
						u.contact_nr,
						i.HEI_name,
						u.contact_cell_nr
				FROM sec_UserGroups s, users u
				LEFT JOIN HEInstitution i ON i.HEI_id = u.institution_ref
				WHERE s.sec_user_ref = u.user_id 
				AND s.sec_group_ref= '$groupID' 
				AND u.active = 1
				ORDER BY surname
SQLUSER;

			$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
?>
				<table class="saphireframe" >
<?php
				while ($row = mysqli_fetch_array($rs)){
					$tbl_row = <<<TBLROW
						<tr class="doveblox">
							<td class="specials">$row[surname], $row[name]</td>
							<td class="specials">$row[email]</td>
							<td class="specials">$row[contact_nr] $row[contact_cell_nr]</td>
							<td class="specials">$row[HEI_name]</td>
						</tr>
TBLROW;
					echo $tbl_row;
				}
?>
				</table>
<?php
			} else {
				echo 'No users have been assigned to this group<br>';
			}
?>
				
			</td>
		</tr>
		</table>
	</td>	
	</tr>	
	</table>

<br>
