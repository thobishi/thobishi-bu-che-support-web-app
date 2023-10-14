<?
	// determine the past 3 years, the current year, 3 future years
	$n_years = 7;
	for ($i=0; $i<$n_years; $i++){
		$y[$i] = (date('Y') + ($i - 4)) . "/" . (date('Y') + ($i - 3));
	}

	$this->showField('detail_budget_year');
	$this->showField('detail_lkp_indicator_ref');
?>
<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr>
	<td class="oncolourb">
		<b>Performance Indicators for CHE</b>
	</td>
</tr>
<tr>
	<td>
		<br>
		The Council on Higher Education (CHE) was established in terms of the Higher Education Act (1997) and is responsibe for:
		<ul>
			<li>advising the minister on all policy matters related to higher education</li>
			<li>executive responsibility for quality assurance in higher education and training institutions</li>
			<li>monitoring and evaluating the achievement of policy goals and objectives, including reporting on the state of South 
			African higher education</li>
			<li>contributing to the development of higher education</li>
		</ul>
		The Council's advisory function is to provide informed, relevant, independent and strategic advice on 
		higher education (HE) policy issues to the minister of Education. The CHE meets periodically with the Minister 
		to lay the basis for ongoing dialogue and interaction. The CHE's business plan for 2009 indicates a revised and 
		more diverse approach which will enable the CHE to enhance its effectiveness and efficiency in providing advice 
		to the Minister, particularly in relation to a number of key areas.
		<br><br>
		The work of the CHE's Higher Education Quality Assurance Committee (HEQCF) has resulted in the development and 
		implantation of a national system of quality assurance that focuses on the accreditation of new and existing programmes, 
		institutional audits, and quality promotion and capacity development and which applies to equally to public and private 
		providers of higher education. Standard setting has recently been added as a new core function of the CHE, and new 
		structures and regulations for this will be developed in 2009.
	</td>
</tr>
<tr>
	<td>
	<br>
	<b>Active performance indicators</b>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td width="2%" align=center class="oncolourb">&nbsp;</td>
		<td width="23%" align=center class="oncolourb">&nbsp;</td>
		<td width="6%" align=center class="oncolourb">&nbsp;</td>
		<td colspan="3" align="center" class="oncolourb">Past</td>
		<td align="center" class="oncolourb">Current</td>
		<td colspan="3" align="center" class="oncolourb">Projected</td>
	</tr>
	<tr>
		<td width="2%" align=center class="oncolourb">&nbsp;</td>
		<td width="23%" align=center class="oncolourb">Indicator</td>
		<td width="6%" class="oncolourb">Import</td>
		<?
		for ($i=0; $i<$n_years; $i++){
			echo '<td width="8%" align=center class="oncolourb">'.$y[$i].'</td>';
		}
		?>
	</tr>
<?
		$sql1 = <<<INDICATOR
			SELECT *
			FROM lkp_indicator
			WHERE indicator_active_ref = 1
			ORDER BY indicator_order
INDICATOR;

		$rs1 = mysqli_query($sql1);

		if ($rs1){
			while ($row1 = mysqli_fetch_array($rs1)){
				$ind_id = $row1["lkp_indicator_id"];
				$ind_desc = $row1["indicator_desc"];
				$ind_order = $row1["indicator_order"];
				$html = '<tr class="onblue"><td width="2%">'.$ind_order.'</td><td width="25%">'.$ind_desc.'</td>';

				$link1 = $this->scriptGetForm ('lkp_indicator', $ind_id, '_startImportEntity');
				$link1 = htmlspecialchars($link1);
				$html .= '<td width="6%"><a href="'.$link1.'">import</a></td>';

				for ($i=0; $i<$n_years; $i++){
					$val_arr = get_perf_ind_annual_val($ind_id, $y[$i]);
					if (count($val_arr)==0){
						$val = 0;
						$link = "javascript:setIndicator('NEW',".$ind_id.",'".$y[$i]."');";
					}else{
						$val = $val_arr['val'];
						$link = "javascript:setIndicator(".$val_arr['id'].",".$ind_id.",'".$y[$i]."');";
					}
					$html .= <<<HTML
								<td width="8%" align=center valign="middle"><a class="bluesmall" style="font-size:7pt" href="$link">Projected: $val</a><br>
HTML;
					
					$list_total = get_list_indicator_total($ind_id, $y[$i]);
					$link2 = "javascript:getIndicatorList(".$ind_id.",'".$y[$i]."');";

					$html .= <<<HTML
								<a class="greensmall" style="font-size:7pt" href="$link2">Listed: $list_total</a></td>
HTML;
				}				
				$html .= "</tr>";
				echo $html;
			}
		}


		function get_perf_ind_annual_val($ind_id,$ind_year){
		
			$ind = array();
			
			$SQL = <<<ANNUAL
				SELECT *
				FROM perf_ind_annual 
				WHERE lkp_indicator_ref = $ind_id
				AND budget_year = "$ind_year"
ANNUAL;
			
			$rs = mysqli_query($SQL);
			
			if ($rs){
				$n = mysqli_num_rows($rs);
				
				while ($row = mysqli_fetch_array($rs)){
					$ind['id'] = $row["perf_ind_annual_id"];
					$ind['val'] = $row["perf_ind_value"];
				}
			}
			return $ind;
		}

		function get_list_indicator_total($ind_id,$ind_year){
		
			$list_total = 0;
			
			$SQL = <<<ANNUAL
				SELECT count(*) as list_nr
				FROM perf_ind_detail 
				WHERE detail_lkp_indicator_ref = $ind_id
				AND detail_budget_year = "$ind_year"
ANNUAL;
			
			$rs = mysqli_query($SQL);
			if ($rs){
				$row = mysqli_fetch_array($rs);
				$list_total = $row["list_nr"];
			}
			return $list_total;
		}
?>
	</table>
</td></tr>
</table>
<br>
<SCRIPT>
function setIndicator(val,ind_id,year){
	document.defaultFrm.detail_budget_year.value = year;
	document.defaultFrm.detail_lkp_indicator_ref.value = ind_id;
	document.defaultFrm.CHANGE_TO_RECORD.value='perf_ind_annual|'+val;
	moveto("_startEditIndicatorForm");
}
function getIndicatorList(ind_id,year){
	document.defaultFrm.detail_budget_year.value = year;
	document.defaultFrm.detail_lkp_indicator_ref.value = ind_id;
	moveto("_startmaintainIND");
}
</SCRIPT>