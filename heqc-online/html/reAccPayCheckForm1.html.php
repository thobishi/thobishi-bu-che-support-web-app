<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id); ?>
		<?php $this->showField("reaccreditation_application_ref"); ?>
		<?php $this->showField("invoice_total"); ?>
		<br>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<br>
			<b>Private providers are charged for the re-accreditation of their programmes.</b>
			<br>
			<br>
			Providers have to pay the total costs of the re-accreditation process as invoiced to them for the results of
			the evaluation to be released to them.
			<br>
			<br><b>You need to calculate the re-accreditation fee for the above provider. At this stage the provider is 
			expected to pay the fee for programme accreditation, a fee for re-accreditation administration and the 
			correspondent fee for each extra site of delivery at which the programme will be offered.</b>
			<br>
			<br>
			Should a site visit be necessary, the provider will be invoiced separately with the direct costs of travel,
			accommodation and subsistence of the evaluators once the site visit has taken place.
			</b>
			</td>
		</tr>
		<tr>
			<td><br><b>Calculate Payment:</b></td>
		</tr>
		</table>
		<table align="center" width="70%">
		<tr>
			<td>Programme accreditation: </td>
			<td colspan="2"><?php $this->showField("programme_fee")?></td>
			<td>&nbsp;</td>
		</tr>
		<?php 
		// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
		// RTN 24/10/2007 - Re-instate payment per additional site that the programme is offered at.
		?>
		<tr>
			<td>Programme fee for additional site of delivery:</td>
			<td colspan="2"><?php $this->showField("prog_fee_additional_sites"); ?></td>
		</tr>
		<tr>
			<td valign="top">This fee includes the following sites:</td>
			<td valign="top" colspan="2"><?php echo (($sites > "")?($sites):("-- No additional sites --"))?></td>
		</tr>
		<tr>
			<td>
			<b>New providers only:</b>
			</td>
			<td colspan="2">
			<b>&nbsp;</b>
			</td>
		</tr>
		<tr>
			<td>Re-accreditation administrative fee per institution:</td>
			<td><?php $this->showField("new_inst_fee"); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php// RTN 24/10/2007 - Remove once off payment per additional site because it is charged per programme
		//<tr>
		//	<td align="right">Fee for additional sites of delivery:</td>
		//	<td colspan="2"> $this->showField("site_fee"); </td>
		//</tr>
		?>
		</table>
	</td>
</tr>
</table>


