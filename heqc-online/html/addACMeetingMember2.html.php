<?php //print_r($this->dbTableInfoArray['AC_Members']->dbTableCurrentID);?>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		$this->getCMD_action($cmd);

		echo '<!--script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script-->';
	}
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "Fill in the AC member details below:<br><hr>";
	$AC_mem_id = $this->dbTableInfoArray["AC_Members"]->dbTableCurrentID;
?>
<br>

</td></tr>
<tr><td>

	<table border='0'>
	<tr>
		<td align="right">Title:</td>
		<td><?php echo $this->showField("ac_mem_title_ref")?></td>
	</tr>
	<tr>
		<td align="right">Name:</td>
		<td><?php echo $this->showField("ac_mem_name")?></td>
	</tr>
	<tr>
		<td align="right">Surname:</td>
		<td><?php echo $this->showField("ac_mem_surname")?></td>
	</tr>
	<tr>
		<td align="right">E-mail:</td>
		<td><?php echo $this->showField("ac_mem_email")?></td>
	</tr>
	<tr>
		<td align="right">Telephone:</td>
		<td><?php echo $this->showField("ac_mem_tel")?></td>
	</tr>
	<tr>
		<td align="right">Fax:</td>
		<td><?php echo $this->showField("ac_mem_fax")?></td>
	</tr>
	<tr>
		<td align="right" valign="top">Postal Address:</td>
		<td><?php echo $this->showField("ac_mem_postal")?></td>
	</tr>
	<tr>
		<td align="right" valign="top">Physical Address:</td>
		<td><?php echo $this->showField("ac_mem_physical")?></td>
	</tr>
	<tr>
		<td align="right">Status:</td>
		<td><?php echo $this->showField("ac_mem_active")?></td>
	</tr>
	<tr>
		<td align="right" valign="top">Restrictions:</td>
		<td>

			<table width="100%" align="left" cellpadding="2" cellspacing="2" border="0">
			<?php
				$dFields = array();

				//Rebecca (2007-01-03): need to edit code if multiple restriction types are implemented
				array_push($dFields, "type__select|name__lkp_restriction_type_ref|description_fld__lkp_restriction_type_desc|fld_key__lkp_restriction_type_id|lkp_table__lkp_restriction_type|lkp_condition__lkp_restriction_type_id > 0|order_by__lkp_restriction_type_desc");


				array_push($dFields, "type__select|name__restricted_field_id|description_fld__HEI_name|fld_key__HEI_id|lkp_table__HEInstitution|lkp_condition__HEI_id NOT IN (1, 2)|order_by__HEI_name");
				$hFields = array("Restrict by", "Member may not view:");


				$this->gridShowRowByRow("lkp_AC_member_restrictions", "lkp_AC_member_restrictions_id", "AC_member_id__".$AC_mem_id, $dFields,$hFields, 10, 5, "true", "true", 0);
			?>
			</table>

		</td>
	</tr>
	</table>

</td></tr>
</table>
<br>
<td><?php echo $this->showField("ac_mem_id")?></td>



