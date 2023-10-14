<table>
	<tr>
		<th width="15%">Section</th>
		<th width="15%">Permission</th>
		<?php
			foreach($roles as $role) {
				echo '<th>'.$role.'</th>';
			}
		?>
	</tr>
	<?php
		foreach($permissions as $controller => $rolePermissions) {
			$splitController = explode('.', $controller);
			echo '<tr>';
				echo '<td rowspan="'.(count($availablePermissions) + 1).'">'.$splitController[1].'</td>';
			echo '</tr>';
			foreach($availablePermissions as $permission) {
				echo '<tr>';
				echo '<td>'.Inflector::humanize($permission).'</td>';
				foreach($roles as $roleId => $role) {
					$checked = isset($rolePermissions[$roleId][$permission]) ? ($rolePermissions[$roleId][$permission]) : false;
					
					$fieldName = $rolePermissions['id'].'.'.$roleId.'.'.$permission;
					echo '<td>'.($checked ? 'Allowed' : 'Denied').'</td>';
				}
				echo '</tr>';
			}				
		}
	?>
</table>