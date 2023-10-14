<a name="application_form_question4"></a>
<br>
<?php

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$prov_type = $this->checkAppPrivPubl($current_id);

	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");


?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>8. PROGRAMME ADMINISTRATIVE SERVICES: (Criterion 8)</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm17_v2", "minimumStandards"); ?>
</fieldset>
<br><br>

<?php

	if ($prov_type == 1) {
		echo $this->buildSiteCriteriaEditforApplication($current_id,'8');
?>

<br>

<table  width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>8.3</b></td><td valign="top"><b>MANAGEMENT INFORMATION SYSTEM</b></td>
</tr>
<tr>
	<td valign="top"><b>8.3.1</b></td><td valign="top"><b>Technical description (such as platform, type of database/s, software, etc.):</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_1_mistechdesc_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.3.2</b></td><td valign="top"><b>Fields of information (e.g. student registration number, race, gender, marks, etc.):</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_2_misfieldsofinfo_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.3.3</b></td><td valign="top"><b>Type and periodicity of reports (e.g. pass rates - annual, progression - quarterly, etc.):</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_3_misreports_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.3.4</b></td><td valign="top"><b>Interface with institution's central MIS (describe):</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_4_misinterface_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.3.5</b></td><td valign="top"><b>Security features (describe):</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_5_missecurityfeatures_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.3.6</b></td><td valign="top"><b>Online access for students?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_3_6_onlineaccess_lkp") ?><br><br></td>
</tr>
</table>

<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<?php
/*
<!-- The following is for PRIVATE providers  -->
*/
//hardcoded - take out
$display2 = "block";
?>
<div style="display:<?php echo $display2?>">
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Policies/procedures for the certification of qualifications:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("8_policies_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
</div>
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour">
					<b>Any other documentation which will indicate your compliance with this criterion.</b><br>
					</td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("8_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>



</fieldset>


<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr>
</table>

<?php
	}

	if ($prov_type == 2) {
		echo $this->getTextContent("accForm17_v2", "publicRegistrarDeclaration");
		//displays the declaration if the user is administrator
		//$admin_id = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "user_ref");
			$user_arr = $this->getInstitutionAdministrator($current_id);
			if ($user_arr[0]==0){
				echo "Processing has been halted for the following reason: <br><br>";
				echo $user_arr[1];
			}
			if (($this->currentUserID == $user_arr[0]) || ($hei_id == 2)) {
				$this->buildRegistrarDeclarationForCriterion($current_id, "8");
			}
	}
?>

<br>

</td>
</tr>
</table>
<hr>