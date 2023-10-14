<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<br>

<?php 

		$SQL = <<<APP
			SELECT * 
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id
			WHERE a.application_status = 2
APP;
//			AND ia_proceedings_id = 3 
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$rs = mysqli_query($conn, $SQL);

		echo "<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>";

		if (mysqli_num_rows($rs) > 0) {
			echo "<tr class='oncolourb'>";
			echo "<td>Id</td>";
			echo "<td>HEQC reference number</td>";
			echo "<td>Programme name</td>";
			echo "<td>Status</td>";
			echo "</tr>";
				while ($row = mysqli_fetch_array($rs)) {
					$msg = "";
					echo "<tr class='onblue'>";
					echo "<td>".$row['ia_proceedings_id']."</td>";
					echo "<td>".$row['CHE_reference_code']."</td>";
					echo "<td>".$row['program_name']."</td>";
					
					$proc_id = $row['ia_proceedings_id'];
					$HEQConline::generateDocument($proc_id,"dir_recomm_document",$fileName,"ia_proceedings","ia_proceedings_id","recomm_doc");
					//$msg = "Recommendation added successfully";
					$msg ="";
					echo "<td>".$msg."</td>";
					echo "</tr>";
				}
			echo "</table>";

			echo "<br><br>";

		}
		else {
			echo "<tr class='onblue' align='center'>";
			echo "<td colspan='4'>No applications have been assigned to this AC Meeting. Please click \"Previous\" to select applications to assign.</td>";
			echo "<tr>";
			echo "</table>";
		}


?>


</td></tr>
</table>
<br>
