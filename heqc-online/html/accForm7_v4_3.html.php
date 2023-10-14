


<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION D: PROGRAMME / QUALIFICATION DESIGN</h2>
</span>
</td></tr>
</table>
<br>
<?php 
	//$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	//if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	//$this->displayRelevantButtons($current_id, $this->currentUserID);
	/*$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }
	$this->displayRelevantButtons($current_id, $this->currentUserID);
	$prov_type = $this->checkAppPrivPubl($current_id);
	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
*/

?>


<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($app_id); }

	$heiID  = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$programmeName = $this->getValueFromTable('Institutions_application','application_id',$app_id,'program_name');

	$path = "";
	
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="1">
	
	<tr>
		<td>
			
				<b>PROVIDE CONCISE RESPONSES TO THE FOLLOWING QUESTIONS.
			
		</td>
	</tr>

</table>


<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>


	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>1.	How does the programme / qualification fit in with the vision and mission of the institution? </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_1_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>2.	Provide the rationale for the programme / qualification, considering the envisaged student intake and stakeholder needs.: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_2_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>3.	Provide the purpose of the programme / qualification: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_4_comment_v2");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>4.	Indicate how the proposed curriculum and exit level outcomes contribute to the achievement of the purpose: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_4_achieve_purpose");?></td>
	</tr>

	<BR>
	<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>5.	Complete the Table  in terms of the module structure of the programme / qualification: </b></td>
		
	<tr/>
	<tr>
		<BR>
		<td ALIGN=RIGHT valign="top" width="35%"><b>  </b></td>
		<BR/>
		<td colspan="2">
			<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0" id="hikeTable">
			<tbody>
			<?php
				$dFields = array();
				array_push($dFields, "type__text|name__course_name");

				//2010-07-28 Robin: Limit NQF level to a drop down list for version 3 applications and up.
				$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
				if ($app_version <= 2){
					array_push($dFields, "type__text|name__nqf_level");
				}
				if ($app_version >= 3){
					array_push($dFields, "type__select|name__nqf_level_ref|description_fld__NQF_level|fld_key__NQF_id|lkp_table__NQF_level|lkp_condition__1|order_by__NQF_id");
				}


				
			//	$dom = new DOMDocument();
				//$dom->load("accForm7_v4_3.html.php");
				// $fund_credits= $dom->getElementsByName("GRID_354553$appTable_1_prog_structure_id$nqf_level$appTable_1_prog_structure");

				//alert($fund_credits);
				//  $total=0;
				//$tcourse_type="Compulsory";
				array_push($dFields, "type__text|name__fund_credits");

				//array_push($dFields, "type__text|name__course_type");
				//array_push($dFields, "$tcourse_type|name__course_type");
				array_push($dFields, "type__select|name__course_type|description_fld__lkp_Compulsory_Description|fld_key__lkp_Compulsory_Description|lkp_table__lkp_Compulsory|lkp_condition__1|order_by__id");
				
				
				array_push($dFields, "type__text|name__year");
				//array_push($dFields, "type__text|name__core_credits");
				array_push($dFields, "type__text|name__semester");
				
				//array_push($dFields, "type__hidden|name__course_typ|value__".$tcourse_type);
				//echo ($dFields);
				
				

				$hFields = array("Module name", "NQF Level of the module", "No. of credits per module","modules", "Year of study (1, 2, 3, 4)",  "Semester (1,2)");
				//$totalFields = array("Total no. of credits for the compulsory modules", "NQF Level of the module", "No. of credits per module", "Compulsory", "Year of study (1, 2, 3, 4)",  "Semester (1,2)");

			//	echo ($dFields);
			//$total = $total + $fund_credits;

				$this->gridShowRowByRow("appTable_1_prog_structure","appTable_1_prog_structure_id","application_ref__".$app_id,$dFields,$hFields, 40, 5, "true", "true",1);
			//	$this->gridShowRowByRow("appTable_1_prog_structure","appTable_1_prog_structure_id","application_ref__".$current_id, 40, 5, "true", "true",1);
			
				//echo $total;
			//$htFields = array("Total");
				

			//array_push($tFields, "type__text|name__total_compulsory_credits");
			
			//$this->gridShowRowByRow("appTable_1_prog_structure","appTable_1_prog_structure_id","application_ref__".$current_id,$tFields,$htFields, 40, 5, "true", "true",1);
		
			?>
			</tbody>
			</table>
		</td>
		
		
		
		
		<td valign="top" class="oncolour"><?php $this->showField("6_policies_rpl_whyNot");?></td>

		<BR>
	</tr>
	<BR>
	<tr>

		<BR>
	<tr/>
	<tr>
		
		
		<td ALIGN=RIGHT valign="top" width="35%"><b>  </b></td>
		
		<BR>
		<td colspan="2">
			<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0" id="hikeee">
			<?php
			/*	$doFields = array();
				array_push($doFields, "type__text|name__course_name");

				//2010-07-28 Robin: Limit NQF level to a drop down list for version 3 applications and up.
				$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
				if ($app_version <= 2){
					array_push($doFields, "type__text|name__nqf_level");
				}
				if ($app_version >= 3){
					array_push($doFields, "type__select|name__nqf_level_ref|description_fld__NQF_level|fld_key__NQF_id|lkp_table__NQF_level|lkp_condition__1|order_by__NQF_id");
				}
				//$tocourse_type="Optional";
				array_push($doFields, "type__text|name__fund_credits");
				//array_push($dFields, "type__text|name__course_type");
				array_push($doFields, "type__select|name__course_type|description_fld__lkp_Optional_Description|fld_key__lkp_Optional_Description|lkp_table__lkp_Optional|lkp_condition__1|order_by__id");
				
				
				array_push($doFields, "type__text|name__year");
				//array_push($dFields, "type__text|name__core_credits");
				array_push($doFields, "type__text|name__semester");
				//array_push($doFields, "type__hidden|name__course_typ|value__".$tocourse_type);
				$hoFields = array("Module name", "NQF Level of the module", "No. of credits per module","Optional", "Year of study (1, 2, 3, 4)", "Semester (1,2)");

				$this->gridShowRowByRow("appTable_1_prog_structure","appTable_1_prog_structure_id","application_ref__".$app_id,$doFields,$hoFields, 40, 5, "true", "true",1);

				*/
			?>
			</table>
		</td>
		
		
		<td valign="top" class="oncolour"><?php $this->showField("6_policies_rpl_whyNot");?></td>
	</tr>
	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>6.	Specify the rules of combination for the constituent modules to indicate coherence: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_7_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>7.	Indicate the rules of progression (semester / year) if applicable: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_7_progression_rules");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>8.	Exit level outcomes: List and number all the ELOs: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("exit_level_outcomes");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>9.	Associated Assessment Criteria: List the AAC per ELO or as integrated across all ELOs: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("associated_assessment_criteria");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>10.	Explain how the competences that will be developed in the programme are aligned to the NQF level of the qualification: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_6_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>11.	Explain how the programme design – in terms of the proportion of theoretical, practical and experiential learning (if applicable) – meets the requirements of the qualification level and type: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_how_design_meet_qual_req");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>12.	International comparability: Indicate how this programme / qualification compares with or relates to professional standards, or to (at least two) comparable accredited programmes / qualifications offered in other parts of the world: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_international_comparability");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>13.	If no comparable programmes / qualifications are indicated, provide substantive reasons why this qualification is not internationally comparable: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_no_international_comparability_reason");?></td>
	</tr>


	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>14.	Describe the horizontal, vertical, and diagonal articulation possibilities of this qualification in relation to other registered qualifications (institutional/internal or external). If there are no articulation possibilities, provide substantive reasons why the programme / qualification should nonetheless be considered viable: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("1_3_comment");?></td>
	</tr>


