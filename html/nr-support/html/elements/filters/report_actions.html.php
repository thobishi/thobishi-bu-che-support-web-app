<?php
	$url = (!empty($filter)) ? '?' . $this->generateFilterValues(Settings::get('template'), $filter) : '';
	$url = 'docgen/xls_' . Settings::get('template') . '.php' . $url;
?>

<div class="reportActions btn-group">
	<a class="btn" href="<?php echo $url; ?>">Download Excel report</a>
</div>
<br /><br />