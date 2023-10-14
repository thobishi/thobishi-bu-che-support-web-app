<?php 
	$searchFor = (isset($_POST['searchFor']) && $_POST['searchFor'] != "") ? $_POST['searchFor'] : "";
	$processed_apps = (isset($_POST['processed_apps']) && $_POST['processed_apps'] != "") ? $_POST['processed_apps'] : "";
	$mode_delivery = readPost('mode_delivery');
?>

<table width="98%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
The following programmes have already been through AC Meetings.
<br>You may add/edit outcomes; conditions or comments; and the AC meetings the programmes went to on this page.
<br>
<br>
	<table border=0 cellpadding="2" cellspacing="2">
	<tr><td>Search by reference number:</td><td><?php echo $this->showField("searchFor")?></td></tr>
	<tr><td>Search by mode of delivery:</td><td><?php $this->showField("mode_delivery"); ?></td></tr>
	<tr align="right"><td>Search for</td><td align="left"><?php $this->showField("processed_apps"); ?></td></tr>

	<tr><td colspan="4" align="right"><input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');"></td></tr>
	</table>
<hr><br>
<?php 
		// application_status: 3 => Been through AC Meeting
		// application_status: 9 => Processed outside of system - outcomes probably added by Stella.
		// 2010-10-04 Robin - removed OR AC_Meeting_ref = 0 from the following because I'm not sure what its trying to do.
		//			case 2  : $processed_appsStr = " AND (AC_desision = '0' OR AC_desision = '-1' OR AC_Meeting_ref = 0)"; break;		//no outcome
		$appNum = ($searchFor) ? " AND CHE_reference_code LIKE '%".$searchFor."%' " : "";
		$processed_appsStr = "";
		switch ($processed_apps) {
			case 1  : $processed_appsStr = " AND AC_desision != '' AND AC_desision != '-1' "; break;		//has outcome
			case 2  : $processed_appsStr = " AND (AC_desision = '0' OR AC_desision = '-1')"; break;		//no outcome
			default : break;
		}

		$mode_deliveryFilter = ($mode_delivery > 0) ? " AND mode_delivery = '" . $mode_delivery . "' " : '';
