<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
The following report has been created by the manager regarding the programme(s) below, please look at it, and make the necessary changes. Once you are satisfied with the report, click "Next".<br>
<br>
<table cellpadding='2' cellspacing='2' border="1">
<tr>
<td class="oncolourb">INSTITUTION</td>
<td class="oncolourb">PROGRAMME</td>
<td class="oncolourb">REFERENCE</td>
<td class="oncolourb">DECISION</td>
<td class="oncolourb">CONDITIONS</td>
</tr>
<pre>
<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$institution = $this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and AC_Meeting_ref=? AND institution_id=? ORDER BY HEI_name,program_name";

$stmt = $conn->prepare($SQL);
$stmt->bind_param("ss", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID, $institution);
$stmt->execute();
$rs = $stmt->get_result();
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
</pre>
</table>
<br>
<?php 
$this->makeLink('file_ref')
?>
</td>
</tr></table>
</td></tr></table>
<script>
function checkFiles(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_file_ref.value == "0"){
			alert("Please upload a report");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
		return true;	
		}
	}	
}
</script>
