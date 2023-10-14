<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	Please select a AC member to manage:<br><br>
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

$SQL = "SELECT * FROM AC_Members WHERE ac_mem_active=1 ORDER BY ac_mem_surname,ac_mem_name";
$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setUser(\"".$row["ac_mem_id"]."\");moveto(\"next\");'>".$row["ac_mem_surname"]." ".$row["ac_mem_name"]."</a><br>";

	}

}
?>
<br><br>
<a href='javascript:setUser("NEW");moveto("next");'>[Add new AC Member]</a><br>
<script>
function setUser(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='AC_Members|'+val;
}
</script>


	</table>
</td></tr></table>