//		$SQL  =<<< SQL
//			SELECT * FROM Institutions_application AS a
//			WHERE (application_status in (3,9) OR AC_desision > 0)
//			$appNum
//			$processed_appsStr
//			ORDER BY CHE_reference_code ASC
//SQL;
//		$SQL = <<<SQL
//			SELECT  a.application_id, a.program_name, a.mode_delivery, a.AC_conditions, a.CHE_reference_code, a.AC_Meeting_ref, 
//			a.institution_id, a.AC_desision, a.AC_Meeting_date, a.submission_date
//			FROM Institutions_application AS a
//			LEFT JOIN ia_proceedings AS p ON p.application_ref = a.application_id
//			WHERE p.ia_proceedings_id IS NULL
//			AND (application_status in (3,9) OR AC_desision > 0)
//			$appNum
//			$processed_appsStr
//			$mode_deliveryFilter
//			ORDER BY CHE_reference_code ASC
//SQL;
//	2017-06-07 Richard: Changed to include all applications
//$SQL = <<<SQL
//			SELECT a.application_id, a.program_name, a.mode_delivery, a.AC_conditions, a.CHE_reference_code, a.AC_Meeting_ref, 
//			a.institution_id, a.AC_desision, a.AC_Meeting_date, a.submission_date
//			FROM Institutions_application AS a
//			WHERE a.submission_date > '1970-01-01'
//			$appNum
//			$processed_appsStr
//			$mode_deliveryFilter
//			ORDER BY CHE_reference_code ASC
//SQL;
//  2017-11-13 : Richard Added SAQA ID
$SQL = <<<SQL
			SELECT a.application_id, a.program_name, a.mode_delivery, a.AC_conditions, a.CHE_reference_code, a.AC_Meeting_ref, 
			a.institution_id, a.AC_desision, a.AC_Meeting_date, a.submission_date, a.SAQA_id
			FROM Institutions_application AS a
			WHERE a.submission_date > '1970-01-01'
			$appNum
			$processed_appsStr
			$mode_deliveryFilter
			ORDER BY CHE_reference_code ASC
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		echo "<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>";

		if (mysqli_num_rows($rs) > 0) {
			echo '<tr><td colspan="10" align="right"><b>Total applications: '.mysqli_num_rows($rs).'</b></td></tr>';

			echo "<tr class='oncolourb' align='center'>";
			echo "<td>Edit</td>";
			echo "<td>HEQC<br/>reference</td>";
			echo "<td>Programme name</td>";
			echo "<td>Mode of delivery</td>";
			echo "<td>Submission date</td>";
			echo "<td>Institution</td>";
			echo "<td>AC Meeting</td>";
			echo "<td>Outcome</td>";
			echo "<td>Comments/conditions</td>";
			echo "<td>Documentation</td>";
			//  2017-11-13 : Richard Added SAQA ID
			echo "<td>SAQA Id</td>";
			echo "</tr>";
				while ($row = mysqli_fetch_array($rs)) {
					$mode_deliveryDesc = $this->getValueFromTable("lkp_mode_of_delivery","lkp_mode_of_delivery_id",$row["mode_delivery"],"lkp_mode_of_delivery_desc");
					$comment_excerpt = ($row['AC_conditions']) ? substr($row['AC_conditions'], 0, 75)."..." : "";
					$app_id = $row['application_id'];
					// needed for reference number link
					$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
					$ref = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"]."</a>";
					$outcome = $row['AC_desision'];
					$outcome = ($outcome > 0) ? $this->getValueFromTable("lkp_desicion", "lkp_id", $outcome, "lkp_title") : "";
					$new_AC = $row['AC_Meeting_ref'];
					$ac_meeting = ($new_AC > 0) ? $this->getValueFromTable("AC_Meeting", "ac_id", $row['AC_Meeting_ref'], "ac_start_date")." - ".$this->getValueFromTable("AC_Meeting", "ac_id", $row['AC_Meeting_ref'], "ac_meeting_venue") : $row["AC_Meeting_date"];
					$submit_date = ($row['submission_date'] > '1970-01-01') ? $row['submission_date'] : "&nbsp;";
					
					echo "<tr class='onblue' valign='top'>";
					echo "<td width='5%' align='center'><a href='javascript:setApp(\"".$app_id."\");moveto(\"next\");'><img src='images/ico_change.gif' border=no></a></td>";
					echo "<td width='5%'>".$ref."</td>";
					echo "<td width='15%'>".$row['program_name']."</td>";
					echo "<td width='15%'>".$mode_deliveryDesc."</td>";
					echo "<td width='5%'>".$submit_date."</td>";
					echo "<td width='15%'>".$this->getValueFromTable("HEInstitution", "HEI_id", $row['institution_id'], "HEI_name")."</td>";
					echo "<td width='5%'>".$ac_meeting."</td>";
					echo "<td width='10%'>".$outcome."</td>";
					echo '<td width="20%"><a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$app_id.'&table=Institutions_application&return_field=AC_conditions&id_name=application_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a></td>';
					echo "<td width='20%'>";
					//$this->view = 1;
					//$this->makeLink('AC_conditions_doc',"", "Institutions_application", "application_id", $app_id);
					$docs_arr = $this->getApplicationDocs($app_id);
					foreach($docs_arr as $d){
						echo "<br />" . $d;
					}
					echo "</td>";
					//  2017-11-13 : Richard Added SAQA ID
					echo "<td width='10%'>".$row['SAQA_id']."</td>";
					echo "</tr>";
				}
		}
		echo "</table>";
		echo "<br><br>";

?>

</td></tr>
</table>

<script>
function setApp(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='Institutions_application|'+val;
}
</script>
