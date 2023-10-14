<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'User')); ?>
	</ul>
</div>

<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php echo sprintf(__('%s User', true), __($this->action == 'admin_edit'? 'Edit' : 'Add', true));?></legend>
	<?php
		echo $this->Form->input('id');

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
			
			echo $this->Form->input('first_name', array(
				'label' => __d('octo-user', 'First name', true),
			));

			echo $this->Form->input('last_name', array(
				'label' => __d('octo-user', 'Last name', true),
			));

			echo $this->Form->input('role_id', array(
				'label' => __d('octo-user', 'Role', true),
			));
			
			
			$userOptions = Configure::read('User');
			if(!empty($userOptions['Fields'])) {
				foreach($userOptions['Fields'] as $fieldName => $options) {
					if(!empty($options['formOptions'])) {
						echo $this->Form->input($fieldName, $options['formOptions']);
					}
				}
			}			
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>