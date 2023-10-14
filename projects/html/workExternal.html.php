<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td align=center class="special1">
	<br>
	<br>
	<span class="specialb">
	Welcome <?php echo $this->getCurrentUserInfo()?>.
	</span>
	<br>
	<br>
</td>
</tr>

<tr>
<td>
	<br>
	<br>
</td>
</tr>

<tr>
<td>
<?php
	$this->showActiveProcesses();
?>
	<br>
	<br>
</td>
</tr>
</table>
