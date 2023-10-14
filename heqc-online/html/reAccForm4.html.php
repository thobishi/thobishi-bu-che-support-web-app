<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($reaccred_id); ?>
		</td>
	</tr>
	<tr>
   		<td colspan="2" class="loud">2.3 Self-evaluation of the programme<hr></td>
	</tr>
	<tr>
		<td colspan="2"><br/>If the preparation of this application included any self-evaluation of the programme, please give a summary of the evaluation process, and the bodies/persons consulted, distinguishing between internal and external consultation.<br></td>
	</tr>
	<tr>
	<td><?php $this->showField("eval_process_summary");?></td>
	</tr>
</table>
<br>
