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
<td colspan="2" class="loud"><b>2.15</b> Self-Evaluation of the programme<br><hr></td>
</tr>
<tr>
	<td colspan="2"><br/><b>2.15.1</b> If the preparation of this application included any self-evaluation of the programme, please give a summary of the evaluation process, and the bodies/persons consulted, distinguishing between internal and external consultation.<br></td>
</tr>
<tr>
<td><?php $this->showField("eval_process_summary");?></td>
</tr>
<tr>
  <td colspan="2"></br><b>2.15.2</b> Having completed your re-accreditation application, are there any areas identified by your institution for improvement and development? 
Please provide a brief summary of the steps being taken to address these areas.<br></td>
</tr>
<tr>
  <td><?php $this->showField("areas_improvement_development");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.15.3</b> Please provide a brief account of any specific areas that you have identified as being especially good practice.
  	<br>
	</td>
</tr>
<tr>
  <td><?php $this->showField("areas_good_practise");?><br><br></td>
</tr>
</table>