</table>
<br>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
Description
</td>
<td>
Template 
</td>
<td>
Upload the template
</td>
</tr>



<tr>
<td>
1. MODULE OUTLINES
</td>
<td>
Upload your institution’s module outline document
</td>
<td>
<?php $this->makeLink("5_moduleoutlines_doc");?>
</td>
</tr>

<tr>
<td>
	Work-Integrated Learning WIL Template is missing on alpha site
Complete the Work-Integrated learning.docx template  if applicable

</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/WORK-INTEGRATED LEARNING.docx"?>"> Work-Integrated learning.docx</a>
</td>
<td>
<?php $this->makeLink("types_implementation_doc");?> <!--THIS IS STILL TO BE DETERMINED BASED ON THE SPECIFICATION DOCUMENT -->
</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Refer to the accompanying <a href="documents/GUIDELINES FOR COMPLETING THE APPLICATION FOR PROGRAMME ACCREDITATION AND QUALIFICATION REGISTRATION.docx" target="_blank">
			 guidelines </a> for completion of this form
		<img src="images/word.gif">
	</td>
</tr>
<tr>
	<input type="button" value="Click me" hidden="hidden"  onclick="finishTable()">
</tr>

</table>


<script type="text/javascript">

 var debugScript = true;

 		function finishTable()
		{
			if (debugScript)
				window.alert("Beginning of function finishTable");
					
			var tableElem = window.document.getElementById("hikeTable"); 
					
			var totalMilesPlanned = computeTableColumnTotal("hikeTable",1);
			//var totalMilesHiked = computeTableColumnTotal("hikeTable",3);

			if (debugScript)
			{
				window.alert("totalMilesPlanned=" + totalMilesPlanned );
			}

			return;
		}

		function computeTableColumnTotal(tableId, colNumber)
		{
			// find the table with id attribute tableId
			// return the total of the numerical elements in column colNumber
			// skip the top row (headers) and bottom row (where the total will go)
					
			var result = 0;
					
			try
			{
				var tableElem = window.document.getElementById(tableId); 		   
				var tableBody = tableElem.getElementsByTagName("tbody").item(0);
				var i;
				var howManyRows = tableBody.rows.length;
				for (i=1; i<(howManyRows-2); i++) // skip first and last row (hence i=1, and howManyRows-1)
				{
					var thisTrElem = tableBody.rows[i];
					var thisTdElem = thisTrElem.cells[colNumber];			
					var thisTextNode = thisTdElem.childNodes.item(0);

					if (debugScript)
					{
						window.alert("text is " +JSON.stringify(thisTextNode.value));
					} // end if

					// try to convert text to numeric
					var thisNumber = parseFloat(thisTextNode.data);
					// if you didn't get back the value NaN (i.e. not a number), add into result
					if (!isNaN(thisNumber))
						result += thisNumber;
				} // end for
					
			} // end try
			catch (ex)
			{
				window.alert("Exception in function computeTableColumnTotal()\n" + ex);
				result = 0;
			}
			finally
			{
				return result;
			}
			
		}
		
		
</script>	

