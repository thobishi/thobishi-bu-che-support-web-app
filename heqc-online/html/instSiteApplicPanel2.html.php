<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$sql = "SELECT evaluator_persnr FROM inst_site_visit 
		LEFT JOIN inst_site_visit_eval ON inst_site_visit_eval.inst_site_visit_ref = inst_site_visit.inst_site_visit_id
		WHERE inst_site_app_proc_ref = ? " ;

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


	//$rs = mysqli_query($sql) or die(mysqli_error());
	$sites_valid_ind = 1;
	while ($row = mysqli_fetch_array($rs)){
		if ($row["evaluator_persnr"] == NULL){
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
		Assign panel members to site visits
		<br>
	</td>
</tr>
<tr>
	<td>
		The panel members need to be assigned to the site/s they must visit. Click on <img src="images/ico_eval.gif" alt="edit" /> next to the site name to 
		assign panel members to that site.
	</td>
</tr>
<tr
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_proc_id,'eval'); ?>
	</td>
</tr>
</table>
<?php
	
?>
