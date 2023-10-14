<?php
        $conn = $this->getDatabaseConnection();
	// select applications that have a valid outcome.
	$sql = <<<APPLHASOUTCOME
		SELECT a.*, i.HEI_id, i.HEI_name, d.lkp_title 
		FROM (Institutions_application a, HEInstitution i)
		LEFT JOIN lkp_desicion d ON d.lkp_id = a.AC_desision
		WHERE a.institution_id = i.HEI_id 
		AND a.flag_eligible_reaccreditation = 1
		ORDER BY i.HEI_name, a.CHE_reference_code
APPLHASOUTCOME;
	$rs = mysqli_query($conn, $sql);
	$n = mysqli_num_rows($rs);
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="left" class="special1">
		<br>
		<span class="specialb">
		APPLICATIONS ELIGIBLE FOR RE-ACCREDITATION
		</span>
		</td>
	</tr>
	<tr>
		<td>
		<p>The following applications have been flagged for re-accreditation.  This means that they are accessible to HEQC-Online 
		Institutional Administrators to apply for re-accreditation.
		</td>
	</tr>
	<tr>
		<td>
			<br>
<?php 		
			$html = <<<HTMLSTR
				<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
				<tr><td align="right" colspan="4"><b>Number of applications for re-accreditation: $n</b></td></tr>
				<tr class='onblueb'>
					<td>HEQC Reference No.</td>
					<td>Programme Name</td>
					<td>Submission Date</td>
					<td>Outcome</td>
				</tr>
HTMLSTR;
			$prev_inst_id = "";
			$n = 0;
			while($row = mysqli_fetch_array($rs)){

				if ($row["HEI_id"] != $prev_inst_id){
				$n += 1;
				$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td colspan="5"><b>$row[HEI_name]</b></td>
					</tr>
HTMLSTR;
				}

				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td>$row[CHE_reference_code]</td>
						<td>$row[program_name]</td>
						<td>$row[submission_date]</td>
						<td>$row[lkp_title]</td>
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
