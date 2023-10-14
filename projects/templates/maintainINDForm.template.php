<?
$this->title			= "CHE Project Register";
$this->bodyHeader		= "formHead";
$this->body				= "maintainINDForm";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > Maintain indicator list</span>";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if ((obj.MOVETO.value == 'next') && (obj.VIEW.value != -1)) {
			if (!valTextRequired(obj.FLD_perf_ind_detail_title,'Please enter a value for name or title.')) {return false};
		return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;

?>
