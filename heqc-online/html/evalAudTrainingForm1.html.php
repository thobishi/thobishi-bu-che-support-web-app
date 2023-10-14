<table><tr><td>
<br>
<br>
<?php $persNr = $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;?>

<?php 
$this->formFields["persnr"]->fieldValue = $persNr;
$this->showField("persnr");
?>


<p><strong>Add/Edit training for: </strong>
<?php 
echo $this->getValueFromTable("Eval_Auditors", "Persnr", $persNr, "Names")." ".$this->getValueFromTable("Eval_Auditors", "Persnr", $persNr, "Surname");
/*$SQL = "SELECT CONCAT(Names,' ',Surname) FROM Eval_Auditors WHERE persNr=".$persNr;
$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo  $row[0] . "</td>";
	}
}*/
?>
</p>
<table border='0'>
<tr>
	<td>&nbsp;</td>
	<td>
		<table border='0'>
		<tr>
			<td>Course Name:</td>
			<td><?php echo $this->showField("course_id_ref")?></td>
		</tr>
		<tr>
			<td>Date Completed:</td>
			<td><?php echo $this->showField("date_completed")?></td>
		</tr>
		<tr>
			<td>Recommend this person:</td>
			<td><?php echo $this->showField("recommend")?></td>
		</tr>
		<tr>
			<td>Reason:</td>
			<td><?php echo $this->showField("comment")?></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</td></tr></table>
