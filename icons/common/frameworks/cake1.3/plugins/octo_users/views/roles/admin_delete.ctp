<h2><?php echo sprintf(__('Delete Role "%s"?', true), $role['Role']['name']); ?></h2>
<p>	
	<?php __('Be aware that your Role and all associated data will be deleted if you confirm!'); ?>
</p>
<?php
	echo $this->Form->create('Role', array(
		'url' => array(
			'action' => 'delete',
			$role['Role']['id'])));
	echo $form->input('confirm', array(
		'label' => __('Confirm', true),
		'type' => 'checkbox',
		'error' => __('You have to confirm.', true)));
	echo $form->submit(__('Continue', true));
	echo $form->end();
?>