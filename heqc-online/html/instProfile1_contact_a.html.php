<?php 
	$contact_type_ref = 2;

	$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	$contact_desc = $this->getValueFromTable("lkp_contact_type","lkp_contact_id", $contact_type_ref,"lkp_contact_desc");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>


<br>
<span class="specialb">Contact details for the <?php echo $contact_desc; ?></span>
<br><br>

<?php 

$this->buildContactsGrid($inst, $contact_type_ref);

?>

</td></tr></table>
