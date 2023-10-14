<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
<?php 

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");

	$prov_type = $this->checkAppPrivPubl($current_id);

	if (($prov_type == 2) || ($hei_id == 2))  {
		//displays the declaration if the user is administrator, or from CHE
		//$admin_id = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "user_ref");
			$user_arr = $this->getInstitutionAdministrator($current_id);
			if ($user_arr[0]==0){
				echo "Processing has been halted for the following reason: <br><br>";
				echo $user_arr[1];
			}
			if (($this->currentUserID == $user_arr[0]) || ($hei_id == 2)) {
				$this->buildRegistrarDeclarationForCriterion($current_id,"final");
			}

		//do not allow to submit if final declaration not filled in
		$finalDeclarationSigned = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "final_registrarDeclaration_signed");
		if ($finalDeclarationSigned != "Yes") {
			$this->formActions["next"]->actionMayShow = false;
		}
	}
?>
		</td>
	</tr>

	<tr>
		<td>
			<br><b>Once you click on "Submit Application and Log out", your application will be sent to the HEQC Accreditation Directorate.</b>
			<br><br>Please use the following reference number in all future queries: <b><?php echo $this->getFieldValue("CHE_reference_code")?></b>
			<br><br>Please note that <font color="red">you are required to print your application form before submitting</font> for your institution's records.</b><br><br>
			You can view / print your application form by clicking on the "View / Print Application Form" in the actions menu.
			<br><br><br><br>
		</td>
	</tr>
</table>

<script>
	var printed = '<?php echo $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "application_printed");?>';
</script>
