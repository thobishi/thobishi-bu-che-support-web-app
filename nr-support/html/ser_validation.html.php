<h3>Validation before submission</h3>
<?php	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$settings = $this->getStringWorkFlowSettings(Settings::get('workFlow_settings'));
	$formsToValidate = array(
					array("ser","Self1 Evaluation report Overview", "auto"),
					array("ser_profile","Table 4.1 A Profile of Institution", "auto"),
					array("ser_contact","Table 4.1 B Contact person", "auto"),
					array("ser_contact_head","Table 4.1 A Contact details of Head of programme", "auto"),		
					array("ser_profile_sites_delivery","Table 4.1 D Sites of delivery", "manual"),									
					array("ser_budget_income","Table 4.5 A Income", "manual"),
					array("ser_budget_expenses","Table 4.5 B Expenses", "manual"),					
					array("ser_budget_student","Table 4.5 C1 Student Support - Number of students", "manual"),
					array("ser_budget_student_totals","Table 4.5 C2 Student Support - Total amounts", "manual"),
					array("ser_academic_qualifications","Table 4.6 A Academic qualifications", "manual"),
					array("ser_academic_demographic","Table 4.6 B1 Demographic profile of staff in the Department/Unit (Nationality)", "manual"),
					array("ser_academic_demographic_race","Table 4.6 B2 Demographic profile of staff in the Department/Unit (Race)", "manual"),
					array("ser_student_demographic","Table 4.7 Demographic Table indicating student rate of completion", "manual"),
					array("ser_data","Data tables", "manual"),
	
				);
			
	echo '<table class="table table-hover">';					
	foreach($formsToValidate as $form){
		echo '<thead>';
		echo '<tr>';
			echo '<th colspan = "3">'.$form[1].'</th>';
		echo '</tr>';
		echo '</thead>';
		$this->validateFields("$form[0]", "", "", $form[2]);
		if (isset($child["$form[0]"]) && $child["$form[0]"] > ""){
			$this->validateFieldsperChild($site_title,$child["$form[0]"],"application_ref",$app_id);
		}
	}
	echo '</table>';
	

?>   
	
<script>
	$('tr.error').click(function () {
		var url = $(this).find('a:first').attr('href');
		window.location.href = url;
	});
	$('tr.error').hover(
    function () {
        if ($(this).find("th").length > 0) return;
        $(this).addClass("validationRowHover");
    },
    function () { $(this).removeClass("validationRowHover"); }
	);
</script>
