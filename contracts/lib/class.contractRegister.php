<?php
define ('TAX_PAYE',0.25);
define ('TAX_UIF', 0.01);
define ('TAX_UIF_MAX',124.78);

//Get the selected consultant type
$consultant_type = isset($_POST['typeSearch'])? $_POST['typeSearch'] : "";

class contractRegister extends miscellaneous {

	var $relativePath;
	/**
	 * default constructor
	 *
	 * this function calls the {@link workFlow} function.
	 * @author Diederik de Roos
	 * @param integer $flowID
	*/
	function contractRegister ($flowID) {
		$this->readPath ();
		$this->workFlow ($flowID);
		$this->populatePublicHolidays ();
	}

	function readPath () {
		global $path;

		$this->relativePath = (isset($path))?($path):("");
	}

	function displayContractHeader($cons_id,$contract_id="",$func=""){
		$consultant_name = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "name")." ".$this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "surname");
		$company = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "company");
		$consultant = ($this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "type") == "2") ? $company : $consultant_name;
		$contract = ($contract_id > 0) ? $this->getValueFromTable("d_consultant_agreements", "agreement_id", $contract_id, "description"):"";

		$consultant = ($consultant > "") ? $consultant . ": " : "";
		$contract = ($contract > "") ? $contract . "-" : "";

		$head = <<<HEAD
			<span class="loud">$consultant $contract $func</span>
HEAD;
		return $head;
	}

	function build_where_criteria($ecrit){
		$whr_arr = array();
		$whr = "";
		$where = "";
		if ($ecrit > ""){
			// $ecrit is in format: ; separates values. : separates accno and description
			// 1. get values in an array.
			$val_arr = explode(";",$ecrit);
			foreach ($val_arr as $val){
				$flds = explode(":",$val);
				if ($flds[0] > ""){
					$whr = "AccNumber = '".$flds[0]."'";
				}
				if (isset($flds[1]) && $flds[1] > ""){
					$whr .= " AND trim(description) = '".$flds[1]."'";
				}
				array_push($whr_arr,$whr);
			}
			$where = "(" . implode(") AND (",$whr_arr) . ")";
		}
		return $where;
	}


	function getSumExpenditure($ecrit){
		$expenditure = 0;

//	Commented out until Pastel Evolution has been implemented.
//		$consultant_type = isset($_POST['typeSearch'])? $_POST['typeSearch'] : "";

//		$where = contractRegister::build_where_criteria($ecrit);
//		if (!$where > "") return 0;

//		$sqlF = <<<sqlF
//			SELECT sum(Amount) as expenditure
//			FROM pastel_ledger_transactions
//			WHERE $where
//sqlF;

//		$rsF = mysqli_query($sqlF) or die(mysqli_error());
//		if (1 == mysqli_num_rows($rsF)){
//			$rowF = mysqli_fetch_array($rsF);
//			$type_boolean = ($consultant_type != 2)? false : true;
//			$expenditure = ($type_boolean)?($rowF["expenditure"]):(contractRegister::computeExpense($rowF["expenditure"]));  // this should be the c value. Only apply the PAYE-UIF function if not a service provider
//		}

		return $expenditure;
	}


	function searchConsult($sql)
	{
		$arr = array();
		$query = mysqli_query($sql) or die(mysqli_error());
		if(mysqli_num_rows($query) > 0)
			return $query;
		return $arr;
	}


	function computeExpense($amount)
	{
		$percentagePAYE = TAX_PAYE;
		$percentageUIF = TAX_UIF;

		if(($amount <= 0) || !(is_numeric($amount)))
			return 0;

		$uif = $percentageUIF * $amount;
		
		if ($uif < 124.78) { 
			$expence = $amount / (1 - $percentagePAYE - $percentageUIF);
		} else {
			$expence = $uif + ($amount / (1 - $percentagePAYE));
		}
		return $expence;
	}

	//Function to insert comments into the comments table.
	function insertComments($contractId, $table, $fields = array(), $values = array()){
		foreach ( $values as $key => $value ) {
			$valuesArr[]  = '"' . $value . '"';
		}

		$date = date("Y-m-d H:m:s");
		array_push($valuesArr,'"'.$date.'"');
		$SQL = "INSERT INTO ".$table."  (".implode(",",$fields).")
				VALUES (".implode(",",$valuesArr).")";
		$rs = mysqli_query($SQL) or die(mysqli_error());
		if($rs)
			return true;
		return false;
	}

	function displayComments($contractId){
		$sql = "SELECT owners_comments.*, users.name, users.surname
				FROM owners_comments
				LEFT JOIN users ON users.user_id = owners_comments.user_ref
				WHERE agreement_ref = ".$contractId." ORDER BY comment_date DESC";
		$query = mysqli_query($sql) or die(mysqli_error());
		if(mysqli_num_rows($query) > 0){
				echo "<table border=\"0\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
				echo "<tr><td><b>Comments and Ratings submitted so far.</b></td></tr>";
				while($rows = mysqli_fetch_array($query)){
					echo "<tr><td>".$rows['CHEcomment']." - submitted by ".$rows['name']." ".$rows['surname']." on ".$rows['comment_date'].
						 "<br>Delivery date deadline rating is: <b>".$rows['deliverydate_deadlines']."</b>" .
						 "<br>Meeting requirements rating is: <b>".$rows['meeting_requirements']."</b>" .
						 "<br>Quality of work rating is: <b>".$rows['quality_work']."</b></td></tr>";
					echo "<tr><td><hr width=\"100%\"></td></tr>";
				}
				echo "</table>";
		}
	}

	function displaySupervisor($che_supervisor_user_ref){
		$supstr = "-- No manager selected --";

		$sql = <<<SUPERVISOR
			SELECT *
			FROM users
			WHERE user_id = $che_supervisor_user_ref
SUPERVISOR;

		$rs = mysqli_query($sql);

		if ($rs && mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$supstr = $row["name"] . " " . $row["surname"] . " (<b>email:</b> "  . $row["email"] . "<b> tel:</b> " . $row["contact_nr"] . ")";
		}

		return $supstr;
	}

	/*
	** Robin 2009-03-11: Calculate a unique reference number for each contract
	*/
	function calcNextIdnumber() {
		$SQL = "SELECT LPAD(next_idnumber,4,0) AS idnumber FROM `lkp_agreement_idnumber`";
		$rs = mysqli_query($SQL);
		$idno = "";
		if ($rs && ($row=mysqli_fetch_array($rs))) {
			$idno = date('Y').$row["idnumber"];
		}
		$SQL = "UPDATE `lkp_agreement_idnumber` SET next_idnumber=(next_idnumber+1)";
		$rs = mysqli_query($SQL);
		return $idno;
	}

