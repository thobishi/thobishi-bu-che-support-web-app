<?php
	$currentUserID = Settings::get('currentUserID');
	$recommendationDataPanel = $this->getRecommendationData('nr_programmes', 'recommendation_start_date <= CURDATE() AND recommendation_end_date >= CURDATE() AND recommendation_completed = 0', 'recommendation_user_ref', $currentUserID);
	echo $this->element('accordian_top', array('accHeader' => $processHeading, 'collapse' => 'reports'));

?>

	<?php
		if(!empty($processes)){
			echo '<table class="table table-hover table-bordered table-striped">';
			echo '<tbody>';			
			foreach($processes as $process){
				if(in_array($process['processes_id'], $reportGroupProccess)){
					if($process['processes_id'] == '24' && empty($recommendationDataPanel)){				
						echo '<tr><td>No Recommendations report available</td></tr>';
					}else{
						echo '<tr><td><a href="javascript:goto(' . $process['processes_id'] . ');">' . $process['processes_desc'] . '</td></tr>';
					}
					
				}
			}
			echo '</tbody>';
			echo '</table>';
		}else{
			echo '<table class="table table-hover table-bordered table-striped">';
			echo '<tbody>';		
			echo '<tr><td>There are no reports</td></tr>';
			echo '</tbody>';
			echo '</table>';			
		}
	?>

<?php
	echo $this->element('accordian_bottom', array());
?>