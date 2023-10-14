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
					
					$chairReportLink = $this->createDocLink($programme['chair_report_doc'], 'Report');
					$chairReport = (!empty($chairReportLink)) ? $chairReportLink :  "Not uploaded";
					$processData[$processCount]['chair_report_doc'] = $chairReport;
					$processData[$processCount]['id'] = $programme['id'];
					$processData[$processCount]['chair_report_due_date'] = ($programme['chair_report_due_date'] == "1970-01-01") ? "Not assigned" : $programme['chair_report_due_date'];
					
					$panel_start_date = $programme['panel_start_date'];
					$panel_end_date = $programme['panel_end_date'];
					$processData[$processCount]['panel_accessDate'] = ($panel_start_date == "1970-01-01" || $panel_end_date == "1970-01-01" ) ? "Not assigned" : $panel_start_date . " to " . $panel_end_date;
					
					$pannelMenberVal = $this->getPanelMembers($programme['id'], "list");					
					$pannelMenber = ($pannelMenberVal > "") ? $pannelMenberVal : "Not assigned";
					$processData[$processCount]['pannel_members'] = $pannelMenber;
					
					$analystReportLink = $this->createDocLink($programme['analyst_report_doc'], 'Report');
					$analystReport = (!empty($analystReportLink)) ? $analystReportLink : "Not uploaded";
					$processData[$processCount]['analyst_report_doc'] = $analystReport;
					
					$analyst_start_date = $programme['analyst_start_date'];				
					$analyst_end_date = $programme['analyst_end_date'];
					$processData[$processCount]['analyst_accessDate'] = ($analyst_start_date == "1970-01-01" || $analyst_end_date == "1970-01-01" ) ?  "Not assigned" : $analyst_start_date." to ".$analyst_end_date;
					
					$prelim_analystValue = $this->db->getValueFromTable('users', 'user_id', $programme['analyst_user_ref'], "CONCAT(name, ' ', surname, ' (', email , ')')");				
					$prelim_analyst = (!empty($prelim_analystValue)) ? $prelim_analystValue :"Not assigned" ;
					$processData[$processCount]['prelim_analyst'] = $prelim_analyst;
					
					$additionalDocArr = $this->getPrelimAdditionalInfo($programme['id']);
					$processData[$processCount]['additionalDoc_list'] = $additionalDocArr;
					
				}
			}
		}
	}
?>

<h3><?php echo $processHeading; ?></h3>
<table class="table table-hover table-bordered table-striped manage_analyst">
	<thead>
		<tr>
			<th colspan="2">
			</th>
			<th colspan="5">
			Panel details
			</th>
			<th colspan="3">
			Preliminary Analysis
			</th>
		</tr>
		<tr>
			<th>
				 Action
			</th>
			<th>
				Institution
			</th>		
			<th>
				Panel members
			</th>
			<th>
				Access dates
			</th>
			<th>
				Chair report
			</th>
			<th>
				Chair report due date
			</th>
			<th>
				Additional documents
			</th>			
			<th>
				Prelim analyst
			</th>
			<th>
				Access dates
			</th>
			<th>
				Prelim analyst report
			</th>
			


		</tr>
	</thead>
	<tbody>
		<?php
			if(!empty($processData)){
				foreach($processData as $processInfo){
				
					$siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"siteVisit_completed");
					$siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"siteVisitSubmittedByAdmin_ind");
					
					$analystReportSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"analystReportSubmittedByAdmin_ind");
					$prelimAnalysis_completed = $this->db->getValueFromTable("nr_programmes","id",$processInfo['id'],"prelimAnalysis_completed");
					
					$prelimAnalyst = (isset($processInfo['prelim_analyst'])) ? $processInfo['prelim_analyst'] : "Not assigned";
					$analystAccessDate = (isset($processInfo['analyst_accessDate'])) ? $processInfo['analyst_accessDate'] : "Not assigned";
					$chair_report_due_date = (isset($processInfo['chair_report_due_date'])) ? $processInfo['chair_report_due_date'] : "Not assigned";
					$analystReport_doc = (isset($processInfo['analyst_report_doc']) && ($prelimAnalysis_completed == '1' || $analystReportSubmittedByAdmin_ind == '1') ) ? $processInfo['analyst_report_doc'] : "Not uploaded";
					$pannelMembers = (isset($processInfo['pannel_members'])) ? $processInfo['pannel_members'] : "Not assigned";
					$panelAccessDate = (isset($processInfo['panel_accessDate'])) ? $processInfo['panel_accessDate'] : "Not assigned";
					$chairReport_doc = (isset($processInfo['chair_report_doc']) && ($siteVisit_completed == '1' || $siteVisitSubmittedByAdmin_ind == '1') ) ?  $processInfo['chair_report_doc'] : "Not uploaded";
					$countAdditionalDoc = count($processInfo['additionalDoc_list']) -1;
					$doc ="";

					echo '<tr>';
					echo '<td>' . $processInfo['action'] . '</td>';
					echo '<td>' . $processInfo['institution'] .' ('. $processInfo['nr_national_review_id'] . ') ' . '</td>';					
					echo '<td>' . $pannelMembers . '</td>';
					echo '<td>' . $panelAccessDate . '</td>';
					echo '<td>' . $chairReport_doc . '</td>';
					echo '<td>' . $chair_report_due_date . '</td>';
					echo '<td>';
					
					foreach($processInfo['additionalDoc_list'] as $index => $additionalDoc_list){
						$doc .= (!empty($additionalDoc_list['docLink'])) ?  $additionalDoc_list['docLink']  : '';
						$doc .= (!empty($additionalDoc_list['docLink']) && $index < $countAdditionalDoc ) ? " | " :'';
					}
					echo $doc;
					echo '</td>' ;					
					
					echo '<td>' . $prelimAnalyst. '</td>';
					echo '<td>' . $analystAccessDate . '</td>';
					echo '<td>' . $analystReport_doc . '</td>';					
					echo '</tr>';
				}
			}
			else{
				echo '<tr><td colspan="5">There are currently no submissions.</td></tr>';
			}
		?>
	</tbody>
</table>