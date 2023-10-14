<div class="ui-state-highlight ui-state-good">
	<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<h2 style="clear: none"><?php __d('octo-users', 'Your account has been created!')?></h2>
	<p>
		<?php
			printf(
				__d(
					'octo-users',
					'Thank you for registering on %s. 
						In order to make use of this system your account first needs to be activated. 
						Details on how to activate your account have been sent to the email that you registered with.
						If you do not receive the email please check your spam mail folder, alternatively you can try <a href="%s">resending the email</a>.',
					true
				),
				Configure::read('Site.site_name'),
				$this->Html->url(array('action' => 'resend'))
			)
		?>
	</p>
</div>

<?php echo $this->Html->link(__d('octo-users', 'Return to login page', true), array('action' => 'login'));?>