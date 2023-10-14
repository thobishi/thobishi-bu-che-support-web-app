<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop();?>
<br><br>
<table width="75%" border=0  cellpadding="2" cellspacing="2"><tr>
	<td>
		<b>Please use the table below to indicate your decision concerning the outcome of this programme:</b>
	</td>
</tr><tr>
	<td>
<?php 
	$headArr = array();
	array_push($headArr, "DECISION");
	array_push($headArr, "CONDITIONS");
	
	$fieldArr = array();
	array_push($fieldArr, "type__select|name__AC_desision_recommend|description_fld__lkp_title|fld_key__lkp_id|lkp_table__lkp_desicion|lkp_condition__1|order_by__lkp_id");
	array_push($fieldArr, "type__textarea|name__AC_conditions_recommend|size__5");
	
	$this->gridShowTableByRow("evalReport", "evalReport_id", "evalReport_id__".$this->dbTableInfoArray["evalReport"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10);
?>
	</td>
</tr></table>
<br><br>
</td></tr></table>