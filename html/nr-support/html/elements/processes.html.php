<table class="table table-hover table-bordered table-striped ">
	<thead>
		<tr>
		  <th>National Review</th>
		  <th>Submission date</th>
		  <th>Action</th>	  
		</tr>
	</thead>
	<tbody>
<?php
	if (count($processes)) {
		foreach ($processes as $row) {
			$desc = $this->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
			$dueStyle = "";
			$due_date= "";
			$expiry_date = "";
			$action = '<a class="btn" href="?ID='.$row["active_processes_id"].'">Continue</a>';
			$viewmode = 0;
			if ($row["due_date"]!="1970-01-01") {
				$due_date = date('j F Y',strtotime($row["due_date"]));
			}else{
				$due_date = "none";
			}
			if ($row["expiry_date"]!="1970-01-01") {
				$expiry_date = date('j F Y',strtotime($row["expiry_date"]));
			}else{
				$expiry_date = "none";				
			}
			if($row["status"]== '1'){
				$viewmode = 2;
			}
?>
<tr>
	<td>
	<?php	
		$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);		
		$flag = true;
		foreach ($arr AS $k=>$v)
		{
			$HEQCref = "";
//Reference number only displayed if it is an application
			if ($k == "Institutions_application")
			{
				$HEQCref = $this->db->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, "CHE_reference_code");
			}
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
?>
	</td>
	<td><?php echo $expiry_date; ?></td>
	<td><?php echo $action; ?></td>
</tr>
<?php
		}
	} else {
		echo '<tr><td colspan="4" align=center>There are currently no active processes</td></tr>';
	}
?>
	</tbody>
</table>