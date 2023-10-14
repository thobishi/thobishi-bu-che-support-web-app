<?php

	$usr_to_copy = $this->getUsersInGroup(34); //usr_finance_emails group
	$usr_copy = "<i>None currently specified</i>";
	$cc = "";
	if (count($usr_to_copy) > 0){
		$cc = array();
		foreach($usr_to_copy as $u){
			array_push($cc, $u[1]);
		}
		$usr_copy = implode(",", $cc);
	}

	$today = date("Y-M-d");
	$rem_method = $this->getDBsettingsValue("reminder_method");
	$rem1_days = $this->getDBsettingsValue("reminder1_days_from_invoice");
	$rem2_days = $this->getDBsettingsValue("reminder2_days_from_reminder1");
	$rem3_days = $this->getDBsettingsValue("reminderw_days_from_reminder2");
	$color_red = "#ff0000";

	//$showProcess = readPost("showProcess");
	$showProcess = 0;
	
	if (isset($_POST["send_reminder"])) {
		$this->sendPaymentReminders();
	}
?>
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="loud"><br>Outstanding payments report:</td>
	</tr>
	<tr>
		<td>
			<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
			<tr class="oncolourb">
				<td colspan="3">Current configuration settings for reminders.  These may be changed by a payments administrator from the Payments menu </td>
			</tr>
			<tr class="oncolourb">
				<td>Configuration setting</td>
				<td>Value</td>
				<td>Description</td>
			</tr>
<?php
		$SQL = <<<SQL
			SELECT * 
			FROM settings 
			WHERE s_key IN ('reminder_method','reminder1_days_from_invoice','reminder2_days_from_reminder1','reminderw_days_from_reminder2')
			ORDER BY s_key
SQL;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
		$rs = mysqli_query($conn, $SQL);
		while ($row = mysqli_fetch_array($rs)) {
?>
			<tr valign="top">
				<td class="oncolour">
					<?php echo $row["s_key"]; ?>
				</td>
				<td class="oncolour"><?php echo $row["s_value"];?></td>
				<td class="oncolour"><?php echo $row["s_description"];?></td>
			</tr>
<?php
		}
?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
<?php
			$sql = <<<SQL
				SELECT 
					application_ref,
					ia_proceedings_ref,
					reaccreditation_application_ref,
					date_invoice,
					invoice_total,
					payment_total,
					date_first_reminder,
					date_final_reminder,
					date_cancelled
				FROM payment
				WHERE date_invoice > '1000-01-01'
				AND received_confirmation = 0
				AND date_cancelled = '1000-01-01'
				ORDER BY date_invoice, payment_id
SQL;
            $rs = mysqli_query($conn, $sql);
			$no_outstanding = mysqli_num_rows($rs);
			$html_head = <<<HTMLHEAD
				<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
				<tr class="Loud">
					<td colspan="6" class="Loud">Outstanding payments as at $today</td>
					<td colspan="7" class="Loud" align="right">Number of outstanding payments: $no_outstanding</td>
				</tr>
				<tr class="doveblox">
					<td class="doveblox">Institution<br>name</td>
					<td class="doveblox">HEQC<br>reference<br>number</td>
					<td class="doveblox">Programme<br>name</td>
					<td class="doveblox">Proceeding type<br>name</td>
					<td class="doveblox">Submission<br>date</td>
					<td class="doveblox">Progress<br>status</td>
					<td class="doveblox">Invoice<br>date</td>
					<td class="doveblox">Invoice<br>total</td>
					<td class="doveblox">Total paid</td>
					<td class="doveblox">Days<br /> outstanding</td>
					<td class="doveblox">First<br>reminder</td>
					<td class="doveblox">Second<br>reminder</td>
					<td class="doveblox">Return<br />notice</td>
				</tr>
