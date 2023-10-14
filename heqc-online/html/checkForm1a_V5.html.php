<?php 
	$this->showField("application_ref");

	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	
	$application_ref = $this->formFields["application_ref"]->fieldValue;

	//$this->formFields["re_submission_date"]->fieldValue = $this->getValueFromTable("Institutions_application","application_id",$app_id,"submission_date");
	$documentuploaded = $this->getValueFromTable("ia_proceedings", "application_ref", $application_ref, "checklist_doc");
	
if($documentuploaded !=null  || $documentuploaded != ''   || $documentuploaded != 0){
	$this->formFields["re_submission_date"]->fieldValue = date("Y/m/d");
    $this->showField("re_submission_date");
//$new_user = $this->functionSettings ($this->getValueFromTable("processes", "processes_id", $this->getValueFromTable("work_flows", "template", $this->template, "processes_ref"), "proscess_supervisor"));


	$checklist_user= $this->getDBsettingsValue("usr_registry_screener");
	$this->formFields["checklist_user_ref"]->fieldValue = $checklist_user;
	$this->showField("checklist_user_ref");
	//$this->formFields["checklist_user_ref"]->fieldValue =$new_user;
	
}

	
	//$ia_proceedings_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	//$screening_id = $this->getValueFromTable("Institutions_application", "application_ref", $application_ref, "screening_id");
	
	//echo $screening_id ;

	$active_user_ref = $this->getValueFromTable("ia_proceedings", "application_ref", $application_ref, "checklist_user_ref");

	$re_submission_date = $this->getValueFromTable("ia_proceedings", "application_ref", $application_ref, "re_submission_date");

	//$active_user_ref = $this->getValueFromTable("Institutions_application", "application_id", $application_ref, "user_ref");

	$email = $this->getValueFromTable("users", "user_id", $active_user_ref, "email");

	$name = $this->getValueFromTable("users", "user_id", $active_user_ref, "name");

	$surname = $this->getValueFromTable("users", "user_id", $active_user_ref, "surname");
?>
<table width="95%" border=0 align="center" cellpadding="1" cellspacing="1"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="85%" border=0 >
<tr>
	<td>
		<b>
From 1 January 2022 both the CHE and SAQA will be screening the application.
</b></td>
</tr><tr>
	<td>
	<br>
	<b>1.	Screen the application using the checklist template</b>
	<ul>
		<li>If you decide to continue with this process yourself, click on the "Checklist application" link on the Actions menu.  If another checklister should do it then click on the "Send Process to Checklister" link on the Actions menu.</li>
		<li>The user who checklisted this application is displayed below</li>
	</ul>
	<table border="1" style="width:100%" >	
	<tr>
			<th>User Name</th>
			<th>E-Mail Address</th>
			<th>Date_uploaded</th>
			<th>Checklisting report</th>
		</tr>
		<tr>
			<td><?php	echo $name;  ?> </td>
			<td><?php echo $email; ?></td>
			<td><?php echo $re_submission_date ?></td>
			<td><?php $this->makeLink("checklist_doc") ?>	</td>
		</tr>
		
	</table>
	
</td>
</tr></table>
<br><br>

<table width="85%" border=0  >
<tr>
	<td><b>

</b></td>
</tr><tr>
	<td>
	<br>
	<b>2.	Upload the SAQA screening report received from SAQA for this application.</b>
	<ul>
		<li>The regulator users will be able to access this application from the Submitted applications Report on HEQC-online and screen it according to their criteria.  Liaise with them re the screening of this application and upload the screening report when received.</li>
		
	</ul>
	<table border="1" style="width:100%" >	
	<tr>
		
		</tr>
		<tr>
			
			<td><?php $this->makeLink("SAQA_screening_report_doc") ?>	</td>
		</tr>
<tr>
<td> <b>3.	Capture the background for this application </b> </td>
</tr>
<tr>
			
			<td>
			<?php 
				if ($this->formFields['applic_background']->fieldValue == ""):
					$this->formFields["applic_background"]->fieldValue = $this->getApplicationBackground($application_ref);
				endif;
				$this->showField('applic_background'); 
			?>
			</td>
		</tr>
	
	
</td>
</tr>


</table>

<tr>
	<td>
	<b>Check this box to indicate that you have completed checklisting this application:  <?php $this->showField('completed_checklisting');	?>
	</b><br><br>
	<tr>

	

	
</tr>
</b></td>
</tr>
<tr>
	<td><b>
	Please note if you check this box and click on Proceed to next process and user, the application will be passed to the user responsible for approving the checklisting report
</b></td>
</tr>
</table>

</td></tr></table>

