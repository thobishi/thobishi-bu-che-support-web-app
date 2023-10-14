<?php if(!isset($sent)) { ?>
	<div class="users form">
	<?php echo $this->Form->create('User', array('url' => array('action' => 'resend')));?>
		<fieldset>
			<legend><?php __d('octo-users', 'Resend activation email');?></legend>
			<p class="information"><?php __d('octo-users', 'Please enter the email address that you originally registered with. The system will then send you an email containing a link which you can use to activate your account.'); ?></p>
		<?php
			echo $this->Form->input('email_address', array('label' => __d('octo-users', 'Email address', true)));
		?>
		</fieldset>
	<?php echo $this->Form->end(__d('octo-users', 'Resend activation email', true));?>
	</div>
<?php } else { ?>
	<div class="ui-state-highlight ui-state-good">
		<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<h2 style="clear: none"><?php __d('octo-users', 'The activation email has been resent.')?></h2>
		<p>
			<?php
				printf(
					__d(
						'octo-users',
						'Details on how to activate your account have been resent to the email that you registered with.
							If you do not receive the email please check your spam mail folder, alternatively you can try <a href="%s">resending the email</a>.',
						true
					),
					$this->Html->url(array('action' => 'resend'))
				)
			?>
		</p>
	</div>

	<?php echo $this->Html->link(__d('octo-users', 'Return to login page', true), array('action' => 'login'));?>
<?php } ?>
