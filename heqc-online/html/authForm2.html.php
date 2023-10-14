<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br><br><br><br>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td nowrap>
				The outcome of the user authorisation is:
				<br>
				<br>
				<?php
				switch ($outcome){
				case 'accepted':
					echo "<b>The user registration has been accepted and the user notified via email.</b>";
					break;
				case 'declined':
					echo "<b>The user registration has been declined and the user notified via email.</b>";
					break;
				default:
					echo '<b>This user was neither accepted or declined. No emails were sent.  Please contact support.</b>';
				}
				?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

