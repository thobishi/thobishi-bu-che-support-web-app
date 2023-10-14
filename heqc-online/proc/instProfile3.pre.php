<?php
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	//$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	$SQL = <<<SQL
		SELECT * 
		FROM `institutional_profile_sites` 
		WHERE main_site = 0 
		AND institution_ref = {$inst} 
		ORDER BY location
SQL;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$rs = mysqli_query($conn, $SQL);
	$content = <<<CONTENT
		<tr class="oncolourb">
			<td>&nbsp;</td>
			<td>Site name</td>
			<td>Year</td>
			<td>Physical Address</td>
			<td>Postal Address</td>
			<td>Contact</td>
			<td>Email</td>
			<td>Telephone</td>
			<td>Fax</td>
			<td>No.<br>programmes<br></td>
		</tr>
CONTENT;
	while ($row = mysqli_fetch_array($rs)) {
		$alink = "&nbsp;";
		$site_id = $row["institutional_profile_sites_id"];
		if ($site_id > 0){
			$link = $this->scriptGetForm ('institutional_profile_sites', $site_id, '_startEditAdditionalSites');
			$alink = "<a href='".$link."'>Edit</a>";
		}
		$address = ($row["address"] > "") ? $row["address"] : "&nbsp;";
		$postal_address = ($row["postal_address"] > "") ? $row["postal_address"] : "&nbsp;";
		$contact_email = ($row["contact_email"] > "") ? $row["contact_email"] : "&nbsp;";
		$contact_nr = ($row["contact_nr"] > "") ? $row["contact_nr"] : "&nbsp;";
		$contact_fax_nr = ($row["contact_fax_nr"] > "") ? $row["contact_fax_nr"] : "&nbsp;";

		$nr_apps = get_number_applications($site_id);
		$site_status_ref = ($row["site_status_ref"] > "") ? $row["site_status_ref"] : "&nbsp;";
		$dlink = $nr_apps;
		if (($site_status_ref == 'new' || $site_status_ref == "&nbsp;") && $nr_apps == 0){
			$dlink = '<a href="javascript:document.defaultFrm.DELETE_RECORD.value=\'institutional_profile_sites|institutional_profile_sites_id|'.$site_id.'\';moveto(\'stay\');">Delete</a>';
		}
		$content .= <<<CONTENT
			<tr valign="top">
				<td>{$alink}</td>
				<td>{$row["site_name"]} - {$row["location"]}</td>
				<td>{$row["establishment"]}</td>
		        <td>{$address}</td>
				<td>{$postal_address}</td>
		        <td>{$row["contact_name"]} {$row["contact_surname"]}</td>
				<td>{$contact_email}</td>
				<td>{$contact_nr}</td>
				<td>{$contact_fax_nr}</td>
				<td>{$site_status_ref}<br />$dlink</td>
			</tr>
CONTENT;
	}

	function get_number_applications($site_id){
		$nr_apps = 0;
		if ($site_id > 0){
			$sql = <<<SQL
				SELECT count(*) AS total
				FROM lkp_sites
				LEFT JOIN Institutions_application ON Institutions_application.application_id = lkp_sites.application_ref
				WHERE sites_ref = {$site_id} 
				AND Institutions_application.submission_date > '1970-01-01'
SQL;
                        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                        if ($conn->connect_errno) {
                            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                            printf("Error: %s\n".$conn->error);
                            exit();
                        }
			$rs = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($rs);
			$nr_apps = $row["total"];
		}
		return $nr_apps;
	}
?>