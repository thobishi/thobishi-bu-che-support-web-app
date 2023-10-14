<?php
	$this->showField("application_ref");
	$this->showField("ia_proceedings_ref");
	
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">
			Invoice amount calculation for <?php echo $pay_desc; ?>
		</span>
		<br>
	</td>
</tr>
<tr>
	<td>
		Private providers are charged for the accreditation of their programmes.   
		<img name="cost_img" src="images/ico_plus.gif" onclick="javascript:showHide2(document.getElementById('cost'), document.getElementById('cost_img'))"> 
		<a href="javascript:showHide2(document.getElementById('cost'), document.getElementById('cost_img'));">Click here to view the Accreditation fees for Private Higher Education Institutions</a>
		<?php include("costs.html"); ?>
		<br><br>
		It is your responsibility to:
			<ul>
				<li>Check that the calculated amount below is the correct accreditation fee for the above provider and application.</li>
				<li>If necessary to pass this application to a colleague to assist you with confirming the amount. Click on 
					<span class="specialb">Send this process to a colleague</span> in the Actions menu.
				</li>
				<li>If the amount calculated by the system is incorrect contact support and log the problem.  
					Type in the correct amount and proceed.
				</li>
			</ul>
	</td>
</tr>
<tr>
	<td>
		<br>
		<table align="center" width="70%">		
<?php		if ($proc_type == 5){
?>
				<tr>
					<td align="right">Programme fee <?php echo $pay_desc; ?></td>
					<td colspan="2"><?php $this->showField("programme_fee")?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="right">Programme fee for additional sites of delivery:</td>
					<td colspan="2"><?php $this->showField("prog_fee_additional_sites")?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td valign="top">This fee includes the following sites:</td>
					<td valign="top" colspan="2"><?php echo $sites; ?></td>
				</tr>
<?php		}
?>	

<?php
if($payment_type == 1)
{
	echo '<tr>
			<td align="right">Administrative fee per programme</td>';
	echo '<td colspan="2">';		
	if(!$isPaid){ $this->showField("condition_administrative_fee_per_programme");} echo $condition_paid;
	echo '		</td>';
	echo '<td>&nbsp;</td>
</tr>';
}

?>

	
<tr>
			<td align="right">Fee <?php echo $pay_desc; ?></td>
			<td colspan="2"><?php $this->showField("proceeding_fee")?></td>
			<td>&nbsp;</td>
</tr>
<tr>
			<td align="right">Total proceeding fee <?php echo $pay_desc; ?></td>
			<td colspan="2"><?php $this->showField("invoice_total")?></td>
			<td>&nbsp;</td>
			</tr>
		</tr>
		</table>
	</td>
</tr>
</table>

