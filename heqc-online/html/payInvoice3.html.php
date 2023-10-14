<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showField("received_confirmation");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">
			Confirmation of payment
		</span>
		<br>
	</td>
</tr>
<tr>
	<td>
		<table border="0" cellpadding="2" cellspacing="2" width="95%">
		<tr>
			<td>
				Please enter the payment date
			</td>
			<td>
				<?php $this->showField("date_payment"); ?>
			</td>
		</tr>
		<tr>
			<td>
				Please enter the amount paid by the institution for this application: <br />
				(<i>to a maximum of the invoice amount</i>)
			</td>
			<td>
				<?php $this->showField("payment_total"); ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<span class="visi">
			Note: This application will only be considered paid up when the amount paid by the institution equals the invoice amount.
		</span>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr><tr>
	<td>
		<table border="0" cellpadding="2" cellspacing="2" width="70%">
		<tr class="oncolour">
			<td>Invoice date</td><td><?php $this->showField("date_invoice"); ?></td>
		</tr>
		<tr class="oncolour">
			<td>Invoice total</td><td><?php $this->showField("invoice_total"); ?></td>
		</tr>
		<tr class="oncolour">
			<td>Date of first reminder</td><td><?php $this->showField("date_first_reminder"); ?></td>
		</tr>
		<tr class="oncolour">
			<td>Date of second reminder</td><td><?php $this->showField("date_final_reminder"); ?></td>
		</tr>
		<tr class="oncolour">
			<td>Date scheduled to be withdrawn<br><i>(The application will be returned to the institution for them to re-submit when they are ready)</i></td>
			<td><?php $this->showField("date_return_inst"); ?></td>
		</tr>
		</table>
	</td>
</tr>
</table>

