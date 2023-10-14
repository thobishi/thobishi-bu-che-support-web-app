<h3>Table 4.1 D Sites of delivery</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$fieldArr = array();

	array_push($fieldArr, "type__select|name__lkp_site_type_id|description_fld__lkp_site_types_desc|fld_key__id|lkp_table__lkp_site_types|lkp_condition__1|order_by__id|default__--Select--");
	array_push($fieldArr, "type__text|name__site_name");
	array_push($fieldArr, "type__textarea|name__physical_address");
	array_push($fieldArr, "type__textarea|name__postal_address");
	array_push($fieldArr, "type__text|name__site_email");
	array_push($fieldArr, "type__text|name__site_tel_no");
	array_push($fieldArr, "type__text|name__site_fax_no");
	array_push($fieldArr, "type__text|name__site_mobile_no");
	
	$headingArr = array("Site type", "Name of site of delivery", "Physical address (Faculty or other)", "Postal address", "Email", "Telephone number", "Fax number", "Mobile number");
		
	$addRowText = "another site of delivery";
	echo '<div class="site_delivery">';
	$this->gridShowTableByRow("nr_programme_sites", "id", "nr_programme_id__" . $prog_id, $fieldArr, $headingArr, 70, 10, true, $addRowText);

	$this->showSaveAndContinue('_label_ser_budget_income');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>