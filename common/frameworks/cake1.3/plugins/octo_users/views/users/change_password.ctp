<div class="users form">
<?php echo $this->Form->create('User', array('url' => array('action' => 'change_password', $token)));?>
	<fieldset>
 		<legend><?php __('Change your password');?></legend>
		<p class="information">
			<?php
				echo sprintf(__('Please enter a new password for %s %s.', true), $user['User']['first_name'], $user['User']['last_name']);
			?>
		</p>
	<?php
		echo $this->Form->input('clean_password', array(
			'type' => 'password',
			'label' => __d('octo-user', 'Account password', true),
			'between' => '<span class="between">'.__d('octo-user', 'The password needs to be a minimum of 6 characters long.', true).'</span>',
			'autocomplete' => 'off'
		));

		echo $this->Form->input('confirm_password', array(
			'type' => 'password',
			'label' => __d('octo-user', 'Confirm password', true),
			'between' => '<span class="between">'.__d('octo-user', 'Please re-enter your password.', true).'</span>'
		));
	?>
	<?php echo $this->Form->end(__d('octo-user', 'Change password', true));?>
	</fieldset>
</div>