<?php
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id",$site_app_id, "institution_ref");
	$html = "";
?>
<input type='hidden' name='cmd' value=''>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteVisitTableTop($site_visit_id); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Indicate which panel members will visit this site
	</td>
</tr>
<tr>
	<td>
		<br>
		The panel members displayed below have been assigned to visit the above site.  Indicate which of these evaluators is the 
		chairman and will be responsible for uploading the evaluator report for the site visit.
	</td>
</tr>
<tr>
	<td>
		<br />
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
		
			$dFields = array();
			array_push($dFields, "type__select|name__evaluator_persnr|description_fld__CONCAT(Surname,', ',Names)|fld_key__Persnr|lkp_table__Eval_Auditors,inst_site_app_proceedings_eval|lkp_condition__inst_site_app_proceedings_eval.inst_site_app_proc_ref=".$site_app_id." AND Eval_Auditors.Persnr=inst_site_app_proceedings_eval.evaluator_persnr|order_by__Surname");
			array_push($dFields, "type__radio|name__chairman_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");

			$hFields = array("Evaluator", "Chairman");
			//$uFields = "inst_site_visit_ref__".$site_visit_id."|inst_site_app_proc_ref__".$site_app_id;
			$uFields = "inst_site_visit_ref__".$site_visit_id;
			
		?>
		<table width="70%" align="center">
			<?php $this->gridShowRowByRow("inst_site_visit_eval","inst_site_visit_eval_id", $uFields, $dFields, $hFields, 40, 5, "true", "true",0);	?>
		</table>
	</td>
</tr>
</table>

<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
