<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions, AC_Meeting_date FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and application_id=? ORDER BY HEI_name,program_name";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
while ($row = mysqli_fetch_array($rs)){
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please enter the AC Meeting Date:</b></td>
</tr><tr>
	<td><input type='TEXT' name="AC_Meeting_date" readonly>
	<a href="javascript:show_calendar('defaultFrm.AC_Meeting_date');"><img src="images/icon_calendar.gif" border=0></a>
	</td>
</tr></table>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
		<b>Using the template below, enter the decisions taken on the AC Meeting</b>:<br>
<br>
<?php 
//$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." ORDER BY HEI_name,program_name";
//$rs = mysqli_query($SQL);
//while ($row = mysqli_fetch_array($rs)){
	echo "<table cellpadding='2' cellspacing='2' border='1'>";
	echo "<tr><td>";
	echo "<table cellpadding='2' cellspacing='2' border='0'>";
	echo "<tr>";
	echo "<td valign=top><strong>INSTITUTION:</strong></td>";
	echo "<td valign=top>".$row["HEI_name"]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td valign=top><strong>PROGRAMME:</strong></td>";
	echo "<td valign=top>".$row["program_name"]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td valign=top><strong>REFERENCE:</strong></td>";
	echo "<td valign=top>".$row["CHE_reference_code"]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td valign=top><strong>DECISION:</strong></td>";
	echo '<td valign=top>';
	echo '<select name="GRID_'.$row["application_id"].'$application_id$AC_desision$Institutions_application">';

	$SSQL = "SELECT * FROM lkp_desicion WHERE 1 ORDER BY lkp_title";
	
	
	$rrs = mysqli_query($conn, $SSQL);
	while ($rrow = mysqli_fetch_array($rrs))
	{
		echo '<option';
			if ($rrow["lkp_id"] == $row["AC_desision"]) echo ' selected ';
		echo ' value="'.$rrow["lkp_id"].'">'.$rrow["lkp_title"].'</option>';
	}

	echo '</select>';
	echo '</td>';
	echo "</tr>";
	echo "<tr>";
	echo "<td valign=top><strong>CONDITIONS:</strong></td>";
	echo '<td valign=top><textarea rows="20" cols="50" name="GRID_'.$row["application_id"].'$application_id$AC_conditions$Institutions_application">'.$row["AC_conditions"].'</textarea></td>';
	echo "</tr>";
	echo "<tr><td colspan='2'><input type='HIDDEN' name='GRID_save_".$row["application_id"]."' value='1'></td></tr>";
	echo '</table>';
	echo "</tr>";
	echo '</table>';

}
?>
	</td>
</tr></table>
<script>
	function checkConditions() {
		if (document.all.MOVETO.value == 'next') {
			obj = document.all;
			for (i=0; i < obj.length; i++) {
				try {
					if ((obj[i].name.indexOf("AC_conditions") != -1) || (obj[i].name.indexOf("AC_desision") != -1)) {
							if ((obj[i].value == "") || (obj[i].value == -1)) {
								alert("Complete the Decisions and Conditions before continuing.");
								obj[i].focus();
								document.all.MOVETO.value = '';
								return false;
							}
					}
				}catch(e){}
			}
		}
		return true;
	}
</script>
</td></tr></table>
