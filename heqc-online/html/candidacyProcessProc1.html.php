<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_ref");
	
	$this->formFields["search_HEQCref"]->fieldValue = readPost('search_HEQCref');
	$this->formFields["search_progname"]->fieldValue = readPost('search_progname');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["subm_start_date"]->fieldValue = readPost('subm_start_date');
	$this->formFields["subm_end_date"]->fieldValue = readPost('subm_end_date');
	$this->formFields["invoice_start_date"]->fieldValue = readPost('invoice_start_date');
	$this->formFields["invoice_end_date"]->fieldValue = readPost('invoice_end_date');
	$this->formFields["evalappoint_start_date"]->fieldValue = readPost('evalappoint_start_date');
	$this->formFields["evalappoint_end_date"]->fieldValue = readPost('evalappoint_end_date');
	$this->formFields["recomm_due_start_date"]->fieldValue = readPost('recomm_due_start_date');
	$this->formFields["recomm_due_end_date"]->fieldValue =  readPost('recomm_due_end_date');
	$this->formFields["acmeeting_start_date"]->fieldValue = readPost('acmeeting_start_date');
	$this->formFields["acmeeting_end_date"]->fieldValue = readPost('acmeeting_end_date');
	$this->formFields["heqcmeeting_start_date"]->fieldValue =  readPost('heqcmeeting_start_date');
	$this->formFields["heqcmeeting_end_date"]->fieldValue =  readPost('heqcmeeting_end_date');
	$this->formFields["outcome_due_start_date"]->fieldValue =  readPost('outcome_due_start_date');
	$this->formFields["outcome_due_end_date"]->fieldValue =  readPost('outcome_due_end_date');
	$this->formFields["search_heqc_decision"]->fieldValue =  readPost('search_heqc_decision');
	$this->formFields["search_outcome"]->fieldValue = readPost('search_outcome');
	$this->formFields["no_outcome"]->fieldValue =  readPost('no_outcome');
	$this->formFields["search_status"]->fieldValue = readPost('search_status');
	
	$this->showField('search_HEQCref');
	$this->showField('search_progname');
	$this->showField('search_institution');
	$this->showField('subm_start_date');
	$this->showField('subm_end_date');
	$this->showField('invoice_start_date');
	$this->showField('invoice_end_date');
	$this->showField('evalappoint_start_date');
	$this->showField('evalappoint_end_date');
	$this->showField('recomm_due_start_date');
	$this->showField('recomm_due_end_date');
	$this->showField('acmeeting_start_date');
	$this->showField('acmeeting_end_date');
	$this->showField('heqcmeeting_start_date');
	$this->showField('heqcmeeting_end_date');
	$this->showField('outcome_due_start_date');
	$this->showField('outcome_due_end_date');
	$this->showField('search_heqc_decision');
	$this->showField('search_outcome');
	$this->showField('no_outcome');
	$this->showField('search_status');
	
	$cross = '<img src="images/dash_mark.gif">';
	$check = '<img src="images/check_mark.gif">';
	
	$sql = <<<SQL
		SELECT ia_proceedings.*, 
		r.lkp_title AS recomm_decision,
		a.lkp_title AS ac_decision,
		h.lkp_title AS heqc_decision,
		ia_proceedings.heqc_board_decision_ref,
		ac_meeting_ref, 
		heqc_meeting_ref,
		lkp_proceedings.lkp_proceedings_desc
		FROM ia_proceedings
		LEFT JOIN lkp_desicion r ON r.lkp_id = ia_proceedings.recomm_decision_ref
		LEFT JOIN lkp_desicion a ON a.lkp_id = ia_proceedings.ac_decision_ref
		LEFT JOIN lkp_desicion h ON h.lkp_id = ia_proceedings.heqc_board_decision_ref
		LEFT JOIN AC_Meeting ON ac_id = ia_proceedings.ac_meeting_ref
		LEFT JOIN HEQC_Meeting ON heqc_id = ia_proceedings.heqc_meeting_ref
		LEFT JOIN lkp_proceedings ON lkp_proceedings_id = lkp_proceedings_ref
		WHERE ia_proceedings_id = $app_proc_id
