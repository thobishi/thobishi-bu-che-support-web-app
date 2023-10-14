<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<?php $this->showField("site_decision");?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Click on the programmes below to view the reports:</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td>
<?php 
	$this->showField("application_ref");
	$this->showField("site_ref");
	$this->showSiteHistoryList ($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"));
?>
	</td>
</tr></table>
<br><br>
</td></tr></table>
<script>
	function goSiteVisitDecision (val) {
		document.defaultFrm.site_decision.value = val;
	}
	
	function changeRefs (app, site) {
		document.defaultFrm.application_ref.value = app;
		document.defaultFrm.site_ref.value = site;
	}
</script>
