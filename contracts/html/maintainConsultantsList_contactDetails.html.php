<?php 
	// Managers may only view consultants that are assigned to them.
	$isManager = $this->sec_partOfGroup(3);
	// Users with the overview role are authorised to view only not to edit
	$isOverview = $this->sec_partOfGroup(4);
	if ($isManager || $isOverview) {
		$this->view = 1;
		$this->formStatus = FLD_STATUS_TEXT;
		$this->formActions["next"]->actionMayShow = 0;
	}
	
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Edit / Add Consultant Details</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
				<tr>
					<td width="30%">Type of consultant:</td>
					<td><?php echo $this->showField("type")?></td>
				</tr>
				<tr>
					<td>Contact person title:</td>
					<td><?php echo $this->showField("title")?></td>
				</tr>
				<tr>
					<td>Contact person initials:</td>
					<td><?php echo $this->showField("initials")?></td>
				</tr>
				<tr>
					<td>Contact person name:</td>
					<td><?php echo $this->showField("name")?></td>
				</tr>
				<tr>
					<td>Contact person surname:</td>
					<td><?php echo $this->showField("surname")?></td>
				</tr>
				<tr>
					<td>Contact person idnumber:</td>
					<td><?php echo $this->showField("idnumber");?></td>
				</tr>
				<tr>
					<td>Contact person gender:</td>
					<td><?php echo $this->showField("gender")?></td>
				</tr>
				<tr>
					<td>Contact person race:</td>
					<td><?php echo $this->showField("race")?></td>
				</tr>
				<tr>
					<td>Organisation/institution:</td>
					<td><?php echo $this->showField("company")?></td>
				</tr>
				<tr>
					<td>E-mail:</td>
					<td><?php echo $this->showField("email")?></td>
				</tr>
				<tr>
					<td>Contact Number:</td>
					<td><?php echo $this->showField("contact_nr")?></td>
				</tr>
				<tr>
					<td valign="top">Postal Address:</td>
					<td><?php echo $this->showField("postal_address")?></td>
				</tr>
<!-- 2009-05-27: Robin - Removed because cannot get a direct match.  Will relook at it when Pastel Evolution is used.
				<tr>
					<td colspan="2">
					The Pastel extraction criteria information is needed for the expenditure reports.  If no extraction criteria are specified then expenditure 
					will be reflected as 0.
					<i>
					<br>
					In some cases financial data cannot be extracted for a specific contract but only per consultant because
					the contract level of detail is not specified in pastel. Specify list of pastel account numbers and descriptions 
					associated with this consultant e.g. 215201,215227 or 215201:B Clarke;215203:B Clark.
					</i>
					</td>
				<tr>
					<td>Pastel extraction criteria:</td>
					<td><?php // echo $this->showField("pastel_accnumber")?></td>
				</tr>
-->
				<tr>
					<td>Status as consultant:</td>
					<td><?php echo $this->showField("status")?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>









