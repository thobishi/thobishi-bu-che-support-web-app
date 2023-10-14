<?php
$inst_id= $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;

//$provider = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");


$provider = $this->getValueFromTable("HEInstitution", "HEI_id",$inst_id, "priv_publ");


?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>INSTITUTION INFORMATION:</b>
<br><br>
Enter your institutional information or update it if it is already filled in
<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td width="30%" align="right"><b>Institutional Name:</b></td>
	<td class="oncolour">&nbsp;<?php echo  $this->getValueFromTable("HEInstitution", "HEI_id", $this->formFields["institution_ref"]->fieldValue, "HEI_name"); ?></td>
</tr>
<tr>
	<td width="30%" align="right"><b>Institutional Type:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("institutional_type");?></td>
</tr>
<tr>
	<td width="30%" align="right"><b>Existing or New?</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("new_institution");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Mode of Provisioning:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("mode_delivery");?></td>
</tr>
<?php if ($provider == 1) {  ?>
<tr>
	<td width="30%" align="right"><b>Company registration (CIPC document)</b></td>
	<td class="oncolour"><i >For a private provider</i>
		<br>
		&nbsp;<?php $this->makelink("cipc_doc");?></td>
</tr>


<tr>
	<td width="30%" align="right"><b>Holding company :</b></td>
	<td class="oncolour">
	<i>For a private provider, provide the full registered company name of the Holding company, if applicable.</i>
		<br>
		&nbsp;<?php $this->showField("holding_company_name");?></td>
</tr>

<tr>
	<td width="30%" align="right"><b>DHET File number: </b></td>
	<td class="oncolour">
	<i>For a new private provider applicant, provide the File Number (15/3/1/1/X) if the institution has already submitted an application for registration with the DHET.</i>
		<br>
		&nbsp;<?php $this->showField("dhet_file_no");?></td>
</tr>
<?php } ?>
<?php if ($provider == 1) {  ?>
<tr>
	<td width="30%" align="right"><b>DHET Registration number:</b></td>
	<td class="oncolour">
		<span class="specials">New private providers that have not yet received a certificate of registration from DHET and all  public providers will not have a registration number. This only applies to institutions that have already been registered by DHET as private higher education institutions</span>
		<br>&nbsp;<?php $this->showField("dhet_registration_no");?>
	</td>
</tr>
<tr>
	<td width="30%" align="right"><b>DHET Registration Certificate</b></td>
	<td class="oncolour"><i >For registered private providers, upload current DHET Registration Certificate </i>
		<br>
		&nbsp;<?php $this->makelink("dhet_registration_doc");?></td>
</tr>
<?php } ?>
<tr>
	<td valign="top" width="30%" align="right"><b>Mission and how it is reflected by your existing programmes:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("mission");?></td>
</tr>
<tr>
	<td valign="top" width="30%" align="right"><b>Brief overview of the institution:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("overview");?></td>
</tr>
<tr>
	<td valign="top" width="30%" align="right"><b>Main goals of the institution:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("institution_goals");?></td>
</tr>
</table>
<?php $this->showField("institution_ref");?>
<?php $this->showField("last_updated_date");?>
<?php $this->showField("menu_or_app");?>
<br><br>
</td></tr></table>
