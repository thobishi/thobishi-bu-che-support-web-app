<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Search for Evaluators:</span></td>
</tr><tr>
	<td colspan="6">On the selection of appropriate evaluators rests the strength of the outcome of the Accreditation Phase of the accreditation process.  The Evaluators database will help you choose evaluators for this programme. In making your selection: 
<ul>
<li>Always chose more evaluators than you need, so you do not have to repeat the process.</li>
</ul>
</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td align="right"><b>Race:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("race") ?></td>
	<td align="right"><b>Gender:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("gender") ?></td>
	<td align="right"><b>Disability:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("disibility") ?></td>
</tr><tr>
	<td align="right"><b>Province:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("province") ?></td>
	<td align="right"><b>Sector:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("sector") ?></td>
	<td align="right"><b>Full/Part Time:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("full_part") ?></td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td align="right"><b>Teaching Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
	<td align="right"><b>Research Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Research_experience") ?></td>
	<td align="right"><b>Audit Evaluation Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Audit_Eval_Experience") ?></td>
</tr><tr>
	<td align="right"><b>Management Experience:</b>&nbsp;</td>
	<td class="oncolour" colspan="5">
	<?php	$this->showField("Spent_time_Management") ?>
	</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="right"><b>ETQA:</b>&nbsp;</td><td class="oncolour" colspan="4"><?php $this->showField("ETQA_ref") ?></td>
</tr><tr>
	<td colspan="2" align="right"><b>CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code") ?>
	</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="right"><b>Search:</b> (by Name)</td><td class="oncolour" colspan="4"><?php $this->showField("searchText") ?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4">
	<input class="btn" type="button" value="Search" onClick="doSearch();">
	</td>	
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr></table>
</FORM>
<?php 
$this->formAction = "";
$this->formTarget = "";
$this->formName = "defaultFrm";
$this->formOnSubmit = "return checkManager();";
$this->createForm();
?>
<table width="550" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="center">
	<fieldset class="go">
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<span class="msgn">
			Your search results will be displayed in box (i) below. 
			<br><br>
			The <img src="images/info_off.png" width="16" height="15" alt="Information button"> button  next to
			each Evaluator will launch a new window with more information on the specific Evaluator. 
			From within the new window, you will be able to add the Evaluator to the Accreditation Process.
			</span>
			</td>
		</tr>
	</table>
	</fieldset>
	<br>	
	</td>
	<td>&nbsp;</td>
	<td align="center">
	<fieldset class="go">
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<span class="msgn">
			The Evaluators that you select to be part of the Accreditation Process will be displayed in box (ii) below. 
			<br><br>
			To remove an Evaluator from the list,
			click the remove button. <br>
			Example remove button: &nbsp;<img src="images/btn_remove_off.gif" width="33" height="22" alt="Remove">
			</span>
			</td>
		</tr>
	</table>
	</fieldset>
	<br>	
	</td>
</tr>
<tr>
<td class="oncolour" width="55%" valign="top">
	<table width="300" align="center" cellpadding="2" cellspacing="2" border="0"><tr>
		<td>i) <b>Search Results:</b>
		<br>
		<IFRAME id="resultsFrame" name="resultsFrame" src="" style="height:150"></IFRAME></td>
	</tr></table>
</td>
<td width="30" align="center">
<img src="images/insert.gif" width="25" height="16" alt="">
<br>
<a href="javascript:removeManager(document.defaultFrm.elements['QAmanager']);removeManager(document.defaultFrm.elements['QAmanagerID']);"><img src="images/btn_remove.gif" width="33" height="22" alt="Remove" border="0"></a>
</td>
<td class="oncolour" valign="top" width="40%">
	<table width="220" align="center" cellpadding="2" cellspacing="2" border="0"><tr>
		<td>ii) <b>Selected Manager:</b><br><?php $this->showField("QAmanagerID")?><?php $this->showField("QAmanager") ?></td>
	</tr></table>
</td>
</tr></table>
</td></tr></table>
</td></tr></table>
<?php 
	$SQL = "SELECT Names, Surname, QAmanager_ref FROM Eval_Auditors, siteVisit WHERE Persnr=QAmanager_ref AND ".$this->dbTableInfoArray["siteVisit"]->dbTableKeyField."=?";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID);
	$sm->execute();
	$RS = $sm->get_result();


	//$RS = mysqli_query($SQL);
	if ($row = mysqli_fetch_array($RS)) {
		echo '<script>';
		echo 'document.defaultFrm.QAmanager.value = "'.$row["Surname"].", ".$row["Names"].'";';
		echo 'document.defaultFrm.QAmanagerID.value = "'.$row["QAmanager_ref"].'";';
		echo '</script>';
	}
?>

<script>
	function addManager (obj, val) {
		obj.value = val;
	}
	
	function removeManager (obj) {
		obj.value = "";
	}
	
	function checkManager () {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!(document.defaultFrm.QAmanager.value > "")) {
				alert('Please select a manager before continuing');
				return false;
			}
		}
		return true;
	}
</script>
