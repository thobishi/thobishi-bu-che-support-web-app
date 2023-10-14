<br><br>

<table class="oncolourswitchb" width="80%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td class="special1" width="60%" valign="top" align="center">
	<span class="loud">Welcome to the CHE Contract Register System</span>
</td>
</tr>

<tr>
<td width="30%" valign="top" align="center">
	<table class="oncolourswitchb" width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="5"><span class="expiry"><?php echo $this->noLogonMessage?></span></td>
		</tr>
		<tr>
			<td align=right width="40%">Username / email address:</td>
			<td width="10%" colspan="2"><?php echo $this->showField("oct_username") ?></td>
		</tr>
		<tr>
			<td align=right>Password:</td>
			<td colspan="2"><?php echo $this->showField("oct_passwd") ?></td>

			<td colspan="2" align="left">
			<input type="submit" class="btn" name="login" value="Login">
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
			<td align="left"><a href="?goto=8">Forgot password</a>?</td>
		</tr>
<?php echo /* HTM COMMENT OUT
		<tr>
			<td colspan="2" align="center">Check browser capabilities? click <a href="?goto=79">here</a></td>
		</tr>
	*/
?>
	</table>

		<table class="oncolourswitchb" width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td align="center" valign="top"><img src="images/ie_logo_small.gif">&nbsp;<i>Please note that this system requires Internet Explorer 6 or higher in order to achieve full functionality</i>
				</td>
			</tr>
<?php 
	/*
			<tr>

			<td colspan="2">
				Please <a href="html/browser_settings.html" target="_blank">click here</a> in order to check your browser settings against the recommended settings.
				</td>
			</tr>
	*/
?>
	</table>
</td>
</tr>
<tr><td align=center class="special1" colspan=2>
</td></tr>
</table>

<br><br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
The CHE Contract Register assists users in complying with audit requirements through the management and monitoring of consultant/service provider contracts.
Authorised users are required to capture and update the details of contracts with consultants/service providers. 
A reporting interface in the system allows authorised users to monitor the status of consultants/service providers contracted by the CHE.
</td></tr>
</table>

<br><br>
<br>

<table width="100%" border=0 class="footer"><tr><td>&nbsp;</td></tr></table>