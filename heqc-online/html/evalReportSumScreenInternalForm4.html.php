<a name="application_form_question5"></a>
<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
		<br>
		<?php 
			if (! $this->view ) {
				$this->showInstitutionTableTop();
			}
		?>
	</td>
		<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr>
			<td></td>
		</tr><tr>
	</td><td>
		<b>6. STUDENT ASSESSMENT&nbsp;</b>
<br><br>
Taking into account the relationship between the institution's answer to question 5 and the supporting documentation provided, indicate
<br><br>
<table><tr>
	<td valign="top"><b>6.1</b></td><td valign="top"><b>Evaluate the moderation and external examiner policies of the institution in relation to this programme.</b></td>
</tr><tr>
	<td valign="top"><b>6.2</b></td><td valign="top"><b>Comment on the adequacy of the institution's policies to monitor student progress.</b></td>
</tr><tr>
	<td valign="top"><b>6.3</b></td><td valign="top"><b>Are the assessment policies of the institution explicitly stated and to what extent are they reliable and valid?</b></td>
</tr><tr>
	<td valign="top"><b>6.4</b></td><td valign="top"><b>Do the institution's policies ensure that there are feedback loops between assessment and curriculum design as well as monitoring of student progress?</b></td>
</tr><tr>
	<td valign="top"><b>6.5</b></td><td valign="top"><b>Comment on the administrative systems in place for recording of assessment, dealing with student disputes and certification.</b></td>
</tr><tr>
	<td valign="top"><b>6.6</b></td><td valign="top"><b>Evaluate the extent to which the RPL policy, procedures and systems are rigorous and aligned to the learning programme. If RPL policies are to be applied, comment on the competence of staff in relation to the assessment of RPL.</b></td>
	</tr></table>
<br><br>
	</td>
</tr></table>
<table border="1" width="100%"><tr>
	<td>
		<table><tr>
			<td><b>Summarised evaluator comments:</b></td>
		</tr><tr>
			<td>&nbsp;</td>
		</tr>
		
<?php 
$question = 6;
$avg = 0;
$SQL = "SELECT * FROM evalReport WHERE application_ref=? AND evalReport_status_confirm=1";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);

$fields = mysqli_list_fields($this->DBserver, "evalReport");
$columns = mysqli_num_fields($rs);

while ($row = mysqli_fetch_array($rs)){
	$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
	$avg += $row["evalReport_q".$question."_comp"];
	echo "<tr>";
	echo "<td><b><a href='javascript:openEvalDiv(document.all.div_".$row["Persnr_ref"].");'>".$name."</a></b> - ".$row["evalReport_q".$question."_comp"]."% compliance</td>";
	echo "</tr><tr><td>";
	echo "<div id='div_".$row["Persnr_ref"]."' style='display:none'>";
	$i=0;
	$j=1;
	while ($i < mysqli_num_fields($rs)) {
		$meta = mysqli_fetch_field($rs, $i);
		if (stristr($meta->name, $question."_eval_question_".$j)) {
			echo "<table><tr>";
			echo "<td><b>".$question.".".$j.":</b></td>";
			echo "<td>".$row[$question."_eval_question_".$j]."</td>";
			echo "</tr></table>";
			$j++;
		}
		$i++;
	}
	echo "	<table><tr>";
	echo "		<td><b>Comment on minimum standards:</b> <br>".$row["evalReport_q".$question]."</td>";
	echo "	</tr></table>";
	echo "</div>";
	echo "</td></tr><tr>";
	echo "<td><i>";
	if ($row["evalReport_comment".$question] > "") {
	echo "Notes:<br>".$row["evalReport_comment".$question];
	}
	echo "</i><br><br></td>";
	echo "</tr>";
}
$avg = round($avg/mysqli_num_rows($rs),2);
	echo "<tr>";
	echo "<td>";
	echo "<b>Average compliance for Question 6:</b>";
	echo "&nbsp".$avg."%<br><br>";
	echo "</td>";
	echo "</tr>";
//	$this->formFields["application_comp".$question]->fieldValue = $avg;
//	$this->showField('application_comp'.$question);

?>
		
		</table>
	</td><td valign="top">

<b>Chair Evaluator's comment:</b><br>
<?php 
$this->formFields["application_comment".$question]->fieldValue = $this->getValueFromTable("application_summery_comments", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "application_comment".$question);
$this->showField('application_comment'.$question);
?>	
	</td>
</tr></table>
<table width="100%"><tr>
	<td><b>Please add your comment here.</b></td>
</tr><tr>
	<td><?php $this->showField('application_comment_internal'.$question);?></td>
</tr></table>
<script>
	function openEvalDiv (obj) {
		if (obj.style.display == "none") {
			obj.style.display = "Block";
		}else{
			obj.style.display = "none";
		}
	}
</script>
<?php $this->createEvalActions($question);?>
<?php $this->createEvalSummaryActions($question);?>