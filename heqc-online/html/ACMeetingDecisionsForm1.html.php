<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
Use the following decision entering template to enter the decisions and conditions for the programmes discussed at the AC Meeting held on  <strong><?php echo $this->formFields["ac_start_date"]->fieldValue?></strong>:<br><br>Please click on the reference number of the programme you want to enter data for. Once all the programmes are completed, a "Next" button will appear on the right navigation bar. Please, click the "Next" button to go to the next screen.<br>
<br>
<?php 
echo '<input type="hidden" name="re_visit" value=0>';
if (isset($_POST["re_visit"]) && ($_POST["re_visit"] > "")) {
	$this->setValueInTable("active_processes", "active_processes_id", $_POST["re_visit"], "processes_ref", 60);
	$this->setValueInTable("active_processes", "active_processes_id", $_POST["re_visit"], "work_flow_ref", 0);
	$this->setValueInTable("active_processes", "active_processes_id", $_POST["re_visit"], "status", 0);
	$this->setValueInTable("siteVisit", "active_process_ref", $_POST["re_visit"], "siteVisit_complete", 0);
	$this->setValueInTable("siteVisit", "active_process_ref", $_POST["re_visit"], "siteVisit_complete", 0);
	$this->setValueInTable("siteVisit", "active_process_ref", $_POST["re_visit"], "site_visit", "Yes");
}

$this->formActions["next"]->actionMayShow = false;
$counter = 0;
$this->formFields["current_edit_ref"]->fieldValue = 0;
$this->showfield("current_edit_ref");

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$SQL = "UPDATE Institutions_application SET application_status=2 WHERE AC_Meeting_ref=?";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);

$SQL = "SELECT application_id,CHE_reference_code,AC_desision,AC_conditions FROM Institutions_application WHERE AC_Meeting_ref=? ORDER BY CHE_reference_code";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
echo "<table cellpadding='2' cellspacing='2' border='1'>";

while ($row = mysqli_fetch_array($rs)){
	$img = "images/check_mark.gif";
	if (($row["AC_desision"] == -1) || ($row["AC_conditions"] == "")){
		$img = "images/question_mark.gif";
		$counter += 1;
	}
	$site_SQL = "SELECT siteVisit_id, object_sitevisit_visit, site_ref, active_process_ref FROM siteVisit WHERE siteVisit_complete=1 AND application_ref=?";

	$sm = $conn->prepare($site_SQL);
        $sm->bind_param("s", $row["application_id"]);
        $sm->execute();
        $site_RS = $sm->get_result();

	
	//$site_RS = mysqli_query($site_SQL);
	$site_ids = '<td>';
	while ($site_RS && ($site_row=mysqli_fetch_array($site_RS))) {
		if ($site_row["object_sitevisit_visit"] == 2) {
			$site_ids .= $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_row["site_ref"], "site_name")." - <a href='javascript:re_sitevisit(".$site_row["active_process_ref"].");'>Send back for re-visit</a><br>";
		}
	}
	$site_ids .= '</td>';
	echo "<tr>";
	echo "<td><img src='".$img."'></td>";
	echo "<td valign=top><a href=\"javascript:document.defaultFrm.FLD_current_edit_ref.value = '".$row["application_id"]."';moveto('next');\">".$row["CHE_reference_code"]."</a></td>";
	echo $site_ids;
	echo "</tr>";
}
	echo "</table>";
	if ($counter == 0){
		$this->formActions["next"]->actionMayShow = true;
		//$this->scriptTail .= "showHideAction('next', true);";
	}

?>
<script>
	function re_sitevisit(val) {
		document.all.re_visit.value = val;
		moveto('stay');
	}
</script>
</td>
</tr></table>
</td></tr></table>
