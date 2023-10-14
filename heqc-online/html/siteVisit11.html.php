<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<table width="85%" border=0  cellpadding="2" cellspacing="2">
<?php 
	$this->showField("generate");
	
	if (isset($_POST["generate"]) && ($_POST["generate"] > 0)) {
		$file = $this->generateReport("genSiteVisitReport('', ".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref").")");
		$ext = strrchr($file,".");
		copy($file, $this->TmpDir."siteVisit-Report".$ext);
		unlink($file);
		$file = $this->TmpDir."siteVisit-Report".$ext;
	
		$message = nl2br ($this->getTextContent ($this->template, "siteVisitReport"));
		$to = $this->getDBsettingsValue("che_registry_email");
		$subject = "Site visit report";
		$this->mimemail ($to, "", $subject, $message, $file);
		$this->scriptTail .= "\n\n showHideAction('stay', false);\n\n";
		echo '<tr>';
		echo '<td>The site visit report has been sent successfully.</td>';
		echo '</tr></table>';
	}else {
		echo '<tr>';
		echo '<td>The site visit report is completed. You need to send it to registry for printing and filling. To send report to registry click <a href="javascript:genRep();moveto(\'stay\')">here</a></td>';
		echo '</tr></table>';
	}
?>
</td></tr></table>
