<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_race_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_race_desc"), "Number of evaluators per Race", array("Race", "# Evaluators"),"","","`lkp_race` ON lkp_race_id=Race")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_gender_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_gender_desc"), "Number of evaluators per Gender", array("Gender", "# Evaluators"),"","","`lkp_gender` ON lkp_gender_id=Gender")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_yn_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_yn_desc"), "Number of evaluators per Disability", array("Disability", "# Evaluators"),"","","`lkp_yes_no` ON lkp_yn_id=Disability")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr></table>
<br><br>
</td></tr></table>