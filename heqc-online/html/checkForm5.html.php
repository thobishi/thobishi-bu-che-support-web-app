<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<?php 
$this->showField("confirm_email");

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$flag = false;

if ($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id"), "priv_publ") == 1){
	$flag = true;
}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><b>Please make sure that your e-mails to the DoE and SAQA have been answered.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><?php $this->showField("confirm_saqa_email") ?> &nbsp; SAQA e-mail confirmation</td>
</tr>
<?php 	if (!$flag) {?>
<tr>
	<td><?php $this->showField("confirm_doe_email") ?> &nbsp; PQM - DoE e-mail confirmation</td>
</tr>
<?php 	}
	if ($flag) {
?>
<tr>
	<td><?php $this->showField("confirm_doe_provider_email") ?> &nbsp; DoE provider confirmation</td>
</tr>
<?php 	}?>
<tr>
	<td><?php $this->showField("fwd_saqa") ?> &nbsp; Did you forward the SAQA confirmations to registry?</td>
</tr><tr>
	<td><?php $this->showField("fwd_doe") ?> &nbsp; Did you forward the DoE confirmations to registry?</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>
</td></tr></table>
