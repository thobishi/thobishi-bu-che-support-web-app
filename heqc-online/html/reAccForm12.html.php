<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
  <td colspan="2" class="loud"><b>2.10</b> Teaching and learning<br><hr></td>
</tr>
<tr>
  <td colspan="2"><br/><b>2.10.1</b> Types of learning activities in the programme, and number of hours a student is expected to devote to each type. (Refer to the table provided in 2.8.1 "Programme design details".)<br></td>
</tr>
<tr>
	<td>


<?php 

	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}

	$headArr = array();
		array_push($headArr, "Type of learning activity");
		array_push($headArr, "Hours");
		array_push($headArr, "% of learning time");

		$fieldArr = array();

		array_push($fieldArr, "type__text|name__hours|size__3");
		array_push($fieldArr, "type__text|name__learning_time|size__3");

	$this->gridShow("reaccred_learning_activities", "reaccred_learning_activities_id", "reaccred_programme_ref__".$progID, $fieldArr, $headArr, "reaccred_lkp_learning_activities", "lkp_learning_activities_id", "lkp_learning_activities_desc", "lkp_learning_activities_ref", 1)

?>

 </td>
</tr>
<!--<tr>
  <td colspan="2"><br/><b>2.10.2</b> What provision is made to ensure that all academic staff, both full-time and part-time, are familiar with the teaching and learning policy of the institution, and are able to apply the policy appropriately and in a manner consonant with the programme design, outcomes, mode(s) of delivery, learning materials, assessment criteria, and student profile?<br></td>
</tr>
<tr>
  <td colspan="2"><?php // $this->showField("teaching_learning_policy");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.10.3</b> What systems, structures and procedures are in place to ensure that members of the academic staff participate in and contribute to curriculum development and the revision of learning materials?<br></td>
</tr>
<tr>
  <td><?php//$this->showField("curriculum_development");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.10.4</b> What procedures are in place for monitoring, evaluating and improving teaching and learning?<br></td>
</tr>
<tr>
  <td><?php//$this->showField("improving_teaching_learning");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.10.5</b> How does the programme take into account the need to include focus on HIV/AIDS?<br></td>
</tr>
<tr>
  <td><?php//$this->showField("focus_on_hiv");?><br><br></td>
</tr>-->
<tr>
  <td colspan="2"><b>2.10.2</b> What mechanisms exist for identifying and supporting weak or "at-risk" students?<br></td>
</tr>
<tr>
  <td><?php $this->showField("mechanisms_identifying_students");?><br><br></td>
</tr>
</table>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>

<script>
//	improvement(document.defaultFrm.FLD_1_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
//	checkCriteria (document.defaultFrm.FLD_1_criteria);

	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}

	function checkPlacement (obj, obj2) {
		if (obj.value == 1) {
			alert("Please fill in the block below");
		}
	}
</script>