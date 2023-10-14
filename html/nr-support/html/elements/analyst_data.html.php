<h3>Preliminary analysis</h3>
<?php
	if(!empty($prelimDataAnalyst)){
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
						Downloads
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
				foreach($prelimDataAnalyst as $prelim){
					$this->view = 1;
					$prog_id = $prelim['id'];
					$url = "javascript:showSERreadOnly(" . $prog_id . ");";
					$serDoc = $this->createDocLink($prelim['ser_doc'], 'SER');
					$serSignOff = $this->createDocLink($prelim['signoff_doc'], 'Sign-off');
					$serOnline = '<a href="' . $url . '">Online data tables</a>';
					$prelimReport = $this->createDocLink($prelim['analyst_report_doc'], 'My pre-lim report');
					$accessDate = ($prelim['analyst_start_date'] == "1970-01-01" || $prelim['analyst_end_date'] == "1970-01-01" ) ? "Not assigned" : $prelim['analyst_start_date'] . " to " . $prelim['analyst_end_date'];
					echo '<tr>';
					echo '<td>' . $prelim['hei_name'] . '</td>';
					echo '<td>' . $prelim['nr_programme_name'] . '</td>';
					echo '<td>' . $accessDate . '</td>';
					echo '<td>' . $serDoc . ' | ' .  $serSignOff . ' | ' . $serOnline . '</td>';
					echo '<td><a href="html_documents/template_BSW_prelim_analysis.docx" target="_blank">Prelim-analysis template</a></td>';
					echo '<td>' . (($prelimReport > '') ? $prelimReport : 'Not uploaded') . '</td>';
					$this->view = 0;
					$link = $this->scriptGetForm('nr_programmes', $prog_id, '_ser_prelim_upload');
					$action = "<a class='btn' href='" . $link . "'>Upload</a>";
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