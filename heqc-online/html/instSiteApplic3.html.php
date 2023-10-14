<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_application"]->dbTableCurrentID;
	$proceedings = 'false';
	if (isset($this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID) 
			&& $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID != 'NEW'){
		$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
		$proceedings = 'true';
	}
	$inst_id = $this->getValueFromTable("inst_site_application","inst_site_app_id", $site_app_id, "institution_ref");
	$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");

	$this->formFields["institution_ref"]->fieldValue = $inst_id;
	$this->showField("institution_ref"); 
	
	if (!($this->formFields["inst_site_app_ref"]->fieldValue > 0)){
		$this->formFields["inst_site_app_ref"]->fieldValue = $site_app_id;
	}
	$this->showField("inst_site_app_ref"); 

	$sites_valid_ind = 1;
	if ($proceedings == 'false'){
		$sites_valid_ind = 0;
	}

	$this->formFields["sites_valid_ind"]->fieldValue = $sites_valid_ind;
	$this->showField("sites_valid_ind");	
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td valign='top'>Institution</td>
				<td class='oncolourb'><?php echo $inst_name; ?></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Select sites
	</td>
</tr>
<tr>
	<td>
		Select type of proceedings: <?php $this->showField("lkp_site_proceedings_ref"); ?><span class="visi">Please note that this description will appear in the recommendation</span>
	</td>
</tr>
<tr>
	<td>
		The following sites have been selected for site visits.  If a site visit will no longer take place please click on Delete.
		Please note that all site visit information will be deleted.
		<br>
<?php

		$sched_site_arr = array();
		$sched_site_list = '';
		$html_rows = '<tr><td colspan="9">No sites have been selected for site visits for this institution</td></tr>';
		$html_site_rows = '';
		$imgPath = "/images";

		if ($proceedings == 'true'){
			// Identify sites that have been scheduled for a site visit for this application
			// Site visits can take place at different times and for different reasons for the same institution
			// Several site visits are grouped together into one site visit application so that they can be processed together 
			// and not one site at a time.
			$sql_sched = <<<SQLSCHED
				SELECT * 
				FROM inst_site_visit, institutional_profile_sites 
				WHERE inst_site_visit.institutional_profile_sites_ref = institutional_profile_sites.institutional_profile_sites_id
				AND inst_site_app_proc_ref = $site_app_proc_id
				AND inst_site_visit.institution_ref = $inst_id
SQLSCHED;
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			//$sm = $conn->prepare($sql_sched);
			//$sm->bind_param("ss", $site_app_proc_id, $inst_id);
			//$sm->execute();
			//$rs_sched = $sm->get_result();


			$rs_sched = mysqli_query($conn, $sql_sched);
			$num_sched = mysqli_num_rows($rs_sched);
			if ($num_sched > 0){
				$html_rows = "";
				while ($row = mysqli_fetch_array($rs_sched)):
					$site_visit_id = $row["inst_site_visit_id"];
					$site_id = $row["institutional_profile_sites_ref"];
					$name = $row["site_name"];
					$location = $row["location"];
					$established = $row["establishment"];
					$jscriptRemove = $this->scriptGetFormrmv_site("inst_site_visit", $site_visit_id , "_label_site_visit1");
					$rmv_site = <<<REMOVE
						<td width="5%" align='center'>
							<a href='$jscriptRemove'>
								<img src="$imgPath/ico_cancel.gif" border=0>
							</a>
						</td>
REMOVE;
					$html_rows .=<<< HTML
						<tr class='onblue'>
							<td valign="top">$location, $name ($established)</td>
							<td>$row[address]  </td>
							<td>$row[postal_address]</td>
							<td>$row[contact_name]</td>
							<td>$row[contact_surname]</td>
							<td>$row[contact_email]</td>
							<td>$row[contact_nr]</td>
							<td>$row[contact_fax_nr]</td>
							<td>$rmv_site</td>
							
						</tr>
HTML;
				array_push($sched_site_arr, $site_id);
				endwhile;
			} 
		}
	
	
		$html = <<<hhtml
			<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>
			<tr>
				<td class='onblueb'>Site</td>
				<td class='onblueb'>Physical address</td>
				<td class='onblueb'>Postal address</td>
				<td class='onblueb'>Contact<br>Name</td>
				<td class='onblueb'>Contact<br>Surname</td>
				<td class='onblueb'>Contact<br>Email</td>
				<td class='onblueb'>Contact<br>Tel.No.</td>
				<td class='onblueb'>Contact<br>Fax No.</td>
				<td class='onblueb'>Delete site visit</td>
			</tr>
			$html_rows
			</table>
hhtml;
		echo $html;

		$sched_site_list = implode(",",$sched_site_arr);
		$where = '';
		if ($sched_site_list > '') {
			$where = "AND institutional_profile_sites.institutional_profile_sites_id NOT IN ($sched_site_list)";
		}

		// Identify unscheduled sites for the institution
		$sql_sites = <<<SQLSITES
			SELECT * 
			FROM institutional_profile_sites
			WHERE institutional_profile_sites.institution_ref = $inst_id
			$where
SQLSITES;

		$rs_sites = mysqli_query($this->getDatabaseConnection(),$sql_sites);
		$num_sites = mysqli_num_rows($rs_sites);
		if ($num_sites > 0){
			while ($row = mysqli_fetch_array($rs_sites)):
				$site_id = $row["institutional_profile_sites_id"];
				$name = $row["site_name"];
				$location = $row["location"];
				$established = $row["establishment"];
				$chk = '<input type="Checkbox" name="site_id[]" value="'.$row["institutional_profile_sites_id"].'">';
				
				$html_site_rows .=<<< HTML
					<tr class='onblue'>
						<td valign="top">$location, $name ($established)</td>
						<td>$row[address]</td>
						<td>$row[postal_address]</td>
						<td>$row[contact_name]</td>
						<td>$row[contact_surname]</td>
						<td>$row[contact_email]</td>
						<td>$row[contact_nr]</td>
						<td>$row[contact_fax_nr]</td>
						<td>$chk</td>
					</tr>
HTML;
			endwhile;
		} else {
			$html_site_rows = '<tr><td colspan="9">No more sites are available for selection for this institution</td></tr>';
		}
?>
	<br>
	<hr>
	<br>
		The following are the sites for the institution that have not been selected for a site visit.  
		Please check the box next to the sites for which to schedule site visits and click on <span class="specialb">Continue</span> in the Actions menu.
	<br>
	<br>
<?php
	$html_site = <<<hhtml
		<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>
		<tr>
			<td class='onblueb'>Site</td>
			<td class='onblueb'>Physical address</td>
			<td class='onblueb'>Postal address</td>
			<td class='onblueb'>Contact<br>Name</td>
			<td class='onblueb'>Contact<br>Surname</td>
			<td class='onblueb'>Contact<br>Email</td>
			<td class='onblueb'>Contact<br>Tel.No.</td>
			<td class='onblueb'>Contact<br>Fax No.</td>
			<td class='onblueb'>
				Select<br>
				<a href="javascript:checkall(document.defaultFrm.elements['site_id[]'],true);"><span class="special"><i>Select All</i></span></a>
				<br>
				<a href="javascript:checkall(document.defaultFrm.elements['site_id[]'],false);"><span class="special"><i>Deselect All</i></span></a>
			</td>
		</tr>
		$html_site_rows
		</table>
hhtml;
	echo $html_site;	
?>
	</td>
</tr>
</table>