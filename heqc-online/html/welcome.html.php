
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td class="special1" width="60%" valign="top">
<span class="loud">Council on Higher Education (CHE) Privacy Policy</span>

<br>
 <p>Access the CHE’s Privacy Policy at <a href="https://www.che.ac.za/privacy-policy"> https://www.che.ac.za/privacy-policy</a>   for information about how the CHE processes personal data collected from or provided by you in using this website.</p>
         
		
<span class="loud">Welcome to the Higher Education Quality Committee Accreditation System</span>
	
	<br>
<?php /*if ($this->userInterface == 2) */{ ?>
	This screen will give you basic information on the HEQC accreditation process and help you to start your application.
	<br><br>
	Institutions may submit applications for accreditation of programmes throughout the year, taking into account that the Accreditation Committee makes decisions five times a year.
	<br><br>
		<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<div id="accreditation" style="display:block">
			<fieldset>
			<legend><span class="specialb"><i>Accreditation</i></span></legend>
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td>
						<span class="special">
						Accreditation is the recognition status given for a stipulated period of
						time by the HEQC to a programme after an evaluation indicates that
						it meets or exceeds a minimum threshold of educational quality.
						<br><br>
						To learn more about the accreditation process itself, <a href="javascript: goto('115');">click here</a>
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
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<img id="cost_img" name="cost_img" src="images/ico_plus.gif" onclick="javascript:showHide2(document.getElementById('cost'), document.getElementById('cost_img'));"> <a href="javascript:showHide2(document.getElementById('cost'), document.getElementById('cost_img'));">What does it cost?</a>
			<br><br>
			<?php include("costs.html"); ?>
			</td>
		</tr>
		</table>
	<br>
<?php } ?>
<?php if ($this->userInterface == 3) { ?>
<table><tr><td>
Welcome to the HEQC Evaluator's Portal.
<br><br>
This zone of HEQC ONLINE
<ul>
<li>	Allows you to see and print the application you will evaluate.
<li>	Enables you to do an evaluation online.
<li>	Provides you with all the documentation attached to an application.
<li>	Allows you to access an institution's profile.
<li>	Allows you to update your personal details in our database.
</ul>
</td></tr></table>

<?php } ?>
	<br>
</td>
<td width="30%" valign="top" align="center">
	<table class="oncolourswitchb" width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="2"><span class="expiry"><?php echo $this->noLogonMessage ?></span></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<span class="loud">Login</span>
			</td>
		</tr>
		<tr>
			<td align=right><b>Username<br>(email address):</b></td>
			<td><?php $this->showField("oct_username") ?></td>
		</tr>
		<tr>
			<td align=right><b>Password:</b></td>
			<td><?php $this->showField("oct_passwd") ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<input type="submit" class="btn" name="login" value="Login">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">New user? Forgot your password? Click <a href="?goto=8">here</a>.</td>
		</tr>
<?php /* HTM COMMENT OUT
		<tr>
			<td colspan="2" align="center">Check browser capabilities? Click <a href="?goto=79">here</a>.</td>
		</tr>
	*/
?>
	</table>

	<br><br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
		<img id="who_img" name="who_img" src="images/ico_plus.gif" onclick="javascript:showHide2(document.getElementById('who'), document.getElementById('who_img'))"> <a href="javascript:showHide2(document.getElementById('who'), document.getElementById('who_img'));">Who can apply?</a>
		<br><br>
		<div id="who" style="display:none">
		<fieldset>
		<legend><span class="specialb"><i>Who can apply?</i></span></legend>
			<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td>
					<span class="special">
					Each institution is permitted to have <b>one</b> HEQC-online administrator who is responsible for the creation and submission of a programme application.
					<br><br>
					In the event that other institutional users require access to the system, then the HEQC-online administrator for the institution has the facility to manage additional institutional users.
					<br><br>
					After applying for a login name, the Council on Higher Education will authorise you as the HEQC-online administrator for the institution. You will be then be issued with the login details enabling you to access the accreditation system.
					<br><br>
					If you are an <b>institutional administrator</b> and wish to apply to be the HEQC-online administrator for your institution, <a href="javascript: goto(3);">click here</a>.
					<br><br>
					If you are an <b>institutional user</b> and you need to gain access to the system, please talk to the HEQC-online administrator for your institution.
					</span>
					<br>
					</td>
				</tr>
			</table>
		</fieldset>
		<br>
		</div>
		<b>If you are an institutional administrator and wish to apply for a login name</b> <a href="javascript: goto(3);">click here</a></b>
		</td>
	</tr>
	</table>

	<br><br>
		
</td>
</tr>
<tr><td align=center class="special1" colspan=2>
</td></tr>
</table>
