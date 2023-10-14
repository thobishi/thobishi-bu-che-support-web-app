Thank you for registering on <?php echo Configure::read('Site.site_name') ?>.

In order to use the system you need to activate your account.

To activate your account, please visit the following link by no later than <?php echo $this->Time->nice($user['User']['token_expiration'])?>:
<?php echo Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'verify_account', $user['User']['email_token'], 'plugin' => 'octo_users'), true); ?>
