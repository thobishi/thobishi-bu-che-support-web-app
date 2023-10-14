$(function() {
	$('.rowByRowAdd').live('click', function(e) {

		var $this = $(this);
		var $parentRow = $this.closest('tr');
		var $rowTemplate = generateTemplate.call(this, $parentRow);
		if ($rowTemplate === false) {
			return true;
		}
		e.preventDefault();

		$parentRow.before($rowTemplate);
	})

	$('.rowByRowDel').live('click', function(e) {
		e.preventDefault();

		var $this = $(this);

		if($this.closest('table').find('tr').length > 4 || $this.hasClass('deleteAll')) {
			$this.closest('tr').remove();
			var id = $this.attr('id').split('-');

			var $deletedField = $('#' + id[0] + '-deleted');

			$deletedField.val($deletedField.val() + id[1] +'|');
		}
	});
});

//--- grid functions add to generic JS file
var insertCount = 1;
function generateTemplate($parentRow) {
	var $this = $(this);
	$template = $parentRow.prev().clone();

	$template.find(':input').each(function() {
		var $_this = $(this);
		var insertId = 'INSERT' + insertCount;
		var inputName = $_this.attr('name')
			.replace(/^GRID_(INSERT)?[0-9]+/, 'GRID_'+insertId)
			.replace(/^GRID_(INSERT)?[0-9]+/, 'GRID_'+insertId);

		if(!inputName.match(/\$/) && inputName.match(/save/)) {
			inputName += '$'+$this.attr('ref');
		}

		$_this.val('').attr('name', inputName);
	});
	var $delLink = $template.find('a.rowByRowDel');
	if ($delLink.length == 0) {
		return false;
	}
	var delId = $delLink.attr('id').replace(/\-(INSERT)?[0-9]+\-del$/, '-INSERT'+insertCount+'-del');
	$delLink.attr('id', delId);
	
	insertCount++;
	
	return $template;
}