<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	Please select a process to manage:<br><br>
<?php 
$this->formFields["wkf_name"]->fieldValue = $_POST["wkf_name"];
$this->showField("wkf_name");

$SQL = "SELECT * FROM template_field WHERE template_name=? ORDER BY fieldName";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $_POST["wkf_name"]);
$sm->execute();
$rs = $sm->get_result();


//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setProc(\"".$row["template_field_id"]."\");moveto(\"next\");'>".$row["fieldName"]."</a><br>";
	
	}

}
?>
<br><br>
<a href='javascript:setProc("NEW");moveto("next");'>[Add new field]</a><br>
<script>
function setProc(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='template_field|'+val;
}
</script>


	</table>
</td></tr></table>
