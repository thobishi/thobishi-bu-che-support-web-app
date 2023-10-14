<h3>Self-evaluation against criteria</h3>
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
	array_push($headArr, "Improvement plan");
	
	$fieldsArr = array();
	array_push($fieldsArr, "type__select|name__lkp_rating_id|description_fld__lkp_ratings_desc|fld_key__id|lkp_table__lkp_ratings|lkp_condition__1|order_by__lkp_ratings_desc|default__--Select--");
	array_push($fieldsArr, "type__checkbox|name__rating_improvement_plan");
	
	$exceptions = array(
		'table' => 'nr_programme_ratings',
		'valueFields' => array(
			'lkp_rating_id',
			'rating_improvement_plan'
		),
		'updateField' => 'lkp_criteria_id',
		'lookupTableField' => 'lkp_criteria_id',
		'and' => 'nr_programme_id=' . $prog_id,
		'saveFields' => array(
			'nr_programme_id',
			'lkp_criteria_id',
			'rating_improvement_plan'
		),
		'saveValues' => array(
			$prog_id,
			'',
			''
		)
	);
	
	echo '<table class="table table-striped criteria-table table-multi">';	
	$this->gridShow("nr_national_review_criteria", "lkp_criteria_id", "nr_national_review_id__".$nr_review_id, $fieldsArr, $headArr, "lkp_criteria", "id", "criterion_title", "lkp_criteria_id", 1, 40, $numberOfRows, false, "", $exceptions);
	echo '</table>';

	$this->existsExceptionsData($exceptions);
	
	$this->showSaveAndContinue('_label_ser_validation');
	$this->cssPrintFile('print.css');
?>

<script>
	$(function(){
		var change = false;
		$("form input:checkbox").change(function(){
			if($(this).is(':checked')){
				$(this).val("1");
				$(this).next("input:HIDDEN").val("1");
			}
			else{
				$(this).val("0");
				$(this).next("input:HIDDEN").val("0");
			}
		});
		$("form input:checkbox").after("<label for='checkbox'>I have submitted an improvement plan as part of my self-evaluation report</label>");				
		$('select').change(function(){
			$this_val = $(this);
			change =true;
			ToogleCheckbox($this_val,change);
		});
		$('select :selected').each(function(){
			$this_val = $(this);
			change = false;
			ToogleCheckbox($this_val,change);
		});		

	});
	
	function ToogleCheckbox(obj,change){		
			if(obj.val() == 'ni'){				
				obj.closest('td').next('td').show();
				obj.closest('td').next('td').addClass('criterionTd');
			}else if((change = true) && (obj.val() != 'ni')) {			
				obj.closest('td').next('td').hide();
				obj.closest('td').next('td').removeClass('criterionTd');
				if(obj.closest('td').next('td').find('input:checkbox').is(':checked')){
					obj.closest('td').next('td').find('input:checkbox').attr('checked',false);					
					obj.closest('td').next('td').find('input:checkbox').val("0");
					obj.closest('td').next('td').find('input:checkbox').next("input:HIDDEN").val("0");
				}
			}else{
				obj.closest('td').next('td').hide();
			}
				
	}
</script>