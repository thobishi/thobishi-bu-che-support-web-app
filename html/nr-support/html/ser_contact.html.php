<?php
$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
?>
<h3>Table 4.1 B Contact person</h3>
<?php		
	echo '<div class="contact_person">';
	$this->showBootstrapField('contact_title', ' Title');

	$this->showBootstrapField('contact_initials', ' Initials');
	
	$this->showBootstrapField('contact_firstname', 'Name');

	$this->showBootstrapField('contact_surname', ' Surname');

	$this->showBootstrapField('contact_designation', ' Designation of contact person');

	$this->showBootstrapField('contact_physical_address', ' Physical Address');

	$this->showBootstrapField('contact_postal_address', ' Postal Address');

	$this->showBootstrapField('contact_email', ' Email');

	$this->showBootstrapField('contact_telephone_no', ' Telephone number');

	$this->showBootstrapField('contact_fax_no', ' Fax Number');

	$this->showBootstrapField('contact_mobile_no', ' Mobile Number');

	$this->showSaveAndContinue('_label_ser_profile_sites_delivery');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>
