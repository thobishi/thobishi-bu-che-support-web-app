<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	Please select a institution to manage:<br><br>
<?php 
$SQL = "SELECT * FROM HEInstitution WHERE 1 ORDER BY HEI_code";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setIns(\"".$row["HEI_id"]."\");moveto(\"next\");'>".$row["HEI_name"]." - <em>(".$row["HEI_code"].")</em></a><br>";
	
	}

}
?>
<br><br>
<a href='javascript:setIns("NEW");moveto("next");'>[Add new institution]</a><br>
<script>
function setIns(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='HEInstitution|'+val;
}
</script>


	</table>
</td></tr></table>
