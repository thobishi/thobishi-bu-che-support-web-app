<div class="users register form">
	<fieldset>
		<legend><?php __d('octo-user', 'Register your account') ?></legend>
		<?php
			echo $this->Form->create('User');

			echo $this->Form->input('email_address', array(
				'label' => __d('octo-user', 'Email address', true),
				'between' => '<span class="between">' . __d('octo-user', 'The email address needs to be a valid, working email. It will also be used for your login details.', true) . '</span>',
				'autocomplete' => 'off'
			));

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
			
			echo $this->Form->input('first_name', array(
				'label' => __d('octo-user', 'First name', true),
			));

			echo $this->Form->input('last_name', array(
				'label' => __d('octo-user', 'Last name', true),
			));
			
			$userOptions = Configure::read('User');
			if(!empty($userOptions['Fields'])) {
				foreach($userOptions['Fields'] as $fieldName => $options) {
					if(!empty($options['formOptions']) && (empty($options['adminOnly']) || $options['adminOnly'] == false)) {
						echo $this->Form->input($fieldName, $options['formOptions']);
					}
				}
			}
			
			if(!empty($userOptions['Registration']['element'])) {
				echo $this->element($userOptions['Registration']['element']);
			}

			echo $this->Form->end(__d('octo-user', 'Register account', true));
		?>
	</fieldset>
</div>