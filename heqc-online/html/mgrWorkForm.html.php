<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1">
<br>
<span class="specialb">
WORK FLOW
</span>
<br>
</td></tr>
<tr><td>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2"><tr>
<td class="oncolourb" align="center">User</td>
<td class="oncolourb" align="center">Process</td>
<td class="oncolourb" align="center">Reference Number</td>
<td class="oncolourb" align="center">Last Update</td>
<td class="oncolourb" align="center">Activation Date</td>
<td class="oncolourb" align="center">Due Date</td>
<td class="oncolourb" align="center">Expiry Date</td>
<td class="oncolourb" align="center">Options</td>
</tr>
<?php 
	$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status=0 ORDER BY last_updated";
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_array ($rs)) {
?>
<tr>
	<td valign="top"><?php echo $row["surname"]?>, <?php echo $row["name"]?></td>
	<td valign="top"><?php echo $this->workflowDescription($row["active_processes_id"],$row["processes_id"])?></td>
	<td valign="top">
<?php 	$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
	$flag = true;
	foreach ($arr AS $k=>$v) {
		if ($k == "Institutions_application") {
			$flag = false;
			echo $this->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, "CHE_reference_code");
			break;
		}
	}
	if ($flag) {
		echo "&nbsp;";
	}
?></td>
	<td valign="top"><?php echo $row["last_updated"]?></td>
	<td valign="top" nowrap align="center"><?php echo (($row["active_date"] != "1970-01-01")?($row["active_date"]):("-"))?></td>
	<td valign="top" nowrap align="center"><?php echo (($row["due_date"] != "1970-01-01")?($row["due_date"]):("-"))?></td>
	<td valign="top" nowrap align="center"><?php echo (($row["expiry_date"] != "1970-01-01")?($row["expiry_date"]):("-"))?></td>
	<td valign="top">
	<nobr>[<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='active_processes|<?php echo $row["active_processes_id"]?>';moveto(247);">Change User</a></nobr>]
	<nobr>[<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='process_comments|NEW';document.defaultFrm.CMT_ID.value='<?php echo $row["active_processes_id"]?>';moveto(254);">View/Add Comment</a>]</nobr>
	</td>
	
</tr>
<?php 
		}
	}else{
		echo '<tr><td colspan="4">There are currently no active processes</td></tr>';
	}
?>
</table>
<br><br>
</td></tr></table>
