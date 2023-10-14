<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td colspan="2">
	<b>The institution indicated below has requested a login name to submit an application for accreditation.
	<br><br>
	Please, check the validity of the phone number and e-mail before accepting the request.</b>
	</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td align="right">Surname: </td>
	<td><?php $this->showField("surname")?></td>
</tr><tr>
	<td align="right">Name: </td>
	<td><?php $this->showField("name")?></td>
</tr><tr>
	<td align="right">Title: </td>
	<td><?php echo $this->showField("title_ref")?></td>
</tr><tr>
	<td align="right">User E-mail: </td>
	<td><?php $this->showField("email")?></td>
</tr><tr>
	<td align="right">Contact Telephone No: </td>
	<td><?php $this->showField("contact_nr")?></td>
</tr><tr>
	<td align="right">Contact Cell No: </td>
	<td><?php $this->showField("contact_cell_nr")?></td>
</tr><tr>
	<td align="right">Institution: </td>
	<td><?php $this->showField("institution_ref")?>&nbsp;Other:<?php $this->showField("new_inst")?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><?php $this->showField("institution_name")?></td>
</tr><tr>
	<td align="right">Public Nursing Institution: </td>
	<td><?php $this->showField("public_nursing_college")?></td>
</tr><tr>
	<td align="left">Institutional Application form</td>
	<td class="oncolour"><?php $this->makeImport("registration_doc")?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><?php $this->showField("doAccept")?></td>
</tr><tr>
	<td colspan="2"><b>If you decline, give a reason/comment to be added to the email that the institution will receive.</b></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><?php $this->showField("declineReason")?></td>
</tr></table>
</td></tr></table>
