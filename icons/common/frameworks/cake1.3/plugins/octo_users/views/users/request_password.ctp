<?php if(!isset($sent)) { ?>
	<div class="users form">
	<?php echo $this->Form->create('User', array('url' => array('action' => 'request_password')));?>
		<fieldset>
			<legend><?php __d('octo-users', 'Reset password');?></legend>
			<p class="information"><?php __d('octo-users', 'Please enter the email address that you originally registered with. The system will send you an email containing a link which you can then use to reset your password.'); ?></p>
		<?php
			echo $this->Form->input('email_address', array('label' => __d('octo-users', 'Email address', true)));
		?>
		</fieldset>
	<?php echo $this->Form->end(__d('octo-users', 'Reset password', true));?>
	</div>
<?php } else { ?>
	<div class="ui-state-highlight ui-state-good">
		<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<h2 style="clear: none"><?php __d('octo-users', 'A password reset email has been sent.')?></h2>
		<p>
			<?php
				printf(
					__d(
						'octo-users',
						'Details on how to reset your password have been emailed to you.
							If you do not receive the email please check your spam mail folder, alternatively you can try <a href="%s">resending the email</a>.',
						true
					),
					Configure::read('Site.site_name'),
					$this->Html->url(array('action' => 'request_password'))
				)
			?>
		</p>
	</div>

	<?php echo $this->Html->link(__d('octo-users', 'Return to login page', true), array('action' => 'login'));?>
<?php } ?>