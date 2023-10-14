<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr><td>

<?
	// get projects to load.  These have been loaded from MS*Word project register application as text into the projects_load table
	$sql = "select * from projects_load";
	$rs = mysqli_query($sql);
	$new = mysqli_num_rows($rs);

	$load = ($new > 0) ? 1 : 0;

	if (!$load)	echo "There are no projects to load.";

	if ($load){

		echo "There are " . $new . " projects to load.";

		$html = "<table>";

		while ($row = mysqli_fetch_array($rs)){

			// check whether the project has already been loaded.

			$csql = <<<sql
				SELECT proj_code, project_short_title
				FROM project_detail
				WHERE proj_code = '$row[proj_code]'
sql;

			$crs = mysqli_query($csql) or die (mysqli_error());
			if (mysqli_num_rows($crs) > 0){
				$html .= "<tr><td>Project: " . $row["proj_code"] . " " . $row["project_short_title"] . " already exists and cannot be loaded. Please delete it first and then reload.</td></tr>";
			}
			else {  // load project

				$ins = <<<ins
				INSERT INTO project_detail (proj_code, directorate_ref,
					project_full_title, project_short_title, phase,
					planned_start_date, planned_end_date,
					background_rationale, goals, methodology_design,
					beneficiaries, deliverables,
					date_loaded)
				VALUES ("$row[proj_code]", "$row[directorate_ref]",
					"$row[project_full_title]", "$row[project_short_title]", "$row[phase]",
					"$row[planned_start_date]", "$row[planned_end_date]",
					"$row[background_rationale]",	"$row[goals]", "$row[methodology_design]",
					"$row[beneficiaries]", "$row[deliverables]", now())
ins;

				$irs = mysqli_query($ins) or die(mysqli_error());
				$new_id = mysqli_insert_id();

				// Insert project detail records per budget_year
				$ins = <<<ins_c3
				INSERT INTO project_budget_per_year (budget_year, project_ref, planned_budget)
				VALUES ('$row[budget_year]', $new_id, '$row[planned_budget]')
ins_c3;
				$irs = mysqli_query($ins) or die(mysqli_error());

				// Add mandate records
				for ($i=1; $i <= 4; $i++){
					$fld = "che_mandate".$i;
					if ($row[$fld] > 0){
						$ins = <<<ins_c1
							INSERT INTO project_detail_mandate (project_ref, che_mandate_ref, relevance_ref)
							VALUES ($new_id, $i, $row[$fld]);
ins_c1;
						$irs = mysqli_query($ins) or die(mysqli_error());
					}
				}

				for ($i=1; $i <= 19; $i++){
					$fld1 = "personnel_ref".$i;
					$fld2 = "personnel_name".$i;
					$fld3 = "role".$i;
					if ($row[$fld1] > 0 or $row[$fld2] > "" or $row[$fld3] > ""){
						$ins = <<<ins_c2
							INSERT INTO project_team (project_ref, personnel_ref, personnel_name, role)
							VALUES ($new_id, '$row[$fld1]', '$row[$fld2]', '$row[$fld3]');
ins_c2;
						$irs = mysqli_query($ins) or die(mysqli_error());
					}
				}

			} // end load

		} // end while of rs

		$html .= "</table>";
		echo $html;
	}

?>


	</td></tr>
</table>
<br>