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
					
					$processData[$processCount]['nr_national_review_id'] = $programme['nr_national_review_id'];
					
					$processData[$processCount]['action'] = '<a class="btn" href="?ID=' . $process["active_processes_id"] . '">Continue</a>';
					
					$recommendationReportLink = $this->createDocLink($programme['recommendation_report_doc'], 'Report');
					$recommendationReport = (!empty($recommendationReportLink)) ? $recommendationReportLink :  "Not uploaded";
					$processData[$processCount]['recommendation_report_doc'] = $recommendationReport;
										
					$heqcRecommendationReportLink = $this->createDocLink($programme['heqc_recommendation_report_doc'], 'HEQC Report');
					$heqcRecommendationReport = (!empty($heqcRecommendationReportLink)) ? $heqcRecommendationReportLink :  "Not uploaded";
					$processData[$processCount]['heqc_recommendation_report_doc'] = $heqcRecommendationReport;					
					
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
				HEQC Reference Committee report
			</th>			

		</tr>
	</thead>
	<tbody>
		<?php
			if(!empty($processData)){
				foreach($processData as $processInfo){

					echo '<tr>';
					echo '<td>' . $processInfo['action'] . '</td>';					
					echo '<td>' . $processInfo['institution'] .' ('. $processInfo['nr_national_review_id'] . ') ' . '</td>';
					echo '<td>' . $processInfo['heqc_recommendation_report_doc'] . '</td>';			
					echo '</tr>';
				}
			}
			else{
				echo '<tr><td colspan="5">There are currently no submissions.</td></tr>';
			}
		?>
	</tbody>
</table>