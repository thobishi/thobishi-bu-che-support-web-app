<?php
	if(!empty($processes)){
		$processData = array();
		foreach($processes as $processCount => $process){
			$this->parseWorkFlowString($process['workflow_settings']);
			$serData = (isset($this->dbTableInfoArray['nr_programmes'])) ? $this->db->getMultipleFieldsFromTable($this->dbTableInfoArray['nr_programmes']->dbTableName, $this->dbTableInfoArray['nr_programmes']->dbTableKeyField, $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID) : array();
			if(!empty($serData)){
				foreach($serData as $ser){
					$admin = $this->getProgrammeAdministrator($ser['id'], $ser['hei_id']);
					$adminEmail = (isset($admin[0])) ? $this->db->getValueFromTable('users', 'user_id', $admin[0], 'email') : 'No administrator found';
					$processData[$processCount]['institution'] = $ser['hei_name'];
					$processData[$processCount]['nr_programme_name'] = $ser['nr_programme_name'] . ' (' . $ser['nr_programme_abbr'] . ')';
					$processData[$processCount]['date_submitted'] = $ser['date_submitted'];
					$processData[$processCount]['admin'] = $adminEmail;
					$processData[$processCount]['action'] = '<a class="btn" href="?ID=' . $process["active_processes_id"] . '">Continue</a>';
				}
			}
		}
		unset($this->dbTableInfoArray['nr_programmes']);
	}
?>

<h3><?php echo $processHeading; ?></h3>
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
				Submission date
			</th>
			<th>
				Administrator
			</th>
			<th>
				Action
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if(!empty($processData)){
				foreach($processData as $processInfo){
					echo '<tr>';
					echo '<td>' . $processInfo['institution'] . '</td>';
					echo '<td>' . $processInfo['nr_programme_name'] . '</td>';
					echo '<td>' . $processInfo['date_submitted'] . '</td>';
					echo '<td>' . $processInfo['admin'] . '</td>';
					echo '<td>' . $processInfo['action'] . '</td>';
					echo '</tr>';
				}
			}
			else{
				echo '<tr><td colspan="5">There are currently no submissions.</td></tr>';
			}
		?>
		<tr>
		</tr>
	</tbody>
</table>