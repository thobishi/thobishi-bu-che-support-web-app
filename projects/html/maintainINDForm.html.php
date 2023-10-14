<?
$det_ind_id = $this->dbTableInfoArray["perf_ind_detail"]->dbTableCurrentID;

if ($det_ind_id == 'NEW'){
	$year   = readPOST('detail_budget_year');
	$ind_id = readPOST('detail_lkp_indicator_ref');

	$this->formFields['detail_lkp_indicator_ref']->fieldValue = $ind_id;
	$this->showField("detail_lkp_indicator_ref");

	$this->formFields['detail_budget_year']->fieldValue = $year;
	$this->showField("detail_budget_year");

	$this->formFields['disp_lkp_indicator_ref']->fieldValue = $ind_id;
	$this->formFields['disp_budget_year']->fieldValue = $year;
}else{

	$this->formFields['disp_lkp_indicator_ref']->fieldValue = $this->formFields['detail_lkp_indicator_ref']->fieldValue;
	$this->formFields['disp_budget_year']->fieldValue = $this->formFields['detail_budget_year']->fieldValue;
}
?>
<br>
<table width='95%' border='0' align='center'>
<tr>
<td>
	<span class="speciale">Maintain Indicator List > Add/edit</span>
	<hr>
Please make the changes that you require and then click on Save.
<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td style="font:bold">Budget year:</td>
		<td class="oncolourb"><?$this->showField("disp_budget_year")?></td>
	</tr>
	<tr>
		<td style="font:bold">Indicator:</td>
		<td class="oncolourb">
			<?$this->showField("disp_lkp_indicator_ref");?>
			<br>
			<?
			echo $this->getValueFromTable("lkp_indicator","lkp_indicator_id",$this->formFields['detail_lkp_indicator_ref']->fieldValue,"indicator_desc");
			?>
		</td>
	</tr>
	<tr>
		<td style="font:bold">Title or description</td>
		<td class="oncolourb">
			<table>
				<tr><td style="font:italic">This title will differ depending on the indicator.  It should uniquely identify an individual item 
				satisfying the criteria for the indicator.  It may be the name of an individual or institution or a title of 
				a publication, workshop or conference depending on the indicator.</td></tr>
				<tr><td><?$this->showField("perf_ind_detail_title")?></td></tr>
			</table>
		</td>
	</tr>
	</table>
</td></tr></table>
</td></tr>
</table>
<br>
