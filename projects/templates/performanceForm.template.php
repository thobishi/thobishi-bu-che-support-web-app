<?

$this->title			= "CHE Project Register";
$this->bodyHeader		= "formHead";
$this->body				= "performanceForm";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > List of CHE Core Mandates</span>";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == 'next') {
			if (!valTextRequired(obj.FLD_indicator_type,'Please enter a value for performance indicator short title.')) {return false};
			if (!valTextRequired(obj.FLD_indicator_desc,'Please enter a value for performance indicator description.')) {return false};
		    return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;

?>
