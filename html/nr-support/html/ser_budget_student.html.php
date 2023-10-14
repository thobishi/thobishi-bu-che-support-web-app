<h3>Table 4.5 C1 Student Support - Number of students</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$lookupCols = array(
		'lkp_race',
		'lkp_gender'
	);
	
	$lookupRows = array(
		'Year' => 'lkp_years',
		'Type of support' => 'lkp_finance_types'
	);
	
	$fieldsTosave = array(
		'No. students' => 'nr_students'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_bursaries',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'race',
				'gender',
				'nr_students',
				'year',
				'finance_type_id'
			)
		),
		'value_fields' => array(
			'nr_students'
		),
		'WHERE' => ' nr_programme_id = ' . $prog_id
	);
	
	$totals = array(
		'depth' => 3,
		'top_row_count' => array(
			'total' => '4',
			'span' => '2'
		),
		'col_levels' => array(
			'1' => array(
				'total' => '4',
				'span' => '1'
			),
			'2' => array(
				'display' => false	
			)
		)
	);
	
	echo '<div class="multiGridDiv numbers">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_budget_student');
	echo '</div>';

	$this->showSaveAndContinue('_label_ser_budget_student_totals');
	$this->cssPrintFile('print.css');
?>
