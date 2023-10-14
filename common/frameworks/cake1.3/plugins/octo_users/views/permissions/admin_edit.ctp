<?php echo $this->Form->create('Permissions'); ?>
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
		$allPermissions = $availablePermissions;
		$i=0;
		foreach($permissions as $controller => $rolePermissions) {
			$class = ($i++ % 2) ? ' class="altrow"' : '';
			$splitController = explode('.', $controller);
			
			$availablePermissions = !empty($rolePermissions['availablePermissions']) ? $rolePermissions['availablePermissions'] : $allPermissions;
			
			$controllerTitle = empty($rolePermissions['title']) ? $splitController[1] : $rolePermissions['title'];
			
			echo '<tr'.$class.'>';
				echo '<td rowspan="'.(count($availablePermissions) + 1).'">'.$controllerTitle.'</td>';
			echo '</tr>';
			foreach($availablePermissions as $permission => $title) {
				if(is_numeric($permission)) {
					$permission = $title;
				}
				
				if(empty($title)) {
					$title = Inflector::humanize($permission);
				}
				
				echo '<tr'.$class.'>';
				echo '<td>'.$title.'</td>';
				foreach($roles as $roleId => $role) {
					$checked = isset($rolePermissions[$roleId][$permission]) ? ($rolePermissions[$roleId][$permission]) : false;
					
					$fieldName = $rolePermissions['id'].'.'.$roleId.'.'.$permission;
					echo '<td>'.$this->Form->checkbox($fieldName, array('checked' => $checked)).'</td>';
				}
				echo '</tr>';
			}				
		}
	?>
</table>
<?php echo $this->Form->end('Save'); ?>