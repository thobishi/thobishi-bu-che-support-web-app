<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_province_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_province_desc"), "Number of evaluators per Province", array("Province", "# Evaluators"), "", "", "`lkp_province` ON lkp_province_id=Province ")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_full_part_desc,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_full_part_desc"), "Number of evaluators per Full / Part time", array("Time", "# Evaluators"),"","","`lkp_full_part` ON lkp_full_part_id=Full_part")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_qualifications_desc,'No Value')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_qualifications_desc"), "Number of evaluators per Highest Qualification", array("Highest Qualification", "# Evaluators"),"","","`lkp_qualifications` ON lkp_qualifications_id = qualifications_ref")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->evaluatorStats(array("IFNULL(lkp_employer_name,'Unclassified')", "count(*)"), array("`Eval_Auditors`"), "", "", array("lkp_employer_name"), "Number of evaluators per Institution", array("Institution", "# Evaluators"),"","","`lkp_employer` ON lkp_employer_id = employer_ref")?></td>
</tr><tr>
	<td>&nbsp;</td>
</tr></table>
<br><br>
</td></tr></table>