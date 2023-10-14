<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>';
echo '<b>SITE INFORMATION:</b><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of Site Information</span></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';
$this->validateFields("siteForm1");
$this->validateFields("siteForm2");
$this->validateFields("siteForm3");
$this->validateFields("siteForm4");

echo '</table>';
echo '</table>';
?>
<script>
	document.all.VALIDATION.value = 274;
</script>
