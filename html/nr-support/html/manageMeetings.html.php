<h3>Manage Meetings</h3>
<?php
	$detailsArr = $this->getMeetingDetails();
	if(!empty($detailsArr)){
?>	
<div class="nr_progressDiv">
<?php
	foreach($detailsArr as $detail){
		$meetingDate = isset($detail['nr_meeting_start_date']) && isset($detail['nr_meeting_end_date']) ? $detail['nr_meeting_start_date']. " to " . $detail['nr_meeting_end_date'] : '';
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
				<tr class="rg_tableTh">
					<th class="rg_tableTh" colspan = "3">
						<strong>Access dates: </strong><?php echo $detail['nrc_access_start_date'] . ' to ' . $detail['nrc_access_end_date'];?>
					</th>
				</tr>				
				<tr>
					<th>
						Actions
					</th>					
					<th>
						Programmes
					</th>
					<th>
						members
					</th>
					<th>
						Meeting minutes document
					</th>					
				</tr>
			</thead>
			<tbody>
<?php			
			if (!empty($detail['nrMeetingsProg'])){
				$totalProg = count($detail['nrMeetingsProg']);
				$showMeetingMem = true;
				echo '<tr>';
				echo '<td>' . $detail['editLink'] . '</td>';
				
				if (!empty($detail['nrMeetingsProg'])){
					if ($showMeetingMem) {
						echo '<td rowspan ="' .$totalProg. '">';
							foreach($detail['nrMeetingsProg'] as $prog){
								echo '<ul>';
								 echo  "<li>" . $prog['prog_name'] ."</li>";
								echo '</ul>';
							}
						echo '</td>';
					}
				}
				
				if ($showMeetingMem) {
					echo '<td rowspan ="' .$totalProg. '">';
						echo $detail['nrMeetingsMem'];			
					echo '</td>';
					$showMeetingMem = false;
				}				
				echo '<td>' . $this->createDocLink($detail['nrc_meeting_minutes_doc'], 'Meeting minutes')  . '</td>';
							
				echo '</tr>';
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
