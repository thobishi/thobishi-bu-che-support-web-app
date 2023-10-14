<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td colspan="2"><b>You need to organize the site visit with the institution. Complete the following fields to fill in the details for the letter to the institution</b></td>
</tr><tr><td colspan="2">&nbsp;</td></tr><tr>
	<td align="right" valign="top"><b>Panel Members</b></td>
	<td><br><?php foreach ($evals AS $key=>$value) {
			echo $value;
			echo "<br>";
		  }
		?>
	</td>
</tr><tr>
	<td align="right" valign="top"><b>1st Possible Visit</b></td>
	<td><?php $this->showField("date_visit1");?></td>
</tr><tr>
	<td align="right" valign="top"><b>2nd Possible Visit</b></td>
	<td><?php $this->showField("date_visit2");?></td>
</tr>
<tr>
	<td align="right" valign="top"><b>Documents Required</b></td>
	<td><?php $this->showField("docs_required");?></td>
</tr><tr>
	<td align="right" valign="top"><b>Site of Delivery</b></td>
	<?php 
		$this->formFields["site_delivery"]->fieldValue = $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable($this->dbTableCurrent, $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField, $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location");
	?>
	<td><?php $this->showField("site_delivery");?></td>
</tr><tr>
	<td align="right" valign="top"><b>Infrastructure</b></td>
	<td><?php $this->showField("infrastructure");?></td>
</tr><tr>
	<td align="right" valign="top"><b>Staff</b></td>
	<td><?php $this->showField("staff");?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2"><b>To view the letter that will be sent to the institution proposing a site visit, click <a href="javascript:moveto('next')">here</a></b></td>
</tr></table>
</td></tr></table>
<script>
	function checkDates () {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if ((document.defaultFrm.FLD_date_visit1.value == '1970-01-01') || (document.defaultFrm.FLD_date_visit2.value == '1970-01-01')) {
				alert ('Please fill in the dates of the site visit before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if (document.defaultFrm.FLD_date_visit1.value == document.defaultFrm.FLD_date_visit2.value) {
				alert("The two dates can't be the same.");
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_docs_required.value == "")) {
				alert ('Please fill in the documents required before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_site_delivery.value == "")) {
				alert ('Please fill in the site of delivery before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_infrastructure.value == "")) {
				alert ('Please fill in the infrastructure before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_staff.value == "")) {
				alert ('Please fill in the staff before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
</script>
