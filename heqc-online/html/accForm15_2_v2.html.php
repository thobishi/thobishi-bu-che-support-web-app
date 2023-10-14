<a name="application_form_question7"></a>
<br>
<?php
	$site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<b>7. INFRASTRUCTURE AND LIBRARY RESOURCES: (Criterion 7)</b>&nbsp; [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
<fieldset>
<legend>Minimum standards</legend>
Suitable and sufficient venues, IT infrastructure and library resources are available for students and staff in the programme. Policies ensure the proper management and maintenance of library resources, including support and access for students and staff. Staff development for library personnel takes place on a regular basis.
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>7.1</b></td>
	<td valign="top">
	<b>Describe teaching and learning facilities in relation to this programme (classrooms, seminar rooms,
	work rooms, studios etc).</b>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_1_learnfacility_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.2</b></td>
	<td valign="top">
	<b>Provide details of laboratory or special equipment required and available for the programme.</b>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_2_labequip_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.3</b></td>
	<td valign="top">
	<b>Describe how health and occupational safety and clinical regulations are complied with
	(details required if specialised rooms and equipment are used).  </b>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_3_healthreg_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.4</b></td>
	<td valign="top"><b>Provide details of IT infrastructure (hardware and software) in relation to staff and students. </b>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_4_itinfrastructure_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.5</b></td>
	<td valign="top"><b>Provide details of library and resources for this programme.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_5_library_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.6</b></td>
	<td valign="top"><b>Detail student access options to library, facilities and equipment.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_6_studentaccess_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.7</b></td>
	<td valign="top"><b>Provide details of maintenance and upgrade of facilities and resources.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_7_maintenance_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>7.8</b></td>
	<td valign="top"><b>Provide details of training provided to both staff and students in IT and usage
	of the library and other resource facilities.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("7_8_resourcetraining_text") ?></td>
</tr>
</table>
<br><br>


<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>


	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Financial plan for the maintenance and upgrading of infrastructure/resources:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_financialplan_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Proposed or actual library holdings/budget specific to programme:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_librarybudget_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Specialised equipment or infrastructure specific to this programme:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_specialequip_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Occupational Health and Safety Certificate:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_safetycertificate_doc") ?><br></td>
				</tr>
			</table>
		</li>

		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour">
					<b>Any other documentation which will indicate your compliance with this criterion.</b><br>
					</td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>



</fieldset>
<br><br>
</td></tr></table>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>

<hr>
