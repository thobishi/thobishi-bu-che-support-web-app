
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><?php $this->evaluatorStats(array("CONCAT(Names, ' ', Surname)", "CHE_reference_code", "evalReport_date_sent"), array("`Eval_Auditors`", "`Institutions_application`", "`evalReport`"), array("application_id=application_ref", "Persnr_ref=Persnr", "evalReport_completed=0", "evalReport_status_confirm=1"), " AND ", "", "Evaluators", array("Name", "Reference Number", "Date Sent"), array("CONCAT(Names, ' ', Surname)", "CHE_reference_code"))?></td>
</tr></table>
<br><br>
