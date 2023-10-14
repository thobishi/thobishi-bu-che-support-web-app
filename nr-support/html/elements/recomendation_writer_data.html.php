<h3>Recommendations to write</h3>
<?php
	if(!empty($RecommendationDataPanel)){
?>
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<th>
						Institution
					</th>
					<th>
						Programme
					</th>
					<th>
						Access dates
					</th>
					<th>
						SER Submission
					</th>
					<th>
						Pre-lim
					</th>
					<th>
						Panel
					</th>
					<th>
						Additional documents
					</th>					
					<th>	
						Criteria evaluation comparison
					</th>														
					<th>
						Downloads
					</th>
					<th>
						Recommendation due date
					</th>					
					<th>
						Report
					</th>					
					<th>
						Actions
					</th>					
				</tr>
			</thead>
			<tbody>
<?php          


				foreach($RecommendationDataPanel as $recommendation){
					$this->view = 1;
					$prog_id = $recommendation['id'];
					$countAdditionalDoc = count($recommendation['additionalDoc_list']) -1;
					$doc ="";
					
					$url = "javascript:showSERreadOnly(" . $prog_id . ");";
					$serDoc = $this->createDocLink($recommendation['ser_doc'], 'SER');
					$serSignOff = $this->createDocLink($recommendation['signoff_doc'], 'Sign-off');
					$serOnline = '<a href="' . $url . '">Profile of the programme</a>';
					
					$analyst_report = $this->createDocLink($recommendation['analyst_report_doc'], 'Pre-lim report');
					
					$recommendation_report_doc  = $this->createDocLink($recommendation['recommendation_report_doc'], 'My report');
					
					$chair_report_doc  = $this->createDocLink($recommendation['chair_report_doc'], 'Chair report');
					
					$accessDate = ($recommendation['recommendation_start_date'] == "1970-01-01" || $recommendation['recommendation_end_date'] == "1970-01-01" ) ? "Not assigned" : $recommendation['recommendation_start_date'] . " to " . $recommendation['recommendation_end_date'];
					
					echo '<tr>';
					echo '<td>' . $recommendation['hei_name'] . '</td>';
					echo '<td>' . $recommendation['nr_programme_name'] . '</td>';
					echo '<td>' . $accessDate . '</td>';
					echo '<td>' . $serDoc . ' | ' .  $serSignOff . ' | ' . $serOnline . '</td>';
					echo '<td>' . $analyst_report . '</td>';
					echo '<td>' . (($chair_report_doc > '') ? $chair_report_doc : 'Not uploaded') . '</td>';
					echo '<td>';
					foreach($recommendation['additionalDoc_list'] as $index => $additionalDoc_list){
						$doc .= (!empty($additionalDoc_list['docLink'])) ?  $additionalDoc_list['docLink']  : '';
						$doc .= (!empty($additionalDoc_list['docLink']) && $index < $countAdditionalDoc ) ? " | " :'';
					}
					echo $doc;
					echo '</td>';
					$this->view = 0;
					$comparisonLink = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_recommWriter_Criteria');
					$comparison = "<a href='" . $comparisonLink . "'>SER and Panel criteria compared</a>";
					echo '<td>' . $comparison . '</td>';
					echo '<td><a href="html_documents/Recommendation_template.docx" target="_blank">Recommendation report template</a></td>';
					$this->view = 1;					
					echo '<td>' . $recommendation['recommendation_report_due_date'] . '</td>';
					echo '<td>' . (($recommendation_report_doc > '') ? $recommendation_report_doc : 'Not uploaded') . '</td>';
					$this->view = 0;
					$link = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_recommWriter_report');
					$action = "<a class='btn' href='" . $link . "'>Continue</a>";
					echo '<td>' . $action . '</td>';					
					echo '</tr>';
				}
?>
			</tbody>
		</table>
<?php
	}
	else{
		echo 'There are currently no recommendations assigned to you.';
	}
?>