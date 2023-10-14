<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __d('octo-user', 'Update your account');?></legend>
	<?php
		echo $this->Form->input('id');

			echo $this->Form->input('email_address', array(
				'label' => __d('octo-user', 'Email address', true),
				'between' => '<span class="between">' . __d('octo-user', 'The email address needs to be a valid, working email. It will also be used for your login details.', true) . '</span>',
				'autocomplete' => 'off'
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
	?>
		
	<a href="#" target="password" id="changePassword"><?php __d('octo-users', 'Change your password'); ?></a>
	<fieldset id="password">
		<legend><?php __d('octo-users', 'Change your password'); ?></legend>
		<?php
			echo $this->Form->input('current_password', array('type' => 'password', 'value' => '', 'label' => __d('octo-users', 'Existing password', true)));
			echo $this->Form->input('clean_password', array('type' => 'password', 'value' => '', 'label' => __d('octo-users', 'New password', true)));
			echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '', 'label' => __d('octo-users', 'Confirm new password', true)));
		?>
	</fieldset>		
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<script type="text/javascript">
	$(function() {
		if($('#password').find('.error').length) {
			$('#changePassword').hide();
		}
		else {
			$('#password').hide();
		}
		
		$('#changePassword')
			.button()
			.click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				$('#password').fadeIn('fast');
				$(this).hide();
			})
	})
</script>