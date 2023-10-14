<!--a name="application_form_question1"></a-->
<a name="application_form_question15"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php 
	$headArr = array();
	array_push($headArr, "");
	array_push($headArr, "Yes / No");
	array_push($headArr, "Comment");
	array_push($headArr, "Upload File");

	$fieldsArr = array();
	array_push($fieldsArr, "type__radio|name__yes_no|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");
	array_push($fieldsArr, "type__textarea|name__comment_text");
?>
<br><br>
<b>15. STATUS OF PROGRAMME/QUALIFICATION OFFERINGS:</b>
<br><br>
<i>In the table below provide information about the registration status of your programmes and sites of delivery. Please, ensure that your information is updated as and when required.</i>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->gridShow("institutional_profile_pol_budgets_prog_offerings", "institutional_profile_pol_budgets_prog_offerings_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldsArr, $headArr, "lkp_pol_budgets_prog_offerings", "lkp_pol_budgets_prog_offerings_id", "lkp_pol_budgets_prog_offerings_desc", "lkp_pol_budgets_prog_offerings_ref", 1, 40, 10, true, "inst_uploadDoc");
?>
</table>
</td></tr></table>
