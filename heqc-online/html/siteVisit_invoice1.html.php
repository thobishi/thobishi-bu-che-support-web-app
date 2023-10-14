<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<b>Now that the site visit has finished you need to invoice the institution. To do that, obtain from the administrator responsible for logistics the detail of the direct costs of the site visit and add them to the table below:</b>
<br><br>
<table width="50%" border=0  cellpadding="2" cellspacing="2"><tr>
	<td align="right">Site visit fee:</td>
	<td>R <?php echo $this->getDBsettingsValue("payment_site_fee");?></td>
</tr><tr>
	<td align="right">Additional fee for Extra site:</td>
	<td>R <?php echo $this->getDBsettingsValue("payment_additional_fee_siteVisit");?></td>
</tr><tr>
	<td  align="right">Direct costs:</td>
	<td>&nbsp;</td>
</tr><tr>
	<td align="right">Travel:</td>
	<td>R <?php $this->showField("direct_travel_costs");?></td>
</tr><tr>
	<td align="right">Accommodation:</td>
	<td>R <?php $this->showField("direct_accomodation_costs");?></td>
</tr><tr>
	<td align="right">Subsistence:</td>
	<td>R <?php $this->showField("direct_subsistence_costs");?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td align="right"><b>Total (plus VAT):</b></td>
	<td>R <?php $this->showField("total_costs");?></td>
</tr></table>
</td></tr></table>
