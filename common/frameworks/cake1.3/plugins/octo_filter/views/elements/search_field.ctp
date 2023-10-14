<?php
	echo $this->Form->input('search', array(
		'div' => false,
		'label' => array(
			'class' => 'inline',
			'text' => __d('octo_filter', 'Search users', true)
		),
		'class' => 'inline short'
	));

	echo $this->Form->button(__d('octo_filter', 'Go', true));
	echo $this->Form->button(__d('octo_filter', 'Reset', true));
?>