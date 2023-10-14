<br>

<?php 
	$currentUserID = $this->currentUserID;
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "You have confirmed your attendance at the following meetings. You will be granted viewing access to programmes up until the day of the HEQC Meeting.";
	echo "<br>Each row displays a meeting - for each meeting, you will see: ";
	echo "<ul>";
	echo "<li>The meeting date (please note you will only be able to view applications until the day before the meeting).</li>";
	echo "<li>The venue of the meeting.</li>";
	echo "<li>The list of applications that will be discussed at this meeting. If you click on this link, you will be able to view the following:";
		echo "<ul>";
		echo "<li>Application submission (reference number, programme name)</li>";
		echo "<li>Institutional profile</li>";
		echo "<li>Evaluator report <b><i>(not any they should be excluded from however)</i></b></li>";
		echo "<li>Directorate recommendations <b><i>(as above)</i></b></li>";
		//echo "<li>Representations (letter of appeal -> for next phase)</li>";
		echo "</ul>";
	echo "</li>";
	echo "<li>The minutes of the previous HEQC meeting.</li>";
	echo "<li>The summary of the AC recommendations.</li>";
	echo "</ul>";
?>

</td></tr>
</table>

<!---------------------------------------->

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<?php
		echo "<tr class='oncolourb'>";
		echo "<td><Meeting date</td>";
		echo "<td>Meeting venue</td>";
		echo "<td>Applications assigned to meeting</td>";
		echo "<td>AC summary</td>";
		echo "<td>Minutes of previous meeting</td>";
		if ($this->sec_partOfGroup(27)) { // HEQC Meeting minutes group
			echo "<td>Take minutes</td>";
		}
		echo "</tr>";

		// Display all meetings for minute takers - else display meeting that you are assigned to as a heqc member.
		$selector = 0;
		if ($this->sec_partOfGroup(27)){
			$SQL  = <<<SQL
				SELECT * 
				FROM HEQC_Meeting
				ORDER BY heqc_start_date
SQL;
		} else {
			$SQL  = "SELECT * FROM heqc_meeting_members, HEQC_Meeting";
			$SQL .= " WHERE heqc_meeting_members.user_ref=?";
			$SQL .= " AND heqc_meeting_members.heqc_meeting_ref=heqc_id ";
			$SQL .= " AND HEQC_Meeting.heqc_member_access_date >= ?";
			$SQL .= " ORDER BY heqc_start_date";
			$selector = 1;
		}
                $conn = $this->getDatabaseConnection();
                $stmt = $conn->prepare($SQL);
                if($selector == 1)
                    $stmt->bind_param("ss", $currentUserID, date("Y-m-d"));

                $stmt->execute();

                $rs = $stmt->get_result();
		
		//$rs = mysqli_query($conn, $SQL);
		if (!$rs){
			$this->writeLogInfo(10, "SQL-GETVAL", "Current user is: *" . $currentUserID . "*<br><br>" . $SQL."  --> ".mysqli_error($conn), true);
		}
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array($rs))
			{
				$agendaDoc = new octoDoc($row['ac_summary_doc']);
				$prev_minutesDoc = new octoDoc($row['prev_minutes_doc']);

				echo "<tr class='onblue'>";
				echo "<td>".$row["heqc_start_date"]."</td>";
				echo "<td>".$row["heqc_meeting_venue"]."</td>";
				echo "<td><a href='pages/heqcApplicationList.php?heqc_ref=".base64_encode($row['heqc_id'])."&member_id=".base64_encode($currentUserID)."' target='_blank'>Click to view application list</a></td>";
				echo "<td><a href='".$agendaDoc->url()."' target='_blank'>".$agendaDoc->getFilename()."</a></td>";
				echo "<td><a href='".$prev_minutesDoc->url()."' target='_blank'>".$prev_minutesDoc->getFilename()."</a></td>";
				if ($this->sec_userInGroup("HEQC Meeting minutes")) {
					$link1 = $this->scriptGetForm ('HEQC_Meeting', $row['heqc_id'], '_label_HEQCminute_edit');
					echo "<td><a href='$link1'>Display list for edit</a></td>";
				}
				echo "</tr>";
			}
		}
		else {
			echo "<tr>";
			echo "<td colspan='10' align='center'>";
			echo "-- You have no HEQC Meetings assigned to you at the moment. --";
			echo "</td></tr>";
		}
	?>
</table>
<br>

