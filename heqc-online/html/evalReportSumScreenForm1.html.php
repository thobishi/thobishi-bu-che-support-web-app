<a name="application_form_question1"></a>
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
			<td><span class="specialb"><br>Final evaluation report</span></td>
		</tr><tr>
	</td><td>
		As the chair of this evaluation panel it is your responsibility to submit to the HEQC a single consensual evaluation report. This screen is designed to help with this task.
		<br><br>
		The window below is divided into two panels. The left panel allows you to read the answers of your colleagues to each question in the evaluation form. The right panel allows you to write, what you consider the collective answer. You can access from this screen the institution profile, the application form and its supporting documentation, should you need to consult them again.
		<br><br>
		Once you have finished your report, you will send it to your colleagues for their comments and endorsements. Only after your colleagues have accepted the final report, it can be submitted to the HEQC.
		<br><br><Br>
		<b>1. PROGRAMME DESIGN&nbsp;</b>
<br><br>
<table><tr>
	<td valign="top"><b>1.1</b></td><td valign="top"><b>Evaluate the extent to which the programme is aligned to the institution's mission and goals and if the programme is integrated into the planning and resource allocation processes of the institution.</b></td>
</tr><tr>
	<td valign="top"><b>1.2</b></td><td valign="top"><b>Evaluate the congruency between the qualification structure (SAQA submission) and the programme design and curriculum. Comment on the articulation possibilities with other programmes.</b></td>
</tr><tr>
	<td valign="top"><b>1.3</b></td><td valign="top"><b>Comment on the appropriacy of the NQF level, qualification designation, sequencing of modules and categorization of modules as fundamental, core and electives. Your evaluation should take into account minimum standards ii, iii, iv, v and vi.</b></td>
</tr><tr>
	<td valign="top"><b>1.4</b></td><td valign="top"><b>In relation to the table of learning activities, please evaluate the adequacy of the hours allocated to teaching and the types of learning activities planned. Please note that your evaluation should take into account the specified credits, % of learning activities and the mode of delivery specified by the institution.</b></td>
</tr><tr>
	<td valign="top"><b>1.5</b></td><td valign="top"><b>In the case of professional/vocational programmes, evaluate the extent to which the design of the programme takes into account the requirements of the profession or occupation.</b></td>
</tr><tr>
	<td valign="top"><b>1.6</b></td><td valign="top"><b>In the case of service learning, what is the extent to which the learning programmes are integrated into institutional and academic planning and the required mechanism, structures, systems are in place.</b></td>
</tr><tr>
	<td valign="top"><b>1.7</b></td><td valign="top"><b>Evaluate the teaching and learning policy of the institution.</b></td>
</tr><tr>
	<td valign="top"><b>1.8</b></td><td valign="top"><b>If the institution has provided any other policies relevant to this criterion, please comment on these in relation to the overall criterion statement.</b></td>
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
$question = 1;
$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$this->showField('application_ref');
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
	echo "<b>Average compliance for Question 1:</b>";
	echo "&nbsp".$avg."%<br><br>";
	echo "</td>";
	echo "</tr>";
	$this->formFields["application_comp".$question]->fieldValue = $avg;
	$this->showField('application_comp'.$question);

?>
		
		</table>
	</td><td valign="top">

Add your summary here:<br>
<?php 
$this->showField('application_comment'.$question);
?>	
	</td>
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