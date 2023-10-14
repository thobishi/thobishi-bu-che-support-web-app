<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id", $site_app_id, "institution_ref");

	// Get all the sites for the institution
	$sql = <<<SQL
		SELECT * 
		FROM institutional_profile_sites
		LEFT JOIN inst_site_visit ON inst_site_visit.site_ref = institutional_profile_sites.institutional_profile_sites_id 
									AND inst_site_visit.inst_site_app_proc_ref = $site_app_id
		WHERE institutional_profile_sites.institution_ref = $inst_id
SQL;
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	//$sm = $conn->prepare($sql);
	//$sm->bind_param("ss", $site_app_id, $inst_id);
	//$sm->execute();
	//$rs = $sm->get_result();


	$rs = mysqli_query($conn, $sql);
	$n_sites = mysqli_num_rows($rs);
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<tr>
	<td>
		<br>
		<span class="specialh">Site visit application</span>
			//$this->displaySiteVisitHeader();
		<br><br>
	</td>
</tr>
<tr>
	<td>
		The institution has the following sites.  Please check the box next to the sites that must be included in this application.
	</td>
</tr>
<tr>
	<td>
<?php
		$html = <<<HTMLSTR
			<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
			<tr><td align="right" colspan="5"><b>Number of sites: $n_sites</b></td></tr>
			<tr class='onblueb'>
				<td width="40%">Site Name</td>
				<td>Site location</td>
				<td>Physical address</td>
				<td>Contact</td>
				<td>Telephone</td>
				<td>Email</td>
				<td>Opening date</td>
				<td>Closing date</td>
				<td>
					Select<br>
					<a href="javascript:checkall(document.defaultFrm.elements['site_id[]'],true);"><span class="special"><i>Select All</i></span></a>
					<br>
					<a href="javascript:checkall(document.defaultFrm.elements['site_id[]'],false);"><span class="special"><i>Deselect All</i></span></a>
				</td>
			</tr>
HTMLSTR;
		$n = 0;
		while($row = mysqli_fetch_array($rs)){
		
			$n += 1;
			$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");

			$sel = ($row["inst_site_visit_id"] > 0) ? " CHECKED" : "";
			$chk = '<input type="Checkbox" name="site_id[]" value="'.$row["institutional_profile_sites_id"].'"' . $sel .'>';

			$contact = $row["contact_name"] . " " . $row["contact_surname"];
			$html .= <<<HTMLSTR
				<tr bgcolor="$bgColor">
					<td>$row[site_name]</td>
					<td>$row[location]</td>
					<td>$row[address]</td>
					<td>$contact</td>
					<td>$row[contact_nr]</td>
					<td>$row[contact_email]</td>
					<td>$row[start_date]</td>
					<td>$row[end_date]</td>
					<td>$chk</td>
				</tr>
HTMLSTR;
		}
		$html .= <<<HTMLSTR
					</table>
HTMLSTR;
		echo $html;
?>
	</td>
</tr>
</table>