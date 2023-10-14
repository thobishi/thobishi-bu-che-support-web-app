<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="center">
		<br />
		Indicate whether the recommendation user has accepted to do the directorate recommendation for this application.
		If the user has not answered or declined then please click Previous and assign this application to another user.
	</td>
</tr>
<tr>
	<td align="center">
	<br>
	<table align="center">
	<tr class="oncolour">
		<td>
		<?php 
			$recomm_user_id = $this->GetValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"recomm_user_ref");
			echo $this->getUserName($recomm_user_id);
		?>
		</td>
		<td><?php $this->showfield('lop_status_confirm'); ?></td>
	</tr>
	</table>

<!--	<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
-->
<?php
//$unique_flds = "application_ref__".$app_id;

//$headingArray = array();
//array_push($headingArray,"Recommendation user");
//array_push($headingArray,"Confirmed");

//$fieldArr = array();
//array_push($fieldArr, "type__select|name__user_ref|status__3|size__100|description_fld__name__surname|fld_key__user_id|lkp_table__users|lkp_condition__1|order_by__surname");
//array_push($fieldArr, "type__radio|name__lop_status_confirm|description_fld__lkp_confirm_desc|fld_key__lkp_confirm_id|lkp_table__lkp_confirm|lkp_condition__1|order_by__lkp_confirm_desc");

//$ref = "";

//$this->gridShowRowbyRow("ia_proceedings", "ia_proceedings_id", $unique_flds, $fieldArr, $headingArray);
?>
<!--
<input type="hidden" name="rec_id">
<input type="hidden" name="contact_eval">
-->
<!--</table>
-->
</td></tr>

<tr><td align="center">
<br>
	The recommendation user that has accepted to take part in the programme recommendation will have access to the programme until: <?php $this->showfield('recomm_access_end_date'); ?>
	<br>
	<br>
	</td>
</tr>

</table>
