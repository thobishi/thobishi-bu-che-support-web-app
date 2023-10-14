<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>From the names indicated below select a person to be responsible for the preparation and submission of the site visit report to the HEQC. The person you select should have some institutional management experience.</b></td>
</tr><tr>
	<td>
<?php 
	foreach ($pers_arr AS $key=>$val) {
		$check = "";
		if ($response_pers == $val) {
			$check = " CHECKED";
		}
		echo '<input type="radio" name="FLD_responsible_person_ref" value="'.$val.'" '.$check.'>&nbsp;'.$key;
		echo '<br>';
	}
?>
	</td>
</tr></table>
</td></tr></table>
<script>
	function checkRespPerson() {
		var obj = document.defaultFrm;
		var flag = false;
		if (obj.MOVETO.value == 'next') {
			for (i=0; i<obj.length; i++) {
				if ((obj[i].type == 'radio') && (obj[i].checked)) {
					flag = true;
				}
			}
			if (!(flag)) {
				alert('Please select a responsible person before continuing.');
				obj.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
</script>
