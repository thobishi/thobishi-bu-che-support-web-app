<?php
        $conn = $this->getDatabaseConnection();
	$sql = <<<INST
		SELECT i.HEI_id, i.HEI_code, i.HEI_name, i.flag_eligible_evaluation,
			u.Uni_tech, n.new_existing_short_desc, m.lkp_mode_of_delivery_desc
		FROM (HEInstitution i, institutional_profile p)
		LEFT JOIN lkp_uni_tech u ON p.institutional_type = ID
		LEFT JOIN lkp_inst_new_existing n on n.lkp_inst_new_existing_id = p.new_institution
		LEFT JOIN lkp_mode_of_delivery m ON m.lkp_mode_of_delivery_id = p.mode_delivery
		WHERE i.HEI_id = p.institution_ref
		AND i.flag_eligible_evaluation = 1
		ORDER BY i.HEI_name
INST;
//echo $sql;
        $rs = mysqli_query($conn, $sql);
	$n = mysqli_num_rows($rs);
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="left" class="special1">
		<br>
		<span class="loud">
		Institutions flagged for evaluation
		</span>
		</td>
	</tr>
	<tr>
		<td>
		<p>The following institutions have been flagged for evaluation.  This means that they are accessible to evaluators 
		in the HEQC-Online Evaluator Portal from menu option: <b>Tools / Institutional Profile Evaluation</b>
		</td>
	</tr>
	<tr>
		<td>
			<br>
<?php 		
			$html = <<<HTMLSTR
				<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
				<tr><td align="right" colspan="5"><b>Number of institutions for evaluation: $n</b></td></tr>
				<tr class='onblueb'>
					<td width="40%">Institution Name</td>
					<td>Institutional Administrator</td>
					<td>Type</td>
					<td>Admin</td>
					<td>Mode of<br>delivery</td>
				</tr>
HTMLSTR;
                        $conn = $this->getDatabaseConnection();
			$prev_inst_id = "";
			$n = 0;
			while($row = mysqli_fetch_array($rs)){

				$n += 1;
				$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
                                
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
				//$admrs = mysqli_query($admsql);
				$inst_adm = "";
				while (	$admrow = mysqli_fetch_array($admrs)){

				$inst_adm .= <<<HTMLSTR
						$admrow[inst_admin]<br>
HTMLSTR;
				}
				
				
				$institution = $row["HEI_name"] . " (" .$row["HEI_code"] . ")";
				
				$html .= <<<HTMLSTR
					<tr bgcolor="$bgColor">
						<td>$institution</td>
						<td>$inst_adm</td>
						<td>$row[Uni_tech]</td>
						<td>$row[new_existing_short_desc]</td>
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
