<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ();?>
<br><br>
<?php 
	if (isset($_POST["eval_id"]) && ($_POST["eval_id"] > 0)) {
		$SQL = "INSERT INTO `evalReport` (application_ref, Persnr_ref, evalReport_completed, evalReport_status_confirm, do_sitevisit_checkbox) VALUES (?, ?, 1, 1, 0)";
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $_POST["eval_id"]);
                $sm->execute();
                $RS = $sm->get_result();
		//$RS = mysqli_query($SQL);
	}
?>
<br><br>
<b>The evaluator has been added.</b>
</td></tr></table>
