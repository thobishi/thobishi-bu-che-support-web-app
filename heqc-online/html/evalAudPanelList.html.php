<br>
<br>
<table border='0'>
<tr>
<td>&nbsp;</td>
<td>
	<table border='0'>
	<tr>
	<td valign="top"><strong>Panel Attendance History for: </strong> 
<?php 

$persNr = $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;

$SQL = "SELECT CONCAT(Names,' ',Surname) FROM Eval_Auditors WHERE persNr=?";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $persNr);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo  $row[0] . "</td>";
	}
}
?>
	</tr>
	<tr>
	<td valign="top">Click on the <i>audited institution</i> to edit a panel attendance record. Click on <i>Add audit panel attendance</i> in the menu to add a new panel attendance record.</td>
	</tr>
	<tr>
	<td>
		<table border="1">
		<tr>
			<td width="13%"><strong>Date Attended</strong></td>
			<td width="25%"><strong>Institution Audited</strong></td>		
			<td width="18%"><strong>Recommendation</strong></td>
			<td width="39%"><strong>Comment</strong></td>
			<td width="5%"><strong>Click to Delete</strong></td>			
		</tr>
<?php 
$SQL = "SELECT eval_auditors_panel_id, persnr, date_attended, HEI_name, lkp_rating_desc, comment  FROM eval_auditors_panel,HEInstitution,lkp_rating  WHERE eval_auditors_panel.institution_ref = HEInstitution.HEI_id and eval_auditors_panel.recommend = lkp_rating.lkp_rating_id and eval_auditors_panel.persNr= ? ORDER BY date_attended";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $persNr);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
	?>
		<tr>
			<td><?php echo $row["date_attended"]?></td>
<?php			echo '<td><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value=\'eval_auditors_panel|'.$row["eval_auditors_panel_id"].'\'; moveto(\'next\');">' . $row["HEI_name"] . "</a></td>"."\n";
?>
			<td><?php echo $row["lkp_rating_desc"]?></td>
			<td><?php echo $row["comment"]?></td>
			<td><a href="javascript:delEvalpanel(<?php echo $row["eval_auditors_panel_id"]?>)">[delete]</a></td>
		</tr>
	<?php 
	}
}
else{
?>
		<tr>
			<td colspan="5">No panel attendance captured yet. Please click on Add panel attendance to add panel attendance for this person.</td>
		</tr>
<?php 
}
	?>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
<script>
function delEvalpanel(val){
	document.defaultFrm.DELETE_RECORD.value = 'eval_auditors_panel|eval_auditors_panel_id|'+val;
	moveto('stay');
}
</script>
