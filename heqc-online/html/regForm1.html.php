<?php

	$this->formFields["registration_date"]->fieldValue = date("Y-m-d");
	$this->showField("registration_date");

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	<span class="specialh">Online registration for higher education providers</span>
	<br>
	<br>	
	Please complete the form below and upload your completed <span class="special">
	HEQC Online User - Institutional Application form on the next page and then submit your application for approval</span>
	</td>
</tr>
<tr>
	<td>Please note that your e-mail address will be used as your <B><i>username</i></B> and that your <B><i>password</i></B> 
	will be the <B><i>password</i></B> that you fill in on the application form below.  Also note that we will not be able 
	to send your <B><i>password</i></B> via e-mail as the <B><i>password</i></B> is encrypted on our system.  
	We will however be able to generate a new one.</td>
</tr>
<tr>
<td>
	<br>
	<br>
	<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="left"><b>Surname*</b></td>
		<td class="oncolour"><?php $this->showField("surname")?></td>
	</tr><tr>
		<td align="left"><b>Name*</b></td>
		<td class="oncolour"><?php $this->showField("name")?></td>
	</tr><tr>
		<td align="left"><b>Title*</b></td>
		<td class="oncolour"><?php $this->showField("title_ref")?></td>
	</tr><tr>
		<td align="left"><b>E-mail Address*</b></td>
		<td class="oncolour"><?php $this->showField("email")?></td>
	</tr><tr>
		<td align="left"><b>Password*</b></td>
		<td class="oncolour"><?php $this->showField("password")?></td>
	</tr><tr>
		<td align="left"><b>Re-type Password*</b></td>
		<td class="oncolour"><input type="password" id="passwd2" name="passwd2" size="40"></td>
	</tr><tr>
		<td align="left" nowrap><b>Contact No*<br>Telephone No.</b> 
		</td>
		<td class="oncolour"><?php $this->showField("contact_nr")?></td>
	</tr><tr>
		<td align="left" nowrap><b><i>or</i><br>Cell No.</b> 
		</td>
		<td class="oncolour"><?php $this->showField("contact_cell_nr")?></td>
	</tr><tr>
		<td align="left"><b>Institution*</b></td>
		<td class="oncolour"><?php $this->showField("institution_ref")?>&nbsp;Other:<?php $this->showField("new_inst")?></td>
	</tr><tr>
		<td>&nbsp;</td>
		<td class="oncolour"><?php $this->showField("institution_name")?> </td>
	</tr><tr>
                <td align="left" nowrap><b>Is your institution a<br>public nursing institution?*</b>
		</td>
                <td class="oncolour"><?php $this->showField("public_nursing_college")?> </td>
	</tr>
	</table>
	<br><br>
	</td>
</tr>
<tr>
	<td colspan="2"><b><i>* Indicates required fields</i></b>
	</td>
</tr>
</table>
