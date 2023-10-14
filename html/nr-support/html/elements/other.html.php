<h3><?php echo $processHeading; ?></h3>
<?php
	if(!empty($processes)){
?>
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<th>Process</th>
					<th>Reference</th>
					<th>Last Updated</th>
				</tr>
			</thead>
			<tbody>
<?php
			foreach($processes as $process){
				$desc = $this->workflowDescription($process["active_processes_id"], $process["processes_ref"]);
				echo '<tr>';
				echo '<td><a href="?ID=' . $process["active_processes_id"] . '">' . $desc . '</td>';
				$activeProcess = $this->parseOtherWorkFlowProcess($process["active_processes_id"]);
				$ref = "";
				foreach($activeProcess as $activeProcessKey => $activeProcessValue){
					$descFieldNameArr = $this->db->getValueFromTable ("processes", "processes_id", $process["processes_ref"], "desc_fields");
					$descFieldName  = explode ("|", $descFieldNameArr);
					foreach($descFieldName as $value){
						$ref = $this->table_field_info($process["active_processes_id"], $value);
						if($ref != ""){
							break;
						}
					}
					break;
				}
				echo '<td>' . $ref . '</td>';
				echo '<td>' . $process["last_updated"] . '</td>';
				echo '</tr>';
			}
?>
			</tbody>
		</table>
<?php
	}
?>