<?php

	if(!empty($details)){
?>

		<div class="nr_progressReportDiv">
<?php
		foreach ($details as $detail){
			echo 'Institution: <strong>'. $detail['hei_name'] . '</strong><br>';		
			echo 'Programme: '. $detail['nr_programme_name'] . '<br>';
			echo 'Code: <i>'. $detail['hei_code'] . '</i> HEQSF: <i>' . $detail['heqsf_reference_no'] . '</i><br><br>';
			if(!empty($detail['nrMeetingDetails'])){			
?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="4">
								National Review Committee meeting
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php
					foreach($detail['nrMeetingDetails'] as $nrMeeting){
						echo '<tr>';
							echo '<td>' ;
								echo '<strong>Report:</strong> ';
								echo ($this->createDocLink($nrMeeting['heqc_nrc_report_doc'], 'National Review Committee report') > '') ? $this->createDocLink($nrMeeting['heqc_nrc_report_doc'], 'National Review Committee report') : "Not uploaded" ;
							echo '</td>';
							echo '<td>'. '<strong>Date:</strong> '. $nrMeeting['nr_meeting_start_date']. '</td>';
							echo '<td>';
									if (!empty($nrMeeting['assigned_memberArr'])){
										echo '<strong>Assigned:</strong> ';
										foreach($nrMeeting['assigned_memberArr'] as $assignedMember){
											echo '<ul>';
											echo  (!empty($assignedMember['userDetails'])) ? "<li>" . $assignedMember['userDetails'] ."</li>" : '';
											echo '</ul>';
										}
									}else{
										echo '<strong>Assigned:</strong> none';
									}
							echo '</td>';
							echo '<td>' ;
								echo ($nrMeeting['nrc_access_start_date'] > "" && $nrMeeting['nrc_access_end_date'] > "") ? ('<strong>Access:</strong> '.$nrMeeting['nrc_access_start_date'] . ' to ' . $nrMeeting['nrc_access_end_date']) : 'No access dates assigned';
							echo '</td>';							
						echo '</tr>';
					}
				echo '</tbody>';
				echo '</table>';
			}
			
			if(!empty($detail['rgMeetingDetails'])){			
?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="4">
								Reference Committee meeting
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
					foreach($detail['rgMeetingDetails'] as $rgMeeting){
						echo '<tr>';
							echo '<td>' ;
								echo '<strong>Report:</strong> ';
								echo ($this->createDocLink($rgMeeting['heqc_recommendation_report_doc'], 'Reference Committee report') > '') ? $this->createDocLink($rgMeeting['heqc_recommendation_report_doc'], 'Reference Committee report') : "Not uploaded" ;
							echo '</td>';
							echo '<td>'. '<strong>Date:</strong> '. $rgMeeting['rg_meeting_start_date']. '</td>';
							echo '<td>';
									if (!empty($rgMeeting['assigned_memberArr'])){
										echo '<strong>Assigned:</strong> ';
										foreach($rgMeeting['assigned_memberArr'] as $assignedMember){
											echo '<ul>';
											echo  (!empty($assignedMember['userDetails'])) ? "<li>" . $assignedMember['userDetails'] ."</li>" : '';
											echo '</ul>';
										}
									}else{
										echo '<strong>Assigned:</strong> none';
									}
							echo '</td>';
							echo '<td>' ;
								echo ($rgMeeting['rgc_access_start_date'] > "" && $rgMeeting['rgc_access_end_date'] > "") ? ('<strong>Access:</strong> '.$rgMeeting['rgc_access_start_date'] . ' to ' . $rgMeeting['rgc_access_end_date']) : 'No access dates assigned';
							echo '</td>';							
						echo '</tr>';
					}
				echo '</tbody>';
				echo '</table>';
			}		
		
			if($detail['recommDetails']['link_recomm_report'] > "" && ($recommendationCompleted == '1' || $recommendationSubmittedByAdmin_ind == '1' ) ){

?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="4">
								Recommendation writers
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
					echo '<tr>';
						echo '<td>' . '<strong>Report:</strong> '. $detail['recommDetails'] ['link_recomm_report']. '</td>';
						echo '<td>' . '<strong>Assigned:</strong> '.$detail['recommDetails'] ['recommWriter']. '</td>';
						echo '<td>' . '<strong>Due date:</strong> '.$detail['recommDetails'] ['due-date']. '</td>';
						echo '<td>' . '<strong>Access:</strong> '.$detail['recommDetails'] ['accessDates']. '</td>';
					echo '</tr>';

				echo '</tbody>';
				echo '</table>';
			}		
		
			if(($detail['panelFinding']['panel_report']) > "" && ($siteVisit_completed == '1' || $siteVisitSubmittedByAdmin_ind == '1' )){			
?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="3">
								Review panel's findings
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
					echo '<tr>';
						echo '<td>' . '<strong>Report:</strong> '.$detail['panelFinding'] ['panel_report']. '</td>';
						echo '<td>' . '<strong>SER and Panel criteria compared:</strong> '.$detail['panelFinding'] ['comparisonLink']. '</td>';
						echo '<td>';
								
								if (!empty($detail['panelFinding'] ['additionalDocArr'])){
									echo '<strong>Additional docs:</strong> ';
									foreach($detail['panelFinding'] ['additionalDocArr'] as $additionalDoc){
										echo '<ul>';
										echo  (!empty($additionalDoc['docLink'])) ? "<li>" . $additionalDoc['docLink'] ."</li>" : '';
										echo '</ul>';
									}
								}else{
									echo '<strong>Additional docs:</strong> none';
								}
						echo '</td>';
					echo '</tr>';

				echo '</tbody>';
				echo '</table>';
			}			
		
			if($detail['siteVisitDetails']['site_visit_date'] != "Not assigned"){

?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="3">
								Site visit
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
					echo '<tr>';
						echo '<td>' . '<strong>Date:</strong> '. $detail['siteVisitDetails'] ['site_visit_date']. '</td>';
						echo '<td>' . '<strong>Assigned:</strong> '.'<ul>' .$detail['siteVisitDetails'] ['members'].'</ul>'. '</td>';
						echo '<td>' . '<strong>Access:</strong> '.$detail['siteVisitDetails'] ['accessDates']. '</td>';
					echo '</tr>';

				echo '</tbody>';
				echo '</table>';
			}		
		
			if($detail['prelimAnalysis']['link_analyst_report'] > ""){

?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="3">
								Pre-lim analysis
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
					echo '<tr>';
						echo '<td>' .'<strong>Report:</strong> '. $detail['prelimAnalysis'] ['link_analyst_report']. '</td>';
						echo '<td>' .'<strong>Assigned:</strong> '. $detail['prelimAnalysis'] ['analyst']. '</td>';
						echo '<td>' .'<strong>Access:</strong> '. $detail['prelimAnalysis'] ['accessDates']. '</td>';
					echo '</tr>';

			echo '</tbody>';
			echo '</table>';
			}		

		
			if(!empty($detail['screening'])){

?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="3">
								Screening
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
				foreach($detail['screening'] as $screening){
					echo '<tr>';
						echo '<td>' .'<strong>Report:</strong> '. $screening ['link_report']. '</td>';
						echo '<td>' .'<strong>Assigned:</strong> '. $screening ['screener']. '</td>';
						echo '<td>' .'<strong>Date:</strong> '. $screening ['date_screening_signed']. '</td>';
					echo '</tr>';
				}
			echo '</tbody>';
			echo '</table>';
			}		
		
		
		
			if(!empty($detail['serSubmissionArr'])){

?>
				<table class="table table-hover table-bordered table-striped nr_progress">
					<thead>
						<tr>
							<th colspan="4">
								SER submission
							</th>	
						</tr>					
					</thead>
					<tbody>
<?php			
		
				echo '<tr>';
					echo '<td>' . '<strong>Report:</strong> '.$detail['serSubmissionArr'] ['ser']. '</td>';
					echo '<td>' . '<strong>Sign off:</strong> '.$detail['serSubmissionArr'] ['sign_off']. '</td>';
					echo '<td>' .'<strong>Data:</strong> '. $detail['serSubmissionArr'] ['data_table']. '</td>';
					echo '<td>' . '<strong>Date:</strong> '.$detail['serSubmissionArr'] ['date_submitted']. '</td>';
				echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			}
		}
		echo '</div>';
	}