<?php
	// $this->pr($restrictionArr);
	if(!empty($assignmentArr)){
?>
		<div class="nr_progressDiv">
			<table class="table table-hover table-bordered table-striped nr_progress">
				<thead>
					<tr>

						<th rowspan="2">
							Users
						</th>
						<th rowspan="2">
							Programmes assigned
						</th>
						<!--<th>
							Actions
						</th>-->				
					</tr>
				</thead>
				<tbody>
<?php			
					foreach($assignmentArr as $userId => $info){
							echo '<tr>';
							echo '<td>';
								echo $info['userDetail'];
							echo '</td>';

							echo '<td>';
								echo '<ul>';
								foreach($info['progName'] as $index => $progName){
									echo '<li>' . $progName . '</li>';
								}
								echo '</ul>';
							echo '</td>';
							// echo '<td>';
								// echo $info['edit'];
							// echo '</td>';							
							echo '</tr>';

						
					}
?>
				</tbody>
			</table>
		</div>
<?php
	}
	else{
		echo 'No progammes has been assigned to a user at this time.';
	}
?>