<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
The e-mail has been sent to the institutions that payed for their site visits. 
<br><br>
<?php 
	if ($not_payed) {
		echo '<input type="hidden" name="site_visit_not_payed" value="1">';
?>
	Please click "Next" to see the list of outstanding payments.
<?php 
	}else{
?>
	Please click "Next" to end this process.
<?php 
	}
?>
<br><br><br><br><br><br><br><br><br><br><br>
</td>
</tr></table>
</td></tr></table>
