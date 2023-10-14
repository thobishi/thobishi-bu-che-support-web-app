<?php 
	$arr = array();
	$otherUserTables = $this->parseOtherWorkFlowProcess($_GET["AP"]);
?>
<br><br>
<table width="85%" cellpadding="2" cellspacing="2" align="center" border="1">
	<tr>
		<td class="line" align="center" width="25%"><b>PROCESS</b>	
		</td>
		<td class="line" align="center" width="15%"><b>START</b>
		</td>
		<td class="line" align="center" width="15%"><b>FINISH</b>
		</td>
		<td class="line" align="center" width="15%"><b>OUTCOME</b>
		</td>
		<td class="line" align="center" width="15%"><b>COMMENTS</b>
		</td>
		<td class="line" align="center" width="15%"><b>RESPONSIBLE</b>
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		SUBMISSION
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">send receipt for submission</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;<?php echo ((isset($otherUserTables["payment"])) || (isset($otherUserTables["screening"]) && ($this->getValueFromTable($otherUserTables["screening"]->dbTableName, $otherUserTables["screening"]->dbTableKeyField, $otherUserTables["screening"]->dbTableCurrentID, "documentation") > "")))?("OK"):("")?>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["payment"]))?("OK"):("")?>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">send receipt for documentation</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;<?php echo ((isset($otherUserTables["payment"])) || (isset($otherUserTables["screening"]) && ($this->getValueFromTable($otherUserTables["screening"]->dbTableName, $otherUserTables["screening"]->dbTableKeyField, $otherUserTables["screening"]->dbTableCurrentID, "documentation") > "")))?("OK"):("")?>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["payment"]))?("OK"):("")?>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">archive?</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		PAYMENT
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">calculate and send invoice</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["payment"]) && ($this->getValueFromTable($otherUserTables["payment"]->dbTableName, $otherUserTables["payment"]->dbTableKeyField, $otherUserTables["payment"]->dbTableCurrentID, "invoice_sent") > ""))?("OK"):("")?>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["eval_screening"]))?("OK"):("")?>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">receipt payment</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["payment"]) && ($this->getValueFromTable($otherUserTables["payment"]->dbTableName, $otherUserTables["payment"]->dbTableKeyField, $otherUserTables["payment"]->dbTableCurrentID, "received_confirmation") > ""))?("OK"):("")?>
		</td>
		<td>&nbsp;<?php echo (isset($otherUserTables["eval_screening"]))?("OK"):("")?>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		APPOINT EVALUATORS
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">check DB evaluators</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">Send invitation</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Confirmation/contract(template)</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">4.&nbsp;</td>
					<td width="88%">authorise login</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">5.&nbsp;</td>
					<td width="88%">payment</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		EVALUATION
		</td>
	</tr>
		<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">Acknowledge evaluation</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">prepare report</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Circulate report</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		SITE VISIT
		</td>
	</tr>
		<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">Set date</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">Organise panel(look in DB)</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Organise logistics</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Write report</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		AC MEETING
		</td>
	</tr>
		<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">set date</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">prepare report</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">send report</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">4.&nbsp;</td>
					<td width="88%">prepare  agenda</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">5.&nbsp;</td>
					<td width="88%">send agenda</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">6.&nbsp;</td>
					<td width="88%">File meeting resolution</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		COMMUNICATE
		</td>
	</tr>
		<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">Letter to HEIs</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">Update SAQA/DoE</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Update website</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td class="oncolourb" colspan="6">
		APPEAL
		</td>
	</tr>
		<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">1.&nbsp;</td>
					<td width="88%">Acknowledge letter</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">2.&nbsp;</td>
					<td width="88%">Set AC meeting</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">3.&nbsp;</td>
					<td width="88%">Send documentation</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td width="12%" valign="top">4.&nbsp;</td>
					<td width="88%">Communicate resolution</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;
		</td>
	</tr>
</table>
<br><br>
