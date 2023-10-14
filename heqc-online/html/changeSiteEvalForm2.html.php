<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ();?>
<br><br>
<?php

        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	if (isset($_POST["old_eval_id"]) && ($_POST["old_eval_id"] > 0)) {
		echo '<input type="hidden" name="eval_id" value="'.$_POST["eval_id"].'">';
		$SQL = "SELECT * FROM `evalReport` WHERE  application_ref = ? AND Persnr_ref = ?";
	
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $_POST["eval_id"]);
                $sm->execute();
                $RS = $sm->get_result();
                
                //$RS = mysqli_query($SQL);
		$num = mysqli_num_rows($RS);
		if ($num > 0) {
			$SQL = "UPDATE `evalReport` SET do_sitevisit_checkbox=1 WHERE application_ref = ? AND Persnr_ref = ?";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $_POST["eval_id"]);
                        $sm->execute();
                        
			//mysqli_query($SQL);
		}else {
			$SQL = "SELECT is_manager FROM `evalReport` WHERE application_ref=? AND Persnr_ref=?";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $_POST["old_eval_id"]);
                        $sm->execute();
                        $rs = $sm->get_result();
			//$rs = mysqli_query ($SQL);
			
			
			$row = mysqli_fetch_array($rs);
			$manager = (($row["is_manager"] > "") || ($row["is_manager"] > 0))?(1):(0);
			$SQL = "INSERT INTO `evalReport` (application_ref, Persnr_ref, evalReport_completed, evalReport_status_confirm, do_sitevisit_checkbox, is_manager) VALUES (?, ?, 1, 1, 1, ?)";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("sss", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $_POST["eval_id"], $manager);
                        $sm->execute();
                        
			//$rs = mysqli_query($SQL);
		}
	}
?>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please click "Next" to send the following letter of appointment to the evaluator:</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
<td>
<?php 
$this->showEmailAsHTML("siteVisit2", "sitevisit confirmation");
?>
</td>
</tr>
</table>
<br><br>
<b>The evaluator has been changed.</b>
</td></tr></table>
