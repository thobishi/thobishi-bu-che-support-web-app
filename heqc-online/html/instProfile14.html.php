<a name="application_form_question10"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>10. GENERAL LIBRARY BUDGET: (in Rands)</b>
<?php 

	$headArr = array();
	array_push($headArr, "");
	// Display last 5 years to max of 2017 - because data structure requires additional columns added in the database.
	// If you add more columns then this can be extended to the last year of the column added.
	$year = (date('Y') > 2022) ? 2022 : date('Y');

	for ($i=6; $i>=0; $i--){
		$y1 = $year - $i;
		$y2 = ($y1 + 1) % 2000;
		array_push($headArr, "$y1/$y2");
	}
	//array_push($headArr, "2003/4");
	//array_push($headArr, "2004/5");
	//array_push($headArr, "2005/6");
	//array_push($headArr, "2006/7");
	//array_push($headArr, "2007/8");
	//array_push($headArr, "2008/9");
	//array_push($headArr, "2020/21");
	array_push($headArr, "Comments");
	
	$fieldArr = array();
	for ($i=6; $i>=0; $i--){
		$y1 = $year - $i;
		$y2 = ($y1 + 1) % 2000;
		array_push($fieldArr, "type__text|name__{$y1}_{$y2}");
	}
	//array_push($fieldArr, "type__text|name__2002_3|status__3");
	//array_push($fieldArr, "type__text|name__2004_5|status__3");
	//array_push($fieldArr, "type__text|name__2005_6|status__3");
	//array_push($fieldArr, "type__text|name__2006_7");
	//array_push($fieldArr, "type__text|name__2007_8");
	//array_push($fieldArr, "type__text|name__2008_9");
	array_push($fieldArr, "type__textarea|name__comments_text");
?>
<br><br>
<i>Please complete the following information in relation to your institution's infrastructure</i>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->gridShow("institutional_profile_library_budget", "institutional_profile_library_budget_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, "lkp_inst_profile_library_budget", "lkp_inst_profile_library_budget_id", "lkp_inst_profile_library_budget_desc", "lkp_inst_profile_library_budget_ref");
//	$this->makeGRID("lkp_inst_profile_library_budget", $evalArr, "lkp_inst_profile_library_budget_id", "1", "institutional_profile_library_budget", "institutional_profile_library_budget_id", "lkp_inst_profile_library_budget_ref", "institution_ref", $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
</td></tr></table>
