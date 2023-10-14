<h3>Reference Committee (RC) meetings to attend</h3>
<?php
	if(!empty($meetingDetails)){
?>
<strong>National Review programme: </strong> <?php echo $this->listActiveNR_ids("name");?><br>

<?php				
					// $this->pr($meetingDetails);
					$currentUserId = Settings::get('currentUserID');
					// $this->pr($this->getMeetingProgAssigned(Settings::get('currentUserID')));
					$progAssignArr = $this->getMeetingProgAssigned($currentUserId );
					foreach($meetingDetails as $info){
						$meetingDate = isset($info['rg_meeting_start_date']) && isset($info['rg_meeting_end_date']) ? $info['rg_meeting_start_date']. " to " . $info['rg_meeting_end_date'] : '';
						
					// <strong>Meeting date: </strong><?php echo $meetingDate
?>
		
		<div class="nr_progressDiv">
			<table class="table table-hover table-bordered table-striped nr_progress">							
				<thead>
					<tr class="rg_tableTh">
						<th class="rg_tableTh" colspan = "5">
							<strong>Meeting date: </strong><?php echo $meetingDate;?>
						</th>
					</tr>				
					<tr>
						<th class = "rg_table-title" colspan="5">
							Assigned Programmes
						</th>
					</tr>
					<tr>
						<th>
							Institution name
						</th>
						<th>
							Recommendation writer report
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
					</tr>
				</thead>
				<tbody>
<?php
											
					foreach($info['nrMeetingsProg'] as $prog){
						$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$prog['id'],"recommendation_completed");
						if(!empty($info['nrMeetingsProg'])){
							if(in_array($prog['id'],$progAssignArr[$currentUserId]['progIdArr'])){												
								echo '<tr>';
								echo '<td>' ;
									echo  $prog['hei_name'];
								echo '</td>';
								echo '<td>' ;
									echo (!empty($prog['docsRelated']['recomendationReport']) && $recommendationCompleted == '1') ? $prog['docsRelated']['recomendationReport'] : 'Not uploaded';							
								echo '</td>';
								echo '<td>' ;
										echo (!empty($prog['docsRelated']['panelReport'])) ? $prog['docsRelated']['panelReport']: 'Not uploaded';					
								echo '</td>';						
								echo '<td>' ;
									if(!empty($prog['docsRelated']['serSubmission'])){
										$submissionArr = explode ("|",$prog['docsRelated']['serSubmission'] );
										unset($submissionArr[1]);
										echo implode("|", $submissionArr);
									}else{
										echo '';
									}
		
								echo '</td>';
								echo '<td>' ;
									echo (!empty($prog['docsRelated']['criteriaComparison'])) ? $prog['docsRelated']['criteriaComparison'] : 'Not uploaded';				
								echo '</td>';								
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
						<th  class = "rg_table-title" colspan="5">
							Additional Programmes
						</th>
					</tr>				
					<tr>
						<th>
							Institution name
						</th>
						<th>
							Recommendation writer report
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
					</tr>
				</thead>
				<tbody>
<?php
											
						foreach($info['nrMeetingsProg'] as $prog){
							$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$prog['id'],"recommendation_completed");
							if(!empty($info['nrMeetingsProg'])){
								if(!in_array($prog['id'],$progAssignArr[$currentUserId]['progIdArr'])){												
									echo '<tr>';
									echo '<td>' ;
										echo  $prog['hei_name'];
									echo '</td>';
									echo '<td>' ;
										echo (!empty($prog['docsRelated']['recomendationReport']) && $recommendationCompleted == '1') ? $prog['docsRelated']['recomendationReport'] : 'Not uploaded';							
									echo '</td>';
									echo '<td>' ;
											echo (!empty($prog['docsRelated']['panelReport'])) ? $prog['docsRelated']['panelReport']: 'Not uploaded';					
									echo '</td>';						
									echo '<td>' ;
										if(!empty($prog['docsRelated']['serSubmission'])){
											$submissionArr = explode ("|",$prog['docsRelated']['serSubmission'] );
											unset($submissionArr[1]);
											echo implode("|", $submissionArr);
										}else{
											echo '';
										}		
									echo '</td>';
									echo '<td>' ;
										echo (!empty($prog['docsRelated']['criteriaComparison'])) ? $prog['docsRelated']['criteriaComparison'] : 'Not uploaded';				
									echo '</td>';								
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
		echo 'No RC meetings assigned to you at this time.';
	}
?>