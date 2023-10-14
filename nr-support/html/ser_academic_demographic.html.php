<h3>Table 4.6 B1 Demographic profile of staff in the Department/Unit (Nationality)</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
	$lookupCols = array(
		'lkp_nationality',
		'lkp_gender'
	);
	
	$lookupRows = array(
		'Level' => 'lkp_demographic_types'
	);
	
	$fieldsTosave = array(
		'Total' => 'total'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_academic_demographics_nat',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'nationality_id',
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
			'total' => '2',
			'span' => '2'
		),
		'col_levels' => array(
			'1' => array(
				'total' => '2',
				'span' => '1'
			),
			'2' => array(
				'display' => false	
			)
		)
	);
	
	echo '<div class="multiGridDiv numbers academic_demographic">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_academic_demographic');
	echo '</div>';
	
	$this->showSaveAndContinue('_label_ser_academic_demographic_race');
	$this->cssPrintFile('print.css');
?>
