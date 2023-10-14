<h3>Criteria validation</h3>
<?php	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$settings = $this->getStringWorkFlowSettings(Settings::get('workFlow_settings'));
	$formsToValidate = array(									
					array("ser_prelim_analysis_criteria","Criteria validation", "manual")				
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
	echo '</table>'
	

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
