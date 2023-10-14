<br>
<br>
<table border='0'>
<tr>
<td>&nbsp;</td>
<td>
	<table border='0'>
	<tr>
	<td valign="top"><strong>Academic Expertise for: </strong> 
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
	<td valign="top">Click on the <i>major field</i> to edit an academic expertise record. Click on <i>Add academic expertise</i> in the menu to add a new record.</td>
	</tr>
	<tr>
	<td>
		<table border="1">
		<tr>
			<td width="13%"><strong>Major Field</strong></td>
			<td width="25%"><strong>Main Sub-field</strong></td>		
			<td width="18%"><strong>Own Qualification</strong></td>
			<td width="39%"><strong>Highest level of<br>qualification<br>to evaluate</strong></td>
			<td width="5%"><strong>Click to Delete</strong></td>			
		</tr>
<?php 
$SQL = "SELECT * FROM eval_auditors_academic_expertise  WHERE eval_auditors_academic_expertise.persnr_ref=?";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $persNr);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
	?>
		<tr>
<?php 			echo '<td><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value=\'eval_auditors_academic_expertise|'.$row["eval_auditors_academic_expertise_id"].'\'; moveto(\'next\');">' . $row["major_field"] . "</a></td>"."\n";
?> 			<td><?php echo $row["sub_field"]?></td> 
			<td><?php echo $row["qualification"]?></td>
			<td><?php echo $row["highest_level"]?></td>
			<td><a href="javascript:delEvalAcaExp(<?php echo $row["eval_auditors_academic_expertise_id"]?>)">[delete]</a></td>
		</tr>
	<?php 
	}
}
else{
?>
		<tr>
			<td colspan="5">No academic expertise captured yet. Please click on Add academic expertise to add academic expertise for this person.</td>
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
function delEvalAcaExp(val){
	document.defaultFrm.DELETE_RECORD.value = 'eval_auditors_academic_expertise|eval_auditors_academic_expertise_id|'+val;
	moveto('stay');
}
</script>
