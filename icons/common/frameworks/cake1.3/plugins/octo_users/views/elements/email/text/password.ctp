A request to reset your password for <?php echo Configure::read('Site.site_name') ?> has been received. 

To change your password, please visit the following link by no later than <?php echo $this->Time->nice($user['User']['token_expiration'])?>:
<?php echo Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'change_password', $user['User']['password_token']), true); ?> 

If you did not request a password change, you can ignore this email.