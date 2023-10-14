<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<tr>
	<td>
		<br>
		<span class="specialh">Site visit application 1</span>
		<br><br>
	</td>
</tr>
<tr>
	<td>
		A site visit may take place for several reasons:
		<ul>
			<li>In the case of a new institution</li>
			<li>When an institution is proposing to make use of a new or additional site of delivery</li>
			<li>When an institution is proposing to employ new modes of delivery</li>
			<li>When an institution wishes to extend its level of offerings from undergraduate programmes to postgraduate programmes</li>
			<li>A public complaint regarding the institution received via the Department of Education (DOE)</li>
			<li>An accreditation application that results in an outcome of deferral until after a site visit</li>
			<li>An accreditation application that results in an outcome of accreditation with conditions and one of the conditions is a site visit</li>
		</ul>
	</td>
</tr>
<tr>
	<td>
		<b>The following types of site visits (based on processing that must take place) may be scheduled:</b>
		<br>
		<br>
		<?php
		$sql = "SELECT * FROM lkp_site_proceedings";

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$rs = mysqli_query($conn, $sql);
		if (mysqli_num_rows($rs) > 0){
			$html = <<<HTML
				<table class="saphireframe" width="100%" border="0"  cellpadding="8" cellspacing="0">
				<tr>
					<td class="doveblox">Type of site visit</td>
					<td class="doveblox">Processing to take place</td>
				</tr>
HTML;
			while ($row = mysqli_fetch_array($rs)){
				$desc = simple_text2html($row["lkp_site_proceedings_desc"]);
				$act = simple_text2html($row["proceedings_action"]);
				$html .= <<<HTML
					<tr>
						<td class="saphireframe">{$desc}</td>
						<td class="saphireframe">{$act}</td>
					</tr>
HTML;
			}
			$html .= "</table>";
			echo $html;
		}
		?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<br>
		<b>Please select the institution for which a site visit or site visits must be scheduled.</b>
		<br>
		<span class="specialsi">Note: Only institutions with at least one site and one submitted programme application are listed.</span>
		<br>
		<br>
		<?php 
			if ($this->formFields["institution_ref"]->fieldValue > 0){
				$this->formFields["institution_ref"]->fieldStatus = 2;
			}
			$this->showField("institution_ref"); 
             
                     
		?>
		
		<br>
		<br>
		<br>
		
 <?php
                       
                          if ($this->formFields["siteapp_doc"]->fieldValue > 0) {
				$this->formFields["institution_ref"]->fieldStatus = 2;
			
                   echo $this->makeLink("siteapp_doc");}  ?> 

		
	</td>
	

</tr>
<tr>
	<td>
		<br>
		<b>Please enter the application number for this site visit application</b><br>
		<span class="specialsi">(Note: This no is included in the directorate recommendation)</span>
		<br>
		<br>
		<?php $this->showField("site_application_no"); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		Click next to proceed to selection of sites to be visited.
	</td>
</tr>
</table>