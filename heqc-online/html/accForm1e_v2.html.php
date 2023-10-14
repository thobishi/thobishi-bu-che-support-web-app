<br>
<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>
	<tr>
		<td>Below are the details of the site you selected to be removed.
		<br>
		<span class="visi">If you elect to remove this site, note that ALL references to this site will also be removed - any documents, answered questions, etc.</span>
		<br><br>
		<hr>
		</td>
	</tr>
</table>
<?php

	$site_id = $this->dbTableInfoArray["institutional_profile_sites"]->dbTableCurrentID;

	$SQL =<<<SQL
		SELECT * FROM institutional_profile_sites WHERE institutional_profile_sites_id=?
SQL;

        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
        $sm = $conn->prepare($SQL);
        $sm->bind_param("s", $site_id);
        $sm->execute();
        $rs = $sm->get_result();
        
        //$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		echo "<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>";
		while ($row = mysqli_fetch_array($rs)) {
?>
		<tr>
			<td colspan="2">
				<span class="specialb">Site details:</span>
			</td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' width="20%">Site name: </td>
			<td class='oncolour'><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"site_name"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb'>Location: </td>
			<td class='oncolour'><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"location"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb'>Established: </td>
			<td class='oncolour'><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"establishment"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Physical address: </td>
			<td class='oncolour'><?php echo simple_text2html($this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"address")); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Postal address: </td>
			<td class='oncolour'><?php echo simple_text2html($this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"postal_address")); ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<br>
				<span class="specialb">Contact Person's Details for this site:</span>
			</td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person title: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_title_ref"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person name: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_name"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person surname: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_surname"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person email: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_email"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person telephone number: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_nr"); ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person fax number: </td>
			<td class='oncolour' valign="top"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id,"contact_fax_nr"); ?></td>
		</tr>
		<tr>
			<td colspan="2"><br><hr></td>
		</tr>
<?php 
		}
		echo "</table>";
	}
?>

<table cellpadding='15' cellspacing='2' width='25%' border='0' align='center'>
	<tr>
		<td align="right">
		<input type="button" value="Confirm removal" onClick="javascript:removeSite('Are you sure?')"></td>
	</tr>
</table>

<br>

<script>
	function removeSite(message) {
		if(confirm(message)) alert("you've removed a site");
	}
</script>
