<?php
	function set_prev_proceeding(){
		/********************************************************************************/
		// STEP 1:
		//
		// Identify all applications that have proceedings and all active processes are closed i.e processing is complete			
		/********************************************************************************/

		$sql = <<<SQL1
			SELECT `ia_proceedings_id`,`application_ref` , `lkp_proceedings_ref` , `ac_meeting_ref` , `heqc_meeting_ref` , `heqc_decision_due_date` , `proceeding_status_ind` , `proceeding_status_date` , `prev_ia_proceedings_ref` 
			FROM `ia_proceedings` 
			WHERE application_ref IN (
			2254, 2280, 2289, 2329, 2368, 2383, 2385, 2387, 2393, 2406, 2531, 2556, 2590, 2606, 2643, 2644, 2645, 2670, 
			2682, 2689, 2702, 2706, 2715, 2717, 2718, 2722, 2728, 2732, 2733, 2736, 2737, 2738, 2749, 2758, 2765, 2777, 
			2792, 2793, 2794, 2795, 2796, 2797, 2799, 2800, 2804, 2807, 2808, 2815, 2818, 2825, 2826, 2831, 2836, 2837, 
			2838, 2841, 2844, 2845, 2846, 2847, 2849, 2855, 2861, 2863, 2864, 2872, 2876, 2883, 2891, 2904, 2913, 2927, 
			2935, 2946, 2949, 2951, 2974, 2976, 3072, 3073, 3087, 3092, 3093, 3098, 3101, 3116, 3119, 3120, 3122, 3126, 
			3160, 3164, 3178, 3209, 3222, 3229, 3233, 3240, 3243, 3247, 3321, 3329, 3405
			)
			ORDER BY application_ref, ia_proceedings_id
SQL1;
		$rs = mysqli_query($sql) or die(mysqli_error());
		$html = "<table>";
		$prev_app_id = 0;
		$prev_proc_id = 0;
		while ($row = mysqli_fetch_array($rs)){
			$app_id = $row["application_ref"];
			$proc_id = $row["ia_proceedings_id"];
			if ($app_id == $prev_app_id){
				if ($row['prev_ia_proceedings_ref'] == 0){  // Not set to a value. therefore update
					$upd = "UPDATE ia_proceedings SET prev_ia_proceedings_ref = $prev_proc_id WHERE ia_proceedings_id = $proc_id";
					mysqli_query($upd) or die(mysqli_error());
					echo "Application: $app_id " . mysqli_affected_rows() . " rows updated</br>";
				}
			}
			$html .= <<<HTML
				<tr>
					<td>$row[application_ref]</td><td>$row[ia_proceedings_id]</td><td>$row[ac_meeting_ref]</td><td>$row[heqc_meeting_ref]</td><td>$row[heqc_decision_due_date]</td><td>$row[proceeding_status_ind]</td><td>$row[proceeding_status_date]</td><td>$row[prev_ia_proceedings_ref]</td>
				</tr>
HTML;
			$prev_app_id = $app_id;
			$prev_proc_id = $row['ia_proceedings_id'];
		}
		$html .= "</table>";
		echo $html;
	}
	
	$dbhandle = mysqli_connect("localhost", "", "")
	  or die("Unable to connect to MySQL");

	$selected = mysqli_select_db("CHE_heqconline",$dbhandle)
	  or die("Could not select database");

	set_prev_proceeding();
	
	//close the connection
	mysqli_close($dbhandle);
?>
