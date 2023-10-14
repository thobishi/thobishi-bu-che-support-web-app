<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
 $details = $this->getInstProgressDetails($_POST, Settings::get('template'));
 $nr_programme_id = $details[0]['id'];

//echo $prog_id;

$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_profile_llb');
$link4 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_data_llb');
$fieldsComplete = $this->getStatusOfSection('nr_programmes', $prog_id, array('ser_profile'));
$totalComplete = round((($fieldsComplete['totalCompleted']) /($fieldsComplete['totalRows']) * 100));


$userEmail = $this->db->getValueFromTable('users', 'user_id', Settings::get('currentUserID'), 'email');

$emailChunks = explode("@", $userEmail);
$univChunks = explode(".", $emailChunks[1]);
$universityCode = $univChunks[0];



if (file_exists("html_documents/HEMIS/". strtoupper($universityCode) . " HEMIS DATA.xlsx")) {
//echo "File exists";
$hemisDownload = "<th scope='col'><a target='_blank' href='html_documents/HEMIS/". strtoupper($universityCode) . " HEMIS DATA.xlsx'><img src='images/excel.jpg' alt='DOC'>". strtoupper($universityCode) . " HEMIS</a></th>";
}
else {
// echo "default case";
/* $hemisDownload = '
			<th scope="col">
                        
                            <a target="_blank" href="html_documents/HEMIS/DUT HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC">DUT HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/NMU HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />NMU HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/NWU HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />NWU HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/RHODES HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />RHODES HEMIS</a>
                        </th>
                        <th scope="col">
                            <a target="_blank" href="html_documents/HEMIS/SMU HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />SMU HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/TUT HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />TUT HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/TUT HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />TUT HEMIS</a>
                            <a target="_blank" href="html_documents/HEMIS/UFH HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UFH HEMIS</a>
                        </th>
                        <th scope="col">
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UFS HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UFS HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UJ HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UJ HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UKZN HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UKZN HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UL HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UL HEMIS</a>
                        </th>
                        <th scope="col">
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UNISA HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UNISA HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UNIZULU HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UNIZULU HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/UP HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />UP HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/VUT HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />VUT HEMIS</a>

                        </th>
                        <th scope="col">
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/WITS HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />WITS HEMIS</a>
                            <a class="col-md-2" target="_blank" href="html_documents/HEMIS/WSU HEMIS DATA.xlsx"><img src="images/excel.jpg" alt="DOC" />WSU HEMIS</a>
                        </th>
';*/
$hemisDownload = 'No Data';
}
?>



