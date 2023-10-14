<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>';
echo '<b>INSTITUTION INFORMATION:</b><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of Institution Information</span></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';
$this->validateFields("insForm1a");
$this->validateFields("insForm1");
$this->validateFields("insForm2");
$this->validateFields("insForm3");
$this->validateFields("insForm4");
$this->validateFields("insForm5");

echo '</table>';
echo '</table>';
?>
<script>
	document.defaultFrm.VALIDATION.value = 135;
</script>
