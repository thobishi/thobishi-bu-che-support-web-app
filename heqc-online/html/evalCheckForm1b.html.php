<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
		<?php 
			$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr";

			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
			$sm->execute();
			$RS_evalReport = $sm->get_result();


			//$RS_evalReport = mysqli_query($SQL);
			$num_rows = mysqli_num_rows($RS_evalReport);
			$str = "( ";
			for ($i=0; $i < $num_rows; $i++) {
				if ($row = mysqli_fetch_array($RS_evalReport)) {
					$str .= "Persnr_ref='".$row["Persnr_ref"]."'";
					if ($i < ($num_rows - 1)) {
						$str .= " OR ";
					}
				}
			}
			$str .= " )";
			$this->evaluatorStats(array("CONCAT(Names, ' ', Surname)", "CHE_reference_code", "evalReport_date_sent"), array("`Eval_Auditors`", "`Institutions_application`", "`evalReport`"), array("application_id=application_ref", "Persnr_ref=Persnr", "evalReport_completed=0", "evalReport_status_confirm=1", $str), " AND ", "", "# Evaluations per Evaluator", array("Name", "Reference Number", "Date Sent"), array("CONCAT(Names, ' ', Surname)", "CHE_reference_code"), "None of these evaluators are currently busy with applications");
		?>
	</td>
</tr><tr>
	<td>
	<?php 
	$headingArray = array();
	array_push($headingArray,"Evaluator");
	array_push($headingArray,"Accept");

	$refDispArray = array();
	array_push($refDispArray,"Names");
	array_push($refDispArray,"Surname");

	$dispFields = array();
	array_push($dispFields,"pre_chosen_checkbox");

	$this->makeGRID("Eval_Auditors, evalReport",$refDispArray,"Persnr"," application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr=Persnr_ref","evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray);
	?>
	</td>
</tr><tr>
	<td><br><br>
		<b>The following chairperson has been nominated by your collegue:</b><br>
		<?php echo $chair?>
		<br><br>
		<b>If you wish to change the chairperson, use the table below.</b>
		<br><br>
		<table width="60%" border=1 align="center" cellpadding="2" cellspacing="2"><tr>
			<td colspan="2">Please choose a chair person to do the final evaluation:</td>
		</tr>
			<?php 
			mysqli_data_seek($RS_evalReport, 0);
			echo '<tr><td>';
			echo '<select name="do_summary">';
			while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
				$SEL = "";
				$rs2 = mysqli_query("SELECT do_summary FROM evalReport_nominees WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
				if (mysqli_num_rows($rs2) == 0) {
					$rs2 = mysqli_query("SELECT do_summary FROM evalReport WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
				}
				if ($rs2 && ($row2=mysqli_fetch_array($rs2))) {
					if ($row2["do_summary"] > 0) $SEL = " SELECTED ";
				}
				echo '<option value="'.$row["evalReport_id"].'" '.$SEL.'>'.$row["Names"]." ".$row["Surname"].'</option>';
			}
			echo '</select>';
			echo '</td></tr>';
			?>
		</tr></table>
	</td>
</tr><tr>
	<td><br>Do you want to select more evaluators? <i>(Tick for "yes")</i>
	<?php 
		$this->createInput ("more_evals", "checkbox", "1");
		$this->showField("more_evals");
	?>
	</td>
</tr></table>
</td></tr></table>
<script>
	function checkPreviousEvals (val) {
		for (i=0; i < document.defaultFrm.length; i++) {
			obj = document.defaultFrm[i];
			if ((obj.name.substring(0, 5) == "GRID_") && (obj.type == "checkbox")) {
				str = obj.name.substring(5, obj.name.length);
				str = str.substring(0, str.indexOf("$"));
				if (parseInt(str) == val) obj.checked = true;
			}
		}
	}
</script>
<?php 
$SQL = "SELECT * FROM `evalReport_nominees`, `evalReport` WHERE evalReport_nominees.application_ref=evalReport.application_ref AND evalReport_nominees.Persnr_ref=evalReport.Persnr_ref AND evalReport.application_ref=?";
$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$RS = $sm->get_result();
//$RS = mysqli_query($SQL);
if (mysqli_num_rows($RS) == 0) {
	mysqli_data_seek($RS_evalReport, 0);
	$RS = $RS_evalReport;
}
if (mysqli_num_rows($RS) > 0) {
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		echo '<script>checkPreviousEvals ('.$row["evalReport_id"].');</script>';
	}
}
?>