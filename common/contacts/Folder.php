<?php 

class Folder {

  public $seq, $id, $parent, $desc, $root, $type;

  public function __construct() {
    $this->seq   =null;
    $this->id    ="";
    $this->parent=null;
    $this->desc  ="";
    $this->root  ="";
    $this->type  ="";
  }

  public function loadFromDB($row) {
		$this->seq   =$row["TreeSeq"];
		$this->id    =$row["TreeCode"];
	  $this->parent=$row["ParentRef"];
	  $this->desc  =$row["TreeDesc"];
	  $this->root  =$row["RootRef"];
    if (isset($row["type"])) {
	    $this->type  =$row["type"];
    }
  }
	
  public function isPublic($orgCode) {
    if ($orgCode==$this->root) return true;
    return false;
  }

  public function isPrivate($orgCode) {
    return !$this->isPublic($orgCode);
  }

  static public function folderInList($folderId, $list) {
    $result=false;
    foreach ($list as $folder) {
      if ($folderId==$folder->id) {
        $result=true;
        break;
      }
    }
    return $result;
  }
  
}

?>
