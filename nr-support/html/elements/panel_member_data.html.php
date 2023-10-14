<h3>Virtual Site Visit - Panel member</h3>
<?php
	if(!empty($prelimDataPanel)){
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
						Virtual Site Visit
					</th>
					<th>
						Access dates 
					</th>
					<th>
						SER Submission
					</th>
					<th>
						Additional Documents
					</th>
					<th>
						Downloads
					</th>
					<th>
						Reports
					</th>	
                                        <th>
                                                Actions
                                        </th>				
				</tr>
			</thead>
			<tbody>
<?php
				foreach($prelimDataPanel as $prelim){
					$this->view = 1;

					$prog_id = $prelim['id'];
					$url = "javascript:showSERreadOnly(" . $prog_id . ");";
					$serDoc = $this->createDocLink($prelim['ser_doc'], 'SER');
					$serSignOff = $this->createDocLink($prelim['signoff_doc'], 'Sign-off');
					$serOnline = '<a href="' . $url . '">Online data tables</a>';
					$prelimReport = $this->createDocLink($prelim['analyst_report_doc'], 'Desktop evaluation report');
					$chair_report_doc  = $this->createDocLink($prelim['chair_report_doc'], 'Our report');
					$accessDate = ($prelim['panel_start_date'] == "1970-01-01" || $prelim['panel_end_date'] == "1970-01-01" ) ? "Not assigned" : $prelim['panel_start_date'] . " to " . $prelim['panel_end_date'];
					
					$additionalDocsArr = $this->getPrelimAdditionalInfo($prelim['id']);
					$doc = "";

					echo '<tr>';
					echo '<td>' . $prelim['hei_name'] . '</td>';
					echo '<td>' . $prelim['nr_programme_name'] . '</td>';
					echo '<td>' . $prelim['site_visit_date'] . '</td>';
					echo '<td>' . $accessDate . '</td>';
					echo '<td>' . $serDoc . ' | ' .  $serSignOff . ' | ' . $serOnline . ' | ' . $prelimReport . '</td>';
					echo '<td>';

					foreach ($additionalDocsArr as $additionalDoc){
						$doc .= (!empty($additionalDoc['docLink'])) ? $additionalDoc['docLink'] : '';
						$doc .= (!empty($additionalDoc['docLink'])) ? " | " : '';
					}
					$doc = substr($doc, 0, -2);
					echo $doc;
					echo '</td>';
					//BSW link
					//echo '<td><a href="html_documents/Panel_review_template.docx" target="_blank">Evaluation report template</a></td>';
					//LLB link
					echo '<td><a href="html_documents/SER Desktop Evaluation Report Template_ 2020-06-19 _Final .docx" target="_blank">Site visit template</a></td>';
					echo '<td>' . (($chair_report_doc > '') ? $chair_report_doc : 'Not uploaded') . '</td>';
                                         					
					$this->view = 0;
                                          $link = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_panelEvalReport');
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
		echo 'There are currently no activities assigned to you.';
	}
?>
