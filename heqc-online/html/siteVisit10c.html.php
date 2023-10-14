<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>


<?php 
	$this->buildSiteVisitReportTable("",0,0);
?>


<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right" valign="top"><b>Site Name:</b> </td>
	<td class="oncolour" valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<?php 	
	$this->showField("saveReport");
	if (isset($_POST["saveReport"]) && ($_POST["saveReport"]==1)) {
		$this->scriptTail .= "\n\n showHideAction('stay', false);\n\n";
		$this->saveSiteVisitReport ($_POST);
?>
		<table width="75%" border=0  cellpadding="2" cellspacing="2">
		<tr>
			<td>Your report has been saved sucessfully. Click "Next" to continue.</td>
		</tr></table>
		<script>document.all.saveReport.value=1;</script>
<?php 
	}else {
?>
		<table width="75%" border=0  cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<b>You have received the report from the site visit. Please enter the relevant information on the screen to complete the site visit process.</b>
			<br><br>
			This screen helps you to complete a report on the site visit. 
				Using the categories, "commend", "meets-minimum-standards", "has not reached minimum standards" and "does not comply", 
				fill in every field in the table below. PLEASE, DO NOT FORGET TO ADD COMMENTS ON EVERY ITEM.
			</td>
		</tr><tr>
			<td>Once you finish, click at the bottom of the table to save your report. DO NOT CLICK NEXT WITHOUT SAVING YOUR WORK.</td>
		</tr></table>
		<br><br>
<?php 
		if ($this->checkIfSiteVisitReportDone ()) {
			$this->showSiteVisitReport ();
		}else {
			echo $this->buildSiteVisitReportTable ("", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref"), $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		}
?>
		<br><bR>
		<table width="75%" border=0  cellpadding="2" cellspacing="2">
		<tr>
			<td>To save the report click <a href="javascript:moveto('stay');">here</a></td>
		</tr></table>
<?php 
		echo '<script>document.all.saveReport.value=1;</script>';
	}
?>
<br><br>
</td></tr></table>
