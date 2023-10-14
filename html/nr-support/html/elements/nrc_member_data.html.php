<h3>NRC meetings to attend</h3>
<?php
	if(!empty($meetingDetails)){
		$currentUserId = Settings::get('currentUserID');
		$progAssignArr = $this->getMeetingProgAssigned($currentUserId, "nr_meeting_programmes_assignment");
		foreach($meetingDetails as $info){			
			$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$info['id'],"recommendation_completed");
			$meetingDate = isset($info['nr_meeting_start_date']) && isset($info['nr_meeting_end_date']) ? $info['nr_meeting_start_date']. " to " . $info['nr_meeting_end_date'] : '';
			$nr_national_review_id = isset($info['nr_national_review_id']) ? $info['nr_national_review_id'] : '';
			$progName = $this->db->getValueFromTable("nr_national_reviews","id",$nr_national_review_id,"programme_to_review");
?>
		<div class="nr_progressDiv">
			<table class="table table-hover table-bordered table-striped nr_progress">
				<thead>
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "2">
							<strong>Meeting date: </strong><?php echo $meetingDate;?>
						</th>
					</tr>					
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "2">
							<strong>National Review programme: </strong><?php echo $progName;?>
						</th>
					</tr>
					<tr>
						<th class = "rg_table-title" colspan="5">
							Assigned Programmes
						</th>
					</tr>					
					<tr>
						<th>
							Institution list
						</th>
						<!--<th>
							Recommendation writer report
						</th>						
						<th>
							Review Panel report
						</th>
						<th>
							Pre-lim report
						</th>						
						<th>
							SER Submission
						</th>		
						<th>
							Criteria evaluation comparison
						</th>-->
					</tr>
				</thead>
				<tbody>
<?php			
					
						
					
						foreach($info['nrMeetingsProg'] as $prog){
							if(in_array($prog['id'],$progAssignArr[$currentUserId]['progIdArr']))	{
								if(!empty($info['nrMeetingsProg'])){
									$progId = $prog['id'];
									$instName = $prog['hei_name'];										

									echo '<tr>';
									echo '<td>' ;
										echo "<a href= 'javascript:progressReportOfNR (\"" . $progId . "\", \"" . $instName . "\"); ' > " . $instName . " </a>";									
										
									echo '</td>';
									/*echo '<td>' ;
												echo (!empty($prog['docsRelated']['recomendationReport']) && $recommendationCompleted == '1') ? $prog['docsRelated']['recomendationReport'] : 'Not uploaded';													
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['panelReport'])) ? $prog['docsRelated']['panelReport'] : 'Not uploaded';					
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['prelimReport'])) ? $prog['docsRelated']['prelimReport'] : 'Not uploaded';					
									echo '</td>';							
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['serSubmission'])) ?  $prog['docsRelated']['serSubmission'] : '';
								
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['criteriaComparison'])) ? $prog['docsRelated']['criteriaComparison'] : 'Not uploaded';												
									echo '</td>';*/	
									
									echo '</tr>';
								}
							}
						}

				
?>
				</tbody>
			</table>
			
			<table class="table table-hover table-bordered table-striped nr_progress">
				<thead>					
					<tr>
						<th class = "rg_table-title" colspan="5">
							Additional Programmes
						</th>
					</tr>					
					<tr>
						<th>
							Institution list
						</th>
						<!--<th>
							Recommendation writer report
						</th>						
						<th>
							Review Panel report
						</th>
						<th>
							Pre-lim report
						</th>						
						<th>
							SER Submission
						</th>		
						<th>
							Criteria evaluation comparison
						</th>-->
					</tr>
				</thead>
				<tbody>
<?php			
					
						
					
						foreach($info['nrMeetingsProg'] as $prog){
							if(!in_array($prog['id'],$progAssignArr[$currentUserId]['progIdArr']))	{
								if(!empty($info['nrMeetingsProg'])){
									$progId = $prog['id'];
									$instName = $prog['hei_name'];										

									echo '<tr>';
									echo '<td>' ;
										echo "<a href= 'javascript:progressReportOfNR (\"" . $progId . "\", \"" . $instName . "\"); ' > " . $instName . " </a>";									
										
									echo '</td>';
									/*echo '<td>' ;
												echo (!empty($prog['docsRelated']['recomendationReport']) && $recommendationCompleted == '1') ? $prog['docsRelated']['recomendationReport'] : 'Not uploaded';													
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['panelReport'])) ? $prog['docsRelated']['panelReport'] : 'Not uploaded';					
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['prelimReport'])) ? $prog['docsRelated']['prelimReport'] : 'Not uploaded';					
									echo '</td>';							
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['serSubmission'])) ?  $prog['docsRelated']['serSubmission'] : '';
								
									echo '</td>';
									echo '<td>' ;
												echo (!empty($prog['docsRelated']['criteriaComparison'])) ? $prog['docsRelated']['criteriaComparison'] : 'Not uploaded';												
									echo '</td>';*/	
									
									echo '</tr>';
								}
							}
						}
				}
			
?>
				</tbody>
			</table>			
		</div>
<?php
	}
	else{
		echo 'No NRC meetings assigned to you at this time.';
	}
?>