<?php 
	$ins_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>PROGRAMME INFORMATION:</b>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
	<b>If your institution has more than one site of delivery, please indicate at which sites the proposed programme will be delivered.<br>
		 The number of sites in the box below will change automatically once you have selected the sites where the programme will be offered.
	</b>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td ALIGN=RIGHT width="30%"><b>PROGRAMME NAME:</b></td>
	<td width="70%" class="oncolour"><?php echo $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "program_name") ?></td>
	<td></td>
</tr><tr>
	<td ALIGN=RIGHT><span class="msgn"><b>Number of sites:</b></span></td>
	<td class="oncolour"><?php $this->showField("noOfSites") ?></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center">
			<fieldset class="go">
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td>
						<span class="msgn">
						Please select the sites where the programme will be delivered from the 'Available Site(s)' box below (i)
						Click on the insert button to include the selected sites.
						<br>
						Example insert button: <img src="images/btn_insert_off.gif" width="33" height="22" alt="Insert">
						<br><br>
						The sites displayed in the 'Selected Site(s)' box (ii) will be part of the programme.
						<br><br>
						To remove a site from the list, click the remove button.
						<br>
						Example remove button: <img src="images/btn_remove_off.gif" width="33" height="22" alt="Remove">
						</span>
						</td>
					</tr>
					<tr>
						<td><span class="msgn">The sites displayed in the ‘Selected Site(s)’ box (ii) are those at which the programme will be delivered. <b>Please ensure that information specific to the sites is provided in the relevant sections of the application form.</b></span></td>
					</tr>
				</table>
			</fieldset>
	</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td ALIGN=RIGHT valign="top"><b>Sites:</b></td>
	<td><table width="550" cellpadding="2" cellspacing="2" border="0">
	<tr>
		<td valign="top" class="oncolour" width="300">
		i) <b>Available Site(s)</b>
		<br>
		<SELECT name="sites_select" size="10" style="width:295;" MULTIPLE>
<?php 
        $conn = $this->getDatabaseConnection();
	$SQL = "SELECT * FROM institutional_profile_sites WHERE institution_ref=?";
	$stmt = $conn->prepare($SQL);
        $stmt->bind_param("s", $ins_id);
        $stmt->execute();
        $rs = $stmt->get_result();
                        
	//$rs = mysqli_query($SQL);
	while ($row = mysqli_fetch_array($rs)) {
		$site = $row["site_name"];
		$location = $row["location"];
		echo '<OPTION value="'.$row["institutional_profile_sites_id"].'">'.$site." - ".$location.'</OPTION>';
	}
?>
		</SELECT></td>
		<td width="30" align="center">
		<a href="javascript:addSites();"><img src="images/btn_insert.gif" width="33" height="22" border="0" alt="Insert"></a>
		<br><br><a href="javascript:removeSites();"><img src="images/btn_remove.gif" width="33" height="22" border="0" alt="Remove"></a>
		</td>
		<td valign="top" class="oncolour" width="220">
		ii) <b>Selected Site(s)</b>
		<?php $this->showField("resultsSelect") ?></td>
		</tr>
	</table>
	</td>
</tr><tr>
	<td ALIGN=RIGHT></td>
	<td></td>
</tr></table>
<br><br>
</td></tr></table>
