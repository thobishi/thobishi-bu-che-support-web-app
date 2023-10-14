<?php 
	$ins_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->formFields["institution_id"]->fieldValue==0) $this->formFields["institution_id"]->fieldValue = $ins_id;
	$this->showField("institution_id");
?>

<script>
	function displayIfOther(value, div_id) {
		if (value == 5) {
			document.getElementById(div_id).style.display="block";
		}
		else {
			document.getElementById(div_id).style.display="none";
		}
	}

</script>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<b>B. APPLICATION FORM FOR PROGRAMME ACCREDITATION:</b>
		<br><br>
	</td>
</tr>
<tr>
	<td>
		The first part of the form requires information about the programme submitted for accreditation.
		Once the application is submitted a reference number will be issued.   This reference number is
		for use in subsequent correspondence.
	</td>
</tr>
<tr>
	<td>
	<br>
	<b>PROGRAMME INFORMATION:</b>
	<br><br>
	</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="left" width="40%"><b>Programme Name:</b></td>
			<td valign="top" class="oncolour"><?php echo $this->showField("program_name");?></td>
		</tr>
		<tr>
			<td align="left" valign="top"><b>Mode of Delivery:</b></td>
			<td valign="top" class="oncolour"><?php echo  $this->showField("mode_delivery") ?></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php 
				$displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'mode_delivery', '5') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'mode_delivery', '5') : "none";
			?>
			<div id="specify_other_div" style="display:<?php echo $displayStyle?>">
				<table width="100%" border=0 align="center" cellpadding="2" cellspacing="0">
					<tr>
						<td align="left" valign="top" width="40%"><b>Other (specify):</b></td>
						<td valign="top" class="oncolour"><?php echo $this->showField("mode_delivery_specify_char") ?></td>
					</tr>
				</table>
			</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td>
		Please indicate all delivery sites for the proposed programme.  (Tuition Centres to be used for
		Distance Education should not be listed in this form.)
	</td>
</tr>
<tr>
	<td>
		<table width="550" cellpadding="2" cellspacing="2" border="0" align="center">

		<tr>
			<td colspan="3" align="right">
				<!--<b>Number of sites:</b>--><b><?php echo $this->showField("noOfSites") ?></b>
			</td>
		</tr>

		<tr>
				<td valign="top" class="oncolour" width="300">
				<b>Sites for your Institution</b>
				<br>
				<SELECT name="sites_select" style="width:295;height:200;" MULTIPLE>
<?php
                                $conn = $this->getDatabaseConnection();
				$SQL = <<<sites
					SELECT *
					FROM institutional_profile_sites
					WHERE institution_ref=$ins_id
					AND institutional_profile_sites_id NOT IN
						(SELECT sites_ref FROM lkp_sites,institutional_profile_sites
						 WHERE institutional_profile_sites_id = sites_ref
						 AND application_ref = "'$app_id'"
						 AND institution_ref = $ins_id)
sites;
                            //    $stmt = $conn->prepare($SQL);
                             //   $stmt->bind_param("sss", $ins_id, $app_id, $ins_id);
                             //   $stmt->execute();
                             //   $rs = $stmt->get_result();
				$rs = mysqli_query($conn, $SQL);

				while ($row = mysqli_fetch_array($rs)) {
					$site = $row["site_name"];
					$location = $row["location"];
					echo '<OPTION value="'.$row["institutional_profile_sites_id"].'">'.$site." - ".$location.'</OPTION>';
				}
				//$stmt->close();
?>
				</SELECT>
				</td>

				<td width="30" align="center">

				<a href="javascript:addSites();">
					<img src="images/btn_insert.gif" width="33" height="22" border="0" alt="Insert">
				</a>
<?php

		$n = $this->getNoOfSitesForApplication($app_id);
		if ($app_id == 'NEW' || $n == 0){
?>
				<br><br>
				<a href="javascript:removeSites();">
					<img src="images/btn_remove.gif" width="33" height="22" border="0" alt="Remove">
				</a>
<?php
		}
?>
				</td>

				<td valign="top" class="oncolour" width="220">
				<b>This programme is offered at these sites</b>
				<?php $this->showField("resultsSelect") ?>
				</td>
			</tr>

		</table>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
