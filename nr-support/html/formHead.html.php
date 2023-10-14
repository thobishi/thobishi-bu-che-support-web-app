<?php $this->createForm('form-horizontal'); ?>
<div class="row-fluid">
    <?php
		if(Settings::get('flowID') == 2 && Settings::get('currentUserID') != 0){
	?>
    <div class="span3">
        <div id="quickLinks" class="quick-links">
            <h4 class="yellow-background">QUICKLINKS</h4>
            <!-- Documents used for BSW
                 DO NOT DELETE AND KEEP DOCUMENTS FOR HISTORICAL REASONS
                            <table class="table file-list">
                                <tr>
                                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/National_Review_Manual.pdf" target="_blank">National Review Manual for the Re-accreditation of Programmes, 2012</a></td>
                                </tr>
                                <tr>
                                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/CHE_accreditation_criteria_Nov2004.pdf" target="_blank">Criteria for Programme Accreditation, 2004</a></td>
                                </tr>
                                <tr>
                                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/CHE_Accreditation_Criteria_for_the_Review_of_BSW_2012.pdf" target="_blank">CHE Accreditation criteria adapted for the Review of Undergraduate </br>Programmes, 2013</a></td>
                                </tr>
                                <tr>
                                    <td><img src="html_images/DOC.png" alt="link_image"/></td><td><a href="html_documents/SER_template.docx"  target="_blank">Self-evaluation Report Template, 2013</a></td>
                                </tr>
                            </table>
            -->
            <table class="table file-list">
                <tr>
                    <td class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/1.Framework National Review_2015.pdf" target="_blank">Framework National Review</a></td>
                <tr>
                    <td class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/2.Framework Qualification Standards Development_2015.pdf" target="_blank">Framework Qualification Standards Development</a></td>
                </tr>
                <tr>
                    <td class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/Standards for Bachelor of Social Work  final version_20150921.pdf" target="_blank">Standard for Bachelor of Social Work final</a></td>
                </tr>
                <!-- <tr>
                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/4.National Review Manual_Doctoral degrees_HEQC Approved_11 April 2019.pdf" target="_blank">National Review Manual Doctoral degrees HEQC Approved 11 April 2019</a></td>
                </tr> -->
                <tr>
                    <td><img src="html_images/DOC.png" alt="link_image"/></td><td><a href="html_documents/BSW_NSAR_template_December 2022.docx" target="_blank">BSW NSAR Template 2022</a></td>
                </tr>
                <tr>
                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/6.PUB_HEQSF.PDF"  target="_blank">PUB HEQSF</a></td>
                </tr>
		<!-- <tr>
                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/Addendum to Manual for National Review (002).pdf"  target="_blank">Addendum to Manual for National Review</a></td>
                </tr> -->

<tr>
                    <td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/BSW Evaluation Guidelines _NSAR_2023_Final.pdf"  target="_blank">BSW Evaluation Guidelines 2023</a></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
		}
		elseif(Settings::get('flowID') != 2 && Settings::get('currentUserID') != 0 || (Settings::get('flowID') == 9)){
			echo '<div class="span3">';
    $actionsNumbers = array(
    'Profile ' => 4.1,
    'Budget' => 4.5,
    'Academic staff' => 4.6,
    'Student rate of completion' => 4.7,
    );

    $actions = $this->showUpdatedActions('actions');

    if(!empty($actions)){
    echo '<div id="generalActions" class="general-actions side-box">';
    foreach($actions as $displayActionData){
    echo $displayActionData['link'];
    }
    echo '</div>';
    }

    $sections = $this->showUpdatedActions('sections');

    if(!empty($sections)){
    echo '<div id="pageSections" class="page-sections side-box">';
    echo '<h1 class="yellow-background">Sections to complete</h1>';
    echo '<ul class="nav nav-list">';
	// To Remove section heading
        $removeSectionList = array('ser_prelim_validation', 'ser_prelim_criteria');
        foreach($sections as $heading_key => $sectionData){
	if (in_array($heading_key, $removeSectionList))
	  continue;
        $depth = $this->array_depth($sectionData);
        if($depth > 1){
        $sectionNumber = (isset($actionsNumbers[$heading_key])) ? '<span class="sectionNo">' . $actionsNumbers[$heading_key] . '</span>' : '';
        echo '<li class="nav-header">' . $sectionNumber . $heading_key . '</li>';
        foreach ($sectionData as $sectionActionKey => $sectionActionData){
        $classArray = explode(' ', $sectionActionData['class']);
        $class = (in_array('current', $classArray)) ? '-current' : '';
        $link = (!empty($class)) ? strip_tags($sectionActionData['link']) : $sectionActionData['link'];
        echo '<li class="nav-child' . $class . '">' . $link . '</li>';
        }
        }else{
        $classArray = explode(' ', $sectionData['class']);
        $class = (in_array('current', $classArray)) ? 'current' : '';
        $cleanAction = trim(strip_tags($sectionData['link']));
        $sectionNumber = (isset($actionsNumbers[$cleanAction])) ? '<span class="sectionNo">' . $actionsNumbers[$cleanAction] . '</span>' : '';
        $link = (!empty($class)) ? strip_tags($sectionData['link']) : $sectionData['link'];
        echo '<li class="' . $class . '">' . $sectionNumber . $link . '</li>';
        }
        }

        echo '</ul></div>';
    }

    echo '</div>';
}
?>
<div class="<?php echo ((Settings::get('currentUserID') > 0) || (Settings::get('flowID') == 9)) ? 'span9' : 'span12'; ?>">