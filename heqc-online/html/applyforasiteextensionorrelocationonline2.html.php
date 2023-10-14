
<?php

$provider = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
 

?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>


<tr>
	<td>
		<br>
		<span class="specialh">Institution Application: New Sites of Delivery or Extension of programme offering</span>
		<br><br>
	</td>
</tr>
<tr>
	<td>
		If your application is ready upload your application below and click on Submit application in the Actions menu to submit the application to the CHE.  Once submitted you will not have access to this application.
		If you are still busy with your application you may access this application from a link on your Home page


	</td>
</tr>
<tr>
	<td>
		
		<br>
		<?php 
		echo $this->showField("inst_site_app_id");
		echo $this->showField("institution_ref");
		echo $this->showField("site_application_no");



$dt = new DateTime();

$dt->format('Y-m-d H:i:s');
//echo $dt->format('Y-m-d H:i:s');


			echo $this->showField("submition_date");
	    	echo $this->showField("app_type");


$this->formFields["submition_date"]->fieldValue =$dt->format('Y-m-d H:i:s');

?>
	</td>
</tr>
<tr>

	<br>
	<td>
		<br>
		<br>
		<table width="95%" border=0>
		 
		<tr>

	<br>
		</tr>
		<tr>
			
	<td width="30%" align="right"><b>Institutional Name:</b></td>
	<td class="oncolour">&nbsp;<?php echo $this->getValueFromTable("HEInstitution", "HEI_id",  $this->formFields["institution_ref"]->fieldValue, "HEI_name"); ?></td>

		</tr>
		<tr>
			<td valign="top">Upload new site or extension Application</td>
			<td> <?php echo $this->makeLink("siteapp_doc"); ?> </td>
		</tr>
	
		<?php 
			//if ($this->formFields["institution_ref"]->fieldValue > 0){
			//	$this->formFields["institution_ref"]->fieldStatus = 2;
			//}
			//formFields["institution_ref"]->fieldValue = 11)
		//	$this->formFields["institution_ref"]->fieldStatus = 2;
		//$this->formFields["institution_ref"]->fieldValue = $institutionid; 
		?>
		</table>
	</td>
</tr>
</table>
<br>
