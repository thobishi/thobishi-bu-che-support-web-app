<div class="actions">
	<ul>
		<?php echo $this->AuthLinks->pageActions(array('model' => 'AvailableController')); ?>
	</ul>
</div>

<div class="available_controllers index">
<h2><?php __('Available Controllers');?></h2>

<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('title');?></th>
	<th><?php echo $this->Paginator->sort('plugin');?></th>
	<th><?php echo $this->Paginator->sort('controller');?></th>
	<th><?php echo $this->Paginator->sort('available_permissions');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($available_controllers as $available_controller):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $available_controller['AvailableController']['title']; ?>
			</td>
			<td>
				<?php echo $available_controller['AvailableController']['plugin']; ?>
			</td>
			<td>
				<?php echo $available_controller['AvailableController']['controller']; ?>
			</td>
			<td>
			</td>
			<td class="actions"><?php echo $this->AuthLinks->recordActions($available_controller['AvailableController']); ?></td>
		</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('paging', array('plugin' => 'octoplus')); ?>
</div>