<?php 
	$id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="loud">
		<br>
		Information request history:
		</td>
	</tr>
	<tr>
		<td>
		<br>
		<?php $this->displayApplicationRequests($id); ?>
		</td>
	</tr>
</table>
<br>
