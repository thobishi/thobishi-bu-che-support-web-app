<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$sql = "SELECT site_visit_report_doc 
		FROM inst_site_visit 
		WHERE inst_site_app_proc_ref = ?";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($sql);
	$sm->bind_param("s", $site_proc_id);
	$sm->execute();
	$rs = $sm->get_result();


	//$rs = mysqli_query($sql);
	$sites_valid_ind = 1;
	while ($row = mysqli_fetch_array($rs)){
		if ($row["site_visit_report_doc"] == 0){
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
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Site visit reports from evaluators
		<br>
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_proc_id,'report'); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="visi">If this application is ready for management approval, please check this box to continue. <?php $this->showField("eval_complete_ind");?></span>
		<br>Please note if you check this box and click on Next, the application will be passed to management and the evaluators 
		will no longer have access to the application to ensure consistence of information during management approval.
	</td>
</tr>
</table>
