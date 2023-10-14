<table width="98%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php 

	$fc_arr = $this-> build_reacc_search_criteria($_POST);

	$filter_criteria = (count($fc_arr) > 0) ? "AND ". implode(' AND ',$fc_arr) : "";

?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right">
			AC Meeting date: From
			</td>
			<td>
			<?php $this->showField('acmeeting_start_date');	?> to <?php $this->showField('acmeeting_end_date');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			HEQC Reference Number:  
			</td>
			<td>
			<?php $this->showField('search_HEQCref');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			Programme name:  
			</td>
			<td>
			<?php $this->showField('search_progname');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			Institution:  
			</td>
			<td>
			<?php $this->showField('search_institution');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			Outcome: 
			</td>
			<td>
			<?php $this->showField('search_outcome');	?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<hr><br>
<?php 
		$SQL  =<<<SQL
			SELECT * FROM Institutions_application_reaccreditation
			WHERE (reacc_application_status != '-1' AND reacc_submission_date > '1970-01-01')
			$filter_criteria
			ORDER BY referenceNumber ASC
SQL;
                $conn = $this->getDatabaseConnection();
                $rs = $conn->query($SQL);
		//$rs = mysqli_query($SQL);
		
		$html = "<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>";
		
		$num_rows = mysqli_num_rows($rs);

		if ($num_rows > 0) {
			$html .= <<<HTML
			<tr><td colspan="8" align="right"><b>Total applications: $num_rows</b></td></tr>
			<tr class='oncolourb' align='center'>
			<td>Edit/Add outcome</td>
			<td>HEQC ref. number</td>
			<td>Programme name</td>
			<td>Institution</td>
			<td>AC Meeting</td>
			<td>Outcome</td>
			<td>Comments</td>
			<td>Documentation</td>
			</tr>
HTML;
			
			while ($row = mysqli_fetch_array($rs)) {

				$reacc_id = $row['Institutions_application_reaccreditation_id'];
				$inst_id = $row["institution_ref"];
				$outcome_id = $row['reacc_decision_ref'];
				$doc_id = $row['reacc_acmeeting_doc'];

				switch ($outcome_id){
				case 2:
					$comment_field = 'reacc_conditions';
					break;
				case 4:
					$comment_field = 'reacc_deferral_comment';
					break;
				default:
					$comment_field = 'reacc_acmeeting_comment';
				}

				// needed for reference number link
				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reacc_id;
				$linka = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$reacc_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>';
				$outcome = ($outcome_id > 0) ? $this->getValueFromTable("lkp_reacc_decision", "lkp_reacc_id", $outcome_id, "lkp_reacc_title") : "";
				$ac_meeting = $row["reacc_acmeeting_date"];
				$link1 = 'javascript:setApp(\''.$reacc_id.'\');moveto(\'next\');';
				$program_name = $row['programme_name'];
				$inst = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");
				if (strlen($row["$comment_field"]) > 75){
					$comment_excerpt = substr($row["$comment_field"], 0, 75)."...";
					$linkc = "<a href=\"javascript:void window.open('pages/viewComment.php?item_id=".$reacc_id."&table=Institutions_application_reaccreditation&return_field=".$comment_field."&id_name=Institutions_application_reaccreditation_id','','width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no');\">".$comment_excerpt."</a>";
				} else {
					$linkc = $row["$comment_field"];
				}


				$html .= <<<HTML2
				<tr class='onblue' valign='top'>
				<td width='5%' align='center'><a href=$link1><img src='images/ico_change.gif' border=no></a></td>
				<td width='5%'>$linka</td>
				<td width='15%'>$program_name</td>
				<td width='15%'>$inst</td>
				<td width='10%'>$ac_meeting</td>
				<td width='10%'>$outcome</td>
				<td width="25%">$linkc</td>
				<td width='10%'>
HTML2;

				if ($doc_id > ''){
					$document = new octoDoc($doc_id);
//print_r($document);
					$html .= "<a href='".$document->url()."' target='_blank'>".$document->getFilename()."</a>";
				}
				$html .= "</td>";
				$html .= "</tr>";
			}
		}
		echo $html;
		echo "</table>";
		echo "<br><br>";

?>

</td></tr>
</table>

<script>
function setApp(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='Institutions_application_reaccreditation|'+val;
}
</script>
