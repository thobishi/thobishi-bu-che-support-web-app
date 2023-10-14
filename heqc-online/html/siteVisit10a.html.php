<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><b>The following task needs to be finalized within the next two days:</b>
	<br><br>
		<li>Prepare programme for site visit</li> 
	</td>
</tr></table>
</td></tr></table>
