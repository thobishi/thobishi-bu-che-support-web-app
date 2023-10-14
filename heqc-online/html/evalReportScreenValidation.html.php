<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of Evaluator Report</span></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';
echo '<tr><td align="center"><b>EVALUATOR REPORT COMMENT VALIDATION:</b></td></tr>';

$this->validateFields("evalReportScreenForm1");
$this->validateFields("evalReportScreenForm2");
$this->validateFields("evalReportScreenForm3");
$this->validateFields("evalReportScreenForm4");
$this->validateFields("evalReportScreenForm5");
$this->validateFields("evalReportScreenForm6");
$this->validateFields("evalReportScreenForm7");


echo '</table>';
echo '</table>';
?>
<script>
	document.all.VALIDATION.value = 235;
</script>
