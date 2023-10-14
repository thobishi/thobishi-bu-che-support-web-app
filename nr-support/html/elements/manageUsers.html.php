	<table class="table table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>Id</th>
			<th>Title</th>
			<th>Name</th>
			<th>Surname</th>
			<th>Email</th>
			<th>Contact number</th>
			<th>Contact group</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
<?php	
		foreach ($detailArr as $detail){
			echo '<tr>';
				echo '<td>' . $detail['user_id'] . '</td>';
				echo '<td>' . $detail['lkp_title_desc'] . '</td>';
				echo '<td>' . $detail['name'] . '</td>';
				echo '<td>' . $detail['surname'] . '</td>';
				echo '<td>' . $detail['email'] . '</td>';
				echo '<td>' . $detail['contact_nr'] . '</td>';
				echo '<td>' ;
				if(isset($detail['userGroups'])){
					echo '<ul>';
					foreach($detail['userGroups'] as $group){
						echo '<li>' . $group . '</li>';
					}
					echo '</ul>';
				}
				echo '</td>' ;
				echo '<td>' . $detail['lkp_active_desc'] . '</td>';
				echo '<td>' . $detail['editUser']. " " . $detail['changePassword']. '</td>';				
			echo '</tr>';
			
		}
?>
	</tbody>
	</table>
