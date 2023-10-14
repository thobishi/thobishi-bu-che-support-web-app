<br>
<br>
<table border='0'>
<tr>
<td>&nbsp;</td>
<td>
	<table border='0'>
	<tr>
	<td valign="top"><strong>Training History for: </strong> 
<?php 

$persNr = $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;


$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
                
$SQL = "SELECT CONCAT(Names,' ',Surname) FROM Eval_Auditors WHERE persNr=?";

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
	<td valign="top">Click on the <i>course name</i> to edit a training record. Click on <i>Add training</i> in the menu to add a new training record.</td>
	</tr>
	<tr>
	<td>
		<table border="1">
		<tr>
			<td width="13%"><strong>Date Completed</strong></td>
			<td width="25%"><strong>Course Name</strong></td>		
			<td width="18%"><strong>Recommendation</strong></td>
			<td width="39%"><strong>Comment</strong></td>
			<td width="5%"><strong>Click to Delete</strong></td>			
		</tr>
<?php 
$SQL = "SELECT eval_auditors_training_id, persnr, date_completed, lkp_course_name, lkp_rating_desc, comment FROM eval_auditors_training,lkp_course,lkp_rating WHERE eval_auditors_training.course_id_ref = lkp_course.lkp_course_id and eval_auditors_training.recommend = lkp_rating.lkp_rating_id and eval_auditors_training.persNr=? ORDER BY date_completed";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $persNr);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
	?>
		<tr>
			<td><?php echo $row["date_completed"]?></td>
<?php			echo '<td><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value=\'eval_auditors_training|'.$row["eval_auditors_training_id"].'\'; moveto(\'next\');">' . $row["lkp_course_name"] . "</a></td>"."\n";
?>
			<td><?php echo $row["lkp_rating_desc"]?></td>
			<td><?php echo $row["comment"]?></td>
			<td><a href="javascript:delEvaltrain(<?php echo $row["eval_auditors_training_id"]?>)">[delete]</a></td>
		</tr>
	<?php 
	}
}
else{
?>
		<tr>
			<td colspan="5">No training captured yet. Please click on Add training to add training for this person.</td>
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
function delEvaltrain(val){
	document.defaultFrm.DELETE_RECORD.value = 'eval_auditors_training|eval_auditors_training_id|'+val;
	moveto('stay');
}
</script>
