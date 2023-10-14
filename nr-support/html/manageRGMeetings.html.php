<h3>Manage RC Meetings</h3>
<?php
	$detailsArr = $this->getMeetingDetails("", "", "", "","", array(), "", "rg_meeting");
	// $this->pr($detailsArr);
	if(!empty($detailsArr)){	
?>	

<div class="nr_progressDiv">
<?php
	foreach($detailsArr as $detail){
		$meetingDate = isset($detail['rg_meeting_start_date']) && isset($detail['rg_meeting_end_date']) ? $detail['rg_meeting_start_date']. " to " . $detail['rg_meeting_end_date'] : '';
		$nationalReviewId = isset($detail['nr_national_review_id']) ? $detail['nr_national_review_id'] : '';
		$nationalReviewName = ($nationalReviewId > '') ? $this->db->getValueFromTable("nr_national_reviews","id",$nationalReviewId,"programme_to_review") : '' ;
		
?>
	<table class="table table-hover table-bordered table-striped nr_progress">
			<thead>
				<tr class="rg_tableTh">
					<th class="rg_tableTh" colspan = "3">
						<strong>National Review programme: </strong> <?php echo $nationalReviewName;?>
					</th>
				</tr>
				<tr class="rg_tableTh">
					<th class="rg_tableTh" colspan = "3">
						<strong>Meeting date: </strong><?php echo $meetingDate;?>
					</th>
				</tr>			
				
				</tr>
				<tr class="rg_tableTh">
					<th class="rg_tableTh" colspan = "3">
						<strong>Access dates: </strong><?php echo $detail['rgc_access_start_date'] . ' to ' . $detail['rgc_access_end_date'];?>
					</th>
				</tr>
				<tr class="rg_tableTh">
					<th class="rg_tableTh" colspan = "3">
						<strong>Meeting minutes document: </strong><?php echo $this->createDocLink($detail['rgc_meeting_minutes_doc'], 'Meeting minutes'); ?>
					</th>
				</tr>								
				<tr class="rg_tableTh">
					<th class="rg_tableTh">
						<?php echo $detail['editLink']; ?>
					</th>
				</tr>
				<tr class="rg_tableTh">
					<th class="rg_tableTh">
						<a href= "javascript:goto(5);"><img src= "images/members.png" /></a>
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
						RC members
					</th>
				</tr>
			</thead>
			<tbody>
<?php			
		
			

			if (!empty($detail['nrMeetingsProg'])){
				$totalProg = count($detail['nrMeetingsProg']);
					$showMeetingMem = true;
					foreach($detail['nrMeetingsProg'] as $prog){
						$tdFlag =0;
						echo '<tr>';
							echo '<td>';
							 echo  $prog['prog_name'];
							 echo '</td>';
							echo '<td>';
								$assignedUserAdrr = $this->getAssignedUserByProg($prog['id']);
								if(!empty($assignedUserAdrr)){
									foreach($assignedUserAdrr as $assignedUser){										
											echo (!empty($assignedUser['userDetails'])) ?  $assignedUser['userDetails']."\n" : 'Not assigned';										
									}
								}
								
							echo '</td>';
					
						if ($showMeetingMem) {
							echo '<td rowspan ="' .$totalProg. '">';
								echo $detail['nrMeetingsMem'];			
							echo '</td>';
							$showMeetingMem = false;
						}
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
		echo "No meetings found CLick on add on the top left to add a new meeting";
	}
?>