HTMLHEAD;
			echo $html_head;
			while ($row = mysqli_fetch_array($rs)){
				$app_id = $row["application_ref"];
				$app_proc_id = $row["ia_proceedings_ref"];
				$reacc_id = $row["reaccreditation_application_ref"];
				if ($app_id > 0){
					$ent_sql = <<<SQL
						SELECT HEI_code, HEI_name, CHE_reference_code AS ref, program_name AS name, 'Application' AS type, submission_date AS sub_date
						FROM Institutions_application, HEInstitution 
						WHERE Institutions_application.institution_id = HEInstitution.HEI_id
						AND application_id = $app_id
SQL;
				}
				if ($app_proc_id > 0){
					$ent_sql = <<<SQL
						SELECT HEI_code, HEI_name, CHE_reference_code AS ref, program_name AS name, lkp_proceedings_desc AS type, 
						ia_proceedings.submission_date AS sub_date
						FROM (ia_proceedings, Institutions_application, HEInstitution)
						LEFT JOIN lkp_proceedings ON ia_proceedings.lkp_proceedings_ref = lkp_proceedings_id
						WHERE ia_proceedings.application_ref = Institutions_application.application_id
						AND Institutions_application.institution_id = HEInstitution.HEI_id
						AND ia_proceedings_id = $app_proc_id
SQL;
				}
				if ($reacc_id > 0){
					$ent_sql = <<<SQL
						SELECT HEI_code, HEI_name, referenceNumber AS ref, programme_name AS name, 'reaccred' AS type, reacc_submission_date AS sub_date
						FROM Institutions_application_reaccreditation, HEInstitution 
						WHERE Institutions_application_reaccreditation.institution_ref = HEInstitution.HEI_id
						AND Institutions_application_reaccreditation_id = $reacc_id
SQL;
				}
				$ent_rs = mysqli_query($conn, $ent_sql);// or die(mysqli_error());
				$ent_row = mysqli_fetch_array($ent_rs);
				
				$inst_name = $ent_row["HEI_name"];
				$ref_no =  $ent_row["ref"];
				$ent_name =  $ent_row["name"];
				$ent_type =  $ent_row["type"];
				$submission_date =  $ent_row["sub_date"];
				$date_invoice =  $row["date_invoice"];
				$invoice_total =  $row["invoice_total"];
				$payment_total =  $row["payment_total"];
				$seconds_diff =  strtotime("now") - strtotime($date_invoice);
				$days_outstanding = $seconds_diff/(60 * 60 * 24);
				$weeks = floor($days_outstanding / 7);
				$days = $days_outstanding % 7;
				$outstanding = "";
				if ($weeks > 0){
					$outstanding .= $weeks . " weeks ";
				}
				if ($days > 0){
					$outstanding .= $days . " days";
				}

				$first_rem = $row["date_first_reminder"];
				if ($row["date_first_reminder"] == '1000-01-01'){
					$first_rem = "-";
					if ($days_outstanding > $rem1_days){
						$first_rem = '<span style="color:'.$color_red.'">Due</span>';
					}
				}

				$final_rem = $row["date_final_reminder"];
				if ($row["date_final_reminder"] == '1000-01-01'){
					$final_rem = "-";
					if ($row["date_first_reminder"] > '1000-01-01'){  // first reminder must have been sent
						$rem2_sec =  strtotime("now") - strtotime($row["date_first_reminder"]);  //days since first reminder was sent
						$rem2_outstanding = $rem2_sec/(60 * 60 * 24);
						if ($rem2_outstanding > $rem2_days){
							$final_rem = '<span style="color:'.$color_red.'">Due</span>';
						}
					}
				}
				
				$wdraw_rem = $row["date_cancelled"];
				if ($row["date_cancelled"] == '1000-01-01'){
					$wdraw_rem = "-";
					if ($row["date_final_reminder"] > '1000-01-01'){  // final reminder must have been sent
						$rem3_sec =  strtotime("now") - strtotime($row["date_final_reminder"]);  //days since first reminder was sent
						$rem3_outstanding = $rem3_sec/(60 * 60 * 24);
						if ($rem3_outstanding > $rem3_days){
							$wdraw_rem = '<span style="color:'.$color_red.'">Due</span>';
						}
					}
				}
				
				$process = "&nbsp;";
				if ($showProcess == 1){
					// A proceeding payment record will have an ia_proceeding_ref and an application_ref
					if ($app_proc_id > 0){
						$proc_arr = $this->getActiveProcessforApp($app_proc_id,"proceeding");
						$process =  '(<span class="specialsi">'. $proc_arr['name'] .'</span>)';
					} else if ($app_id > 0){ 
						$proc_arr = $this->getActiveProcessforApp($app_id,"applic");
						$process =  '(<span class="specialsi">'. $proc_arr['name'] .'</span>)';
					}
					if ($reacc_id > 0){
						$proc_arr = $this->getActiveProcessforApp($reacc_id,"reacc");
						$process =  '(<span class="specialsi">'. $proc_arr['name'] .'</span>)';
					}
				}
				
				$html_row = <<<HTMLROW
					<tr>
						<td>$inst_name</td>
						<td>$ref_no</td>
						<td>$ent_name</td>
						<td>$ent_type</td>
						<td>$submission_date</td>
						<td>$process</td>
						<td>$date_invoice</td>
						<td>$invoice_total</td>
						<td>$payment_total</td>
						<td>$outstanding</td>
						<td>$first_rem</td>
						<td>$final_rem</td>
						<td>$wdraw_rem</td>
					</tr>
HTMLROW;
				echo $html_row;
			}
			?>
				</table>
		</td>
	</tr>
	<tr>
		<td>
<?php
			if (empty($_POST["send_reminder"])) {
				$instr = <<<INSTR
					<br>
					Check the box below to send the reminders (indicated as <span style="color:{$color_red}">Due</span>) to institutional administrators and copied to {$usr_copy} (specified in the Finance emails group).
					<br><br><br>
INSTR;
				echo $instr;
				echo "Send reminders? &nbsp; " . $this->showField("send_reminder");
				$instr = <<<INSTR
				&nbsp;<input class="btn" type="button" value="Send" onClick="checkSendReminder(document.defaultFrm.send_reminder);">
INSTR;
				echo $instr;
			}

?>
		</td>
	</tr>
	</table>