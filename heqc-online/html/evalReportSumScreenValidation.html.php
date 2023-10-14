<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>';
$this->showInstitutionTableTop();
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';
echo '<tr><td align="center" colspan="2"><b>SUMMARY EVALUATOR\'S COMMENTS:</b></td></tr>';

$this->validateFields("evalReportSumScreenForm1");
$this->validateFields("evalReportSumScreenForm2");
$this->validateFields("evalReportSumScreenForm3");
$this->validateFields("evalReportSumScreenForm8");
$this->validateFields("evalReportSumScreenForm4");
$this->validateFields("evalReportSumScreenForm5");
$this->validateFields("evalReportSumScreenForm6");
$this->validateFields("evalReportSumScreenForm7");

echo '</table>';
echo '</table>';
?>
<script>
	document.defaultFrm.VALIDATION.value = 236;
</script>
