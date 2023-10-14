<h3>Recommendation's evaluation against criteria</h3>
<?php

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;

	$nr_review_id = $this->db->getValueFromTable("nr_programmes","id",$prog_id ,"nr_national_review_id");
	$sql = "SELECT nr_national_review_criteria.lkp_criteria_id, lkp_criteria.criterion_title
	FROM lkp_criteria, nr_programmes, nr_national_review_criteria
	WHERE
	nr_national_review_criteria.nr_national_review_id = nr_programmes.nr_national_review_id
	AND lkp_criteria.id = nr_national_review_criteria.lkp_criteria_id
	AND nr_programmes.id = '$prog_id'";
	$rs = $this->db->query($sql);
	$numberOfRows = $rs->rowCount();

	$headArr = array();
	array_push($headArr, "Criteria");
	array_push($headArr, "Rating");
	
	$fieldsArr = array();
	array_push($fieldsArr, "type__select|name__recomWriter_rating_id|description_fld__lkp_ratings_desc|fld_key__id|lkp_table__lkp_ratings|lkp_condition__1|order_by__lkp_ratings_desc|default__--Select--");
	
	$exceptions = array(
		'table' => 'nr_programme_ratings',
		'valueFields' => array(
			'recomWriter_rating_id'
		),
		'updateField' => 'lkp_criteria_id',
		'lookupTableField' => 'lkp_criteria_id',
		'and' => 'nr_programme_id=' . $prog_id,
		'saveFields' => array(
			'nr_programme_id',
			'lkp_criteria_id'
		),
		'saveValues' => array(
			$prog_id,
			''
		)
	);
	
	echo '<table class="table table-striped criteria-table table-multi">';	
	$this->gridShow("nr_national_review_criteria", "lkp_criteria_id", "nr_national_review_id__".$nr_review_id, $fieldsArr, $headArr, "lkp_criteria", "id", "criterion_title", "lkp_criteria_id", 1, 40, $numberOfRows, false, "", $exceptions);
	echo '</table>';
	
	$this->existsExceptionsData($exceptions);
	
?>
<script>

</script>
