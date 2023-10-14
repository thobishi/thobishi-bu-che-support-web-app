<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<b>Did you receive confirmation from the institution for the site visit?</b>
<?php 
	$this->showField("inst_confirm_visit");
?>
<br><br>
<b>Did they object to any of the evaluators?</b>
<?php 
	$this->showField("object_sitevisit_evals");
?>
<br><br>
<b>Did they object to the site visit itself?</b>
<?php 
	$this->showField("object_sitevisit_visit");
?>
</td></tr></table>
