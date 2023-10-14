<?php

require_once('contacts/globals.php');

class ContactsDB {

  public function __construct() {
    static $conn=false;
    if ($conn) return;
    $conn=mysqli_connect(DBSERVER, DBUSER, DBPWD);
    if (!$conn) {
      die(LINKDOWN.' : Could not connect: ' . mysqli_error());
    }
    mysqli_select_db(DBNAME);
  }

  public function addUser($user) {
    if ($user->username=='') die('Username required');
    $sql="INSERT INTO users (id, username, name, surname, office_phone, cell_phone, password, email, licence_key, user_type, organization) VALUES (NULL, '".
         mysqli_real_escape_string($user->username)."', '".
         mysqli_real_escape_string($user->name)."', '".
         mysqli_real_escape_string($user->surname)."', '".
         mysqli_real_escape_string($user->office_phone)."', '".
         mysqli_real_escape_string($user->cell_phone)."', ".
         "md5('".mysqli_real_escape_string($user->password)."'), '".
         mysqli_real_escape_string($user->email)."', '".
         mysqli_real_escape_string($user->licence_key)."', '".
         mysqli_real_escape_string($user->user_type)."', '".
         mysqli_real_escape_string($user->organization)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $sql="select id from users where username='".mysqli_real_escape_string($user->username)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs, MYSQL_ASSOC) or die('No result returned')) {
      $user->id=$row['id'];
    }
    $this->createRootFolder($user->id, $user->name." ".$user->surname);
  }
  
  public function updateUser($user) {
    if ($user->username=='') die('Username required');
    if ($user->id=='') die('Id required');
    $sql="update users set".
         " username='".mysqli_real_escape_string($user->username)."', ".
         " name='".mysqli_real_escape_string($user->name)."', ".
         " surname='".mysqli_real_escape_string($user->surname)."', ".
         " office_phone='".mysqli_real_escape_string($user->office_phone)."', ".
         " cell_phone='".mysqli_real_escape_string($user->cell_phone)."', ";
    if ($user->password!='') $sql.=" password=md5('".mysqli_real_escape_string($user->password)."'), ";
    $sql.=" email='".mysqli_real_escape_string($user->email)."', ".
         " licence_key='".mysqli_real_escape_string($user->licence_key)."', ".
         " user_type='".mysqli_real_escape_string($user->user_type)."', ".
         " organization='".mysqli_real_escape_string($user->organization)."' where id='".mysqli_real_escape_string($user->id)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function removeUser($userId, $username) {
    $sql="delete FROM users WHERE username='".mysqli_real_escape_string($username)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $sql="delete FROM tree WHERE TreeCode='".mysqli_real_escape_string('U'.$userId)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  // returns the user record on success
  public function getUser($username) {
    $result=null;
    $sql="SELECT * FROM users WHERE username='".mysqli_real_escape_string($username)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs)) {
      $result=new User();
      $result->loadFromDB($row);
    }
    return $result;
  }

  // returns the user record on success
  public function getUserById($userId) {
    $result=null;
    $sql="SELECT * FROM users WHERE id='".mysqli_real_escape_string($userId)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs)) {
      $result=new User();
      $result->loadFromDB($row);
    }
    return $result;
  }
  
  public function getAllUsers() {
    $result=array();
    $sql="SELECT * FROM users order by name, surname and user_type!='alias'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $user=new User();
      $user->loadFromDB($row);
      $result[]=$user;
    }
    return $result;
  }

  public function getAliasesForCompany($company) {
    $result=array();
    $sql="SELECT * FROM users where organization='".mysqli_real_escape_string($company)."' and user_type='alias' order by name, surname";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $user=new User();
      $user->loadFromDB($row);
      $result[]=$user;
    }
    return $result;
  }

  public function getUsersForCompany($company) {
    $result=array();
    $sql="SELECT * FROM users where organization='".mysqli_real_escape_string($company)."' and user_type!='alias' order by name, surname";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $user=new User();
      $user->loadFromDB($row);
      $result[]=$user;
    }
    return $result;
  }
  
  public function getOrganizations() {
    $result=array();
    $sql="SELECT * FROM organization order by organization";;
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $org=new Organization();
      $org->loadFromDB($row);
      $result[]=$org;
    }
    return $result;
  }
  
  public function getOrganization($name) {
    $result=null;
    $sql="SELECT * FROM organization where organization='".mysqli_real_escape_string($name)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $org=new Organization();
      $org->loadFromDB($row);
      $result=$org;
    }
    return $result;
  }

  public function addOrganization($orgname) {
    $sql="INSERT INTO organization (organization) VALUES ('".mysqli_real_escape_string($orgname)."')";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  // returns true if password has been successfully updated.
  public function updatePassword($user) {
    $error=false;
    $sql="update users set password=md5('".mysqli_real_escape_string($user->password)."') where id=".mysqli_real_escape_string($user->id);
    if (mysqli_query($sql) or die(mysqli_error()." : ".$sql)) {
      mymail($user->email, "New Octo Contacts password", 
        "Dear $user->name,".
        "\n\nYou have requested a new password from the Octo Contacts Administration.".
        "\n\nUsername: $user->name".
        "\nPassword: $user->password".
        "\nWeb site: http://www.octoplus.co.za/".
        "\n\nKind regards,".
        "\nOcto Contacts Administration.", "From: contacts@octoplus.co.za\nReply-To: contacts@octoplus.co.za");
      $error=true;
    }
    return $error;
  }

  // returns user if the username matches the password otherwise returns null.
  public function authenticate($username, $passwd) {
    $result=null;
    $sql="SELECT * FROM users WHERE username='".mysqli_real_escape_string($username)."' AND password=md5('".mysqli_real_escape_string($passwd).
         "') and user_type!='alias'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs)) {
      $result=new User();
      $result->loadFromDB($row);
    }
    
    return $result;
  }
  
  // returns user if the username matches the licence key otherwise returns null.
  public function authenticateLicence($username, $licenceKey) {
    $result=null;
    if ($licenceKey==0) return $result;
    $sql="SELECT * FROM users WHERE username='".mysqli_real_escape_string($username)."' AND licence_key='".mysqli_real_escape_string($licenceKey).
         "' and user_type!='alias'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs)) {
      $result=new User();
      $result->loadFromDB($row);
    }
    return $result;
  }

  // returns user if licence key is valid.
  public function authenticateLicenceOnly($licenceKey) {
    $result=null;
    if ($licenceKey==0) return $result;
    $sql="SELECT * FROM users WHERE licence_key='".mysqli_real_escape_string($licenceKey)."' and user_type!='alias'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs)) {
      $result=new User();
      $result->loadFromDB($row);
    }
    return $result;
  }
  
  public function expireKey($username) {
    $sql="UPDATE users SET licence_key='0' WHERE username='".mysqli_real_escape_string($username)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function changeKey($username, $newKey) {
    $sql="UPDATE users SET licence_key='".mysqli_real_escape_string($newKey)."' WHERE username='".mysqli_real_escape_string($username)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function getReportYears() {
    $result=array();
    $sql="SELECT distinct left(dateSent,4) FROM sendemail order by dateSent";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_row($rs)) {
      $result[]=$row[0];
    }
    return $result;
  }

  public function delTemplate($id) {
    $sql="delete FROM templates WHERE template_id='".mysqli_real_escape_string($id)."'";
    return mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function getTemplate($id) {
    $result=null;
    $sql="SELECT * FROM templates WHERE template_id='".mysqli_real_escape_string($id)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $result=new Template();
      $result->loadFromDB($row);
    }
    return $result;
  }
  
  public function addTemplate($template) {
    $sql="INSERT INTO templates (template_id, template_name, template_html) VALUES (NULL, '".
         mysqli_real_escape_string($template->name)."', '".
         mysqli_real_escape_string($template->html)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $sql="select LAST_INSERT_ID()";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_row($rs) or die('No result returned')) {
      $template->id=$row[0];
    }
  }

  public function updateTemplate($template) {
    if ($template->id=='') die('template id required');
    $sql="update templates set".
         " template_name='".mysqli_real_escape_string($template->name)."', ".
         " template_html='".mysqli_real_escape_string($template->html)."' where template_id='".mysqli_real_escape_string($template->id)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function getAllTemplates() {
    $result=array();
    $sql="SELECT * FROM templates order by template_id";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $template=new Template();
      $template->loadFromDB($row);
      $result[]=$template;
    }
    return $result;
  }

  // Get a person given his email address
  public function getPerson($email) {
    $result=null;
    $sql="select * from person where email='".mysqli_real_escape_string($email)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $result=new Person();
      $result->loadFromDB($row);
    }
    return $result;
  } 
  
  public function getPersonFromId($personId) {
    $result=null;
    $sql="select * from person where id='".mysqli_real_escape_string($personId)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $result=new Person();
      $result->loadFromDB($row);
    }
    return $result;
  } 

  public function removePerson($email) {
    $sql="delete FROM person WHERE email='".mysqli_real_escape_string($email)."'";
    return mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function addPerson($person) {
    if ($person->email=='') die('Email address required');
    $sql="INSERT INTO person (id, name, surname, email, phone, fax, mobile, company, job_title, postal_addr, postal_code, physical_addr, physical_code,".
         " email2, email3, keywords) VALUES (NULL, '".
         mysqli_real_escape_string($person->name)."', '".
         mysqli_real_escape_string($person->surname)."', '".
         mysqli_real_escape_string($person->email)."', '".
         mysqli_real_escape_string($person->phone)."', '".
         mysqli_real_escape_string($person->fax)."', '".
         mysqli_real_escape_string($person->mobile)."', '".
         mysqli_real_escape_string($person->company)."', '".
         mysqli_real_escape_string($person->job_title)."', '".
         mysqli_real_escape_string($person->postal_addr)."', '".
         mysqli_real_escape_string($person->postal_code)."', '".
         mysqli_real_escape_string($person->physical_addr)."', '".
         mysqli_real_escape_string($person->physical_code)."', '".
         mysqli_real_escape_string($person->email2)."', '".
         mysqli_real_escape_string($person->email3)."', '".
         mysqli_real_escape_string($person->keywords)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $sql="select id from person where email='".mysqli_real_escape_string($person->email)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs, MYSQL_ASSOC) or die('No result returned')) {
      $person->id=$row['id'];
    }
  }

  public function updatePerson($person) {
    if ($person->email=='') die('Email address required');
    if ($person->id=='') die('Id required');
    $sql="update person set".
         " name='".mysqli_real_escape_string($person->name)."', ".
         " surname='".mysqli_real_escape_string($person->surname)."', ".
         " email='".mysqli_real_escape_string($person->email)."', ".
         " phone='".mysqli_real_escape_string($person->phone)."', ".
         " fax='".mysqli_real_escape_string($person->fax)."', ".
         " mobile='".mysqli_real_escape_string($person->mobile)."', ".
         " company='".mysqli_real_escape_string($person->company)."', ".
         " job_title='".mysqli_real_escape_string($person->job_title)."', ".
         " postal_addr='".mysqli_real_escape_string($person->postal_addr)."', ".
         " postal_code='".mysqli_real_escape_string($person->postal_code)."', ".
         " physical_addr='".mysqli_real_escape_string($person->physical_addr)."', ".
         " physical_code='".mysqli_real_escape_string($person->physical_code)."', ".
         " email2='".mysqli_real_escape_string($person->email2)."', ".
         " email3='".mysqli_real_escape_string($person->email3)."', ".
         " keywords='".mysqli_real_escape_string($person->keywords)."' where id='".mysqli_real_escape_string($person->id)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  // Creates a folder and returns the id of the newly created folder
  public function createFolder($parentId, $parentRoot, $folderName) {
    $sql="INSERT INTO tree (TreeSeq, TreeCode, ParentRef, TreeDesc, RootRef)".
         " VALUES (NULL, 'TC', '".mysqli_real_escape_string($parentId)."', '".
         mysqli_escape_string($folderName)."', '".
         mysqli_escape_string($parentRoot)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    
    $sql="select LAST_INSERT_ID()";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $row = mysqli_fetch_row($rs) or die('No result returned');
    $newId=$row[0];
    $sql="UPDATE tree SET TreeCode = 'TC".$newId."' WHERE TreeSeq=".$newId;
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    return 'TC'.$newId;
  }
  
  public function createRootFolder($userId, $description) {
    $sql="INSERT INTO tree (TreeSeq, TreeCode, ParentRef, TreeDesc, RootRef)".
         " VALUES (NULL, '".
         mysqli_real_escape_string('U'.$userId)."', NULL, '".
         mysqli_escape_string($description)."', '".
         mysqli_escape_string('U'.$userId)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
 
  public function removeFolder($folderId) {
    $this->unlinkAll($folderId);
    // now remove the folder
    $sql="DELETE FROM tree WHERE TreeCode='".mysqli_real_escape_string($folderId)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function unlinkAll($folderId) {
    // first unlink everybody
    $sql="DELETE FROM lnk_tree_person WHERE tree_ref='".mysqli_real_escape_string($folderId)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function unlinkPersonFromFolder($personId, $folderId) {
    $sql="DELETE FROM lnk_tree_person WHERE person_ref='".mysqli_real_escape_string($personId)."' AND tree_ref='".mysqli_real_escape_string($folderId).
         "' and type='P'";
    mysqli_query ($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function unlinkGroupFromFolder($groupSeq, $folderId) {
    $sql="DELETE FROM lnk_tree_person WHERE person_ref='".mysqli_real_escape_string($groupSeq)."' AND tree_ref='".mysqli_real_escape_string($folderId).
         "' and (type='G' or type='R')";
    mysqli_query ($sql) or die(mysqli_error()." : ".$sql);
  }
 
  // Returns true if the person is already in the folder.
  public function isPersonInFolder($personId, $folderId) {
    $result=false;
    $sql="select * from lnk_tree_person where type='P' and person_ref='".mysqli_real_escape_string($personId)."' and tree_ref='".
          mysqli_real_escape_string($folderId)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs)) {
      $result=true;
    }
    return $result;
  }
  
  // Returns true if the person is already in the folder.
  public function isObjectInFolder($objectId, $folderId, $type) {
    $result=false;
    $sql="select * from lnk_tree_person where person_ref='".mysqli_real_escape_string($objectId)."' and tree_ref='".
          mysqli_real_escape_string($folderId)."' and type='".mysqli_real_escape_string($type)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row=mysqli_fetch_array($rs)) {
      $result=true;
    }
    return $result;
  }

  // Returns true if person linked OK
  public function linkPersonToFolder($personId, $folderId) {
    return $this->linkObjectToFolder($personId, $folderId, 'P');
  }
  
  // Links a group to a folder - If the group is already linked just update the $type if necessary by unlinking and relinking
  public function linkGroupToFolder($groupId, $folderId, $type) {
    if ($this->isObjectInFolder($groupId, $folderId, $type)) return false;
    $this->unlinkGroupFromFolder($groupId, $folderId);
    return $this->linkObjectToFolder($groupId, $folderId, $type);
  }
  
  // Returns true if object linked OK
  public function linkObjectToFolder($objectId, $folderId, $type) {
    if ($folderId=='') return false;
    if ($this->isObjectInFolder($objectId, $folderId, $type)) return false;
    $sql="insert into lnk_tree_person (lnk_tree_person_id, person_ref, tree_ref, type) VALUES (NULL, '".
         mysqli_real_escape_string($objectId)."', '".
         mysqli_real_escape_string($folderId)."', '".
         mysqli_real_escape_string($type)."')";
    mysqli_query ($sql) or die(mysqli_error()." : ".$sql);
    return true;
  }
  
  // Returns true if person linked OK
  public function linkPersonEmailToFolder($email, $folderId) {
    if ($folderId=='') return false;
    $person=$this->getPerson($email);
    if (!$person) return false;
    return $this->linkPersonToFolder($person->id, $folderId);
  }
  
  // Returns true if the folder has child folders.
  public function doesFolderHaveChildren($folderId) {
    $result=false;
    $sql="SELECT * FROM tree WHERE ParentRef = '".mysqli_real_escape_string($folderId)."'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs)) $result=true;
    return $result;
  }

  public function getFolder($folderId) {
    $result=null;
    $sql="SELECT * FROM tree WHERE TreeCode = '".mysqli_real_escape_string($folderId)."'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs)) {
      $result=new Folder();
      $result->loadFromDB($row);
    }
    return $result;
  }

  public function getFolderFromName($folderName, $parentId) {
    $result=null;
    $sql="SELECT * FROM tree WHERE TreeDesc='".mysqli_real_escape_string($folderName)."' and ParentRef='".mysqli_real_escape_string($parentId)."'";
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_array($rs)) {
      $result=new Folder();
      $result->loadFromDB($row);
    }
    return $result;
  }

  public function getChildFolders($folderId) {
    $result=array();
		$sql="SELECT * FROM tree WHERE ParentRef='".mysqli_real_escape_string($folderId)."' ORDER BY TreeDesc";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $folder=new Folder();
      $folder->loadFromDB($row);
      $result[]=$folder;
    }
    return $result;
  }

  public function getFoldersAsTreeList($userOrg, $userCode) {
    $result=array();
    if ($userOrg!='') {
      $parent=$this->getFolder($userOrg);
      $result[]=$parent;
      $result=array_merge($result, $this->getChildFoldersAsTreeList($userOrg, $parent->desc));
    }
    $parent=$this->getFolder($userCode);
    if ($parent!=null) {
      $result[]=$parent;
      $result=array_merge($result, $this->getChildFoldersAsTreeList($userCode, $parent->desc));
    }
    return $result;
  }

  public function getChildFoldersAsTreeList($folderId, $parentPrefix) {
    $result=array();
    $folders=$this->getChildFolders($folderId);
    foreach ($folders as $folder) {
      $folder->desc=$parentPrefix." - ".$folder->desc;
      $result[]=$folder;
      $result=array_merge($result, $this->getChildFoldersAsTreeList($folder->id, $folder->desc));
    }
    return $result;
  }
  
  public function getFoldersForPerson($personId, $userOrg, $userCode) {
    $result=array();
    $userOrgSql="";
    if ($userOrg!='') $userOrgSql=" or RootRef='".mysqli_real_escape_string($userOrg)."'";
		$sql="SELECT t.* FROM lnk_tree_person l, tree t where l.type='P' and l.person_ref='".mysqli_real_escape_string($personId).
         "' and l.tree_ref=t.TreeCode and (RootRef='".mysqli_real_escape_string($userCode)."'".$userOrgSql.")";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $folder=new Folder();
      $folder->loadFromDB($row);
      $result[]=$folder;
    }
    return $result;
  }

  public function getNrOfPeopleInFolder($folderId) {
    return $this->getNrOfObjectsInFolder($folderId, 'P');
  }
  
  public function getNrOfGroupsInFolder($folderId) {
    return $this->getNrOfObjectsInFolder($folderId, 'G')+$this->getNrOfObjectsInFolder($folderId, 'R');
  }
  
  public function getNrOfObjectsInFolder($folderId, $type=null) {
    $typesql="";
    if (isset($type)) $typesql="type='".$type."' and";
    $result=0;
    $sql="SELECT COUNT(DISTINCT person_ref) FROM lnk_tree_person WHERE ".$typesql." tree_ref='".mysqli_real_escape_string($folderId)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row1=mysqli_fetch_array($rs)) {
      $result=$row1[0];
    }
    return $result;
  }
  
  public function getPeopleInFolder($folderId, $sort, $counter=null, $numRows=null) {
    $result=array();
    $order=" ORDER BY name, surname"; 
    if ($sort) $order=" ORDER BY surname, name";
    $limit="";
    if (isset($counter) && isset($numRows)) {
      $limit=" LIMIT ".mysqli_real_escape_string($counter).", ".mysqli_real_escape_string($numRows);
    }
    $sql="SELECT p.* FROM person p, lnk_tree_person lt WHERE lt.type='P' and p.id=lt.person_ref AND lt.tree_ref='".
      mysqli_real_escape_string($folderId)."' ".$order.$limit;
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $person=new Person();
      $person->loadFromDB($row);
      $result[]=$person;
    }
    return $result;
  }
  
  public function getGroupsInFolder($folderId, $sort, $counter=null, $numRows=null) {
    $result=array();
    $order=" ORDER BY TreeDesc"; 
    $limit="";
    if (isset($counter) && isset($numRows)) {
      $limit=" LIMIT ".mysqli_real_escape_string($counter).", ".mysqli_real_escape_string($numRows);
    }
    $sql="select t.*, lt.type from tree t, lnk_tree_person lt where (lt.type='G' or lt.type='R') and t.TreeSeq=lt.person_ref and lt.tree_ref='".
      mysqli_real_escape_string($folderId)."' ".$order.$limit;
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $folder=new Folder();
      $folder->loadFromDB($row);
      $result[]=$folder;
    }
    return $result;
  }

  public function getNrOfPeopleInSearch($searchCriteria) {
    $result=0;
    $sql="SELECT COUNT(DISTINCT person.id) FROM person WHERE MATCH(name, surname, email)".
      " AGAINST ('".mysqli_real_escape_string($searchCriteria)."') OR email LIKE '%".mysqli_real_escape_string($searchCriteria)."%'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row1=mysqli_fetch_array($rs)) {
      $result=$row1[0];
    }
    return $result;
  }

  public function getPeopleInSearch($searchCriteria, $sort, $counter, $numRows) {
    $result=array();
    $order=" ORDER BY name, surname"; 
    if ($sort) $order=" ORDER BY surname, name";
    $sql = "SELECT * FROM person WHERE MATCH(name, surname, email) AGAINST ('".mysqli_real_escape_string($searchCriteria)."')".
      " OR email LIKE '%".mysqli_real_escape_string($searchCriteria)."%' ".$order." LIMIT ".mysqli_real_escape_string($counter).", ".
      mysqli_real_escape_string($numRows);
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $person=new Person();
      $person->loadFromDB($row);
      $result[]=$person;
    }
    return $result;
  }

  public function getEmailQueueSize($status=null) {
    $statussql="";
    if (isset($status)) $statussql=" where status='".mysqli_real_escape_string($status)."'";
    $sql="Select count(*) from email_queue".$statussql;
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_row($rs) or die('No result returned'));
    return $row[0];
  }

  public function getEmailQueueSizeForBatch($batchId, $status=null) {
    $statussql="";
    if (isset($status)) $statussql=" and status='".mysqli_real_escape_string($status)."'";
    $sql="Select count(*) from email_queue where batchid='".mysqli_real_escape_string($batchId)."'".$statussql;
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_row($rs) or die('No result returned'));
    return $row[0];
  }

  public function getNrUnexpandedBatchesForOrganization($orgname) {
    $sql="SELECT count(*) FROM batch_queue b, sendemail s, users u where b.status='Ready' and b.batchId=s.id and s.fromUserId=u.id ".
         "and u.organization='".mysqli_real_escape_string($orgname)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_row($rs) or die('No result returned'));
    return $row[0];
  }
 
  // returns true on success else false
  public function updateRecipientStatus($orderid, $status, $reason=null) {
    $reasonsql="";
    if (isset($reason)) $reasonsql=", reason='".mysqli_real_escape_string(substr($reason,0,255))."'";
    $sql="update email_queue set status='".mysqli_real_escape_string($status)."'".$reasonsql.", processed=now() where orderid='".mysqli_real_escape_string($orderid)."'";
    return mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function addRecipientToEmailQueue($batchId, $emailaddr, $type) {
    $sql="INSERT INTO email_queue (orderid, batchid, to_email, type) VALUES (NULL, '".
         mysqli_real_escape_string($batchId)."', '".
         mysqli_real_escape_string($emailaddr)."', '".
         mysqli_real_escape_string($type)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function updateEmailRecipients($batchId, $noOfRcpt) {
    $sql="update sendemail set noOfRcpt='".mysqli_real_escape_string($noOfRcpt)."' where id='".mysqli_real_escape_string($batchId)."'";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function updateEmailBatchQueueStatus($batchId, $status, $reason=null) {
    $reasonsql="";
    if (isset($reason)) $reasonsql=", reason='".mysqli_real_escape_string(substr($reason,0,255))."'";
    $sql="update batch_queue set status='".mysqli_real_escape_string($status)."'".$reasonsql.", processed=now() where batchId='".mysqli_real_escape_string($batchId)."'";
    return mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

  public function addEmailBatchToQueue($batchId) {
    $sql="INSERT INTO batch_queue (batchid) VALUES ('".mysqli_real_escape_string($batchId)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
  
  public function addEmail($email) {
    $sql="INSERT INTO sendemail (id, toIdList, fromUserId, fromAliasId, dateSent, noOfRcpt, subject, body, attach1, attach2, attach3) VALUES (NULL, '".
         mysqli_real_escape_string($email->toIdList)."', '".
         mysqli_real_escape_string($email->fromUserId)."', '".
         mysqli_real_escape_string($email->fromAliasId)."', '".
         mysqli_real_escape_string($email->dateSent)."', '".
         mysqli_real_escape_string($email->noOfRcpt)."', '".
         mysqli_real_escape_string($email->subject)."', '".
         mysqli_real_escape_string($email->body)."', '".
         mysqli_real_escape_string($email->attach1)."', '".
         mysqli_real_escape_string($email->attach2)."', '".
         mysqli_real_escape_string($email->attach3)."')";
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $sql="select LAST_INSERT_ID()";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if ($row = mysqli_fetch_row($rs) or die('No result returned')) {
      $email->id=$row[0];
    }
  }

  public function getEmailForUser($userId, $date) {
    $result=array();
    $sql="SELECT * FROM sendemail where fromUserId='".mysqli_real_escape_string($userId)."' and dateSent like '".mysqli_real_escape_string($date.'%').
      "' order by dateSent, id";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $email=new Email();
      $email->loadFromDB($row);
      $result[]=$email;
    }
    return $result;
  }

  public function getEmail($batchId) {
    $result=null;
    $sql="SELECT * FROM sendemail where id='".mysqli_real_escape_string($batchId)."'";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $email=new Email();
      $email->loadFromDB($row);
			$email->body = $this->downloadImageSrc ($email);
      $result=$email;
    }
    return $result;
  }

	// search for all the IMG tag, check if the image is on the web, then 
	// download itin the auto folder.  Replace the image name, with the local
 	// name.
	private function downloadImageSrc ($email) {
		$tags = new htmlTags ($email->body);
    $tags->setTagName ("img");

    foreach ($tags as $key => $value) {
      $src = $tags->getArrtib("src");
      if (!strncasecmp($src, 'http://', 7)) {
				$attPath = getUserImageDiskPath($email->fromUserId)."auto/";
				if (!file_exists($attPath)) {
					mkdir($attPath,0777);
				}
				$fin = fopen ($src, "r");
				$imgName = basename($src);
				$fout = fopen ($attPath.$imgName, "a");

				if (!$fin || !$fout) continue;

				while (!feof($fin)) {
				  fwrite($fout, fread($fin, 8192));
				}
				fclose ($fout);
				fclose ($fin);
				chmod ($attPath.$imgName, 0666);
				$tags->setArrtib("src", getUserImageRelPath($email->fromUserId)."auto/".$imgName);
			}
      if (!$tags->getArrtib("width") && !$tags->getArrtib("height")) {
				$size = getimagesize ($attPath.$imgName);
				$tags->setArrtib("width", $size[0]);
				$tags->setArrtib("height",$size[1]);
      }
		}
    return ($tags->getHTML());
	}

  
  public function getMostRecentMail($userId) {
    $result=array();
    $sql="SELECT * FROM sendemail where fromUserId='".mysqli_real_escape_string($userId)."' order by dateSent desc, id desc";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $email=new Email();
      $email->loadFromDB($row);
      $isInList=false;
      foreach ($result as $inlist) {
        if ($email->checkIfMostRecentDuplicate($inlist)) {
          $isInList=true;
          break;
        }
      }
      if (!$isInList) {
        $result[]=$email;
        if (count($result)>=MOST_RECENT_LIST_LENGTH) break;
      }
    }
    return $result;
  }

  // Update the log file.
  function updateLog ($uid, $ip=0) {
    $first_time = true;
    $sql="SELECT * FROM dblog WHERE user_ref=".mysqli_real_escape_string($uid);
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $total_refreshes=0;
    if ($row = mysqli_fetch_array($rs)) {
      $first_time = false;
      $total_refreshes=$row["total_refreshes"]+1;
    }
  
    if ($first_time) {
      $sql="INSERT INTO dblog (user_ref, date_of_first_use, date_of_last_use, last_ip, first_ip) VALUES ('".
        mysqli_real_escape_string($uid)."','".date("Y-m-d")."','".date("Y-m-d")."', '".mysqli_real_escape_string($ip)."', '".
        mysqli_real_escape_string($ip)."')";
    } else {
      $sql="UPDATE dblog SET date_of_last_use='".date("Y-m-d")."', last_ip='".mysqli_real_escape_string($ip)."', total_refreshes='".$total_refreshes.
           "' WHERE user_ref='".mysqli_real_escape_string($uid)."'";
    }
    $rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }
	
  // Echo's the values of a folder (used for exporting)
  function extractData($folderId) {
		$hSql = "SHOW COLUMNS FROM `person`";
		$hResult = mysqli_query($hSql) or die(mysqli_error()." : ".$hSql);
		$headingArr = array();
		$hRow = mysqli_fetch_array($hResult);// skip first row
		while ($hRow = mysqli_fetch_array($hResult)) {
			$displayName = $hRow['Field'];
			array_push ($headingArr, $displayName);
		}
		$valuesArr = array();
		$sql = "SELECT p.* FROM person p, lnk_tree_person lt WHERE lt.type='P' and p.id = lt.person_ref AND lt.tree_ref='".mysqli_real_escape_string($folderId)."'";
		$result = mysqli_query($sql) or die(mysqli_error()." : ".$sql);

		while ($row = mysqli_fetch_row($result)){
			$row = array_splice ( $row, 1 );
			$field = '"'.implode('","',$row).'"';
			array_push($valuesArr,$field);
		}

		$heading = '"'.implode('","', $headingArr).'"';
		echo $heading."\n";

		foreach ($valuesArr as $key => $value){
			echo $valuesArr[$key];
			echo "\n";
		}
	}
	
  // used for tinyMCE to get a list of url's for images that can be included in your email.
  function writeEmailImageList() {
		$sql = "SELECT * FROM email_images";
		$rs = mysqli_query($sql) or die(mysqli_error()." : ".$sql);
		$c = 0;
		while ($row = mysqli_fetch_array($rs)) {
			if ($c > 0)
			{echo ',';}
			echo '["'.$row['img_title'].'", "email_images/'.$row['img_url'].'"]';
			$c++;
		}
	}

  function addImage($title, $url) {
    $sql="select * from email_images where img_url='".mysqli_real_escape_string($url)."'";
		$rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    if (mysqli_fetch_row($rs)) { // entry exists
      $sql="update email_images set img_title='".mysqli_real_escape_string($title)."' where img_url='".mysqli_real_escape_string($url)."'";
    } else {
      $sql="INSERT INTO email_images (img_title, img_url) VALUES ('".
           mysqli_real_escape_string($title)."', '".
           mysqli_real_escape_string($url)."')";
    }
    mysqli_query($sql) or die(mysqli_error()." : ".$sql);
  }

}

?>
