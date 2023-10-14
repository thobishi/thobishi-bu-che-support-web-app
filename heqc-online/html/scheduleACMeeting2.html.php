<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php   
        $conn = $this->getDatabaseConnection(); 
	$ac_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$this->getACMeetingTableTop($ac_id);

	$SQL = "SELECT * FROM lnk_ACMembers_ACMeeting, AC_Members WHERE ac_meeting_ref=? AND ac_member_ref=ac_mem_id";
	$stmt = $conn->prepare($SQL);
        $stmt->bind_param("s", $ac_id);
        $stmt->execute();
        $rs = $stmt->get_result();
	//$rs = mysqli_query($conn, $SQL);

//	if (mysqli_num_rows($rs) > 0){
		$ass_members_arr = array();
		while ($row = mysqli_fetch_array($rs)) {
			array_push($ass_members_arr,$row["ac_member_ref"]);
		}

// 2010-07-26 Robin: Removed because user want to re-assign ac members.
		//since we are not submitting any new members, we can skip the validation
//		echo '<input type="hidden" name="submitACmembers" value="0">';
//		echo "The AC Members assigned to this meeting are:";
//		echo "<hr>";

//		echo '<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">';

//		while ($row = mysqli_fetch_array($rs)){
//			echo "<tr class='onblue'>";
//			echo "<td>";
//			echo $row["ac_mem_name"]." ".$row["ac_mem_surname"];
//			echo "</td>";
//			echo "<td>";
//			echo "(".$row["ac_mem_email"].") ";
//			echo "</td></tr>";
//		}
//		echo "</table>";

//	}

//	else {
		//since we are submitting new members, we need to go through validation
		echo '<input type="hidden" name="submitACmembers" value="1">';

		echo "This is a list of all the active AC members in the system. If you do not see an AC member to assign to this AC meeting, please either add them to the database or set their status to \"Active\" by going to \"AC Meeting\" > \"Manage AC members\".";
		echo "<br><br><span class='visi'>Note: All members that you select will immediately have access to this meeting's documentation.</span>";
		echo "<br><br>To assign AC members to this AC meeting, please select them using the checkbox to the left of their names:";
		echo "<hr>";
?>
		</td></tr>

		<tr><td>
			<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
			<?php 
			$SQL = "SELECT * FROM AC_Members WHERE ac_mem_active=1 ORDER BY ac_mem_surname, ac_mem_name";
			$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$selected = "";
					if (in_array($row["ac_mem_id"], $ass_members_arr)) $selected = ' checked';
					echo "<tr class='onblue'><td width='5%'>";
					echo '<input name="atMeeting_'.$row["ac_mem_id"].'"'. $selected .' type="Checkbox">';
					echo "</td>";
					echo "<td>";
					echo $row["ac_mem_name"]." ".$row["ac_mem_surname"];
					echo "</td>";
					echo "<td>";
					echo $row["ac_mem_email"];
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