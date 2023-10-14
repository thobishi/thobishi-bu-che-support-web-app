<script type="text/javascript">
menunum=0;
menus=new Array();
_d=document;

function addmenu(){
	menunum++;
	menus[menunum]=menu;
}

function dumpmenus(){
	mt="<script type=text\/javascript>";
		for(a=1; a < menus.length; a++){
			mt+=" menu"+a+"=menus["+a+"];";
		}
	mt+="<\/script>";
	_d.write(mt);
}

effect = "Fade(duration=0.2);Alpha(style=0,opacity=100);Shadow(color='000000', Direction=125, Strength=1)";

timegap=300;		// The time delay for menus to remain visible
followspeed=5;		// Follow Scrolling speed
followrate=40;		// Follow Scrolling Rate
suboffset_top=4;	// Sub menu offset Top position
suboffset_left=6;	// Sub menu offset Left position
closeOnClick = true;

style1=[			// style1 is an array of properties. You can have as many property arrays as you need. This means that menus can have their own style.
"ffffff",			// Mouse Off Font Color
"336699",			// Mouse Off Background Color
"336699",			// Mouse On Font Co"picture galleries","http://",,,0lor
"C1D1E0",			// Mouse On Background Color
"",					// Menu Border Color
11,					// Font Size in pixels
"normal",			// Font Style (italic or normal)
"bold",				// Font Weight (bold or normal)
"Verdana, Helvetica, sans-serif;",	// Font Name
1,					// Menu Item Padding
"",					// Sub Menu Image (Leave this blank if not needed)
,					// 3D Border & Separator bar
"66ffff",			// 3D High Color
"336699",			// 3D Low Color
"",					// Current Page Item Font Color (leave this blank to disable)
"",					// Current Page Item Background Color (leave this blank to disable)
"",					// Top Bar image (Leave this blank to disable)
"",					// Menu Header Font Color (Leave blank if headers are not needed)
"",					// Menu Header Background Color (Leave blank if headers are not needed)
"ffffff",			// Menu Item Separator Color
];

<?

// 20070619 (Diederik): this just does not look right....
if(isset($this->currentUserID) && $this->currentUserID > 0){

	function buildMenu ($subs,$inGroups,$name="") {
		// 20070619 (Diederik): WE need a desc this include file
		include ("menuSettings.php");

		$always = implode(",",$menuAlways);
		$menusel = (($name>"")?("= '".$name."'"):("= 0"));
		$SQL = "SELECT distinct(processes_id), menu_perant FROM processes, lnk_SecGroup_process WHERE menu_perant".$menusel." AND menu_in_menu='main' AND menu_is_item='Yes' AND ((process_ref=processes_id AND secGroup_ref in (".$inGroups.")) OR (processes_id in (".$always."))) ORDER BY menu_sequence_number , processes_desc asc";
//		echo $SQL."<br>";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0) {
		 array_push($subs,$name);
			while($row = mysqli_fetch_array($rs)) {
				$subs = buildMenu ($subs,$inGroups,$row[0]);
			}
		}
		return ($subs);
	}

	$subs = array();
	$inGroups = implode(",",$this->sec_inGroups());
	$subs = buildMenu ($subs,$inGroups);

	include ("menuSettings.php");
	$always = implode(",",$menuAlways);

	$numMenu = 0;
	//bou al die menus wat in die structure define is
	foreach ($subs as $val){
		$numMenu++;
		if ($val == ""){
			echo "addmenu(menu=['mainmenu',87,0,70,,'left',style1,1,'center',,,1,,,,,,,,,,";
		}else{
			echo "addmenu(menu=['".$val."', , , 130, 1, , style1, , 'left', effect,,,,,,,,,,,,";
		}
		$parentSel = (($val>"")?("= '".$val."'"):("= 0"));
		$SQL = "SELECT distinct processes_id,processes_desc,menu_alt_name,on_item_click FROM processes, lnk_SecGroup_process WHERE menu_perant".$parentSel." and menu_in_menu='main' and menu_is_item='Yes' AND ((process_ref=processes_id AND secGroup_ref in (".$inGroups.")) OR (processes_id in (".$always."))) ORDER BY menu_sequence_number , processes_desc asc ";
//		echo $SQL;

		$rs = mysqli_query($SQL);

		while($row = mysqli_fetch_array($rs)) {
//			if ($this->sec_partOfGroup ($row["sec_group"])) {
				$url = "#";

			switch ($row["on_item_click"]){
				case "href" : $url = "javascript:goto(".$row["processes_id"].");"; break;
				case "showDescription" : $url = "javascript:showProcessDescription(".$row["processes_id"].");"; break;

			}

				$menuName = $row["processes_desc"];
				if ($row["menu_alt_name"] > "") $menuName = $row["menu_alt_name"];
				echo ",'".$menuName."', ";
				if (in_array ($row["processes_id"], $subs)){
					echo "'show-menu=".$row["processes_id"]."', '".$url."',,1";
				}else{
					echo "'".$url."', '#',,1";
				}
//			}
		}
		echo "]);\n";
	}

// doen dieselfede as die dumpmenus javascript function
	$mt="";
	for($a=1; $a <= $numMenu; $a++){
		$mt.=" menu".$a."=menus[".$a."];";
	}
	echo $mt;
}
?>
</script>
<script type="text/javascript" src="menu/damenu.js"></script>
