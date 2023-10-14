<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'Role')); ?>
	</ul>
</div>


<div class="roles index">
	<h2>Roles</h2>

	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?></p>	
	<table>
		<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th>Default</th>
			<th class="actions">Actions</th>
		</tr>
		<?php foreach($roles as $role) {?>
		<tr>
			<td><?php echo $role['Role']['name'] ?></td>
			<td><?php echo $role['Role']['default'] ? 'Default' : ''?></td>
			<td class="actions"><?php echo $this->AuthLinks->recordActions($role['Role']); ?></td>
		</tr>
		<?php }?>
	</table>
	
	<?php echo $this->element('paging', array('plugin' => 'octoplus')); ?>
</div>
