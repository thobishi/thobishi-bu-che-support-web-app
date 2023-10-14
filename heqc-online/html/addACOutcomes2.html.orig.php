<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<br>
You are entering AC outcome information for:
</td></tr>
<tr><td align="center">
<?php 
	$application_id = ($this->dbTableInfoArray['Institutions_application']->dbTableCurrentID);
	$this->displayApplicationForOutcomes($application_id);
	$AC_history_link = "<a href='pages/acMeetingHistory.php?app_ref=".base64_encode($application_id)."' target='_blank'><i>(View this application's AC meeting history)</i></a>";

?>
</td></tr>

<tr>
<td>
<br>
	<table border=0 cellpadding="2" cellspacing="2">
		<tr class="onblue">
			<td width="30%">Please select the AC meeting that this application was tabled at:</td>
			<td><?php echo $this->showField("AC_Meeting_ref")?></td>
		</tr>

		<tr class="onblue">
			<td valign="top">Please select the AC outcome of the application from the above meeting:</td>
			<td><?php echo $this->showField("AC_desision")?></td>
		</tr>

		<tr class="onblue">
			<td valign="top">Please upload the conditions/comments of this application's accreditation outcome:</td>
			<td><?php echo $this->makeLink("AC_conditions_doc")?></td>
		</tr>

		<tr valign="top" class="onblue">
			<td valign="top">Please enter any relevant comments:</td>
			<td><?php echo $this->showField("AC_conditions")?></td>
		</tr>
		<tr class="onblue">
			<td width="30%">&nbsp;</td>
			<td><?php echo $AC_history_link?></td>
		</tr>
	</table>


<?php 
/*
//grid: for future use - adding multiple outcomes?

	echo '<table border="0" width="95%">';
	echo "<tr>";
	echo "<td valign='top' align='center' width='17%'>";

	$dFields = array();
	//array_push($dFields, "type__select|name__Persnr_ref|description_fld__CONCAT(Surname,',',Names)|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__Surname>'' AND Evaluator=1|order_by__Surname");
	array_push($dFields, "type__select|name__AC_Meeting_ref|description_fld__CONCAT(ac_start_date,' - ',ac_meeting_venue)|fld_key__ac_id|lkp_table__AC_Meeting|lkp_condition__ac_id|order_by__ac_start_date");

	echo "</td>";
	echo "<td width='17%'>";
	array_push($dFields, "type__select|name__AC_decision|description_fld__lkp_title|fld_key__lkp_id|lkp_table__lkp_desicion|lkp_condition__lkp_id|order_by__lkp_title");
	echo "</td>";
	echo "<td width='50%'>";
	array_push($dFields, "type__textarea|name__AC_conditions");
	echo "</td>";
	echo "<td width='5%'>";
	//to add Outcome type when we've discussed: Round Robin/representation.etc
	//array_push($dFields, "type__radio|name__AC_type|description_fld__lkp_AC_type_desc|fld_key__lkp_AC_type_id|lkp_table__lkp_AC_type|lkp_condition__lkp_AC_type_id|order_by__lkp_AC_type_desc");


	//array_push($dFields, "type__radio|name__do_summary|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");

	//$hFields = array("AC Meeting Date", "Decision", "Conditions", "Type of Outcome");
	$hFields = array("AC Meeting Date", "Decision", "Conditions");

	$this->gridShowRowByRow("ia_AC_outcomes","ia_AC_outcomes_id","application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dFields,$hFields, 40, 5, "true","true");

	echo "</td></tr>";
	echo '</table>';
*/
?>

</td>
</tr>


</td></tr>
</table>
<br>

