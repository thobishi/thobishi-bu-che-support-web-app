<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'AvailableController')); ?>
	</ul>
</div>

<div class="users form">
<?php echo $this->Form->create('AvailableController');?>
	<fieldset>
 		<legend><?php echo sprintf(__('%s Controller', true), __($this->action == 'admin_edit'? 'Edit' : 'Add', true));?></legend>
	<?php
		echo $this->Form->input('id');

		echo $this->data['AvailableController']['plugin'] . '.' . $this->data['AvailableController']['controller'];
		echo $this->Form->input('title');
	?>
		
		<fieldset>
			<legend>Available permissions</legend>
			<?php
				$fields = 0;
				foreach($availablePermissionFields as $availablePermissionField) {
					echo '<fieldset>';
					echo '<legend>'.Inflector::humanize($availablePermissionField).'</legend>';
					echo $this->Form->input('AvailablePermission.'.$fields.'.id');
					echo $this->Form->input('AvailablePermission.'.$fields.'.permission', array('type' => 'hidden', 'value' => $availablePermissionField));
					
					echo $this->Form->input('AvailablePermission.'.$fields.'.active', array('label' => 'Permission active'));
					
					echo $this->Form->input('AvailablePermission.'.$fields.'.title', array('label' => 'Permission title'));
					echo '</fieldset>';
					$fields++;
				}
			?>			
		</fieldset>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>