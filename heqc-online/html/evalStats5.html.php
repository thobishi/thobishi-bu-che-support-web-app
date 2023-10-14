<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_experience_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_experience_desc"), "Number of evaluators per Teaching Experience", array("Experience", "# Evaluators"),"","","`lkp_experience` ON lkp_experience_id=Teaching_experience")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_experience_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_experience_desc"), "Number of evaluators per Research Experience", array("Experience", "# Evaluators"),"","","`lkp_experience` ON lkp_experience_id=Research_expereince")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_experience_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_experience_desc"), "Number of evaluators per Administration Experience", array("Experience", "# Evaluators"),"","","`lkp_experience` ON lkp_experience_id=Admin_Experience")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_experience_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_experience_desc"), "Number of evaluators per Management Experience", array("Experience", "# Evaluators"),"","","`lkp_experience` ON lkp_experience_id=Manage_Experience")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr></table>
<br><br>
</td></tr></table>