<fieldset class="searchFieldset">
	<legend>Filter options</legend>
	<?php
		$sec_group_id = readPOST('sec_group_id');
		$this->formFields['sec_group_id']->fieldValue = $sec_group_id;
		
		$name = readPOST('name');
		$this->formFields['name']->fieldValue = $name;
		
		$surname = readPOST('surname');
		$this->formFields['surname']->fieldValue = $surname;
		
		$email = readPOST('email');
		$this->formFields['email']->fieldValue = $email;
	
		$active = readPOST('active');
		$this->formFields['active']->fieldValue = $active;
		
		$this->showBootstrapField('name', 'User name');
		$this->showBootstrapField('surname', 'User surname');
		$this->showBootstrapField('email', 'User email');
		$this->showBootstrapField('sec_group_id', 'User role');
		$this->showBootstrapField('active', 'Active');
	?>
	<input type="button" class="btn searchForm" value="Apply filter"> | <a class="clearSearchLink" href="#">Clear filter</a>
</fieldset>