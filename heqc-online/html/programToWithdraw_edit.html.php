<br>
<?php
	$this->formFields["search_progname"]->fieldValue = readPost('search_progname');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["search_HEQCref"]->fieldValue = readPost('search_HEQCref');
	$this->formFields["report_ind"]->fieldValue = readPost('report_ind');	
	$this->formFields["data"]->fieldValue = readPost('data');
	
	$this->showField("search_progname");
	$this->showField("search_institution");
	$this->showField("search_HEQCref");
	$this->showField("report_ind");
	$this->showField("data");
	
	$app_id = readPost("data");
	$chk_withdraw = '<input type="Checkbox" name="withdrawConfirm" value="1" >';
	if ($app_id > 0){
		$sqlProcess = "SELECT * FROM active_processes
					   WHERE status = 0 AND workflow_settings LIKE '%application_id=$app_id%'";
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
		$rs = mysqli_query($conn, $sqlProcess);
		$processName = '';
		$active_processes_id = '';
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array ($rs)) {
				$processName .= $this->workflowDescription($row["active_processes_id"],$row["processes_ref"]);
				$active_processes_id .= $row["active_processes_id"];
			}
		}
		
		$this->getApplicationInfoTableTop($app_id);
		
		$date_withdrawn = date("Y-m-d");
		$user = $this->getValueFromTable("users","user_id", $this->currentUserID,"name");
		$this->formFields["application_ref"]->fieldValue = $app_id;
		$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
		$this->formFields["date_withdrawn"]->fieldValue = $date_withdrawn;
		$this->showField("application_ref");
		$this->showField("user_ref");
		$this->showField("date_withdrawn");
?>		
		<input type="hidden" name="active_processes_id" value="<?php echo $active_processes_id; ?>">
		<input type="hidden" name="processName" value="<?php echo $processName; ?>">
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="loud">Withdraw programme for above application:</td>
		</tr>
		<tr>
			<td>
				Please complete the following fields and click Save in the actions menu.
			</td>
		</tr>
		<tr>
			<br />
			<td>
				<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td class="visi" colspan="2">
					It will be recorded that <?php echo $user; ?> withdrew this programme with the above details on <?php echo $date_withdrawn; ?>.
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<?php	
							// $prev_title_list = $this->getApplicationTitleHistory($app_id); 
							// if ($prev_title_list > ''){
								// echo "<b>List of previous titles</b>";
								// echo $prev_title_list;
							// }
						?>
					</td>
				</tr>
				<tr>
					<td>Reason for the programme withdrawal</td><td><?php $this->showField('reason'); ?></td>
				</tr>		
				<tr>
					<td>Document as evidence for the programme withdrawal</td><td><?php $this->makeLink('reason_doc'); ?></td>
				</tr>
				<tr>
					<td class="visi" colspan="2">
						Please indicate that you confirm to withdraw this programme by checking the box.<?php echo $chk_withdraw?>
					</td>	
				</tr>
				</table>
			</td>
		</tr>		
		</table>	
<?php
	}
	else {
		echo "The programme can't be withdrawn for this application.  Please contact HEQC-online support to assist with the problem.";
	}
?>
