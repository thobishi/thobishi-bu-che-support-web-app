<br><br>

<table class="oncolourswitchb" width="80%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td class="special1" width="60%" valign="top" align="center">
	<span class="loud">Welcome to the CHE Project Register System</span>
<?php echo if ($this->userInterface == 2) { ?>
	The CHE Project Register is a register of projects taking place in the CHE.
	<br><br>
	Users register their projects on an annual basis.<br>
	Financial Information is downloaded to the Project Register on a monthly basis.
	Reports may be drawn for Projects Information and financial information.
	<br><br>
		<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<div id="information" style="display:block">
			<fieldset>
			<legend><span class="specialb"><i>Project Register</i></span></legend>
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td>
						<span class="special">
							X
						<br><br>
							X
						</span>
						</td>
					</tr>
				</table>
			</fieldset>
			</div>
			</td>
		</tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		<br>
	<br>
<?php echo } ?>
<?php echo if ($this->userInterface == 3) { ?>
<table><tr><td>
Welcome to the CHE Project Register.
<br><br>
This zone of HEQC ONLINE
<ul>
<li>	Allows you to update information in your project.
</ul>
</td></tr></table>

<?php echo } ?>
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
				<td align="right" valign="top"><img src="images/ie_logo_small.gif">&nbsp;<i>Please note that this system requires Internet Explorer 6 or higher in order to achieve full functionality</i>
				</td>
			</tr>
			<tr>

<?
	/*			<td colspan="2">
				Please <a href="html/browser_settings.html" target="_blank">click here</a> in order to check your browser settings against the recommended settings.
				</td>
	*/
?>
			</tr>
	</table>
</td>
</tr>
<tr><td align=center class="special1" colspan=2>
</td></tr>
</table>

<br><br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
The CHE Project Register is a register of projects taking place in the CHE. Users register their projects on an annual basis. 	Financial Information is downloaded to the Project Register on a monthly basis. Reports may be drawn for Projects Information and financial information.
</td></tr>
</table>

<br><br>
<br>

<table width="100%" border=0 class="footer"><tr><td>&nbsp;</td></tr></table>