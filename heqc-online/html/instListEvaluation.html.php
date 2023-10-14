<?php

	$fc_arr = array();

	$ref = readPost('codeSearch');
	if ($ref > ''){
		array_push($fc_arr,'HEI_code like ("%'.$ref.'%")');
	}
	
	$inst = readPost('nameSearch');
	if ($inst > ''){
		array_push($fc_arr,'HEI_name LIKE ("%'.$inst.'%")');
	}
	
	$filter_criteria = (count($fc_arr) > 0) ? "AND ". implode(' AND ',$fc_arr) : "";
	
	$conn = $this->getDatabaseConnection();
	// select applications that have a valid outcome.
	$sql = <<<INST
		SELECT i.HEI_id, i.HEI_code, i.HEI_name, 
			u.Uni_tech, m.lkp_mode_of_delivery_desc
		FROM (HEInstitution i, institutional_profile p)
		LEFT JOIN lkp_uni_tech u ON p.institutional_type = ID
		LEFT JOIN lkp_mode_of_delivery m ON m.lkp_mode_of_delivery_id = p.mode_delivery
		WHERE i.HEI_id = p.institution_ref 
		AND i.flag_eligible_evaluation = 1
		$filter_criteria
		ORDER BY i.HEI_name
INST;
//echo $sql;
	$rs = mysqli_query($conn, $sql);
	$n = mysqli_num_rows($rs);
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2" align="left" class="special1">
		<br>
		<span class="loud">
		List of Institutions for evaluation
		</span>
	</td>
</tr>
<tr>
	<td colspan="2">
		&nbsp;
	</td>
</tr>
<tr>
	<td width="30%" align="right">Institution Reference number: </td>
	<td>
		<?php 
		$this->formFields['codeSearch']->fieldValue = $ref;
		$this->showField('codeSearch');
		?>
	</td>
</tr>
<tr>
	<td width="30%" align="right">Institution name: </td>
	<td>
		<?php 
		$this->formFields['nameSearch']->fieldValue = $inst;
		$this->showField('nameSearch');
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
		<p>Please click on the institution name to obtain a report of all the institution information and available documents. 
		</td>
	</tr>
	<tr>
		<td>
<?php 		
			$html = <<<HTMLSTR
				<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
				<tr><td align="right" colspan="4"><b>Number of institutions: $n</b></td></tr>
				<tr class='onblueb'>
					<td width="60%">Institution Name</td>
					<td>Type</td>
					<td>Mode of<br>delivery</td>
				</tr>
HTMLSTR;
			$prev_inst_id = "";
			$n = 0;
			while($row = mysqli_fetch_array($rs)){
				$n += 1;
				$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
				
				$tmpSettings = "DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_institutional_profile___institution_ref=".$row["HEI_id"];
				$institution = $row["HEI_name"] . " (" .$row["HEI_code"] . ")";
				$link1 = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["HEI_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$institution."</a>";
				

				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td>$link1</td>
						<td>$row[Uni_tech]</td>
						<td>$row[lkp_mode_of_delivery_desc]</td>
					</tr>
HTMLSTR;
			}
			$html .= <<<HTMLSTR
					</table>
HTMLSTR;
			echo $html;
?>			
		</td>
	</tr>
</table>
