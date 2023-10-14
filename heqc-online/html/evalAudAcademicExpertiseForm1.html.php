<table><tr><td>
<br>
<br>
<?php $persNr = $this->dbTableInfoArray["Eval_Auditors"]->dbTableCurrentID;?>

<?php 
$this->formFields["persnr_ref"]->fieldValue = $persNr;
$this->showField("persnr_ref");
?>


<p><strong>Add/Edit Academic Expertise for: </strong>
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
			<td>Major Field/s:</td>
			<td><?php echo $this->showField("major_field")?></td>
		</tr>
		<tr>
			<td>Main Sub-Fields:</td>
			<td><?php echo $this->showField("sub_field")?></td>
		</tr>
		<tr>
			<td>Own Qualification:</td>
			<td><?php echo $this->showField("qualification")?></td>
		</tr>
		<tr>
			<td>Highest level of<br> qualification<br> to evaluate:</td>
			<td><?php echo $this->showField("highest_level")?></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</td></tr></table>
