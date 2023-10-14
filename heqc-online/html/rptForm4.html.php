<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Below are the required fields that you have filled in. Complete the outstanding fields before continuing.<br><br><i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i></span></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';

$this->validateFields("evaluatorForm2");
$this->validateFields("evaluatorForm3");
$this->validateFields("evaluatorForm4");
?>
<script>
	document.all.VALIDATION.value = 153;
</script>
<?php 

echo '</table><br><br>';
echo '</td></tr></table><br><br>';
?>
