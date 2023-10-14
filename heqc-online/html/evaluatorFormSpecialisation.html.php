<?php 
	function BuildTree($node, $lvl=0, $item="00") {
		$icon  = 'f.gif';

		$SQL = 'SELECT * FROM CESM_Tree WHERE CESM_code1 = ? ORDER BY CESM_code1, CESM_code2'; 
                $conn = $this->getDatabaseConnection();
                
                $sm = $conn->prepare($SQL);
                $sm->bind_param("s", $item);
                $sm->execute();
                $rs = $sm->get_result();
                
		if ($rs) {
			while ($row = mysqli_fetch_array($rs)){
				if (false){
					$leaf = &$node->addItem(new HTML_TreeNode(array('text' => $row["TreeDesc"], '' => "", 'icon' => $icon, 'expandedIcon' => $icon)));
		  	  	}else{
				  	$leaf = &$node->addItem(new HTML_TreeNode(array('text' => $row["Description"], 'link' => $row["CESM_code"], 'icon' => $icon, 'expandedIcon' => $icon, 'obj' => 'document.defaultFrm.elements['."\'FLDS_Specialisations[]\'".']'))); 
		 		}
				if ($lvl==0) {
					$leaf = BuildTree($leaf, 1, $row["CESM_code2"]);
				}
			} // while
		}
		return ($node);
	}

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td><input class="btn" type="button" value="Remove" onClick="removeSelectEntries(document.defaultFrm.elements['FLDS_Specialisations[]']);"></td>
<td></td>
</tr>
<tr>
	<td width="50%" valign=top>
		<?php $this->showField("Specialisations"); ?>
	</td>
	<td valign=top>
		Select Broad and Specific area(s) of specialisation for the evaluator.
<?php 
	$menu = new HTML_TreeMenu();
	$menu = BuildTree($menu);
	// Create the presentation class
	$treeMenu = &new HTML_TreeMenu_DHTML($menu, array('images' => 'images', 'defaultClass' => 'treeMenuDefault'));
	$treeMenu->printMenu();
?>
	</td>
	<td></td>
</tr>
</table>
<br><br>
</td></tr></table>
