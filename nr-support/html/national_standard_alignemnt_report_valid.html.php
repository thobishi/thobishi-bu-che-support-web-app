<h3>Validation before submission</h3>
<?php

	echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));

	$detailsReal = array(); // Initialize the new array

foreach ($details as $row) {
    if ($row['nr_programme_name'] === 'Bachelor of Social Work') {
        $detailsReal[] = $row; // Add the matching row to the new array
    }
}

// Access the desired value(s) in the new array
if (!empty($detailsReal)) {
    foreach ($detailsReal as $row) {
        echo $row['che_reference_code'] . "\n";
    }
} else {
    echo "No matching row found.";
}

    $nr_programme_id = $detailsReal[0]['id'];
	
	// Set dbTableCurrentID to the value of $nr_programme_id
	$this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID = $nr_programme_id;

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
echo $prog_id;
$settings = $this->getStringWorkFlowSettings(Settings::get('workFlow_settings'));
$formsToValidate = array(
array("national_standard_alignemnt_report","National Standard Alignment Report Overview", "auto"),
array("national_standard_alignemnt_report_profile_llb","Table 4.1 SER Title Page", "auto"),
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