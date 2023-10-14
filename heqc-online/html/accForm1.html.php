	<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
	<b>B) PROGRAMME INFORMATION</b>
	<br><br>
	<b>Section B</b> of the application form requires you to provide <i>information about your programme</i>. Once the application is submitted the institution will receive a reference number which will help you to query the state of your application.
	<br><br>
	<table width="80%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="center">
			<fieldset class="go">
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td align="left">
						<span class="msg">Follow the prompts and make use of the help window for further explanation on each step. </span><br>
						</td>
					</tr>
				</table>
			</fieldset>
			</td>
		</tr>
	</table>
	<br><br>
	Please, fill in all the fields below:<br>
	<br><br>
<?php 
$this->formFields["institution_id"]->fieldValue = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
$this->showField("institution_id");
?>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td ALIGN=RIGHT><b>Programme Name:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("program_name");?>
</td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Programme Type:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("prog_type") ?></td>
</tr>
<tr>
<td ALIGN=RIGHT valign="top"><b>Mode of Delivery:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("mode_delivery") ?></td>
</tr>
<tr>
<td ALIGN=RIGHT valign="top"><b>Has the programme been approved by Senate or any other relevant structure?</b></td>
<td valign="top" class="oncolour"><?php $this->showField("senate_approved") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Date of senate approval:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("senate_approved_date") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Designation:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("designation") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Qualifier:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("1st_qualifier") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Second Qualifier:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("2nd_qualifier") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>CESM Classification:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("CESM_code1") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>NQF Level:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("NQF_ref") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Number of Credits:</b></td>
<td valign="top" class="oncolour"><?php 
																		$this->showField("num_credits");
																	?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Expected Minimum Time - Full Time(number of years):</b></td>
<td valign="top" class="oncolour"><?php $this->showField("expected_min_time") ?></td>
</tr>
<tr>
<td ALIGN=RIGHT valign="top"><b>Expected Minimum Duration - Part Time (number of years):</b></td>
<td valign="top" class="oncolour"><?php $this->showField("expected_min_duration") ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b><span class="speciale">Status:</span></b></td>
<td>&nbsp;</td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><div id="notComply" style="display:<?php echo $display1?>"><b>Registration with DoE?</b></div></td>
<td valign="top" class="oncolour"><div id="notComply" style="display:<?php echo $display1?>"><?php $this->showField("is_reg_doe") ?></div></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><div id="notComply" style="display:<?php echo $display1?>"><b>DoE Registration Number?</b></div></td>
<td valign="top" class="oncolour"><div id="notComply" style="display:<?php echo $display1?>"><?php $this->showField("doe_reg_nr") ?></div></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>Is the qualification registered by SAQA on the NQF?</b></td>
<td valign="top" class="oncolour"><?php $this->showField("is_reg_saqa_nqf") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><b>SAQA Registration Number:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("saqa_reg_nr") ?></td>
</tr>
<?php /* taken out: 20050613
<tr>
<td ALIGN=RIGHT valign="top"><b>Has the programme been approved by Senate?</b></td>
<td valign="top" class="oncolour"><?php // $this->showField("senate_approved") ?></td>
</tr>
i*/ ?>
<tr>
<td ALIGN=RIGHT valign="top"><b>Date by which you plan to start offering the programme:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("prog_start_date") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top"><div id="notComply" style="display:<?php echo $display2?>"><b>Is the programme part of your institution’s approved PQM?</b></div></td>
<td class="oncolour"><div id="notComply" style="display:<?php echo $display2?>"><?php $this->showField("is_part_pqm") ?></div></td>
</tr><tr>
<td ALIGN=RIGHT></td>
<td></td>
</tr></table>
<br><br>
</td></tr></table>
