<?php
	function get_proceedings($app_id, $format="1"){
		$sql = <<<SQL
			SELECT * 
			FROM ia_proceedings
			LEFT JOIN lkp_proceedings ON ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
			WHERE application_ref = $app_id
			ORDER BY heqc_meeting_ref, ac_meeting_ref
SQL;
		$rs = mysqli_query($sql) or die(mysqli_error());

		$html = "<tr>";
		if ($format == "2"){
			$html = <<<HTML
				<tr>
					<td>&nbsp;</td>
					<td colspan="5">
						<table>
							<tr>
								<td>Proceeding</td><td>Status</td><td>Status Date</td><td>AC recomm</td><td>AC Meeting</td><td>HEQC recomm</td><td>HEQC Meeting</td>
							</tr>
HTML;
		}
//						<td>$row[ac_decision_ref]</td>
//						<td>$row[heqc_board_decision_ref]</td>
		while ($row = mysqli_fetch_array($rs)){
			$html .= ($format == 2) ? "<tr>" : "";
			$html .= <<<HTML
						<td>$row[application_ref]</td>
						<td>$row[lkp_proceedings_desc]</td>
						<td>$row[proceeding_status_ind]</td>
						<td>$row[proceeding_status_date]</td>
						<td>$row[ac_decision_ref]</td>
						<td>$row[ac_meeting_ref]</td>
						<td>$row[heqc_board_decision_ref]</td>
						<td>$row[heqc_meeting_ref]</td>
HTML;
			$html .= "</tr>";
		}
		if ($format == "2"){
			$html .= <<<HTML
						</table>
					</td>
HTML;
		}
		$html .= "</tr>";
		return $html;
	}

	function set_overall_outcome_for_open_apps(){
		$sql = <<<SQL1
			SELECT distinct application_ref, program_name, AC_desision, Institutions_application.AC_Meeting_ref, AC_Meeting_date, AC_conditions, AC_conditions_doc
			FROM Institutions_application
			LEFT JOIN ia_proceedings ON Institutions_application.application_id = ia_proceedings.application_ref
			LEFT JOIN tmp_aps ON Institutions_application.application_id = tmp_aps.application_id AND status = 0
			WHERE application_ref IS NOT NULL
			AND tmp_aps.application_id IS NOT NULL
SQL1;

		$rs = mysqli_query($sql) or die(mysqli_error());
		echo "Total applications with open proceedings: " . mysqli_num_rows($rs) . "<br/><br/>";
		$html = "<table><tr><td>App ID</td><td>Program name</td><td>AC Outcome</td><td>AC Meeting</td><td>AC Date</td><td>AC Conditions</td><td>AC Doc</td></tr>";
		$no_updated = 0;
		while ($row = mysqli_fetch_array($rs)){
			$app_id = $row["application_ref"];
			$html .= <<<HTML
				<tr>
					<td>$row[application_ref]</td><td>$row[program_name]</td><td>$row[AC_desision]</td><td>$row[AC_Meeting_ref]</td><td>$row[AC_Meeting_date]</td><td>$row[AC_conditions]</td><td>$row[AC_conditions_doc]</td>
				</tr>
HTML;
			$html .= get_proceedings($app_id,2);
			
			// Set overall AC outcome, Meeting and Date to blank
			$upd = <<<UPDATE
				UPDATE Institutions_application
				SET AC_desision = 0,
				AC_Meeting_ref = 0,
				AC_Meeting_date = '1970-01-01'
				WHERE application_id = $app_id
				LIMIT 1
UPDATE;
			$urs = mysqli_query($upd) or die(mysqli_error());
			$no_updated += mysqli_affected_rows();
		}
		$html .= "</table>No of outcomes set to 0: $no_updated";
		echo $html;

	}
	
	function set_overall_outcome_for_completed_apps(){
		/********************************************************************************/
		// STEP 1:
		//
		// Identify all applications that have proceedings and all active processes are closed i.e processing is complete			
		/********************************************************************************/

		$sql = <<<SQL1
			SELECT Institutions_application.application_id, application_ref, program_name, AC_desision, Institutions_application.AC_Meeting_ref, AC_Meeting_date, AC_conditions, AC_conditions_doc
			FROM Institutions_application
			LEFT JOIN ia_proceedings ON Institutions_application.application_id = ia_proceedings.application_ref
			LEFT JOIN tmp_aps ON Institutions_application.application_id = tmp_aps.application_id AND status = 0
			WHERE application_ref IS NOT NULL
			AND tmp_aps.application_id IS NULL
			GROUP BY Institutions_application.application_id
			ORDER BY Institutions_application.application_id 
SQL1;

		$rs = mysqli_query($sql) or die(mysqli_error());
		echo "Total completed applications with proceedings: " . mysqli_num_rows($rs) . "<br/><br/>";
		$html = "<table>";
		while ($row = mysqli_fetch_array($rs)){
			$app_id = $row["application_ref"];
			$html .= <<<HTML
				<tr>
					<td>$row[application_ref]</td><td>$row[program_name]</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>$row[AC_Meeting_ref]</td><td>$row[AC_desision]</td><td>$row[AC_Meeting_date]</td>
				</tr>
HTML;
			$html .= get_proceedings($app_id,1);
			
		}
		$html .= "</table>";
		echo $html;
	}
	
	$dbhandle = mysqli_connect("localhost", "heqc", "workflow")
	  or die("Unable to connect to MySQL");

	$selected = mysqli_select_db("CHE_heqconline",$dbhandle)
	  or die("Could not select database");

	set_overall_outcome_for_completed_apps();
	//set_overall_outcome_for_open_apps();
	
	//close the connection
	mysqli_close($dbhandle);
?>
