<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<?php 
$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions, AC_Meeting_date FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and application_id=? ORDER BY HEI_name,program_name";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();
                        
//$rs = mysqli_query($SQL);
while ($row = mysqli_fetch_array($rs)){
?>
	<table border=0 cellpadding="2" cellspacing="2">
		<tr class="onblue">
			<td width="30%">Please select the AC meeting that this application was tabled at:</td>
			<td><?php echo $this->showField("AC_Meeting_date")?></td>
		</tr>

		<tr class="onblue">
			<td valign="top">Please select the AC outcome of the application from the above meeting:</td>
			<td><?php $this->showField("AC_desision");?></td>
		</tr>

		<tr valign="top" class="onblue">
			<td valign="top">Please enter any relevant comments:</td>
			<td><?php $this->showField("AC_conditions");?></td>
		</tr>

		<tr class="onblue">
			<td valign="top">Please upload the any documents pertaining to this application that went along to the AC meeting:</td>
			<td><?php $this->makeLink("AC_conditions_doc");?></td>
		</tr>

	</table>
<?php 
}
?>

<script>
	function checkConditions() {
		if (document.all.MOVETO.value == 'next') {
			obj = document.all;
			for (i=0; i < obj.length; i++) {
				try {
					if ((obj[i].name.indexOf("AC_Meeting_date") != -1) || (obj[i].name.indexOf("AC_conditions") != -1) || (obj[i].name.indexOf("AC_desision") != -1)) {
							if ((obj[i].value == "") || (obj[i].value == -1)) {
								alert("Complete the date, decisions and conditions before continuing.");
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
<br>
</td></tr></table>
