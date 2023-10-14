<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php
        $conn = $this->getDatabaseConnection();
	$fc_arr = array();

	$ref = readPost('searchText');

	if ($ref > ''){
		array_push($fc_arr,'CHE_reference_code like ("%'.$ref.'%")');
	}
	
	$inst = readPost('institution');
	if ($inst > 0){
		array_push($fc_arr,'institution_id = '.$inst);
	}

	$mode_delivery = readPost('mode_delivery');
	if ($mode_delivery > 0){
		array_push($fc_arr,'mode_delivery = '.$mode_delivery);
	}

	$filter_criteria = (count($fc_arr) > 0) ? "AND ". implode(' AND ',$fc_arr) : "";
	
	// select applications that have a valid outcome.
	$sql = <<<APPLHASOUTCOME
		SELECT a.*, i.HEI_id, i.HEI_name, d.lkp_title 
		FROM Institutions_application a, HEInstitution i, lkp_desicion d
		WHERE a.institution_id = i.HEI_id 
		AND d.lkp_id = a.AC_desision
		AND AC_desision in (1,2)
		$filter_criteria
		UNION 
		SELECT a.*, i.HEI_id, i.HEI_name, d.lkp_title
		FROM (Institutions_application a, HEInstitution i)
		LEFT JOIN lkp_desicion d ON d.lkp_id = a.AC_desision
		WHERE a.institution_id = i.HEI_id 
		AND a.flag_eligible_reaccreditation = 1
		$filter_criteria
		ORDER BY HEI_name, CHE_reference_code
APPLHASOUTCOME;

	$rs = mysqli_query($conn, $sql);
	$n = mysqli_num_rows($rs);
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2" align="left" class="special1">
		<br>
		<span class="specialb">
		APPLICATIONS ELIGIBLE FOR RE-ACCREDITATION
		</span>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;
		
	</td>
</tr>
<tr>
	<td width="30%" align="right">Reference number: </td>
	<td>
		<?php 
		$this->formFields['searchText']->fieldValue = $ref;
		$this->showField('searchText');
		?>
	</td>
</tr>
<tr>
	<td width="30%" align="right">Institution: </td>
	<td>
		<?php 
		$this->formFields['institution']->fieldValue = $inst;
		$this->showField('institution');
		?>
	</td>
</tr>

<tr>
	<td width="30%" align="right">Mode of delivery: </td>
	<td>
		<?php 
		$this->formFields['mode_delivery']->fieldValue = $mode_delivery;
		$this->showField('mode_delivery');
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td><td><input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');"></td>
</tr>
<tr>
	<td colspan="2">
		<hr>
	</td>
</tr>
</table>

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
		<br>
		<p>Please select the applications that must be available to users to apply for re-accreditation.  Applications that are selected 
		will display in the users list of applications for which they may apply for re-accreditation by going to menu option: 
		Reaccreditation / Apply for re-accreditation.
		</td>
	</tr>
	<tr>
		<td>
<?php 		
			$html = <<<HTMLSTR
				<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
				<tr><td align="right" colspan="6"><b>Number of applications: $n</b></td></tr>
				<tr class='onblueb'>
					<td>HEQC Reference No.</td>
					<td width="40%">Programme Name</td>
					<td>Mode of delivery</td>
					<td>Submission Date</td>
					<td>Outcome</td>
					<td>
						Select
					</td>
				</tr>
HTMLSTR;
/*  Robin 2009-01-27:  Removing Select and de-select links because may be dangerous.
	<a href="javascript:checkall(document.defaultFrm.elements['appid_reaccred[]'],true);"><span class="special"><i>Select All</i></span></a>
	<br><a href="javascript:checkall(document.defaultFrm.elements['appid_reaccred[]'],false);"><span class="special"><i>Deselect All</i></span></a>
*/
			$prev_inst_id = "";
			$n = 0;
			while($row = mysqli_fetch_array($rs)){
				$sel = ($row["flag_eligible_reaccreditation"] == 1) ? " CHECKED" : "";
				$chk_reaccred = '<input type="Checkbox" name="appid_reaccred[]" value="'.$row["application_id"].'"' . $sel .'>';
				$mode_deliveryDesc = $this->getValueFromTable("lkp_mode_of_delivery","lkp_mode_of_delivery_id",$row["mode_delivery"],"lkp_mode_of_delivery_desc");

				if ($row["HEI_id"] != $prev_inst_id){
				$admsql = <<<ADMSQL
					SELECT concat( name, " ", surname, ": ", email ) as inst_admin
					FROM users, sec_UserGroups
					WHERE user_id = sec_user_ref
					AND sec_group_ref =4
					AND institution_ref = ?
ADMSQL;
                                $stmt = $conn->prepare($admsql);
                                $stmt->bind_param("s", $row["HEI_id"]);
                                $stmt->execute();
                                $admrs = $stmt->get_result();
   //               echo $admsql;            
				//$admrs = mysqli_query($admsql);
				$admrow = mysqli_fetch_array($admrs);
				
				$n += 1;
				$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td colspan="6"><b>$row[HEI_name] - $admrow[inst_admin]</b></td>
					</tr>
HTMLSTR;
				}

				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td>$row[CHE_reference_code]</td>
						<td>$row[program_name]</td>
						<td>$mode_deliveryDesc</td>
						<td>$row[submission_date]</td>
						<td>$row[lkp_title]</td>
						<td>$chk_reaccred</td>
					</tr>
HTMLSTR;
				$prev_inst_id = $row["HEI_id"];
			}
			$html .= <<<HTMLSTR
					</table>
HTMLSTR;
			echo $html;
?>			
		</td>
	</tr>
</table>
