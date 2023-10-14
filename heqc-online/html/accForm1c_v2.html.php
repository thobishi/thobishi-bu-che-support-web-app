<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

	<br>
<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($app_id); }

	$heiID  = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$programmeName = $this->getValueFromTable('Institutions_application','application_id',$app_id,'program_name');

	$path = "";
?>
	You have specified that the programme <b>"<?php echo $programmeName;?>"</b> will be offered at all the sites displayed below.
	Please note that further in the application form you will be required to enter certain data per site.
	
<!-- 2010-08-02 Robin - Adding option to delete a site.
	<br>
	Please confirm that this is correct before clicking on Next.  Once you have clicked on Next and proceeded you will not
	be able to return to the preceding page and reselect the sites.
	<br>
-->
	
	Please ensure that the contact details are correct for each site below.
	<ul>
		<li>Click on <img src="<?php echo $path?>images/ico_change.gif" border=0> to update the details for a particular site.</li>
		<li>If the programme (<b>"<?php echo $programmeName;?>"</b>) is not going to be offered at a particular site anymore, please click <img src="<?php echo $path?>images/ico_cancel.gif" border=0>.
		Clicking this will take you to a page where you can confirm that you wish to delete all details for this site.</li>
		<!--li>To add to the list of sites that the programme will be offered at, click the <img src="<?php echo $path?>images/ico_send.gif" border=0> button.</li-->
	</ul>
<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL =<<<SQL
		SELECT * FROM lkp_sites
		LEFT JOIN institutional_profile_sites
		ON institutional_profile_sites_id = sites_ref
		WHERE application_ref = $app_id
SQL;
        //$stmt = $conn->prepare($SQL);
        //$stmt->bind_param("s", $app_id);
       // $stmt->execute();
       // $rs = $stmt->get_result();
        
	$rs = mysqli_query($conn,$SQL);
	if (mysqli_num_rows($rs) > 0) {

		$html_rows = "";
		$rmv_head = "";
		$imgPath = $path."images";

		while ($row = mysqli_fetch_array($rs)) {
			$id = $row["institutional_profile_sites_id"];
			$app_site_id = $row["lkp_sites_id"]; 
			$name = $row["site_name"];
			$location = $row["location"];
			$established = $row["establishment"];

			// Robin 2010-08-03: Get id for ia_criteria_per_site if any site info has been captured.  If no id is found
			// then user has not proceeded and site can be deleted on previous page. If an id is found then remove site 
			// button must be added to allow the user to remove the site safely.
			$ia_num = 0;
			$rmv_site = "";
			$ia_sql = <<<SITE
				SELECT ia_criteria_per_site_id
				FROM ia_criteria_per_site
				WHERE application_ref = $app_id
				AND institutional_profile_sites_ref = $id
SITE;
                        //$stmt = $conn->prepare($ia_sql);
                        //$stmt->bind_param("ss", $app_id, $id);
                        //$stmt->execute();
                        //$ia_rs = $stmt->get_result();
			
			$ia_rs = mysqli_query($conn,$ia_sql);
			$ia_num = mysqli_num_rows($ia_rs);
			if ($ia_num == 1){
				$ia_row = mysqli_fetch_array($ia_rs);
				$ia_id = $ia_row["ia_criteria_per_site_id"];

				$rmv_head = "<td class='onblueb' align='center'>Remove programme<br>from site</td>";
				$jscriptRemove = $this->scriptGetForm("ia_criteria_per_site", $ia_id, "_labelRemoveSite");
				$rmv_site = <<<REMOVE
					<td width="5%" align='center'>
						<a href='$jscriptRemove'>
							<img src="$imgPath/ico_cancel.gif" border=0>
						</a>
					</td>
REMOVE;
			}

			$jscript = $this->scriptGetForm("institutional_profile_sites", $id, "_labelEditSiteContactDetails");
			$jscriptRemove = $this->scriptGetForm("lkp_sites", $app_site_id, "_labelRemoveSite");
			$html_rows .=<<<HTML
				<tr class='onblue'>
					<td width="5%" align='center'>
						<a href='$jscript'>
							<img src="$imgPath/ico_change.gif" border=0>
						</a>
					</td>
					<td valign="top">$location, $name ($established)</td>
					<td>$row[contact_name]</td>
					<td>$row[contact_surname]</td>
					<td>$row[contact_email]</td>
					<td>$row[contact_nr]</td>
					<td>$row[contact_fax_nr]</td>
					$rmv_site
				</tr>
HTML;

		}
		$html = <<<hhtml
			<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>
			<tr>
				<td class='onblueb' align='center'>Edit contact<br>details</td>
				<td class='onblueb'>Site</td>
				<td class='onblueb'>Contact<br>Name</td>
				<td class='onblueb'>Contact<br>Surname</td>
				<td class='onblueb'>Contact<br>Email</td>
				<td class='onblueb'>Contact<br>Tel.No.</td>
				<td class='onblueb'>Contact<br>Fax No.</td>
				$rmv_head
			</tr>
			$html_rows
			</table>
hhtml;
		echo $html;
	}
	else
	{
		echo "<table><tr><td></td></tr></table>";
	}
?>
<br>


</td></tr></table
