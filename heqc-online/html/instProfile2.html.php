<?php 
if (!($this->formFields["site_name"]->fieldValue > "")) {
	$this->formFields["site_name"]->fieldValue = "Main Campus";
}
?>
<?php
$provider = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<span class="specialb">Main Campus:</span>
<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">

<tr>
	<td width="30%" align="right"></td>
	<td width="70%">
		<?php
		$value=$this->formFields["institution_ref"]->fieldValue;

		$SQL = "SELECT COUNT(submission_date) from Institutions_application where institution_id = $value AND submission_date > '1000-01-01'";
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		file_put_contents('php://stderr', print_r($rs, TRUE));
		
		if ( $row = mysqli_fetch_array ($rs) ) {
			$count = $row["COUNT(submission_date)"];
		}			

		if($count > 0)
			{
				echo "<p style='color:red; font-size: 15px; font-weight: bold;' > Disabled fields cannot be changed in order to preserve the history. Please capture a new site of delivery on the next page. </p>";			
			}
		?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>Site Name:</b></td>
	<td width="70%" class="oncolour">&nbsp;<?php $this->showField("site_name");?>&nbsp;<i>(generally no need to change)</i></td>
</tr><tr>
	<td width="30%" align="right"><b>Location:</b></td>
	<td width="70%" class="oncolour">&nbsp;<?php $this->showField("location");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Year of Establishment:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("establishment");?> <i>(YYYY)</i></td>
</tr><tr>
	<td width="30%" align="right"><b>Physical Address:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("address");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Postal Address:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("postal_address");?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2">
	<span class="specialb">Contact Person's Details for this site:</span>
	</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td width="30%" align="right"><b>Surname:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("contact_surname");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Name:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("contact_name");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Title:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("contact_title_ref");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Email:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("contact_email");?></td>
</tr><tr>
	<td width="30%" align="right"><b>Contact Number:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("contact_nr");?></td>
<!--</tr><tr>
	<td width="30%" align="right"><b>Contact Fax Number:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("contact_fax_nr");?></td>
</tr>
		-->

<?php if ($provider == 1) {  ?>
	<tr>
	<td colspan="2">&nbsp;</td>
</tr>
	<tr>
	<td colspan="2">
	<span class="specialb">Documents for this site (for private institutions):</span>
	</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="30%" align="right"><b>Title deed or valid lease agreement :</b></td>
	<td class="oncolour">&nbsp;<?php $this->makelink("lease_or_deed_doc");?>
	</td>
</tr>
<tr>
	<td width="30%" align="right"><b>Occupational Health Safety  (OHS) Certificate</b></td>
	<td class="oncolour">&nbsp;<?php $this->makelink("ohs_certificate_doc");?></td>
</tr>
<?php } ?>
<!--
<tr>
	<td width="30%" align="right"><span class="specialb">Enrolments (headcounts):</span></td>
	<td>&nbsp;</td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Undergraduate Contact:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("enrol_under_contact");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Undergraduate Distance:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("enrol_under_distance");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Postgraduate Contact:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("enrol_post_contact");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Postgraduate Distance:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("enrol_post_distance");?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td width="30%" align="right"><span class="specialb">Number of Programmes:</span></td>
	<td>&nbsp;</td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Undergraduate Contact:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("prog_under_contact");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Undergraduate Distance:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("prog_under_distance");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Postgraduate Contact:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("prog_post_contact");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Postgraduate Distance:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("prog_post_distance");?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Full-time Academic Staff:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("full_aca_staff");?></td>
</tr><tr>
	<td valign="top" width="30%" align="right"><b>Part-time Academic Staff:</b></td>
	<td class="oncolour">&nbsp;<?php //$this->showField("part_aca_staff");?></td>
</tr>
-->

</table>


<!-- This is to diable and able the main site delivery address -->
	<?php
		$value=$this->formFields["institution_ref"]->fieldValue;

		$SQL = "SELECT COUNT(submission_date) from Institutions_application where institution_id = $value AND submission_date > '1000-01-01'";
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		file_put_contents('php://stderr', print_r($rs, TRUE));

		if ( $row = mysqli_fetch_array ($rs) ) {
			$count = $row["COUNT(submission_date)"];
		}	

		if($count > 0)
		{	
				
	?>
			<script type="text/javascript">
			
				
				// alert("hello");
				$("[name='FLD_site_name']").attr('disabled', true);
				$("[name='FLD_location']").attr('disabled', true);
				$("[name='FLD_establishment']").attr('disabled', true);
				$("[name='FLD_address']").attr('disabled', true);

			
				// $("#FLD_site_name").attr('disabled','disabled');
				// $("#FLD_location").attr('disabled','disabled');
				// $("#FLD_establishment").attr('disabled','disabled');
				// $("#FLD_address").attr('disabled','disabled');	
				
				
			</script>
	<?php		
		}	
		else
		{					
	?>
			<script type="text/javascript">
				
				$("[name='FLD_site_name']").attr('disabled', false);
				$("[name='FLD_location']").attr('disabled', false);
				$("[name='FLD_establishment']").attr('disabled', false);
				$("[name='FLD_address']").attr('disabled', false);

				
				// $("#FLD_site_name").attr('abled','abled');
				// $("#FLD_location").attr('abled','abled');
				// $("#FLD_establishment").attr('abled','abled');
				// $("#FLD_address").attr('abled','abled');			
				
			</script>
	<?php		
		}					
	?>
	

<?php $this->showField("institution_ref");?>
<?php $this->showField("main_site");?>
<br><br>
</td></tr></table>
