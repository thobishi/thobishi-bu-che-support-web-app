<?php 
	$search_institution = readPost('search_institution');
	$search_site = readPost('search_site');
	$search_che_ref = readPost('search_che_ref');
	$search_programme = readPost('search_programme');
	$this->formFields['search_institution']->fieldValue = $search_institution;
	
?>
	<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
	<tr>
		<td colspan='10'>
			<span class="loud">Programmes per site:</span><br>
			<span class="specialb">(provisionally accredited,  provisionally accredited with conditions, not accredited)</span>
		</td>
	</tr>
	<tr>
		<td>Institution: <?php $this->showField('search_institution'); ?></td>
	</tr>
<!--
	<tr>
		<td>Site: <?php//$this->showField('search_site'); ?></td>
	</tr>
	<tr>
		<td>CHE reference code: <?php//$this->showField('search_che_ref'); ?></td>
	</tr>
	<tr>
		<td>Programme name: <?php//$this->showField('search_programme'); ?></td>
	</tr>
-->
	<tr>
		<td>
			<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
			<input type="hidden" class="btn" name="reportProgrammes" value="Search" onClick="moveto('stay');">

		</td>
	</tr>
	</table>

<?php 

if (isset($_POST["reportProgrammes"])){
	$instcrit_arr = array();
	if ($search_institution > 0) {
		array_push($instcrit_arr,'HEI_id = '.$search_institution);
	}

	$html = "";

	$crit= "";
	if (count($instcrit_arr) > 0){
		$crit = "WHERE " . implode(" AND ", $instcrit_arr);
	}
	$sql = <<<SITEOFFER
		SELECT HEI_id, HEI_name, HEI_code
		FROM HEInstitution
		$crit
		ORDER BY HEI_name
SITEOFFER;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

	$rs = mysqli_query($conn, $sql); // or die(mysqli_error());
	if ($rs){
		$prev = "";
		while ($row = mysqli_fetch_array($rs)){
			$inst = $row['HEI_name'] . "(" . $row['HEI_code'] . ")";
			$progs_html = "";

			$sites = getSites($row['HEI_id']);
			$n_sites = count($sites);

			$sites_html = "No sites have been captured in the institutional profile";
			if ($n_sites > 0){
				$sites_html = <<<SITES
					No. of sites: $n_sites
					<table width="95%" class="saphireframe" align="left" border="1">
					<tr class="doveblox">
						<td>Site</td><td>Location</td><td>address</td><td>Contact name</td><td>Email</td><td>Telephone</td><td>Fax</td>
					</tr>
SITES;

				foreach($sites as $s){
					$sites_html .= <<<SITES
						<tr>
							<td>$s[site_name]</td><td>$s[location]</td><td>$s[address]</td>
							<td>$s[contact_name]</td><td>$s[contact_email]</td><td>$s[contact_nr]</td><td>$s[contact_fax_nr]</td>
						</tr>
SITES;
				}
				$sites_html .= "</table>";
			}
			
			foreach ($sites as $s){
				$progs = $this->getProgrammesForSite($s['institutional_profile_sites_id']);
				$n_progs = (count($progs) > 0) ? "No. of programmes: ".count($progs) : "No programmes have been assigned to this site";
				$site_loc = ($s['location'] > "") ? $s['location'] : $s['site_name'];
				$progs_html .= <<<PROGS
					<tr><td>
						<br><i>Site: $site_loc</i>
						<br>$n_progs
PROGS;

				if (count($progs) > 0){
					$progs_html .= <<<PROGS
						<table width="95%" class="saphireframe" align="left" border="1">
						<tr class="doveblox">
							<td>CHE reference code</td><td>Programme name</td><td>Outcome</td>
						</tr>
PROGS;

					foreach($progs as $p){
						$progs_html .= <<<PROGS
							<tr>
								<td>$p[program_name]</td><td>$p[CHE_reference_code]</td><td>$p[lkp_title]</td>
							</tr>
PROGS;
					}
					$progs_html .= "</table></td></tr>";
				}
			}
			
			$html = <<<TABLE
				<table  width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
				<tr>
					<td><b>Institution: $inst</b></td>
				</tr>
				<tr>
					<td>
						$sites_html
					</td>
				</tr>
				$progs_html
				</table>
				<hr>
TABLE;
			echo $html;
		}
	}
}

function getSites($inst_id){
	$sites = array();
	$sql = <<<SITES
		SELECT institutional_profile_sites_id, 
			   site_name,
			   location, 
			   address,
			   CONCAT(contact_name," ",contact_surname) AS contact_name,
			   contact_email,
			   contact_nr,
			   contact_fax_nr
		FROM institutional_profile_sites
		WHERE institution_ref = ?
		ORDER BY site_name
SITES;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        $sm = $conn->prepare($sql);
        $sm->bind_param("s", $inst_id);
        $sm->execute();
        $rs = $sm->get_result();
        
	//$rs = mysqli_query($sql) or die(mysqli_error());
	if ($rs){
		while ($row = mysqli_fetch_array($rs)){
			$sites[$row['institutional_profile_sites_id']] = $row;
		}
	}
	return $sites;
}

?>