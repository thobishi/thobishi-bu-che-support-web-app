<h3>List of National Review programmes for <em><?php echo $this->getInstitutionInfo('hei_name'); ?></em></h3>
<?php
	echo $this->element('filters/' . Settings::get('template'), $_POST);
	$details = $this->getInstProgressDetails($_POST, Settings::get('template'));
	if(!empty($details)){
?>
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<th>
						Institution code
					</th>
					<th>
						Institution name
					</th>
					<th>
						Programme name
					</th>
					<th>
						HEQSF number
					</th>
					<th>
						Active process and person
					</th>
					<th>
						Submission date
					</th>
					<th>
						Site visit date
					</th>
					<th>
						Reports
					</th>
				</tr>
			</thead>
			<tbody>
<?php			
				foreach($details as $info){
					$nr_programme_id = $info['id'];
					echo '<tr>';
					echo '<td>' . ((isset($info['hei_code'])) ? ($info['hei_code']) : '') . '</td>';
					echo '<td>' . ((isset($info['hei_name'])) ? ($info['hei_name']) : '') . '</td>';
					echo '<td>' .  ((isset($info['nr_programme_name'])) ? ($info['nr_programme_name']) : '') . '</td>';
					echo '<td>' . ((isset($info['heqsf_reference_no'])) ? ($info['heqsf_reference_no']) : '') . '</td>';
					echo '<td>' . ((isset($info['active_process_person'])) ? ($info['active_process_person']) : '') . '</td>';
					echo '<td>' . ((isset($info['date_submitted']) && $info['date_submitted'] != '1970-01-01') ? ($info['date_submitted']) : 'Not submitted') . '</td>';
					echo '<td>Not visited</td>';
					echo '<td>' . ((isset($info['link_report'])) ? ($info['link_report']) : '') . '</td>';
					echo '</tr>';
				}
?>
			</tbody>
		</table>
		<tr>
		<td class="fieldsetData">
			<fieldset><legend>Upload your improvement plan:</legend>
				<?php
					$this->makeLink("improvement_doc", "Improvement plan document", "nr_programmes", "id", $nr_programme_id);
				?>
			</fieldset>
		</td>
		</tr>
<?php
	}else{
		echo 'No results found';
	}
?>