function getConsultantName($consultant_id,$style=1){
		$name = " ";

		$sql = <<<CONNAME
			SELECT t.lkp_consultant_type_desc,l.lkp_title_desc, c.name, c.surname,c.company, c.initials
			FROM d_consultants c
			LEFT JOIN lkp_consultant_type t ON t.lkp_consultant_type_id = c.type
			LEFT JOIN lkp_title l ON l.lkp_title_id = c.title

			WHERE consultant_id = $consultant_id
CONNAME;

		$rs = mysqli_query($sql);
		if ($rs && mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$type = $row["lkp_consultant_type_desc"];
			$name = $row["name"];
			$surname = $row["surname"];
			$company = $row["company"];
			$initials = $row["initials"];
			$title = $row["lkp_title_desc"];

			switch ($style){
				case 2:
					$name = $name . " " . $surname." ".'('.$type.')'." "."<b>$company</b>";
					break;
				case 3: // for email
					$name = $name . " " . $surname." - ". $company  .' ('.$type.')';
					break;
				case 4:
					$name = ($company > '') ? $company . " (" .$name . " " . $surname.")" : $name . " " . $surname;
					break;
				case 5:
					$name = $name . " " . $surname;
					break;
				default:
					$name = $name . " " . $surname." ".($type);
			}

		}

		return $name;
	}

	function getUserName($user_id,$style=1){
		$name = " ";

		$sql = <<<CONNAME
			SELECT l.lkp_title_desc, u.name, u.surname
			FROM users u
			LEFT JOIN lkp_title l ON l.lkp_title_id = u.title_ref
			WHERE user_id = $user_id
CONNAME;

		$rs = mysqli_query($sql);
		if ($rs && mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$name = $row["name"];
			$surname = $row["surname"];
			$title = $row["lkp_title_desc"];

			switch ($style){
				default:
					$name = $name;
			}

		}

		return $name;
	}
	
	// 2009-05-26 Robin
	// Return a list of consultant ids assigned to a manager.
	function getConsultantIdList($usr_id){
		$arr_cid = array();

		if ($usr_id > 0){
			$sql = <<<MGRSQL
				SELECT distinct consultant_ref
				FROM d_consultant_agreements
				WHERE che_supervisor_user_ref = $usr_id
MGRSQL;
			 $rs = mysqli_query($sql);
			 if ($rs && mysqli_num_rows($rs)>0){
			 	while ($row = mysqli_fetch_array($rs)){
					array_push($arr_cid,$row["consultant_ref"]);
				}
			 }
		}
		
		return $arr_cid;
	}
	
	function getExpiredContracts($whr){
		$str_contracts = "";
		$a_contracts = array();
		
		$add_whr = ($whr > "") ? "AND " . $whr : "";
		
		$sql = <<<SELEXPIRE
			SELECT *
			FROM d_consultant_agreements
			WHERE status = 1
			AND end_date < now()
			$add_whr
			ORDER BY che_supervisor_user_ref, description
SELEXPIRE;

		$rs = mysqli_query($sql);
		if ($rs){
			$n = 1;
			$prev_owner = '';
			while ($row = mysqli_fetch_array($rs)){
				$owner = ($row['che_supervisor_user_ref'] > 0) ? $this->getUserName($row['che_supervisor_user_ref']) : 'No manager specified';
				$consultant = $this->getConsultantName($row['consultant_ref'],5);
				// 2011-12-08 Robin: Attempt at displaying data in a text table. Looks good in text editor. Not good in Lotus email. Characters have different length in some fonts.
				/*
				$a_contracts[$n]["Owner"] = $owner;
				$a_contracts[$n]["No"] = $n;
				$a_contracts[$n]["Contract Description"] = substr($row['description'],0,50);
				//$a_contracts[$n]["Start"] = $row['start_date'];
				$a_contracts[$n]["End"] = $row['end_date'];
				$a_contracts[$n]["Consultant"] = $consultant;
				*/
				// 2011-12-08 Robin: Removed string because user wants it aligned in a table.
				//array_push($a_contracts, $n . ". " .$row['description'] . " (".$row['start_date']." to ".$row['end_date']."): ".$consultant);
				if ($owner != $prev_owner){
					$nl = ($prev_owner == '') ? "" : "\n" ;
					array_push($a_contracts, $nl . "Contract Manager: " . $owner);
				}
				array_push($a_contracts, $n . ": " .$row['end_date']. " - " . $row['description'] . " (".$consultant . ")");
				$n++;
				$prev_owner = $owner;
			}
			// 2011-12-08 Robin: Removed string because user wants it aligned in a table.
			$str_contracts = implode("\n",$a_contracts);
			// 2011-12-08 Robin: Attempt at displaying data in a text table. Looks good in text editor. Not good in Lotus email. Characters have different length in some fonts.
			//$str_contracts = $this->draw_text_table($a_contracts);

			}

		return $str_contracts;
	}

