<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'Role')); ?>
	</ul>
</div>

<div class="roles form">
<?php echo $this->Form->create('Role');?>
	<fieldset>
 		<legend><?php echo sprintf(__('%s Role', true), __($this->action == 'admin_edit'? 'Edit' : 'Add', true));?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('default');
	?>
	</fieldset>
<?php echo $this->Form->end('Save');?>
</div>