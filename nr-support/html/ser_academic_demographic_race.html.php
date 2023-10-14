<h3>Table 4.6 B2 Demographic profile of staff in the Department/Unit (Race)</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
	$lookupCols = array(
		'lkp_race',
		'lkp_gender'
	);
	
	$lookupRows = array(
		'Level' => 'lkp_demographic_types'
	);
	
	$fieldsTosave = array(
		'Total' => 'total'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_academic_demographics_rac',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'race',
				'gender',
				'total',
				'demographic_type_id'
			)
		),
		'value_fields' => array(
			'total'
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
	
	echo '<div class="multiGridDiv numbers race_demographic">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_academic_demographic_race');
	echo '</div>';
	
	$this->showSaveAndContinue('_label_ser_student_demographic');
	$this->cssPrintFile('print.css');
?>
