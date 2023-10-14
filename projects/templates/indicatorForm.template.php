<?

$this->title			= "CHE Project Register";
$this->bodyHeader		= "formHead";
$this->body				= "indicatorForm";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Edit > Performance Indicator </span>";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == 'next') {
			if (!valNumberRequired(obj.FLD_perf_ind_value,'Please enter a numeric value for the indicator.')) {return false};
			return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;

?>
