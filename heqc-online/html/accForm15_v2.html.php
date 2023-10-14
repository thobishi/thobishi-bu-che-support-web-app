<a name="application_form_question4"></a>
<br>
<?php
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$prov_type = $this->checkAppPrivPubl($current_id);

	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");


?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>7. INFRASTRUCTURE AND LIBRARY RESOURCES: (Criterion 7) </b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm15_v2", "minimumStandards"); ?>
</fieldset>
<br><br>

<?php

	switch ($prov_type) {
	case "1" :  echo $this->buildSiteCriteriaEditforApplication($current_id,'7');
				break;
	case "2" : 	echo $this->getTextContent("accForm15_v2", "publicRegistrarDeclaration");
			//displays the declaration if the user is administrator
				//$admin_id = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
			$user_arr = $this->getInstitutionAdministrator($current_id);
			if ($user_arr[0]==0){
				echo "Processing has been halted for the following reason: <br><br>";
				echo $user_arr[1];
			}
			if (($this->currentUserID == $user_arr[0]) || ($hei_id == 2)) {
				$this->buildRegistrarDeclarationForCriterion($current_id, "7");
			}
			break;
	}
?>


</td>
</tr>
</table>

<hr>