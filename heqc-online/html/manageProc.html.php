<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<?php $this->showField("proc_nr");?>
	Please select a process to manage:<br><br>
<?php 
$SQL = "SELECT * FROM processes WHERE 1 ORDER BY processes_desc";
$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setProc(\"".$row["processes_id"]."\");moveto(\"next\");'>".$row["processes_desc"]."</a> - <em>(<a href='javascript:setProcRef(".$row["processes_id"].");goto(62);'>Edit Workflows</a>)</em><br>";
	
	}

}
?>
<br><br>
<a href='javascript:setProc("NEW");moveto("next");'>[Add new process]</a><br>
<script>
function setProc(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='processes|'+val;
}

function setProcRef (val) {
	document.defaultFrm.proc_nr.value = val;
}
</script>


	</table>
</td></tr></table>
