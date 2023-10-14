<?php
		
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$sql ="SELECT final_date_visit FROM inst_site_visit WHERE inst_site_app_proc_ref = '$site_proc_id'";;
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	//$sm = $conn->prepare($sql);
//$sm->bind_param("s", $site_proc_id);
//$sm->execute();
	//$rs = $sm->get_result();

	$rs = mysqli_query($conn, $sql);
	$sites_valid_ind = 1;
	while ($row = mysqli_fetch_array($rs)){
		if ($row["final_date_visit"] == '1000-01-01'){
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
		Schedule site visit:
		<br>
	</td>
</tr>
<tr>
	<td>
		The details of the site visit need to be captured for each site. Click on <img src="images/ico_change.gif" alt="edit" /> next to the site name to 
		capture the details for that site visit.
	</td>
</tr>
<tr
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_proc_id,'sched'); ?>
	</td>
</tr>
</table>