SQL;

        $sm = $conn->prepare($sql);
        $sm->bind_param("s", $app_proc_id);
        $sm->execute();
        $rs = $sm->get_result();
        
	//$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	$row = mysqli_fetch_array($rs);
	
	// Set display fields
	$recomm_user = $this->getUserName($row['recomm_user_ref'],'3');
	$recomm_done = ($row["recomm_complete_ind"] == 1) ? $check : $cross;
	$recomm_doc_link = "&nbsp;";
	if ($row["recomm_doc"] > 0){
		$recomm_doc = new octoDoc($row["recomm_doc"]);
		$recomm_doc_link = "<a href='".$recomm_doc->url()."' target='_blank'>".$recomm_doc->getFilename()."</a>";
	}
	//$ac_date = "<i>Not yet assigned (online) to a meeting</i>";
	//if ($row["ac_meeting_ref"] > 0){
	//	$ac_date = $row["ac_start_date"] . " to " . $row["ac_to_date"];
	//}
	//$heqc_date = "<i>Not yet assigned (online) to a meeting</i>";
	//if ($row["heqc_meeting_ref"] > 0){
	//	$heqc_date = $row["heqc_start_date"] . " to " . $row["heqc_to_date"];
	//}
?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Edit proceedings for:</td>
</tr>
<tr>
	<td class="loud"><?php $this->getApplicationInfoTableTopForHEI_sites($app_id); ?></td>
</tr>
</table>
	<table width="90%" border=1 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td  class="onblueb">Type of proceedings</td>
		<td colspan="4"><?php $this->showField("lkp_proceedings_ref"); ?></td>
	</tr>
<?php
$html = <<<HTML

	<tr class="onblueb">
		<td>User assigned<br>to do recommendation</td>
		<td>Access until</td>
		<td>Done</td>
		<td>Recommendation</td>
		<td>Approved<br>recommendation</td>
	</tr>
	<tr>
		<td>$recomm_user</td>
		<td>$row[recomm_access_end_date]</td>
		<td>$recomm_done</td>
		<td>$row[recomm_decision]</td>
		<td>$recomm_doc_link</td>
	</tr>
HTML;
echo $html;
?>
	<tr>
		<td  class="onblueb">AC meeting date</td>
		<td colspan="4"><?php $this->showField("ac_meeting_ref"); ?></td>
	</tr>
<?php
$html = <<<HTML
	<tr>
		<td class="onblueb">AC Recommendation</td>
		<td colspan="4">$row[ac_decision]</td>
	</tr>	
HTML;
echo $html;
?>
	<tr>
		<td class="onblueb">HEQC meeting date</td>
		<td colspan="4"><?php $this->showField("heqc_meeting_ref"); ?></td>
	</tr>	
<?php
$html = <<<HTML
	<tr>
		<td class="onblueb">HEQC Decision</td>
		<td colspan="4">$row[heqc_decision]</td>
	</tr>	
HTML;
echo $html;
?>
	<tr>
		<td class="onblueb">Decision letter that was sent to the institution</td>
		<td colspan="4"><?php $this->makeLink("decision_doc");?></td>
	</tr>
	<tr>
		<td class="onblueb">Due date for response from institution</td>
		<td colspan="4">
			<?php
			//if ($row["heqc_board_decision_ref"] == 2 || $row["heqc_board_decision_ref"] == 3 || $row["heqc_board_decision_ref"] == 4){
			//2017-11-02 Richard: Include Reaccreditation statuses
			if ($row["heqc_board_decision_ref"] == 2 || $row["heqc_board_decision_ref"] == 3 || $row["heqc_board_decision_ref"] == 4 || $row["heqc_board_decision_ref"] == 6 || $row["heqc_board_decision_ref"] == 7 || $row["heqc_board_decision_ref"] == 8){
				$this->showField("heqc_decision_due_date"); 
			} else {
				echo "<i>Not required</i>";
			}
			?>
			 
		</td>
	</tr>	
<?php //if ($row["heqc_board_decision_ref"] == 2){ 
      //2017-11-02 Richard: Include Reaccreditation statuses
      if ($row["heqc_board_decision_ref"] == 2 || $row["heqc_board_decision_ref"] == 6){ ?>
		<tr>
			<td class="onblueb">Upload compliance with conditions document</td>
			<td colspan="4"><?php $this->makeLink("condition_doc");?></td>
		</tr>
<?php } ?>
<?php //if ($row["heqc_board_decision_ref"] == 3){ 
      //2017-11-02 Richard: Include Reaccreditation statuses
      if ($row["heqc_board_decision_ref"] == 3 || $row["heqc_board_decision_ref"] == 7){ ?>
		<tr>
			<td class="onblueb">Upload representation</td>
			<td colspan="4"><?php $this->makeLink("representation_doc");?></td>
		</tr>
<?php } ?>
<?php //if ($row["heqc_board_decision_ref"] == 4){ 
      //2017-11-02 Richard: Include Reaccreditation statuses
      if ($row["heqc_board_decision_ref"] == 4 || $row["heqc_board_decision_ref"] == 8){ ?>
		<tr>
			<td class="onblueb">Upload deferral document</td>
			<td colspan="4"><?php $this->makeLink("deferral_doc");?></td>
		</tr>
<?php } ?>
</table>
