<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of Screening Tasks:</span></td></tr><tr><td>&nbsp;</td></tr></table>';

echo '<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">';

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$provider = strtolower($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id"), "priv_publ"));

$this->validateFields("checkForm1");
$this->validateFields("checkForm1a");
$this->validateFields("checkForm1b");
$this->validateFields("checkForm2");

switch ($provider) {
	case "2":
		$this->validateFields("checkForm3");
		break;
	case "1":
		$this->validateFields("checkForm4");
		break;
}

$this->validateFields("checkForm2a");
$this->validateFields("checkForm5");
?>
<script>
	document.all.VALIDATION.value = "155";
</script>
<?php 
echo '</table>';
echo '</table>';
?>
