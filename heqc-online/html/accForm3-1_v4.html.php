
<?php 
	//$ins_id = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
	$ins_id  = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$inst_name = $this->getInstitutionName($ins_id);
	
	$this->formFields["institution_id"]->fieldValue = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$this->showField("institution_id");
	
	 $app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	 
	// echo $app_id;

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
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION C: SITE OF DELIVERY</h2>
</span>
</td></tr>
</table>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
	<td>
	Refer to the accompanying <a href="documents/GUIDELINES FOR COMPLETING THE APPLICATION FOR PROGRAMME ACCREDITATION AND QUALIFICATION REGISTRATION.docx" target="_blank">
			 guidelines </a> for completion of this form
		<img src="images/word.gif">
	</td>
</tr>
<tr>
	<td>
	<br>
	<b>PROGRAMME / QUALIFICATION INFORMATION:</b>
	<br><br>
	</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="left" width="40%"><b><b>Institution Name:</b></td>
			<td valign="top" class="oncolour">&nbsp;<?php echo($inst_name); ?></td>
		</tr>
		<tr>
			<td align="left" width="40%"><b>Programme / qualification title (HEQSF-aligned format):</b></td>
			<td valign="top" class="oncolour"><?php echo $this->showField("program_name");?></td>
		</tr>
		<tr>
			<td align="left" valign="top"><b>Programme / qualification title abbreviation (HEQSF-aligned format):</b></td>
			<td valign="top" class="oncolour"><?php echo  $this->showField("program_abbr") ?></td>
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
	<b>
	Select the site/s of delivery for the programme/qualification by clicking on the site name in Sites for your Institution and then on the arrow.
	</b>
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
				file_put_contents('php://stderr', print_r("URL : ".$SQL, TRUE));
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
