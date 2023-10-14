<h2><?php echo sprintf(__('Delete User "%s"?', true), $user['User']['first_name'] . ' ' . $user['User']['last_name']); ?></h2>
<p>	
	<?php __('Be aware that your User and all associated data will be deleted if you confirm!'); ?>
</p>
<?php
	echo $this->Form->create('User', array(
		'url' => array(
			'action' => 'delete',
			$user['User']['id'])));
	echo $form->input('confirm', array(
		'label' => __('Confirm', true),
		'type' => 'checkbox',
		'error' => __('You have to confirm.', true)));
	echo $form->submit(__('Continue', true));
	echo $form->end();
?>