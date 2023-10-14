<br>
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
			<td class='oncolourb'><?php $this->showField("site_name") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb'>Location: </td>
			<td class='oncolourb'><?php $this->showField("location") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb'>Established: </td>
			<td class='oncolour'><?php $this->showField("establishment") ?><i> (YYYY)</i></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Physical address: </td>
			<td class='oncolourb'><?php $this->showField("address") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Postal address: </td>
			<td class='oncolourb'><?php $this->showField("postal_address") ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<br>
				<span class="specialb">Contact Person's Details for this site:</span>
			</td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person title: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_title_ref") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person name: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_name") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person surname: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_surname") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person email: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_email") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person telephone number: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_nr") ?></td>
		</tr>
		<tr>
			<td align="left" class='oncolourb' valign="top">Contact person fax number: </td>
			<td class='oncolourb' valign="top"><?php $this->showField("contact_fax_nr") ?></td>
		</tr>
<?php 
		}
		echo "</table>";
	}
?>

<br><br>

