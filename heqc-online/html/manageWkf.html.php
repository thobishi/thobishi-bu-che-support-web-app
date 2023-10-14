<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	Please select a process to manage:<br><br>
<?php 
$this->formFields["proc_nr"]->fieldValue = $_POST["proc_nr"];
$this->showField("proc_nr");
$this->showField("wkf_name");

$SQL = "SELECT * FROM work_flows WHERE processes_ref=? ORDER BY sec_no";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $_POST["proc_nr"]);
$sm->execute();
$rs = $sm->get_result();


//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setProc(\"".$row["work_flows_id"]."\");moveto(\"next\");'>".$row["template"]." - <em>(<a href='javascript:setWkfRef(\"".$row["template"]."\");goto(63);'>Edit Fields</a>)</em><br>";
	
	}

}
?>
<br><br>
<a href='javascript:setProc("NEW");moveto("next");'>[Add new workflow]</a><br>
<script>
function setProc(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='work_flows|'+val;
}

function setWkfRef (val) {
	document.defaultFrm.wkf_name.value = val;
}

</script>


	</table>
</td></tr></table>
