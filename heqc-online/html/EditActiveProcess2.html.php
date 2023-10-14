<?php 

$message = "The process has been handed over and the confirmation e-mails have been sent.";

if (isset($altMessage) && ($altMessage > "")) {
	$message = $altMessage;
}

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="75%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><?php echo $message?>
	</td>
</tr></table>
<br><br>
</td></tr></table>
