<?php $this->Html->css('/schema_analyst/css/statuses.css', null, array('inline' => false)); ?>
<h2><?php echo $name . ' (' . $table . ')'; ?></h2>
<?php echo $this->Html->link('back', array('action' => 'index')); ?>
<h3><?php __('Field length check'); ?></h3>
<table>
	<tr>
		<th><?php __('Field'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Primary key'); ?></th>
		<th><?php __('Max length'); ?></th>
		<th><?php __('Limit'); ?></th>
		<th><?php __('Message'); ?></th>
	</tr>
	<?php foreach($analysis['lengths'] as $properties) : ?>
	<tr class="status-<?php echo $properties['status']; ?>">
		<td><?php echo $properties['name']; ?></td>
		<td><?php echo $properties['type']; ?></td>
		<td><?php echo __(($properties['primary'] ? 'yes' : 'no'), true); ?></td>
		<td><?php echo $properties['data']; ?></td>
		<td><?php echo $properties['limit'] ? $properties['limit'] : '-'; ?></td>
		<td><?php echo $properties['message'] ? $properties['message'] : '-'; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h3><?php __('Charset check'); ?></h3>
<table>
	<tr>
		<th><?php __('Check'); ?></th>
		<th><?php __('Value'); ?></th>
		<th><?php __('Application'); ?></th>
		<th><?php __('Message'); ?></th>
	</tr>
	<tr class="status-<?php echo $analysis['charset']['connection_status']; ?>">
		<td><?php __('Database connection charset'); ?></td>
		<td><?php echo $analysis['charset']['connection']; ?></td>
		<td><?php echo $analysis['charset']['app']; ?></td>
		<td><?php echo $analysis['charset']['connection_message']; ?></td>
	</tr>
	<tr class="status-<?php echo $analysis['charset']['table_status']; ?>">
		<td><?php __('Database table charset'); ?></td>
		<td><?php echo $analysis['charset']['table']['charset']; ?></td>
		<td><?php echo $analysis['charset']['app']; ?></td>
		<td><?php echo $analysis['charset']['table_message']; ?></td>
	</tr>
</table>

<h3><?php __('Index check'); ?></h3>
<p>Working on this now. This section will check:</p>
<ul>
	<li>The primary key index.</li>
	<li>The foreign key indexes following model associations.</li>
	<li>The unique indexes following isUnique validation rules.</li>
</ul>