<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'User')); ?>
	</ul>
</div>

<div class="users index">
<h2><?php __('Users');?></h2>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('email_address');?></th>
	<th><?php echo $this->Paginator->sort('first_name');?></th>
	<th><?php echo $this->Paginator->sort('last_name');?></th>
	<th><?php echo $this->Paginator->sort('role_id');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $user['User']['email_address']; ?>
			</td>
			<td>
				<?php echo $user['User']['first_name']; ?>
			</td>
			<td>
				<?php echo $user['User']['last_name']; ?>
			</td>
			<td>
				<?php echo $user['Role']['name']?>
			</td>
			<td>
				<?php echo $user['User']['created']; ?>
			</td>
			<td class="actions">
				<?php 
					$options = array('delete' => false);
					if($user['User']['active'] && $user['User']['id'] !== Auth::get('User.id')) {
						$options['Revoke access'] = array('action' => 'toggle_state', $user['User']['id']);
					}
					elseif(!$user['User']['active'] && $user['User']['id'] !== Auth::get('User.id')) {
						$options['Restore access'] = array('action' => 'toggle_state', $user['User']['id']);
					}
					
					echo $this->AuthLinks->recordActions($user['User'], $options); 
				?>
			</td>
		</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('paging', array('plugin' => 'octoplus')); ?>
</div>