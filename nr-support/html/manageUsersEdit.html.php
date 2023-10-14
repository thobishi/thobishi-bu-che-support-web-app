<?php
	$curentUserEdit_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
	$rg_groupId = $this->getGroupId('RC member');
	$nrc_groupId = $this->getGroupId('NRC member');
	
	$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	
	$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);	

	$this->showBootstrapField('name', 'Name');

	$this->showBootstrapField('surname', 'Surname');

	$this->showBootstrapField('title_ref', 'Title');

	$this->showBootstrapField('email', 'Email');

	$this->showBootstrapField('contact_nr', 'Contact number');

	$this->showBootstrapField('contact_cell_nr', 'Contact cellular number');
	
	$this->showBootstrapField('active', 'Status');
	
	if($isRgMember){
?>
		<div class="page-header  alert alert-info">
		  <h2>Reference group Restrictions<small> Click on the available programmes to add to the programme restrictions for this user</small></h2>
		</div>
<?php
		echo '<div class = "row-fluid">';
		$this->manageProgrammeDropdown('', 'Programmes to restrict','selected', 'rg_restriction');
		$this->manageProgrammeDropdown('WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() ', '(Click to add programmes to restrict)','available', 'rg_restriction');	
		echo '</div>';
?>		
		<div class="clear"></div><br><br>
		<div class="page-header  alert alert-info">
		  <h2>Reference group Programme assignment<small> Click on the available programmes on the right to assign programmes to this user</small></h2>
		</div>
<?php
		echo '<div class = "row-fluid">';
		$this->manageProgrammeDropdown('', 'Programmes to assign','', 'rg_assignment', 'assigned');
		$this->manageProgrammeDropdown('WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() ', '(Click to add programmes to assign)','', 'rg_assignment','','notAssigned');	
		echo '</div>';		
	}
	
	if($isNRC_member){
?>
		<div class="page-header  alert alert-info">
		  <h2>NRC group Restrictions<small> Click on the available programmes to add to the programme restrictions for this user</small></h2>
		</div>
<?php
		echo '<div class = "row-fluid">';
		$this->manageProgrammeDropdown('', 'Programmes to restrict','', 'nr_restriction','nr_restricted');
		$this->manageProgrammeDropdown('WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() ', '(Click to add programmes to restrict)','', 'nr_restriction', '', 'nrNot_restricted');	
		echo '</div>';	
?>
		<div class="clear"></div><br><br>
		<div class="page-header  alert alert-info">
		  <h2>NRC Programme assignment<small> Click on the available programmes on the right to assign programmes to this user</small></h2>
		</div>
<?php
		echo '<div class = "row-fluid">';
		$this->manageProgrammeDropdown('', 'Programmes to assign','', 'nrc_assignment', 'nrc_assigned');
		$this->manageProgrammeDropdown('WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() ', '(Click to add programmes to assign)','', 'nrc_assignment','','nrc_notAssigned');	
		echo '</div>';	
		
	}
?>

<script>
function selectAll() {
	if (typeof document.defaultFrm.elements['progSelected[]'] != "undefined") {
		sLength = document.defaultFrm.elements['progSelected[]'].length;
		for (i=0; i<sLength; i++) {
			document.defaultFrm.elements['progSelected[]'].options[i].selected = true;
		}
	}
	if (typeof document.defaultFrm.elements['assigned[]'] != "undefined") {
		aLength = document.defaultFrm.elements['assigned[]'].length;
		for (i=0; i<aLength; i++) {
			document.defaultFrm.elements['assigned[]'].options[i].selected = true;
		}	
	}
	if (typeof document.defaultFrm.elements['nr_restricted[]'] != "undefined") {
		nrcLength = document.defaultFrm.elements['nr_restricted[]'].length;
		for (i=0; i<nrcLength; i++) {
			document.defaultFrm.elements['nr_restricted[]'].options[i].selected = true;
		}		
	}
	if (typeof document.defaultFrm.elements['nrc_assigned[]'] != "undefined") {
		nrcAssignedLength = document.defaultFrm.elements['nrc_assigned[]'].length;
		for (i=0; i<nrcAssignedLength; i++) {
			document.defaultFrm.elements['nrc_assigned[]'].options[i].selected = true;
		}		
	}	
	return true;
}
$(document).ready(function(){
	$('select').change(function(){
		var $this = $(this),
			optionSelected = $this.find(':selected'),
			parentId = optionSelected.parent('select')[0].id,
			newId,
			newOption = new Option(optionSelected.text(), optionSelected.val());
			 if(parentId == 'selected'){
				newId = 'available';
			}else if( parentId == 'available'){
				newId = 'selected';
			}else if(parentId == 'assigned'){
				newId = 'notAssigned';
			}else if(parentId == 'nr_restricted'){
				newId = 'nrNot_restricted';
			}else if(parentId == 'nrNot_restricted'){
				newId = 'nr_restricted';
			}else if(parentId == 'nrc_assigned'){
				newId = 'nrc_notAssigned';
			}else if(parentId == 'nrc_notAssigned'){
				newId = 'nrc_assigned';
			}else{
				newId = 'assigned';
			}	

		var s = document.getElementById(newId);
		s.options[s.options.length] = newOption;

		if(optionSelected){
			optionSelected.remove();
		}
	});
})
</script>