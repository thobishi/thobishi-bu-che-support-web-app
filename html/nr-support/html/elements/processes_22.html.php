<?php
	if(!empty($processes)){
		$processData = array();
		foreach($processes as $processCount => $process){
			$this->parseWorkFlowString($process['workflow_settings']);
			$programmeData = $this->db->getMultipleFieldsFromTable($this->dbTableInfoArray['nr_programmes']->dbTableName, $this->dbTableInfoArray['nr_programmes']->dbTableKeyField, $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID);
			if(!empty($programmeData)){
				foreach($programmeData as $programme){
					$admin = $this->getProgrammeAdministrator($programme['id'], $programme['hei_id']);					
					$adminEmail = (isset($admin[0])) ? $this->db->getValueFromTable('users', 'user_id', $admin[0], 'email') : 'No administrator found';
					$processData[$processCount]['institution'] = $programme['hei_name'];
					$processData[$processCount]['id'] = $programme['id'];
					$processData[$processCount]['nr_national_review_id'] = $programme['nr_national_review_id'];
					
					$processData[$processCount]['action'] = '<a class="btn" href="?ID=' . $process["active_processes_id"] . '">Continue</a>';
					
					$recommendationReportLink = $this->createDocLink($programme['recommendation_report_doc'], 'Report');
					$recommendationReport = (!empty($recommendationReportLink)) ? $recommendationReportLink :  "Not uploaded";
					$processData[$processCount]['recommendation_report_doc'] = $recommendationReport;
					
					$processData[$processCount]['recommendation_report_due_date'] = ($programme['recommendation_report_due_date'] == "1970-01-01") ? "Not assigned" : $programme['recommendation_report_due_date'];
					
					$recommendation_start_date = $programme['recommendation_start_date'];
					$recommendation_end_date = $programme['recommendation_end_date'];
					$processData[$processCount]['recommendation_accessDate'] = ($recommendation_start_date == "1970-01-01" || $recommendation_end_date == "1970-01-01" ) ? "Not assigned" : $recommendation_start_date . " to " . $recommendation_end_date;
																			
					$recommendationValue = $this->db->getValueFromTable('users', 'user_id', $programme['recommendation_user_ref'], "CONCAT(name, ' ', surname, ' (', email , ')')");				
					$recommendationUsr = (!empty($recommendationValue)) ? $recommendationValue :"Not assigned" ;
					$processData[$processCount]['recommendation_user_ref'] = $recommendationUsr;
				}
			}
		}
	}
?>

<h3><?php echo $processHeading; ?></h3>
<table class="table table-hover table-bordered table-striped manage_analyst">
	<thead>
		<tr>
			<th>
				 Action
			</th>
			<th>
				Institution
			</th>
			<th>
				Recommendation
			</th>
			<th>
				Access dates
			</th>
			<th>
				Due date
			</th>
			<th>
				Recommendation report
			</th>		

		</tr>
	</thead>
	<tbody>
		<?php
			if(!empty($processData)){
				foreach($processData as $processInfo){
					$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"recommendation_completed");
					$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"recommendationSubmittedByAdmin_ind");
					
					$recommDoc = ($recommendationCompleted == '1' || $recommendationSubmittedByAdmin_ind == '1')  ?  $processInfo['recommendation_report_doc'] : ' ';					
					echo '<tr>';
					echo '<td>' . $processInfo['action'] . '</td>';					
					echo '<td>' . $processInfo['institution'] .' ('. $processInfo['nr_national_review_id'] . ') ' . '</td>';
					echo '<td>' . $processInfo['recommendation_user_ref'] . '</td>';
					echo '<td>' . $processInfo['recommendation_accessDate']. '</td>';
					echo '<td>' . $processInfo['recommendation_report_due_date'] . '</td>';
					echo '<td>' . $recommDoc  . '</td>';					
					echo '</tr>';
				}
			}
			else{
				echo '<tr><td colspan="5">There are currently no submissions.</td></tr>';
			}
		?>
	</tbody>
</table>