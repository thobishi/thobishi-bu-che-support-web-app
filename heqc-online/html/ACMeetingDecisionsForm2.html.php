<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
Once you have uploaded the minutes of the meeting, and are satisfied with the summary table, click "Next" to forward it to your manager, in order for him/her to generate the necessary reports.<br>
<br>
<table cellpadding='2' cellspacing='2' border="1">
<tr>
<td class="oncolourb">INSTITUTION</td>
<td class="oncolourb">PROGRAMME</td>
<td class="oncolourb">REFERENCE</td>
<td class="oncolourb">DECISION</td>
<td class="oncolourb">CONDITIONS</td>
</tr>
<?php 
$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and AC_Meeting_ref=? ORDER BY HEI_name,program_name";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
while ($row = mysqli_fetch_array($rs)){

	echo "<tr>";
	echo "<td valign='top'>".$row["HEI_name"]."&nbsp;</td>";
	echo "<td valign='top'>".$row["program_name"]."&nbsp;</td>";
	echo "<td valign='top'>".$row["CHE_reference_code"]."&nbsp;</td>";
	echo "<td valign='top'>".$this->getValueFromTable("lkp_desicion","lkp_id",$row["AC_desision"],"lkp_title")."&nbsp;</td>";
	echo "<td valign='top'>".$row["AC_conditions"]."&nbsp;</td>";
	echo "</tr>";
}
?>
</table>
<br><br>
Minutes of meeting: <br><br>
<?php $this->makeLink('minutes_doc')?>

</td>
</tr></table>
</td></tr></table>
<script>
function checkFiles(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_minutes_doc.value == "0"){
			alert("Please upload the minutes of the AC Meeting");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
		return true;	
		}
	}	
}
</script>
