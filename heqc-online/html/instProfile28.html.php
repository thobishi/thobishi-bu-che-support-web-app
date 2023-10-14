<a name="application_form_question14"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<?php 
	$headArr = array();
	array_push($headArr, "");
	array_push($headArr, "Yes / No");
	array_push($headArr, "Comment");
	array_push($headArr, "Upload File");

//	$evalArr = array();
//	array_push($evalArr, "lkp_pol_budgets_infrastracture_desc");

	$fieldsArr = array();
//	array_push($fieldsArr, "yes_no");
//	array_push($fieldsArr, "comment_text");
	array_push($fieldsArr, "type__radio|name__yes_no|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");
	array_push($fieldsArr, "type__textarea|name__comment_text");
?>
<b>14. INFRASTRUCTURE:</b>
<br><br>
<i>Please complete the following information in relation to your institution's infrastructure</i>
<br><br>
<i>Please indicate whether your institution has a documented financial plan, by indicating yes/no, and upload the relevant document. If you do not have such a plan, please use the comment box to explain.</i>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->gridShow("institutional_profile_pol_budgets_infrastracture", "institutional_profile_pol_budgets_infrastracture_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldsArr, $headArr, "lkp_pol_budgets_infrastracture", "lkp_pol_budgets_infrastracture_id", "lkp_pol_budgets_infrastracture_desc", "lkp_pol_budgets_infrastracture_ref", 1, 40, 10, true, "inst_uploadDoc");
//	$this->makeGRID("lkp_pol_budgets_infrastracture", $evalArr, "lkp_pol_budgets_infrastracture_id", "1", "institutional_profile_pol_budgets_infrastracture", "institutional_profile_pol_budgets_infrastracture_id", "lkp_pol_budgets_infrastracture_ref", "institution_ref", $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldsArr, $headArr, "", "10", "40", "4", "", "", "", 1, 1);
?>
</table>
</td></tr></table>
