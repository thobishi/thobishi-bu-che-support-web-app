<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td>

	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
		<br>
		Please provide your reasons for not approving the evaluator reports on this application. Please add any further comments
		or instructions to the Project Admininstrator to assist with resolving the problem.
		</td>
	</tr>
	<tr>
		<td>
		When you click on <b>Send to Project Administrator</b>, this application will be returned to the Project Administrator
		with your comments and instructions.  You will not have access to approve this application until the Project Administrator
		marks it ready for approval it and sends it back to you.
		</td>
	</tr>
	<tr>
		<td>
		<br>
		<?php $this->showField('manager_evalreport_comment'); ?>
		</td>
	</tr>
	</table>
</td>
</tr>
</table>
