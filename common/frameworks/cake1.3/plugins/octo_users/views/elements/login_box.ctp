<div class="loginBox">
	<?php
	echo $this->Form->create('UserLogin', array('url' => array('action' => 'login', 'controller' => 'users', 'plugin' => 'octo_users', 'admin' => false)));
		echo '<div id="loginFields">';
			echo $this->Form->input('email_address', array('label' =>  __d('octo-users', 'Email address', true)));
			echo $this->Form->input('password', array('label' =>  __d('octo-users', 'Password', true)));
		echo '</div>';
	echo $this->Form->end(__d('octo-users', 'Login', true));
		
		echo '<div class="loginlinks">';
			echo $this->Html->link(__d('octo-users', 'Register a new account', true), array('action' => 'register', 'controller' => 'users', 'plugin' => 'octo_users', 'admin' => false));
			echo $this->Html->link(__d('octo-users', 'Reset your password', true), array('action' => 'request_password', 'controller' => 'users', 'plugin' => 'octo_users', 'admin' => false));
			echo $this->Html->link(__d('octo-users', 'Resend account activation email', true), array('action' => 'resend', 'controller' => 'users', 'plugin' => 'octo_users', 'admin' => false));
		echo '</div>';
	?>
</div>