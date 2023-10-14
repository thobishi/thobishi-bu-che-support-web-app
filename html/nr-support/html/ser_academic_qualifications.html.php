<h3 class = "academicTitle">Table 4.6 A Academic qualifications</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
	$lookupCols = array(
		'lkp_qual_categories',
		'lkp_gender'
	);
	
	$lookupRows = array(
		'Race' => 'lkp_race',
		'Employment type' => 'lkp_employment_types'
	);
	
	$fieldsTosave = array(
		'Number completed' => 'nr_completed',
		'Number currently studying' => 'nr_current'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_academic_qualifications',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'qual_category_id',
				'gender',
				'nr_completed',
				'race',
				'employment_type_id'
			),
			1 => array(
				'qual_category_id',
				'gender',
				'nr_current',
				'race',
				'employment_type_id'
			)
		),
		'value_fields' => array(
			'nr_completed',
			'nr_current'
		),
		'WHERE' => ' nr_programme_id = ' . $prog_id
	);
	
	$totals = array(
		'depth' => 3,
		'top_row_count' => array(
			'total' => '4',
			'span' => '4'
		),
		'col_levels' => array(
			'1' => array(
				'total' => '4',
				'span' => '2'
			),
			'2' => array(
				'total' => '4',
				'span' => '1'
			)
		)
	);
	
	echo '<div class="multiGridDiv numbers qualifications">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_academic_qualifications');
	echo '</div>';
	
	$this->showSaveAndContinue('_label_ser_academic_demographic');
	$this->cssPrintFile('print.css');
?>