<?php
	$roles = array('5', '6');
	$selectRole = array();
	
	foreach($roles as $role){
		$roleDetails = $this->getSecGroupName($role);
		$selectRole[$role] = $roleDetails['name'];
	}
?>


<div class="alert alert-block alert-error alert-selectPanel fade in" style="display:none;">
	<h4 class="alert-heading">Panel member select error!</h4>
	<p>Please make sure that a panel member is selected.</p>
</div>

<div class="well">
	<table class="table searchTable">
		<tr>
			<td>
				<input id="selectedPanelUsers" type="hidden" />
				<br />
				<br /><span class="infoSmall">(Additional panel members may be added from user administration)</span>
			</td>
			<td>
				<select class="roleSelect">
					<option value="">--Select group--</option>
					<?php
						foreach($selectRole as $value => $text){
							echo '<option value="' . $value . '">' . $text . '</option>';
						}
					?>
				</select>
			</td>
			<td>
				<input type="button" class="btn addButton" value="Select">
			</td>
		</tr>
	</table>
</div>

<?php
	$panelUsers = $this->getSelectedPanel($roles, 'panel_members[]', $prog_id);
	echo 'Select panel members for the site visit:';
	echo $panelUsers['table'];
	
	foreach($roles as $role){
		echo (!empty($panelUsers['list'][$role])) ? '<span id="panelRole_' . $role . '" class="hidden">' . json_encode($panelUsers['list'][$role]) . '</span>' : '';
	}
?>
