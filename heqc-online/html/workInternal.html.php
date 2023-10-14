<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1">
<br>
<span class="specialh">Welcome <?php echo $this->getCurrentUserInfo()?></span>
<br>
<?php
	if ($this->sec_userInGroup("Administrator")){
		$this->makeSumProcTable();
	}

?>
<br><br>

</tr>
<tr>
	<td align=center class="special1">
		<span class="specialrb"><?php echo $this->displayUserMessage; ?></span>
		<br>
		<span class="specialb">You have the following active processes...</span>
	</td>
</tr>
<tr>
	<td>
<?php
	$sortorder = readPost("sortorder");

	$this->showActiveProcesses($sortorder);
?>
<br>
<br>

	</td>
</tr>
</table>
