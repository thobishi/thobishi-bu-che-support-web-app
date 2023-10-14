<br><br>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		Select an institution or enter an application reference number:
	</td>
</tr>

<tr>
	<td width="30%" align="right">Reference number: </td>
	<td><?php $this->showField('searchText');?></td>
</tr>
<tr>
	<td width="30%" align="right">Institution: </td><td><?php $this->showField('institution');?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="hidden" name="runreport" value="iesubmitworkaround">
		<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
	</td>
</tr>
</table>

<br>

<?php 
	if (isset($_POST['runreport']))
	{
		$searchText = (isset($_POST['searchText']) && $_POST['searchText'] != "") ? $_POST['searchText'] : "";
		$institution = (isset($_POST['institution']) && $_POST['institution'] != "") ? $_POST['institution'] : "";

		$whereArr = array("1");

		if ($searchText != ''){
			array_push($whereArr,"CHE_reference_code LIKE '%".$searchText."%' ");
		}

		if ($institution != '0') {
			array_push($whereArr," institution_id = '".$institution."' ");
		}

		$SQL = <<<aSql
			SELECT *
			FROM Institutions_application
aSql;

		$SQL .= " WHERE ".implode(" AND ", $whereArr);
		$SQL .=  " AND submission_date > '1000-01-01'";
		$SQL .=  " ORDER BY CHE_reference_code";
		
	//	echo $SQL;

?>

<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">

<?php 
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if ((mysqli_num_rows($rs) > 0) && (count($whereArr) > 1)) {
		$row = mysqli_fetch_array($rs);

		$HEI_id		= $row["institution_id"];
		$HEI_name	= $this->getValueFromTable("HEInstitution", "HEI_id", $HEI_id, "HEI_name");
		//tmpSettings for link to application and insitutional profile
		$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
		$linkToHEI = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$HEI_name.'</a>';

		echo '<tr><td colspan="2"><b>Institutional Profile</b>: '.$linkToHEI.'</a></td></tr>';
		// Reset result_set
		mysqli_data_seek($rs, 0);

		while ($row = mysqli_fetch_array($rs))
		{
			$HEI_name	= $this->getValueFromTable("HEInstitution", "HEI_id", $HEI_id, "HEI_name");
			$prog_name	= $row["program_name"];
			$app_id		= $row["application_id"];

		//document links
			$secretariat_doc = new octoDoc($row['secretariat_doc']);
			$secretariatLink = "<a href='".$secretariat_doc->url()."' target='_blank'>".$secretariat_doc->getFilename()."</a>";
			$evalReportsArray = $this->listEvaluatorReports($row["application_id"]);
			$eval_reports = "";
			foreach ($evalReportsArray as $value) {
				$eval_reports .= $value;
			}
			$evalFinalReport = new octoDoc($this->getFinalReport_id($app_id));
			$evalFinalLink = "<a href='".$evalFinalReport->url()."' target='_blank'>".$evalFinalReport->getFilename()."</a>";
			$AC_cond_octoDoc = new octoDoc($row['AC_conditions_doc']);
			$AC_cond_doc	 = "<a href='".$AC_cond_octoDoc->url()."' target='_blank'>".$AC_cond_octoDoc->getFilename()."</a>";

			$prog_ref	= $row["CHE_reference_code"];
			$tmpSettingsA = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
			$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettingsA).'\', \'\');">'.$prog_ref.'</a>';
			$AC_date	= $row["AC_Meeting_date"];
			$AC_outcome	= $this->getValueFromTable("lkp_desicion", "lkp_id", $row["AC_desision"], "lkp_title");
			$comment_excerpt = ($row['AC_conditions']) ? substr($row['AC_conditions'], 0, 75)."..." : "";
			$AC_comments = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$app_id.'&table=Institutions_application&return_field=AC_conditions&id_name=application_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a>';
			$AC_history_link = "<a href='pages/acMeetingHistory.php?app_ref=".base64_encode($row['application_id'])."' target='_blank'><i>(View AC meeting history)</i></a>";


			$html = <<<html_text
				<tr class="onblue">
					<td class="oncolourb" width="20%">Reference number:</td>
					<td>$linkToApp</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Programme name:</td>
					<td>$prog_name</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb" valign="top">Evaluator report(s):</td>
					<td>$eval_reports</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Final evaluator report:</td>
					<td>$evalFinalLink</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Directorate recommendation:</td>
					<td>$secretariatLink</td>
				</tr>
				</table>

				<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
				<tr class="onblue">
					<td colspan="2"><b>Latest AC meeting information: </b>$AC_history_link</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Date of AC meeting:</td>
					<td>$AC_date</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Accreditation outcome:</td>
					<td>$AC_outcome</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">Accreditation conditions:</td>
					<td valign="top">$AC_cond_doc</td>
				</tr>
				<tr class="onblue">
					<td class="oncolourb">AC meeting comments:</td>
					<td valign="top">$AC_comments</td>
				</tr>
				<tr>
					<td colspan="2"><hr></td>
				</tr>
html_text;
			echo $html;

		} //end while
	} //end if
	else { echo '<tr><td align="center">- No entries exist for the criteria you have entered -</td></tr>'; }
?>

</table>

<?php 
	}  //end if Submit is pressed
?>

<br>
