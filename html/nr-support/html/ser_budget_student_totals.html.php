<h3>Table 4.5 C2 Student Support - Total amounts</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$lookupCols = array(
		'lkp_finance_types'
	);
	
	$lookupRows = array(
		'Year' => 'lkp_years'
	);
	
	$fieldsTosave = array(
		'Amount' => 'amount'
	);
	
	$tableInfo = array(
		'name' => 'nr_programme_bursary_amounts',
		'key' => 'nr_programme_id',
		'key_value' => $prog_id,
		'fields_grouped' => array(
			0 => array(
				'finance_type_id',
				'amount',
				'year',
			)
		),
		'value_fields' => array(
			'amount'
		),
		'WHERE' => ' nr_programme_id = ' . $prog_id
	);
	
	$totals = array(
		'depth' => 2,
		'top_row_count' => array(
			'total' => '4',
			'span' => '1'
		),
		'col_levels' => array(
			'0' => array(
				'total' => '4',
				'span' => '1'
			)
		)
	);
	
	echo '<div class="multiGridDiv totalAmount">';
	$this->multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, 'ser_budget_student_totals');
	echo '</div>';

	$this->showSaveAndContinue('_label_ser_academic_qualifications');
	$this->cssPrintFile('print.css');
?>

<script>
	$(".multiGridDiv")
		.find('table').children('thead').children('tr').find("th:last").after('<th>Total</th>');
	$(".multiGridDiv")
		.find('table').children('tbody').children('tr').each(function(){
			$(this).find("td:last").after('<td><span class="totalColTextTotal"></span></td>');
		});
	$(".multiGridDiv")
		.find('table').find("tr:last").after('<tr><td><strong>Total</strong></td><td><span class="totalRowText"></span></td><td><span class="totalRowText"></span></td><td><span class="totalRowText"></span></td><td><span class="totalRowTextTotal"></span></td></tr>');
		
	populateValues();
		
	$('form').on('keyup', '.number' , function(e){
		$value = $(this).val();
		$value = ($value == '') ? 0 : $value;
		$(this).val($value);
		$colNumber = $(this).parent('td').index();
		$rowNumber = $(this).parent('td').parent('tr').index();
		$colTotal = 0;
		$rowTotal = 0;
		$rowColTotal = 0;
		
		//add columns
		$(".multiGridDiv")
			.find('table').children('tbody').children('tr').each(function(){
				$inputValue = $(this).find('td').eq($colNumber).find('.number');
				$inputValue = ($inputValue.length > 0) ? parseInt($inputValue.val()) : 0;
				$colTotal += $inputValue;
			});
			
		//add rows
		$(".multiGridDiv")
			.find('table').children('tbody').find("tr").eq($rowNumber).find('td').each(function(){
				$inputValue = $(this).find('.number');
				$inputValue = ($inputValue.length > 0) ? parseInt($inputValue.val()) : 0;
				$rowTotal += $inputValue;
			});
		
		//populate last row
		$(".multiGridDiv")
			.find('table').find("tr:last").find('td').eq($colNumber).find('.totalRowText').html($colTotal);
			
		//populate last col
		$(this).parent('td').parent('tr').find('.totalColTextTotal').html($rowTotal);
		
		//add rows and columns
		$(".totalColTextTotal").each(function(){
			$totalTotalValue = ($(this).html() > '') ? parseInt($(this).html()) : 0;
			$rowColTotal += $totalTotalValue;
		});
		
		//populate last col and row
		$(".totalRowTextTotal").html($rowColTotal);
	});
	
	function populateValues(){
		//populate columns
		$colTotal = 0;
		$rowTotals = new Array();
		$rowColTotal = 0;
		$(".multiGridDiv")
			.find('table').children('tbody').children('tr').each(function(){
				$colTotal = 0;
				$inputValue = $(this).children('td').each(function(){
					$inputValue = $(this).find('.number');
					$colNumber = (($inputValue.length > 0)) ? $(this).index() : 0;
					$inputValue = ($inputValue.length > 0) ? parseInt($inputValue.val()) : 0;
					$colTotal += $inputValue;
					$rowTotals[$colNumber] = (typeof $rowTotals[$colNumber] == "undefined") ? ($inputValue) : ($rowTotals[$colNumber] + $inputValue);
				});
				$(this).find(".totalColTextTotal").html($colTotal);
			});
		
		
		$(".multiGridDiv")
			.find('table').find("tr:last").children('td').each(function(){
				$inputValue = $(this).find('.totalRowText');
				$colNumber = (($inputValue.length > 0)) ? $(this).index() : 0;
				if($inputValue.length > 0){
					$inputValue.html($rowTotals[$colNumber]);
				}
			});
			
		//add rows and columns
		$(".totalColTextTotal").each(function(){
			$totalTotalValue = ($(this).html() > '') ? parseInt($(this).html()) : 0;
			$rowColTotal += $totalTotalValue;
		});
		
		//populate last col and row
		$(".totalRowTextTotal").html($rowColTotal);
	}
		
</script>