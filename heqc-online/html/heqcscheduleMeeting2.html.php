<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	$heqc_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$this->getHEQCMeetingTableTop($heqc_id);

	$SQL = "
		SELECT * 
		FROM heqc_meeting_members, users 
		WHERE heqc_meeting_ref= $heqc_id 
		AND heqc_meeting_members.user_ref = users.user_id";

	$rs = mysqli_query($conn, $SQL);


	$ass_members_arr = array();
	while ($row = mysqli_fetch_array($rs)) {
		array_push($ass_members_arr,$row["user_ref"]);
	}


	//since we are submitting new members, we need to go through validation
	echo '<input type="hidden" name="submitACmembers" value="1">';

	echo "This is a list of all the active HEQC members in the system. A HEQC member is a HEQC-Online user that has been 
		assigned to the HEQC Board Meeting group. If you do not see a particular member in this list, 
		please add them from the HEQC-online user management interface and assign them to the HEQC Board Meeting group";
	echo "<br><br>To assign HEQC members to this meeting, please select them using the checkbox to the left of their names:";
	echo "<hr>";
?>
</td></tr>
<tr><td>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<?php 
	
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

		$SQL = <<<SELECT1
			SELECT * 
			FROM users,sec_UserGroups 
  			WHERE users.user_id = sec_UserGroups.sec_user_ref
			AND sec_UserGroups.sec_group_ref = 24 
			AND users.active=1 
			ORDER BY surname, name
SELECT1;
		$rs = mysqli_query($conn, $SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				$selected = "";
				if (in_array($row["user_id"], $ass_members_arr)) $selected = ' checked';
				echo "<tr class='onblue'><td width='5%'>";
				echo '<input name="atMeeting_'.$row["user_id"].'"'. $selected .' type="Checkbox">';
				echo "</td>";
				echo "<td>";
				echo $row["name"]." ".$row["surname"];
				echo "</td>";
				echo "<td>";
				echo $row["email"];
				echo "</td></tr>";
				}
			}
		?>
	</table>
</td></tr>
<?php 
//	}
?>
	</table>
	<br>