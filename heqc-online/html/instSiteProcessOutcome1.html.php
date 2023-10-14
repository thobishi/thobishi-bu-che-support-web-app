<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>

<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id); ?>
	</td>
</tr>
<tr>
	<td>
<?php
		$rows = "";
		$site_arr = $this->getSiteVisitsForApp($site_proc_id);
//var_dump($site_arr);
		$rows = "";
		foreach($site_arr as $site){
			$whr = " AND inst_site_visit_progs.recomm_offering_ind > 0";
			$progs_approved = $this->getSelectedApplicationsForSiteVisit($site['inst_site_visit_id'], $whr);
			$progs_on_site = $this->getProgrammesForSite($site['institutional_profile_sites_ref']);
//var_dump($progs_approved);
//var_dump($progs_on_site);
			$arr_id = array_unique(array_merge(array_keys($progs_approved), array_keys($progs_on_site)));
			$arr_progs = array();
			foreach($arr_id as $i){
				if (!isset($arr_progs[$i]) && isset($progs_approved[$i])){
					$arr_progs[$i] = $progs_approved[$i];
				}
				if (!isset($arr_progs[$i]) && isset($progs_on_site[$i])){
					$arr_progs[$i] = $progs_on_site[$i];
				}
			}
//var_dump($arr_progs);
//var_dump($arr_id);
			$prog_rows = "";
			foreach($arr_id as $i){
				$on = "No";
				$appr = "No";
				$add = "&nbsp;";
				$rm = "&nbsp";
				if (isset($progs_on_site[$i])){
					$on = "Yes";
				}			
				if (isset($progs_approved[$i])){
					$appr = "Yes";
					$add = ($on=="No") ? '<input type="checkbox" name="add[]" value="'.$site["institutional_profile_sites_ref"]."__".$progs_approved[$i]["application_ref"].'">' : "&nbsp;";
				}			
				if (isset($progs_on_site[$i])){
					$rm = ($appr == "No") ? '<input type="checkbox" name="rm[]" value="'.$progs_on_site[$i]["lkp_sites_id"].'">' : "&nbsp;";
				}			


				$prog_rows .= <<<ROWS
					<tr>
						<td>{$arr_progs[$i]["program_name"]}</td>
						<td>$on</td>
						<td>$appr</td>
						<td>$add</td>
						<td>$rm</td>
					</tr>
ROWS;
			}		
			$rows .= <<<ROWS
					{$site["site_name"]} - {$site["location"]} ({$site["establishment"]})
					<table width="95%" border="1" align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td width="60%">Programme Name</td>
						<td width="10%">Indicated<br />in HEQC</td>
						<td width="10%">Approved<br />for site</td>
						<td width="10%">Add</td>
						<td width="10%">Remove</td>
					</tr>
					$prog_rows
					</table>
					<br />
ROWS;
		}
		$html = <<<HTML
			<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td>
					$rows
				</td>
			</tr>
			</table>
HTML;
		echo $html;
?>
	</td>
</tr>
</table>

