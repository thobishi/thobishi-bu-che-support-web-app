<h2><?php __('Models'); ?></h2>
<?php if ($models) : ?>
<table>
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Plugin'); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($models as $model) : ?>
	<tr>
		<td><?php echo $model['AnalystModel']['name']; ?></td>
		<td><?php echo (!empty($model['AnalystModel']['plugin']) ? $model['AnalystModel']['plugin'] : '-'); ?></td>
		<td><?php echo $this->Html->link(__('check', true), array('action' => 'check', Inflector::underscore($model['AnalystModel']['name']), Inflector::underscore($model['AnalystModel']['plugin']))); ?></td>
	</tr>
	<?php endforeach;?>
</table>
<?php else: ?>
<em><?php __('No models found.'); ?></em>
<?php endif; ?>