function draw_text_table ($table) {
    
    // Work out max lengths of each cell

    foreach ($table AS $row) {
        $cell_count = 0;
        foreach ($row AS $key=>$cell) {
            $cell_length = strlen($cell);

            $cell_count++;
            if (!isset($cell_lengths[$key]) || $cell_length > $cell_lengths[$key]) $cell_lengths[$key] = $cell_length;

        }    
    }

    // Build header bar

    $bar = '+';
    $header = '|';
    $i=0;

    foreach ($cell_lengths AS $fieldname => $length) {
        $i++;

        $name = $fieldname;
        if (strlen($name) > $length) {
            // crop long headings

            //$name = substr($name, 0, $length-1);
			$cell_lengths[$fieldname] = $length = strlen($name);
        }

        $bar .= str_pad('', $length+2, '-')."+";
        $header .= ' '.str_pad($name, $length, '*', STR_PAD_RIGHT) . " |";

    }

    $output = '';

    $output .= $bar."\n";
    $output .= $header."\n";

    $output .= $bar."\n";

    // Draw rows

    foreach ($table AS $row) {
        $output .= "|";

        foreach ($row AS $key=>$cell) {
            $output .= ' '.str_pad($cell, $cell_lengths[$key], '*', STR_PAD_RIGHT) . " |";

        }
        $output .= "\n";
    }

    $output .= $bar."\n";

    return $output;

}

	
	// 2009-05-28 Robin: Get an array of users for a particular group.
	// $grp - Group that the users belong to. Required.
	// $usrStatus - Active or Inactive users.  Default is active.
	function getUsersForGroup($grp,$usrStatus=1){
		$a_usr = array();
		
		$sql = <<<GROUP
			SELECT sec_user_ref
			FROM sec_UserGroups, users
			WHERE sec_user_ref = user_id
			AND active = $usrStatus
			AND sec_group_ref = $grp
GROUP;

		$rs = mysqli_query($sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				array_push($a_usr, $row['sec_user_ref']);
			}
		}

		return $a_usr;
	}
	
	function getComments($contract_id){
		$comments = "-";
		$arr_comment = array();
		$sql = <<<COMMENTS
			SELECT comment
			FROM d_agreement_comments 
			WHERE agreement_ref  = $contract_id
COMMENTS;
		$rs = mysqli_query($sql) or die(mysqli_error());
		if ($rs){

			while ($row = mysqli_fetch_array($rs)){
				array_push($arr_comment, $row["comment"]);
			}
			$comments = implode("<br>",$arr_comment);
		}
		return $comments;
	}
	
	function sendReminders(){	
		$typeDatesArr = array(
			'Service Provider' => array(
				'consultantTypeId' => $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_desc", "Service Provider", "lkp_consultant_type_id"),
				'interval' => 'INTERVAL '.$this->getValueFromTable("settings", "s_key", "serviceProvider_reminder_interval", "s_value").' MONTH'
			),
			'Consultant' => array(
				'consultantTypeId' => $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_desc", "Consultant", "lkp_consultant_type_id"),
				'interval' => 'INTERVAL '.$this->getValueFromTable("settings", "s_key", "consultant_reminder_interval", "s_value").' MONTH'
			)
		);
		$where_arr = array();
		$email_frequency = 'INTERVAL '. $this->getValueFromTable("settings", "s_key", "reminder_send_frequency", "s_value") .' MONTH';
		foreach($typeDatesArr as $typeDate){
			array_push($where_arr, " (type = " . $typeDate["consultantTypeId"] . " AND end_date <= now() + " . $typeDate["interval"].") AND (now() - ". $email_frequency ." >= last_reminder_date)" );
		}
		
		$where = implode(" OR ", $where_arr);

	
		$sql = <<<sql
		SELECT distinct che_supervisor_user_ref, agreement_id, last_reminder_date
					FROM d_consultant_agreements
					LEFT JOIN d_consultants ON d_consultants.consultant_id = d_consultant_agreements.consultant_ref
					WHERE d_consultant_agreements.status = 1
					AND ({$where})
					
sql;
		// echo '<pre>';	
		// print_r($sql);
		// echo '</pre>';
		// exit;
		$rs =  mysqli_query($sql) or die(mysqli_error());		
		$num_rows = mysqli_num_rows($rs);
		
		$a_adm = $this->getUsersForGroup(1);
		$contractExist = ($num_rows > 0) ? 'yes' : 'no';
		// if($num_rows > 0){
			$previousAdm = '';
			foreach ($a_adm as $adm){
				$a_var['adm'] = $adm;
				$a_var['contractExist'] = $contractExist;
				$message = $this->getTextContent ("auto_email_reminder", "auto-administrationExpiredContract",$a_var);
				if($previousAdm != $adm){
					$this->autoMisMail($adm, "Reminder of expiring contracts", $message);
				}
				$previousAdm = $adm;
			}
		// }

		$mailArr = array();
		while($row = mysqli_fetch_array($rs)){	
			$mgr = $row["che_supervisor_user_ref"];
			$agreement_id = $row["agreement_id"];//used to update the last_reminder_date field

			if ($mgr > ""){ 
				$a_var['mgr'] = $mgr;		
					$message = $this->getTextContent ("auto_email_reminder", "auto-notificationExpiredContract",$a_var);
					$mailArr[$mgr][$agreement_id] = $message;
										
			}
		}		
		$id = '';
		$previousId ='';
		if(!empty($mailArr)){
			foreach($mailArr as $mngr => $agreementId_msgArr){


					
				foreach($agreementId_msgArr as $agreementId => $msg){
					if(count($agreementId_msgArr) > 1) {
						$id = implode (", ", array_keys($agreementId_msgArr));
					}else{
						$id = $agreementId;
					}
					
					if ($previousId != $id){
						$this->autoMisMail($mngr, "Reminder of expiring contracts", $msg, $id);
					}
					$previousId = $id;	
				}
			}
			
		}
		
	}
	
	function autoMisMail ($userid, $subject, $message, $agreement_id = "", $cc="", $ownFromAdr=true) {
		$ToAddress = $this->getValueFromTable("users", "user_id", $userid, "email");
		$this->autoMisMailByName($ToAddress, $subject, $message, $agreement_id, ($cc>"")?($cc):(""), $ownFromAdr);
	}
	
	function autoMisMailByName ($email, $subject, $message, $agreement_id, $cc="", $ownFromAdr=true, $filelist="", $isHTML=false) {

		$mail = new PHPMailer();

		//note that if you have added a CC and are testing, you will not see that it is being cc'd
		if (defined('WRK_ALT_EMAIL')) {
			$email = WRK_ALT_EMAIL;
			$cc = '';
		}else{
			$mail->AddBCC('systems@octoplus.co.za');
		}

	  // changed from address to persons own address.
		$mail->From = $this->getValueFromTable("settings", "s_key", "server_from_address", "s_value");
		$mail->FromName = $this->getValueFromTable("settings", "s_key", "server_from_name", "s_value");

		$signature = $this->getValueFromTable("settings", "s_key", "email_che_signature", "s_value");
		if ($cc > '') {
			$cc_arr = explode(",",$cc);
			foreach ($cc_arr as $c){
				$mail->AddCC ($c);
			}
		}
		
		$mail->Subject = $this->getValueFromTable("settings", "s_key", "default_email_subject", "s_value")." ".$subject;
		$message = $message . $signature;
		// if (mayMail) {
		$mail->Host      = "127.0.0.1";
		$mail->Mailer    = "smtp";
		$mail->WordWrap = 75;


		$htmlMessage = $message;
		if ($isHTML != true) {
			$htmlMessage = "<HTML><HEAD><STYLE>BODY {font-family: Verdana;font-size: 10pt;}</STYLE></HEAD>\n<BODY>\n".str_replace ("\n", "<br />\n", htmlentities ($message))."\n</BODY>\n</HTML>";
			$isHTML = true;
		}

		$mail->Body = $htmlMessage; 
		$mail->IsSMTP();
		$mail->IsHTML ($isHTML);
		$mail->AddAddress ($email);
		
		$title = "AUTO-EMAIL";
		if (! $mail->Send() ) {
			echo "AUTO-EMAIL NOT SENT";
		}
		$this->autoWriteLogInfo(10, $title, "An e-mail with subject ".$subject." was sent to ".$email.". The body of the e-mail was:\n\n".$message);
		$mail->ClearAddresses();

		if($agreement_id > ""){
			$idArr = explode(", ",$agreement_id);
			foreach($idArr as $id){
				$this->updateField('d_consultant_agreements','agreement_id','last_reminder_date', date("Y-m-d"), $id); //update the last_reminder_date field
			}
		}

	}

	function autoWriteLogInfo($level, $subject, $log_var, $mail=false) {
		$log_var = system_escape ($log_var);
		$SQL = "INSERT INTO `workflow_log_file` VALUES (NULL, '".$level."','', '','' , NOW(),'' , '".$subject."'";
		$SQL .= ", ";
		if (is_string($log_var)) {
			$SQL .= "'".$log_var."'";
		}
		$SQL .= ")";
		$RS = mysqli_query($SQL) or die("<br><br>Cannot write to log file: ".$SQL);
		if ($mail) {
			$this->autoMisMailByName("heqc@octoplus.co.za", "(".CONFIG.") error report", "ID: ".mysqli_insert_id ()."\n".$log_var, "", "HEQC error log");
		}
	}

	function ListOfContractsToExpire($whr){
		$str_contracts = "";
		$a_contracts = array();
		
		$add_whr = ($whr > "") ? "AND " . $whr : "";
		
		$sql = <<<SELEXPIRE
			SELECT *
			FROM d_consultant_agreements
			LEFT JOIN d_consultants ON d_consultants.consultant_id = d_consultant_agreements.consultant_ref
			WHERE d_consultant_agreements.status = 1			
			$add_whr
			ORDER BY che_supervisor_user_ref, description
SELEXPIRE;
		
		$s_ProviderInterval = $this->getValueFromTable("settings", "s_key", "serviceProvider_reminder_interval", "s_value");
		$consultantInterval = $this->getValueFromTable("settings", "s_key", "consultant_reminder_interval", "s_value");
		$typeIntervals = array(
			'Service Provider' => '+' . $s_ProviderInterval . ' months',
			'Consultant' => '+' . $consultantInterval . ' months'
		);
		$c_groupHeading = "Consultant contracts due to expire within the next " . $consultantInterval . " months: \n";
		$s_groupHeading = "Service providers contracts due to expire within the next " . $s_ProviderInterval . " months: \n";
		
		$rs = mysqli_query($sql);
		if ($rs){
			// $n = 1;
			$prev_owner = '';
			while ($row = mysqli_fetch_array($rs)){
				$NewtitleService = "Service Provider \n";
				$owner = ($row['che_supervisor_user_ref'] > 0) ? $this->getUserName($row['che_supervisor_user_ref']) : 'No manager specified';
				// var_dump($owner);
				$consultant = $this->getConsultantName($row['consultant_ref'],5);
				$type = $this->getConsultantType($row["consultant_ref"]);
				$endDate = (isset($typeIntervals[$type])) ? date('Y-m-d', strtotime($typeIntervals[$type], time())) : "";
				if(!empty($endDate) && $row['end_date'] <= $endDate){
					// $a_contracts[$owner][$row['type']][] = $row['end_date']. " - " . $row['description'] . " (".$consultant . ") \n";
					$a_contracts[$owner][$row['type']][] = "Contract: ". $row['description'] . "\n Company: " . $row['company'] ."\n Contact: " . $consultant ."\n Expiry date: " . $row['end_date'] . "\n\n";
				}
			}	
			foreach($a_contracts as $manager => $consultant_typeArr){
				// $str_contracts .= "Contract Owner: ". $manager ."\n";
				// $str_contracts .= "-----------------------------"."\n";
				// foreach($consultant_typeArr as $consultant_type => $contract_desc){
					// $heading_group = ($consultant_type == 1) ? $c_groupHeading : $s_groupHeading;
					// $str_contracts .= $heading_group;
					// foreach($contract_desc as $key => $desc){
						// $str_contracts .= ($key + 1) .": ".$desc."\n";
					// }
				// }
				$str_contracts .= "Contract Owner: ". $manager ."\n";
				$str_contracts .= "-----------------------------"."\n\n";					
				foreach($consultant_typeArr as $consultant_type => $contract_desc){
					$heading_group = ($consultant_type == 1) ? $c_groupHeading : $s_groupHeading;
					$str_contracts .= $heading_group. "\n";
					foreach($contract_desc as $key => $desc){
						$str_contracts .= ($key + 1) ."    ".$desc."\n";
					}
				}
			
			}
		}
		return $str_contracts;
	}	
	function getConsultantType($consultant_id){
		$name = " ";
		$type = "";

		$sql = <<<CONNAME
			SELECT t.lkp_consultant_type_desc,l.lkp_title_desc, c.name, c.surname,c.company, c.initials
			FROM d_consultants c
			LEFT JOIN lkp_consultant_type t ON t.lkp_consultant_type_id = c.type
			LEFT JOIN lkp_title l ON l.lkp_title_id = c.title

			WHERE consultant_id = $consultant_id
CONNAME;

		$rs = mysqli_query($sql);
		if ($rs && mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$type = $row["lkp_consultant_type_desc"];
		}
		return $type;
	}

// END of Class
}

?>
