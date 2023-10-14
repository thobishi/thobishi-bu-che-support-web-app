<h3>Progress report of National Reviews</h3>
<?php	
	
	echo $this->element('filters/' . Settings::get('template'), $_POST);
	//$this->pr($_POST);

	$details = $this->getNRProgressDetails($_POST, Settings::get('template'),'list');
	$image = '<img src="images/'.Settings::get('imageOK').'">';
	
	// $SQL = "SELECT nr_programmes.id, hei_name FROM nr_programmes";
	// $RS = $this->db->query($SQL, array());
	// while($row = $RS->fetch()){
		// $linkForm = $this->scriptGetForm('nr_programmes', $row['id'], '_report_nr_progress_edit');
		// $link =  "<a href='" . $linkForm . "'>".$row['hei_name']."</a>";
		// echo  $link.'<br>';
	// }
	
	
	if(!empty($details)){
	
?>
	<div class="nr_progressReportDiv">
		<table class="table table-hover table-bordered table-striped nr_progress">
			<thead>
				<tr>
					<th>
						Institution name
					</th>
					<th>
						Programme name
					</th>
					<th>
						SER submission
					</th>
					<th>
						Screening
					</th>
					<th>
						Desktop Evaluation
					</th>	
					<th>
						Site visit
					</th>	
					<th>
						Review panel report
					</th>
					<th>
						Rec report
					</th>
					<th>
						Ref Committee
					</th>
					<th>
						NRC
					</th>					
				</tr>
			</thead>
			<tbody>
<?php			
				foreach($details as $info){
					// $recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$info['id'],"recommendation_completed");
					// $siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$info['id'],"siteVisit_completed");
					
					// $siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$info['id'],"siteVisitSubmittedByAdmin_ind");
					// $recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$info['id'],"recommendationSubmittedByAdmin_ind");					
					
					$heqcRecommReport = $this->createDocLink($info['heqc_recommendation_report_doc'], "HEQC Report");
					$linkForm = $this->scriptGetForm('nr_programmes', $info['id'], '_report_nr_progress_edit');
					$link =  "<a href='" . $linkForm . "'>".$info['hei_name']."</a>";
					echo '<tr>';
					echo '<td>' . $link . '</td>';
					echo '<td>' . $info['nr_programme_name'] . '</td>';
					echo '<td>'. ((!empty($info['serSubmissionArr'])) ? $image : '') . '</td>';					
					echo '<td>' . (($info['screening'] > '') ? $image : '') . '</td>';
					echo '<td>' . (($info['prelimAnalysis']['link_analyst_report'] > '') ? $image : '' ). '</td>';
					echo '<td>' .(($info['panelDetails']['site_visit_date'] != "Not assigned") ? $image : ''). '</td>';
					echo '<td>' .(($info['panelDetails']['link_panel_report'] > '' )  ? $image : ''). '</td>';	
					echo '<td>' .(($info['recommDetails']['link_recomm_report'] > '') ? $image : '').'</td>';
					echo '<td>'. ((!empty($info['rgMeetingDetails'])) ? $image : '') .'</td>';
					echo '<td>'. ((!empty($info['nrMeetingDetails'])) ? $image : '') .'</td>';
					echo '</tr>';
				}
?>
			</tbody>
		</table>
	</div>
<?php
	}else{
		echo 'No results found';
	}
?>
<script>

$(function(){
	$(".nr_progressReportDiv").clickNScroll({
		allowHiliting: true,
	});
});

</script>
