<?php
	if (Settings::isIsset('currentUserID') && Settings::get('currentUserID') > 0){
		function buildMenu($subs, $inGroups, $always, $name="", $db){
			$SQL = "SELECT 
						distinct(processes_id), menu_perant 
					FROM
						processes, lnk_SecGroup_process 
					WHERE 
						menu_perant = :parent
						AND menu_in_menu='main' 
						AND menu_is_item='Yes' 
						AND (
							(
								process_ref=processes_id 
								AND secGroup_ref in ($inGroups)
							) OR (
								processes_id in ($always)
							)
						) 
						ORDER BY menu_sequence_number, processes_desc asc";
			
			$rs = $db->query($SQL, array(
				'parent' => empty($name) ? '0' : $name
			), true);

			if($rs->rowCount() > 0){
				array_push($subs, $name);
				while($row = $rs->fetch()){
					$subs = buildMenu($subs, $inGroups, $always, $row[0], $db);
				}
			}

			return $subs;
		}

		$subs = array();
		$inGroups = implode(",", $this->sec_inGroups());
		include("menuSettings.php");
		$always = implode(",", $menuAlways);

		$subs = buildMenu ($subs, $inGroups, $always, "", $this->db);

		$numMenu = 0;
		foreach ($subs as $val){
			$numMenu++;
			$SQL = "SELECT 
						distinct(processes_id), processes_desc, menu_alt_name, on_item_click, menu_perant 
					FROM 
						processes, lnk_SecGroup_process 
					WHERE 
						menu_perant = :parent
						AND menu_in_menu='main'
						AND menu_is_item='Yes'
						AND (
							(
								process_ref=processes_id 
								AND secGroup_ref in ($inGroups)
							) OR (
								processes_id in ($always)
							)
						) 
						ORDER BY menu_sequence_number , processes_desc asc ";

			$rs = $this->db->query($SQL, array(
				'parent' => empty($val) ? '0' : $val
			), true);
			$count = 0;

			while($row = $rs->fetch()){
				$url = "#";

				switch ($row["on_item_click"]){
					case "href":
						$url = "javascript:goto(" . $row["processes_id"] . ");";
						break; 
					case "showDescription":
						$url = "javascript:showProcessDescription(" . $row["processes_id"] . ");";
						break;
				}
				
				$menuName = $row["processes_desc"];
				
				$menuName = ($row["menu_alt_name"] > "") ? $row["menu_alt_name"] : $menuName;
				
				$parent = in_array($row["processes_id"], $subs) ? $menuName : '';
				
				if(in_array($row["processes_id"], $subs) || $row['menu_perant'] == 0){
					$this->menuOptions['menuItems'][$row["processes_id"]]['name'] = $menuName;
					$this->menuOptions['menuItems'][$row["processes_id"]]['url'] = $url;
				}else{
					$this->menuOptions['menuItems'][$row['menu_perant']]['children'][$count]['url'] = $url;
					$this->menuOptions['menuItems'][$row['menu_perant']]['children'][$count]['name'] = $menuName;
				}
				
				$count++;
			}
		}
	}