<!--a name="application_form_question1"></a-->

<a name="application_form_question16"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<br><br>
	<tr>
		<td><b>16. ADDITIONAL INSTITUTIONAL PROFILE INFO</b> <br><br></td>
	</tr> 
	<tr>
		<td colspan="2"><b>16.1</b> Provide details of all programmes offered by the institution<br>

<?php

	$instProfileId = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	// $headArr = array();
	// array_push($headArr, "");
	// array_push($headArr, "Yes / No");
	// array_push($headArr, "Comment");
	// array_push($headArr, "Upload File");

	// $fieldsArr = array();
	// array_push($fieldsArr, "type__radio|name__yes_no|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");
	// array_push($fieldsArr, "type__textarea|name__comment_text");
?>
<br>
<em>Upload a list of programmes with the following details</em>
<ul>
	<li>Full name of programme</li>
	<li>Site(s) of delivery</li>
	<li>NQF level</li>
	<li>Credit weighting</li>
	<li>Contact (C)/Distance (D)</li>
	<li>Year of first intake</li>
	<li>HEQSF-alignment Category</li>
</ul>

			
<?php
	// $this->gridShow("institutional_profile_pol_budgets_prog_offerings", "institutional_profile_pol_budgets_prog_offerings_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldsArr, $headArr, "lkp_pol_budgets_prog_offerings", "lkp_pol_budgets_prog_offerings_id", "lkp_pol_budgets_prog_offerings_desc", "lkp_pol_budgets_prog_offerings_ref", 1, 40, 10, true, "inst_uploadDoc");
				$this->makeLink("programmes_offeredDoc");
?>
			


		</td>
	</tr>

<tr>
<?php
	$che_siteVisit_headArr = array();
	array_push($che_siteVisit_headArr, "Date");
	array_push($che_siteVisit_headArr, "Address");
	array_push($che_siteVisit_headArr, "Purpose");

	$che_siteVisit_fieldArr = array();
	array_push($che_siteVisit_fieldArr, "type__date|name__latest_che_siteVisit_date");
	array_push($che_siteVisit_fieldArr, "type__textarea|name__latest_che_siteVisit_address");
	array_push($che_siteVisit_fieldArr, "type__textarea|name__latest_che_siteVisit_purpose");
?>

	<td colspan="2"><br><b>16.2</b> Provide the date, address and purpose of the most recent CHE site-visit.<br><br></td>
</tr>
<tr>
	<td>
		<?php 
			$this->gridShowTableByRow("institutional_profile_latest_che_siteVisit", "institutional_profile_latest_che_siteVisit_id", "institution_ref__". $instProfileId, $che_siteVisit_fieldArr, $che_siteVisit_headArr, 70, 10);
		?>
	</td>
</tr>
<tr>
	<td colspan="2"><b>16.3</b> Give details of the institution's facilities for learning and teaching. If applicable, provide details per site of delivery.<br><br></td>
</tr>
<tr>
	<td>
<?php 	
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$sql = "SELECT institutional_profile_sites_id,site_name 
			FROM institutional_profile_sites 
			WHERE institution_ref = ?
			ORDER BY main_site DESC";
	

	$sm = $conn->prepare($sql);
	$sm->bind_param("s", $instProfileId);
	$sm->execute();
	$rs = $sm->get_result();

	//$rs = mysqli_query($conn, $sql);
	$totalInst = mysqli_num_rows($rs);
	$instProfArr = array();
	$facilitiesFieldArr = array();
	array_push($facilitiesFieldArr, "type__text|name__number");
	array_push($facilitiesFieldArr, "type__text|name__capacity");
	array_push($facilitiesFieldArr, "type__text|name__current_usage");	
	array_push($facilitiesFieldArr, "type__text|name__total_number_student");	
	
	$facilitiesHeadArr = array();
	array_push($facilitiesHeadArr, "");
	array_push($facilitiesHeadArr, "Number");
	array_push($facilitiesHeadArr, "Capacity");
	array_push($facilitiesHeadArr, "Current usage (hours per week)");	
	array_push($facilitiesHeadArr, "Total number of students making use of the facilities");
	
	if ($totalInst > 0){

		while($row = mysqli_fetch_array($rs)){
			array_push($instProfArr, $row);
		}
	}
	if(!empty($instProfArr)){
		foreach($instProfArr as $instProf){
		echo '<table width="95%" cellpadding = "2" cellspacing = "2" align= "center" border= "1">';
		echo '<tr>';
			echo '<td colspan="2" class="loud" >Site of delivery</td>';
			echo '<td colspan="3"><b>' . $instProf['site_name'] . '</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="5">';
				$this->gridShow("institutional_profile_learning_teaching_facilities", "institutional_profile_learning_teaching_facilities_id", "institutional_profile_sites_ref__".$instProf['institutional_profile_sites_id'], $facilitiesFieldArr, $facilitiesHeadArr, "lkp_inst_profile_learning_teaching_facilities", "lkp_inst_profile_learning_teaching_facilities_id", "lkp_inst_profile_learning_teaching_facilities_desc", "lkp_inst_profile_learning_teaching_facilities_ref");
			echo '</td>';
		echo '</tr>';
		echo '</table><br>';		
		}
	}
	
?>
	</td>
</tr>

<!--<tr>
	<td colspan="2" class="loud"><b>16.4</b> Are the facilities listed above owned by the institution?<br><hr></td>
</tr>
<tr>
	<td><?php //$this->showField("facilities_LT_owned"); ?></td>
</tr>
<?php 
	// $displayStyle = "none";
	// if ($this->getValueFromTable("institutional_profile", "institution_ref", $instProfileId, "facilities_LT_owned") == "1"){ 
		// $displayStyle = "block"; 
	// }else{
		// $displayStyle = "none"; 
	// }
?>
<tr>
	<td valign="top"  class="loud">
		<div id="facilities_LT_owned_div" style = "display : <?php //echo $displayStyle; ?>">
			16.5</b> If No, who owns the facilities? Provide copy of the lease agreement as an Annexure.<hr><?php //$this->makeLink("facilities_LT_agreementDoc"); ?><br>
		</div>
	</td>
</tr>-->

<tr>
	<td colspan="2"><b>16.4</b> Please provide an organogram of the institutional management and academic structures, including quality assurance structures, clearly indicating areas and levels of responsibility, and the persons responsible.  Include this as an Annexure.<br><br></td>
</tr>
<tr>
	<td><?php $this->makeLink("inst_organogramDoc"); ?></td>
</tr>
</table>
