<?
$ind_val_id = $this->dbTableInfoArray['perf_ind_annual']->dbTableCurrentID;

if ($ind_val_id == 'NEW'){
	$year = readPOST('detail_budget_year');
	$ind_id = readPOST('detail_lkp_indicator_ref');

	$this->formFields['lkp_indicator_ref']->fieldValue = $ind_id;
	$this->showField("lkp_indicator_ref");

	$this->formFields['budget_year']->fieldValue = $year;
	$this->showField("budget_year");

	$this->formFields['disp_lkp_indicator_ref']->fieldValue = $ind_id;
	$this->formFields['disp_budget_year']->fieldValue = $year;
}else{

	$this->formFields['disp_lkp_indicator_ref']->fieldValue = $this->formFields['lkp_indicator_ref']->fieldValue;
	$this->formFields['disp_budget_year']->fieldValue = $this->formFields['budget_year']->fieldValue;
}
?>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
<br>
Please edit the fields below and save
<br>
<br>

<table border='0'>
<tr>
<td>&nbsp;</td>
<td>


	<table border='0'>
	<tr>
		<td style="font:bold">Year:</td>
		<td class="oncolourb"><?$this->showField("disp_budget_year");?></td>
	</tr>
	<tr>
		<td style="font:bold">Indicator:</td>
		<td class="oncolourb"><?$this->showField("disp_lkp_indicator_ref");?>
			<br>
			<?
			echo $this->getValueFromTable("lkp_indicator","lkp_indicator_id",$this->formFields['disp_lkp_indicator_ref']->fieldValue,"indicator_desc");
			?>
		</td>
	</tr>
	<tr>
		<td style="font:bold">Value:</td>
		<td class="oncolourb">
			<table>
			<tr>
				<td style="font:italic">Please enter a numeric value indicating the total projected number for this indicator.
				The list of actual items produced must be captured and will be used to calculate an actual total.
				</td>
			</tr>
			<tr><td><?$this->showField("perf_ind_value");?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" style="font:bold">Comment:</td>
		<td class="oncolourb">
			<table>
			<tr>
				<td style="font:italic">Please enter any comment relevant to this particular year and indicator that you would 
				like noted with this indicator value.
				</td>
			</tr>
			<tr><td><?$this->showField("perf_ind_comment");?></td></tr>
			</table>
		</td>
	</tr>
	</table>

</td>
</tr>
</table>
<br>
</td></tr>
</table>
