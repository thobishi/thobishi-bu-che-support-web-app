<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>PROGRAMME INFORMATION:</b>
<br><br>
<b>1. PROGRAMME DESIGN: (criterion 1 - part 2/3)</b>
<br><br>
<b>PROGRAMME LEVEL:</b>
<br><br>
<table width="85%" align="center" border=0 cellpadding="2" cellspacing="2"><tr>
	<td valign="top" class="oncolourb">Title of Proposed Programme</td>
	<td valign="top"><?php $this->showField("1_title_program") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Qualification Type</td>
	<td valign="top"><?php $this->showField("1_qual_type") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Designation</td>
	<td valign="top"><?php $this->showField("1_designation") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Qualifier</td>
	<td valign="top"><?php $this->showField("1_qualifier") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Second Qualifier</td>
	<td valign="top"><?php $this->showField("1_2nd_qualifier") ?></td>
</tr>
<!--
<tr>
	<td valign="top" class="oncolour">Total NQF Credits</td>
	<td valign="top"><span class="oncolour">Breakdown of NQF Credits at Different Levels:</span>
		<table><tr>
			<td valign="top" class="oncolour">Year 1</td>
			<td valign="top"><?php//$this->showField("1_nqf_year1") ?></td>
		</tr><tr>
			<td valign="top" class="oncolour">Year 2</td>
			<td valign="top"><?php // $this->showField("1_nqf_year2") ?></td>
		</tr><tr>
			<td valign="top" class="oncolour">Year 3</td>
			<td valign="top"><?php //$this->showField("1_nqf_year3") ?></td>
		</tr></table>
	</td>
</tr>
-->
<tr>
	<td valign="top" class="oncolourb">Minimum Duration Full-time <br>(e.g. 3 years or 6 months)</td>
	<td valign="top"><?php $this->showField("min_duration_full_time") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Minimum Duration Part-time <br>(e.g. 3 years or 6 months)</td>
	<td valign="top"><?php $this->showField("min_duration_part_time") ?></td>
</tr></table>
<br><br>
<b>BREAKDOWN OF NQF CREDITS AT DIFFERENT LEVELS:</b>
<a name="appTable_1_nqf_breakdown_per_year"></a>
<br><br>
<?php 
	// Placement (Work)
	// appTable_1_nqf_breakdown_per_year
	$headArr = array();
	$headArr["Year"] = "1";
	$headArr["Credits per NQF Level"] = "6";
	
	$fieldsArr = array();
	$fieldsArr["year_of_study"] = array("(e.g. Year 1)", 10);
	$fieldsArr["credits_level_5"] = "Level 5";
	$fieldsArr["credits_level_6"] = "Level 6";
	$fieldsArr["credits_level_7"] = "Level 7";
	$fieldsArr["credits_level_8"] = "Level 8";
	$fieldsArr["credits_level_9"] = "Level 9";
	$fieldsArr["credits_level_10"] = "Level 10";
	
	echo $this->gridDisplay("Institutions_application", "appTable_1_nqf_breakdown_per_year", "appTable_1_nqf_breakdown_per_year_id", "application_ref",$fieldsArr, 3, 0, $headArr);
	
?>
<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>
</td</tr></table>
<br>
<script>
//	improvement(document.defaultFrm.FLD_1_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
//	checkCriteria (document.defaultFrm.FLD_1_criteria);
</script>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>