<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$sql = "SELECT institution_notification_doc, schedule_doc 
		FROM inst_site_visit 
		WHERE inst_site_app_proc_ref = $site_proc_id";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	//$sm = $conn->prepare($SQL);
//$sm->bind_param("s", $site_proc_id);
	//$sm->execute();
	//$rs = $sm->get_result();


	$rs = mysqli_query($conn, $sql);
	$sites_valid_ind = 1;
	while ($row = mysqli_fetch_array($rs)){
		if ($row["institution_notification_doc"] == 0 || $row["schedule_doc"] == 0){
			$sites_valid_ind = 0;
		}
	}
	$this->formFields["sites_valid_ind"]->fieldValue = $sites_valid_ind;
	$this->showField("sites_valid_ind");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Notification letter to the institution: Upload draft site visit schedule, letter and verify attachments
		<br>
	</td>
</tr>
<tr>
	<td>
		Check the documentation that will be emailed as attachments to the institution.  The email to the institution is generated for viewing 
		on the next page.  The official letter from the CHE and the draft site visit schedule must be uploaded for each site.
		Click on <img src="images/ico_print.gif" alt="edit" /> next to the site name to upload relevant documents.
	</td>
</tr>
<tr
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_proc_id,'docs'); ?>
	</td>
</tr>
<tr>
	<td>
		Verify documents that will be emailed as attachments 
	</td>
</tr>
</table>