<table class="table table-bordered table-striped serTable">
    <tr>
        <td class="serNumber">
            1
        </td>
        <td>
            Download the Self-evaluation report (SER) template and complete each section as indicated.
        </td>
        <td class="fieldsetData">
            <fieldset><legend>Download Documentation</legend>
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Self Evaluation Report Template for Doctoral Studies _HEQC Approved_April 2019.doc"><img src="images/DOC.png" alt="DOC">&nbsp;Doctoral Studies SER</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Appendix A - DHET PQM 2018.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;Appendix A - DHET PQM 2018</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Appendix B -Admission and Registration Criteria.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;Appendix B - Admission and Registration Criteria</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Appendix C-Staff Profile.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;Appendix C - Staff Profile</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Appendix D-Student Progress.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;Appendix D - Student Progress</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="col-md-2" target="_blank" href="html_documents/LLB/Appendix E-Graduate Rates.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;Appendix E - Graduate Rates</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="col-md-2" target="_blank" href="html_documents/LLB/SER_Title Page  Template for Doctoral Studies _HEQC Approved_April 2019.doc"><img src="images/DOC.png" alt="DOC" />&nbsp;SER Title Page</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </fieldset>
        </td>
 
    </tr>

    <tr>
        <td class="serNumber">
            2
        </td>
        <td>
            Download the HEMIS DATA for each institution.
        </td>



        <td class="fieldsetData">
            <fieldset><legend>Download HEMIS Data</legend>
            
            
            
                <table class="table table-bordered table-striped">
                
                    <thead>
                    <tr>
                       <!--<th class="col">
                            First Batch
                        </th>-->

                    </tr>
                    </thead>
                    
                    <tbody>
					<tr> <?php echo $hemisDownload; ?> </tr>
                    </tbody>
                </table>
            </fieldset>
        </td>

    </tr>

    <tr>
        <td class="serNumber">
            3
        </td>
        <td class="text">
            <a href='<?php echo $link1; ?>'>Provide profile data for the programme</a>
        </td>
        <td class="fieldsetData">
            <fieldset><legend>Profile information</legend>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>
                            Status
                        </th>
                        <th>
                            Date completed
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php echo $totalComplete; ?>% complete
                        </td>
                        <td>
                        </td>
                        <td>
                            <a href='<?php echo $link1; ?>'><img src="images/edit.png" alt="Edit" /></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td class="serNumber">
            4
        </td>
        <td>
            Upload your Self-evaluation Report, Appendixes from A to E and the sign-off cover sheet provided in the SER template.
            <br /><br />
            Please note: You can upload new versions until your report is ready for submission.
        </td>
        <td class="fieldsetData">
            <fieldset><legend>Upload your SER and Sign-off Cover sheet</legend>
                <?php
					$this->makeLinkWithoutDelete("ser_doc", "SER document");
				         $this->makeLinkWithoutDelete("appendix_A_doc", "Appendix A");
					$this->makeLinkWithoutDelete("appendix_B_doc", "Appendix B");
					$this->makeLinkWithoutDelete("appendix_C_doc", "Appendix C");
					$this->makeLinkWithoutDelete("appendix_D_doc", "Appendix D");
					$this->makeLinkWithoutDelete("appendix_E_doc", "Appendix E");
                    			$this->makeLinkWithoutDelete("signoff_doc", "SER Title Page"); 
					$this->makeLinkWithoutDelete("additional_doc", "Additional Document");
                                        
                ?>
            </fieldset>
        </td>
        
        
    </tr>

<tr>

<!-- <tr>
        <td class="serNumber">
            5
        </td>
        <td>
            Download the Document
        </td>



        <td class="fieldsetData">
            <fieldset><legend>Download the Document Request from Chairperson </legend>
            
            
            
                <table class="table table-bordered table-striped">
                
                    <thead>
                    <tr>
                       <th class="col">
                            First Batch
                        </th>

                    </tr>
                    </thead>
                    
                    <tbody>
					<tr> <img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a> <?php echo $hemisDownload; ?> </tr>
                    </tbody>
                </table>
            </fieldset>
        </td>

    </tr>-->

    <tr>

		<td class="serNumber">
			5
		</td>
		<td  class="serDescription">
			Upload the Requested Additional Information
		</td>
		<td class="fieldsetData">
            
			<fieldset><legend>Upload the Additional Document Request</legend>
			<?php 
				$this->makeLink("additional_doc1", "Prior Site Visit - Q1-3 of SER","$nr_programmes","",$prog_id);
				$this->makeLink("additional_doc2", "Prior Site Visit - Q4 of SER", "$nr_programmes","",$prog_id);
				$this->makeLink("additional_doc3", "Prior Site Visit - Q5-8 and other support documents","$nr_programmes","",$prog_id);
				
			?>
			</fieldset>

		</td>
	</tr>

	<tr>
		<td class="serNumber">
			6
		</td>
		<td>
			Upload Progress Report:
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Progress Report</legend>
			<?php
				$this->showField("signoff_nr_manual_ind");
				echo ' A place for Institutional Administrators to upload their Progress Reports <br />';
				$this->makeLink("uploadser_progress_report", "Upload Progress Report","$nr_programmes","",$prog_id);
                $this->makeLink("uploadser_progress_report_1", "Upload Additional Progress Report","$nr_programmes","",$prog_id);
                $this->makeLink("uploadser_progress_report_2", "Upload Additional Progress Report","$nr_programmes","",$prog_id);
			?>
			</fieldset>
		</td>
	</tr>

</table>