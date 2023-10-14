<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of Evaluator Report</span></td></tr>';
echo '<tr><td><br><i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i></td></tr></table>';

echo '<br><table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';
echo '<tr><td colspan="2" align="center"><b>EVALUATOR REPORT ANSWERS VALIDATION:</b></td></tr>';

$this->validateFields("sitevisitReportForm1");
$this->validateFields("sitevisitReportForm2");
$this->validateFields("sitevisitReportForm3");
$this->validateFields("sitevisitReportForm10");
$this->validateFields("sitevisitReportForm4");
$this->validateFields("sitevisitReportForm5");
$this->validateFields("sitevisitReportForm6");
$this->validateFields("sitevisitReportForm7");

echo '</table>';
echo '</table>';
?>
<script>
	document.all.VALIDATION.value = 190;
</script>

