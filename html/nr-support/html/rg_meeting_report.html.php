<?php

	echo $this->element('filters/' . Settings::get('template'), $_POST);
	$details = $this->getMeetingDetails("","", "","", "" ,$_POST, Settings::get('template'),"rg_meeting");
	// $this->pr($details);
	// if(!empty($recommendationDataPanel)){
		// echo $this->element('filters/' . Settings::get('template'), $_POST);
		// $details = $this->getSERRecommendationDetails($_POST, Settings::get('template'));
		if(!empty($details)){
?>
		<div class="nr_progressDiv">

<?php			
					
					foreach($details as $info){
					$meetingDate = isset($info['rg_meeting_start_date']) && isset($info['rg_meeting_end_date']) ? $info['rg_meeting_start_date']. " to " . $info['rg_meeting_end_date'] : '';
?>
			
			
			<table class="table table-hover table-bordered table-striped nr_progress">
				<thead>
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "7">
							<strong>National Review programme: </strong> <?php echo $this->listActiveNR_ids("name");?>
						</th>
					</tr>				
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "7">
							<strong>Meeting date: </strong><?php echo $meetingDate;?>
						</th>
					</tr>
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "7">
							<strong>RC members: </strong><?php echo  $info['nrMeetingsMem'];?>
						</th>
					</tr>	
					<tr>						
						<th>
							Institution name
						</th>
						<th>
							Assigned RC member
						</th>						
						<th>
							Recommendation report
						</th>	
						<th>
							Review panel report
						</th>	
						<th>
							SER Submission 
						</th>
						<th>
							Criteria evaluation comparison
						</th>
						<th>
							Reference Committee report
						</th>
					</tr>
				</thead>
				<tbody>
<?php
						if(!empty($info['nrMeetingsProg'])){
							$totalProg = count($info['nrMeetingsProg']);
								foreach($info['nrMeetingsProg'] as $prog){
									// $recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$prog['id'],"recommendation_completed");
									$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$prog['id'],"recommendation_completed");
									$siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$prog['id'],"siteVisit_completed");
									echo '<tr>';
									// echo '<td  rowspan="'.$totalProg.'">' ;
									// echo  $info['nrMeetingsMem'];
									// echo '</td>';						
									echo '<td>' ;							
											echo  $prog['hei_name'] . ' ('.$prog['nr_national_review_id'].')';
									echo '</td>';
									echo '<td>';
										$assignedUserAdrr = $this->getAssignedUserByProg($prog['id']);
										if(!empty($assignedUserAdrr)){
											foreach($assignedUserAdrr as $assignedUser){
													// echo '<ul>';
													echo (!empty($assignedUser['userDetails'])) ?  $assignedUser['userDetails']   : 'Not assigned';
													// echo '</ul>';
											}
										}
										
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['recomendationReport']) && $recommendationCompleted == 1) ? $prog['docsRelated']['recomendationReport']  : 'Not uploaded';			
									echo '</td>';
									echo '<td>' ;
											echo (!empty($prog['docsRelated']['panelReport']) && $siteVisit_completed == 1) ?  $prog['docsRelated']['panelReport']  : 'Not uploaded';
								
									echo '</td>';						
									echo '<td>' ;
											echo (!empty($prog['docsRelated']['serSubmission'])) ?  $prog['docsRelated']['serSubmission'] : '';
								
									echo '</td>';

									echo '<td>' ;
												echo (!empty($prog['docsRelated']['criteriaComparison'])) ?  $prog['docsRelated']['criteriaComparison']  : 'Not uploaded';
							
									echo '</td>';	

									echo '<td>';
												echo (!empty($prog['docsRelated']['heqcRecomendationReport'])) ?  $prog['docsRelated']['heqcRecomendationReport'] : 'Not uploaded';
									echo '</td>' ;	
									
									echo '</tr>';
							}
						}	
					}
?>
				</tbody>
			</table>
		</div>
<?php
		}else{
			echo 'No meetings scheduled';
			// $this->pr($_POST['rg_meeting_start_date'] != 0 || $_POST['rg_meeting_start_date'] != 0);
			if((isset($_POST['rg_meeting_start_date']) && $_POST['rg_meeting_start_date'] == 0) && (isset($_POST['hei_id']) && $_POST['hei_id'] == 0)){
				echo '<script>';
				echo "$('#generalActions').hide();";
				echo '</script>';
			}
		}
	// }else{
		// echo "No results found";

	// }
?>