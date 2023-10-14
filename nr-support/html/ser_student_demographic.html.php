<h3 class = "student_demographicTitle">Table 4.7 Demographic Table indicating student rate of completion</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
	$lookupCols = array(
		'lkp_aca_years',
		'lkp_gender'
	);
	
	$lookupRows = array(
		'Race' => 'lkp_race',
		'Registration types' => 'lkp_registration_types'
	);
	
	$fieldsTosave = array(
		'Total' => 'total'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_students',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'aca_year_id',
				'gender',
				'total',
				'race',
				'registration_type_id'
			)
		),
		'value_fields' => array(
			'total'
		),
		'WHERE' => ' nr_programme_id = ' . $prog_id
	);
	
	/*
		count of levels corresponds to the amount of rows at the top in header
			here there is (race, gender, values to save) = 3
		each value in levels corresponds to the colspan of each row
	*/
	$totals = array(
		'depth' => 3,
		'top_row_count' => array(
			'total' => '4',
			'span' => '2'
		),
		'col_levels' => array(
			'1' => array(
				'total' => '9',
				'span' => '1'
			),
			'2' => array(
				'display' => false	
			)
		)
	);
	
	echo '<div class="page-break"></div>';
	echo '<div class="multiGridDiv numbers student_demographic">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_student_demographic');
	echo '</div>';
	echo '<div class="page-break"></div>';
	
	$this->showSaveAndContinue('_label_ser_data');
	$this->cssPrintFile('print.css');
?>