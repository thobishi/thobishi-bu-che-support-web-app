<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
<?php 
$app_version = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "app_version");
switch($app_version) {
	case 1 :	$this->showEmailAsHTML("checkForm9", "supportingDocsIncomplete");
				break;
	default: // version 2, 3 or 4
				$this->showEmailAsHTML("checkForm9", "supportingDocsIncomplete_v2");
				break;
}

?>
</td>
</tr>
<tr>
</table>
</td></tr></table>
