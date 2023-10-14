<?php

require_once('contacts/globals.php');

MenuBuilder::init();

class MenuBuilder {

  private static $cdb;

  public static function init() {
    self::$cdb=new ContactsDB();
  }

  private static function BuildMenu($node, $lvl, $treeRefId, $showGroupToGroup) {
    $icon  = 'f.gif';

    $folders=array();
    if ($lvl==0) {
      $folder=self::$cdb->getFolder($treeRefId);
      if ($folder) $folders[]=$folder;
    } else $folders=self::$cdb->getChildFolders($treeRefId);

    foreach($folders as $folder) {
      $treeNote='&nbsp;<a href="javascript:addGroup('."\'".$folder->id."\',\'".$folder->desc."\'".');"><img src="images/person.png" alt="Add group as recipient"></a>'.
                '&nbsp;<a href="javascript:addGroupRecursive('."\'".$folder->id."\',\'".$folder->desc."\'".');"><img src="images/person_add2.png" alt="Add group and all subgroups as recipient"></a>';
      if ($showGroupToGroup) {
        $treeNote.='&nbsp;<a href="javascript:addGroupToGroup('."\'".$folder->id."\',\'".$folder->desc."\'".');"><img src="images/group.png" alt="Add group to folder"></a>'.
                   '&nbsp;<a href="javascript:addGroupToGroupRecursive('."\'".$folder->id."\',\'".$folder->desc."\'".');"><img src="images/group_add.png" alt="Add group and all subgroups to folder"></a>';
      }
      $treeNote.='&nbsp;';
      $leaf = &$node->addItem(new HTML_TreeNode(array('text' => $folder->desc, 'link' => $folder->id, 
                                                      'icon' => $icon, 'expandedIcon' => $icon), array(), $treeNote));
      $leaf = self::BuildMenu($leaf, 1, $folder->id, $showGroupToGroup);
    }
    return ($node);
  }

  public static function showTree($treeRefStartId, $showGroupToGroup) {
    $menu=new HTML_TreeMenu();
    $menu=self::BuildMenu($menu, 0, $treeRefStartId, $showGroupToGroup);
    // Create the presentation class
    $treeMenu=&new HTML_TreeMenu_DHTML($menu, array('images' => 'images', 'defaultClass' => 'treeMenuDefault'));
    $treeMenu->printMenu();
  }

}

?>
