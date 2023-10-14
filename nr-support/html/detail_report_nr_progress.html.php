<?php
	echo $this->element('filters/' . Settings::get('template'), $_POST);
	$details = $this->getNRProgressDetails($_POST, Settings::get('template'),'list');
	// $this->pr($details);
	if(!empty($details)){
?>
	<div class="nr_progressReportDiv">
		<table class="table table-hover table-bordered table-striped nr_progress">
			<thead>
				<tr>
					<th rowspan="2">
						Institution code
					</th>
					<th rowspan="2">
						Institution name
					</th>
					<th rowspan="2">
						Programme name
					</th>
					<th rowspan="2">
						HEQSF number
					</th>
					<th rowspan="2">
						Active process and person
					</th>					
					<th rowspan="2" >
						SER submissions
					</th>	
					<th rowspan="2">
						Submission date
					</th>
					<th>
						Screening
					</th>
					<th colspan="3">
						Virtual Site Visit
					</th>
					<th colspan="6">
						Review panel details
					</th>
					<th colspan="4">
						Recommendation details
					</th>
					<th rowspan="2">
						Reference Committee report
					</th>
					<th rowspan="2">
						National Review Committee report
					</th>						
				</tr>
				<tr>
					<th>
						Screener, date, report
					</th>
					<th>
						Virtual Site Visit
					</th>
					<th>
						Access dates
					</th>
					<th>
						Report
					</th>
					<th>
						Site visit date
					</th>					
					<th rowspan="2">
						Panel members
					</th>
					<th>
						Access dates
					</th>
					<th>
						Report
					</th>
					<th>
						Criteria comparison
					</th>					
					<th>
						Additional documents
					</th>					
					<th rowspan="2">
						Recommendation writer
					</th>					
					<th>
						Access dates
					</th>
					<th>
						Due date
					</th>
					<th>
						Report
					</th>					
				</tr>
			</thead>
			<tbody>
<?php			

				foreach($details as $info){
					$heqcRecommReport = $this->createDocLink($info['heqc_recommendation_report_doc'], "Reference Committee Report");
					$heqc_nrc_report_doc = $this->createDocLink($info['heqc_nrc_report_doc'], "National Review Committee Report");
					echo '<tr>';
					echo '<td>' . ((isset($info['hei_code'])) ? ($info['hei_code']) : '') . '</td>';
					echo '<td>' . ((isset($info['hei_name'])) ? ($info['hei_name']) : '') . '</td>';
					echo '<td>' . ((isset($info['nr_programme_name'])) ? ($info['nr_programme_name']) : '') . '</td>';
					echo '<td>' . ((isset($info['heqsf_reference_no'])) ? ($info['heqsf_reference_no']) : '') . '</td>';
					echo '<td class = "large">' . ((isset($info['active_process_person'])) ? ($info['active_process_person']) : '') . '</td>';					
					echo '<td class = "large">';
						$submissionList = '';
						if(!empty($info['serSubmissionArr'])){
							$submissionList .= ($info['serSubmissionArr']['ser'] > "") ? $info['serSubmissionArr']['ser']." | " :'';
							$submissionList .= ($info['serSubmissionArr']['sign_off'] > "") ? $info['serSubmissionArr']['sign_off']." | " :'';
							$submissionList .= ($info['serSubmissionArr']['data_table'] > "") ? $info['serSubmissionArr']['data_table']:'';
						}
						echo $submissionList;
					echo '</td>';
					echo '<td>' . ((isset($info['date_submitted']) && $info['date_submitted'] != '1000-01-01') ? ($info['date_submitted']) : 'Not submitted') . '</td>';
					echo '<td class = "large">' . ((isset($info['screening'])) ? ($info['screening']) : '') . '</td>';
					echo '<td>' . ((isset($info['prelimAnalysis']['analyst'])) ? ($info['prelimAnalysis']['analyst']) : '') . '</td>';
					echo '<td class = "large">' . ((isset($info['prelimAnalysis']['accessDates'])) ? ($info['prelimAnalysis']['accessDates']) : '') . '</td>';
					echo '<td>' . ((isset($info['prelimAnalysis']['link_analyst_report'])) ? ($info['prelimAnalysis']['link_analyst_report']) : '') . '</td>';
					echo '<td>' .((isset($info['panelDetails']['site_visit_date'])) ? ($info['panelDetails']['site_visit_date']) : ''). '</td>';
					echo '<td>' .((isset($info['panelDetails']['members'])) ? ($info['panelDetails']['members']) : ''). '</td>';
					echo '<td class = "large">' .((isset($info['panelDetails']['accessDates'])) ? ($info['panelDetails']['accessDates']) : ''). '</td>';
					echo '<td>' .((isset($info['panelDetails']['link_panel_report'])) ? ($info['panelDetails']['link_panel_report']) : ''). '</td>';	
					echo '<td class = "large">' .((isset($info['comparisonLink'])) ? ($info['comparisonLink']) : ''). '</td>';
					echo '<td  class = "large">';
						$totalAdditionalDocs = count($info['additionalDocArr']) - 1;
						if(!empty($info['additionalDocArr'])){
							$docList = "";
							foreach($info['additionalDocArr'] as $index => $additionalDoc){
								$docList .= $additionalDoc['docLink'];
								$docList .= ($totalAdditionalDocs > $index ) ? " | " : "";
								// echo '<ul>';
								// echo  (!empty($additionalDoc['docLink'])) ? "<li>" . $additionalDoc['docLink'] ."</li>" : '';
								// echo '</ul>';
							}
							echo $docList;
						}
					echo '</td>';	
					
					echo '<td>' .((isset($info['recommDetails']['recommWriter'])) ? ($info['recommDetails']['recommWriter']) : '').'</td>';
					echo '<td class = "large">' .((isset($info['recommDetails']['accessDates'])) ? ($info['recommDetails']['accessDates']) : '').'</td>';					
					echo '<td>' .((isset($info['recommDetails']['due-date'])) ? ($info['recommDetails']['due-date']) : '').'</td>';					

					echo '<td>' .((isset($info['recommDetails']['link_recomm_report'])) ? ($info['recommDetails']['link_recomm_report']) : '').'</td>';
					echo '<td>' . ((isset($info['heqc_recommendation_report_doc'])) ? $heqcRecommReport : '') . '</td>';
					echo '<td>' . ((isset($info['heqc_nrc_report_doc'])) ? $heqc_nrc_report_doc : '') . '</td>';
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
