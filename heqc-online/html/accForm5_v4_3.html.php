

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION G: INFRASTRUCTURE, STAFFING AND HEADCOUNT ENROLMENTS PER SITE OF DELIVERY</h2>
</span>
</td></tr>
</table>
<br>

<a name="application_form_question3"></a>
<br>
<?php
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }
	$this->displayRelevantButtons($current_id, $this->currentUserID);
	$prov_type = $this->checkAppPrivPubl($current_id);
	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");


?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

<b>INFORMATION PER SITE OF DELIVERY</b><br>
<br>
<!--
<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm8_1_v2", "minimumStandards"); ?>
</fieldset>
-->
<br><br>

<?php
echo $this->buildSiteCriteriaEditforApplication($current_id,'3');
	/*switch ($prov_type) {
	case "1" :  echo $this->buildSiteCriteriaEditforApplication($current_id,'3');
				break;
	case "2" : 	
			echo $this->getTextContent("accForm8_1_v2", "publicRegistrarDeclaration");
			//displays the declaration if the user is administrator
			//$admin_id = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "user_ref");
			// Get current active institutional administrator - not user that started the application.
			$user_arr = $this->getInstitutionAdministrator($current_id);
			if ($user_arr[0]==0){
				echo "Processing has been halted for the following reason: <br><br>";
				echo $user_arr[1];
			}
			if (($this->currentUserID == $user_arr[0]) || ($hei_id == 2)) {
				$this->buildRegistrarDeclarationForCriterion($current_id, "3");
			}
			break;
	}*/
?>
<br><br>
</td>
</tr>
</table>





<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL =<<<SQL
		SELECT ia_criteria_per_site_id, institutional_profile_sites.*, ia_criteria_per_site.*
		FROM ia_criteria_per_site, institutional_profile_sites
		WHERE application_ref = $current_id
		AND institutional_profile_sites_id = institutional_profile_sites_ref 
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

			$name = $row["site_name"];
			$location = $row["location"];
			$established = $row["establishment"];

			$institutional_profile_sites_ref = $row["institutional_profile_sites_ref"];
			$ia_criteria_per_site_id = $row["ia_criteria_per_site_id"];
			$application_ref = $row["application_ref"]; 
			$progcoordname_char = $row["3_3_1_progcoordname_char"];
			$n_ft_teach_staff_current = $row["n_ft_teach_staff_current"];
			$n_ft_teach_staff_planned = $row["n_ft_teach_staff_planned"];
			$n_pt_teach_staff_current = $row["n_pt_teach_staff_current"];
			$n_pt_teach_staff_planned = $row["n_pt_teach_staff_planned"];

			// Robin 2010-08-03: Get id for ia_criteria_per_site if any site info has been captured.  If no id is found
			// then user has not proceeded and site can be deleted on previous page. If an id is found then remove site 
			// button must be added to allow the user to remove the site safely.
			$ia_num = 0;
			$rmv_site = "";
			$ia_sql = <<<SITE
				SELECT ia_criteria_per_site_id
				FROM ia_criteria_per_site
				WHERE application_ref = $application_ref
				AND institutional_profile_sites_ref = $institutional_profile_sites_ref
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
					
					<td valign="top">$location, $name ($established)</td>
					<td>$row[n_ft_teach_staff_current]</td>
					<td>$row[n_ft_teach_staff_planned]</td>
					<td>$row[n_pt_teach_staff_current]</td>
					<td>$row[n_pt_teach_staff_planned]</td>
					<td>$progcoordname_char</td>
					
				</tr>
				
HTML;


			$html_rows2 .=<<<HTML
				<tr class='onblue'>
					
					<td valign="top">$location, $name ($established)</td>
					<td>$row[n_headcount_enrol_year1_planned]</td>
					<td>$row[n_headcount_enrol_year2_planned]</td>
					<td>$row[n_headcount_enrol_year3_planned]</td>
					<td>$row[n_headcount_enrol_year4_planned]</td>
					
				</tr>
				
HTML;

		}
		$html = <<<hhtml
			<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>

			<tr>
				<th class='onblueb' rowspan="3">Site of delivery</th>
				<th class='onblueb' colspan="4">Number of teaching staff members per site for this programme/qualification</th>
				<th class='onblueb' rowspan="3">Name of Programme Coordinator per site for this programme/qualification</th>
			</tr>
			<tr>
				<th class='onblueb' colspan="2">Full-time</th>
				<th class='onblueb' colspan="2">Part-time</th>
			</tr>
			<tr>
				<th class='onblueb'>Current</th>
				<th class='onblueb'>Planned</th>  
				<th class='onblueb'>Current</th>
				<th class='onblueb'>Planned</th>                 
			</tr>

			
			$html_rows
			</table>
hhtml;
		echo $html;

		echo "<br><br>";


		$html2 = <<<hhtml2
			<table cellpadding='2' cellspacing='2' width='95%' border='0' align='center'>

			<tr>
				
				<td class='onblueb'>Site of delivery</td>
				<td class='onblueb'>Planned Headcount enrolment for the first enrolment</td>
				<td class='onblueb'>Planned Headcount enrolment for Year 2</td>
				<td class='onblueb'>Planned Headcount enrolment for Year 3</td>
				<td class='onblueb'>Planned Headcount enrolment for Year 4</td>
				
			</tr>

			
			$html_rows2
			</table>
hhtml2;
		echo $html2;

	}
	else
	{
		echo "<table><tr><td></td></tr></table>";
	}
?>
<br>



<hr>