<?php
$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
?>
<h3>Table 4.1 A2 Contact details of Head of programme</h3>
<?php	
	echo '<div class="programme_head">';
	$this->showBootstrapField('head_title', 'Title');

	$this->showBootstrapField('head_initials', 'Initials');
	
	$this->showBootstrapField('head_firstname', 'Name');

	$this->showBootstrapField('head_surname', 'Surname');

	$this->showBootstrapField('head_physical_address', 'Physical Address');

	$this->showBootstrapField('head_email', 'Email');
		
	$this->showBootstrapField('head_telephone_no', 'Telephone number');

	$this->showBootstrapField('head_fax_no', 'Fax Number');

	$this->showBootstrapField('head_mobile_no', 'Mobile Number');

	$this->showSaveAndContinue('_label_ser_contact');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>