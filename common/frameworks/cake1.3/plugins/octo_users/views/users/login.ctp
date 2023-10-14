<div class="users login">
	<?php
	echo $this->Form->create('User');
		echo '<fieldset>';
			echo '<legend>'.__d('octo-users', 'Login', true).'</legend>';
			
			echo '<div class="loginFields">';
			echo $this->Form->input('email_address', array('label' =>  __d('octo-users', 'Email address', true)));
			echo $this->Form->input('password', array('label' =>  __d('octo-users', 'Password', true)));
			echo '</div>';
			
			echo '<div class="loginlinks">';
			if(Configure::read('User.registration') !== false) {
				echo $this->Html->link(__d('octo-users', 'Register a new account', true), array('action' => 'register'));
				echo $this->Html->link(__d('octo-users', 'Resend account activation email', true), array('action' => 'resend'));
			}
			echo $this->Html->link(__d('octo-users', 'Reset your password', true), array('action' => 'request_password'));
			echo '</div>';			
		echo '</fieldset>';
	echo $this->Form->end(__d('octo-users', 'Login', true));
	?>
</div>