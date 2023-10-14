<table width="95%" border=0 align="center" cellpadding="3" cellspacing="3">
<tr>
<td class="oncolourb" align="center">Process</td>
<td class="oncolourb" align="center">Reference</td>
<td class="oncolourb" align="center">Last Updated</td>
</tr>
<?php
	if (count($processes) > 0) {
		foreach ($processes as $row) {
			$desc = $this->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
			$dueStyle = "";
			if ( ($row["due_date"]!="1970-01-01") && ($row["due_date"]<=date("Y-m-d")) ) {
				$dueStyle = "CLASS=due";
			}
			if ( ($row["expiry_date"]!="1970-01-01") && ($row["expiry_date"]<=date("Y-m-d")) ) {
				$dueStyle = "CLASS=expiry";
			}
?>
<tr class='onblue'>
<td><a <?php echo $dueStyle?> href="?ID=<?php echo $row["active_processes_id"]?>"><?php echo $desc?></a></td>
<td align="center">
<?php	
	$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
//print_r($this->parseOtherWorkFlowProcess($row["active_processes_id"]));
		$flag = true;
		foreach ($arr AS $k=>$v)
		{
		$HEQCref = "";
//Reference number only displayed if it is an application
			if ($k == "Institutions_application")
			{
				//$flag = false;
				$HEQCref = $this->db->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, "CHE_reference_code");
			}

/*				if (($row["processes_ref"] == 5))
				{
					$descFieldNameArr = $this->db->getValueFromTable ("processes", "processes_id", $row["processes_ref"], "desc_fields");
					$descFieldName  = explode ("|", $descFieldNameArr);
					$HEQCref .= " (".$this->table_field_info($row["active_processes_id"], $descFieldName[0]).")";
				}
*/
/*
	Edited: Rebecca & Robin 14/11/2006______________________________________
	The if statement below displays the relevant value on the active
	processes page (in Reference column). It traverses
	$descFieldName array until a value is found, which is displayed.
*/
				if (($HEQCref == ""))			//if NO che_reference exists, do...
				{
					$descFieldNameArr = $this->db->getValueFromTable ("processes", "processes_id", $row["processes_ref"], "desc_fields");
					$descFieldName  = explode ("|", $descFieldNameArr);
					foreach ($descFieldName as $value)
					{
					   $HEQCref = $this->table_field_info($row["active_processes_id"], $value);
					   if ($HEQCref != "")
					   {
					   	$flag = false;
					   	break;
					   }
					}
				}
				echo $HEQCref;
				break;
		}
		if ($flag) {
			echo "&nbsp;";
		}
?></td>
<!-- BUG: <td><a href="?goto=6&AP=<?php echo $row["active_processes_id"]?>">View</a></td> -->
<td align="center"><?php echo $row["last_updated"]?></td>
</tr>
<?php 
		}
	} else {
		echo '<tr class="onblue"><td colspan="3" align=center>There are currently no active processes</td></tr>';
	}
?>
</table>