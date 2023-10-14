<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<center>Indicate who of the following evaluators have accepted to take part in the site visit evaluation.</center>
<br><br>
<table width="55%" border=0  cellpadding="2" cellspacing="2" align="center">
<?php 
$SQL = "SELECT Persnr_ref, Names, Surname, E_mail, is_manager FROM Eval_Auditors, evalReport WHERE evalReport_status_confirm=1 AND Persnr=Persnr_ref AND application_ref=?";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$RS = $sm->get_result();

//$RS = mysqli_query($SQL);
while ($row = mysqli_fetch_object($RS)) {
	echo '<tr>';
	echo '<td nowrap>'.$row->Surname.', '.$row->Names.'</td>';
	echo '<td nowrap><input type="radio" name="'.$row->Persnr_ref.'" value="1">No</td>';
	echo '<td nowrap><input type="radio" name="'.$row->Persnr_ref.'" value="2">Yes</td>';
	echo '<td nowrap><input type="radio" name="'.$row->Persnr_ref.'" value="3">Has not replied</td>';
	echo '</tr>';
}
?>
</table>
<br><br>
<center>If all evaluators have confirmed start the logistic arrangements for the visit, by clicking "Next".</center>
<br><br>
</td></tr></table>
<script>
	function checkBoxes() {
		obj = document.defaultFrm;
		if (obj.MOVETO.value == "next") {
			for (i=0; i<obj.length; i++) {
				if ((obj[i].type == "radio") && !(obj[i].checked)) {
					if (obj[i].value == 2) {
						alert('All evaluators must reply before continuing');
						return false;
					}
				}
			}
		}
		return true;
	}
</script>
