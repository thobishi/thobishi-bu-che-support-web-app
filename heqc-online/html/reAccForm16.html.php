<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
<td colspan="2" class="loud"><b>2.14</b> Programme review<br><hr></td>
</tr>
<tr>
  <td colspan="2"></br><b>2.14.1</b> Briefly describe current internal and external programme review processes to ensure that students achieve the required exit level outcomes? Provide evidence or examples of quality improvements introduced as a result of programme reviews conducted during the last three years. Include appropriate evidence of programme review.<br></td>
</tr>
<tr>
  <td><?php $this->showField("regular_periodic_review");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.14.2</b> What user surveys (graduates, peers, external examiners, employers, relevant professional bodies) does the institution conduct to ascertain whether the programme is achieving the intended outcomes? Explain and assess how the results of these surveys are incorporated into the institutional and programmatic strategic, academic and resource planning in order to improve the quality of programme provision?<br></td>
</tr>
<tr>
  <td><?php $this->showField("user_surveys");?><br><br></td>
</tr>
</table>