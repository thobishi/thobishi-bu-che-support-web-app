<?php 
      
require_once("contacts/globals.php");

class SendEmail {

  private $cdb, $count, $queueing, $addressList, $groupList, $mail;
  private $localErrorMsg;
  private $unsubscribeFound;
  private $disclaimerFound;

  public function __construct() {
    $this->cdb=new ContactsDB();
    $this->localErrorMessage='';
  }

  public function setQueueing() {
    $this->queueing=true;
  }

  public function getErrorReason() {
    if ($this->localErrorMessage!='') return $this->localErrorMessage;
    return $this->mail->ErrorInfo;
  }
  
  // Send the mail $email to the person with the address $toEmail from user $fromUser
  // The type can be either 'P' or 'G' to indicate if this user was an individual or
  // exploded from a group.
  public function sendOneMail($toEmail, $email, $fromUser, $type) {
    $result=false;
    $this->localErrorMessage='';
    $this->mail = new phpmailer();
    $this->mail->SetLanguage("en", "../phpmailer/language/");
    if (MAIL_SEND_MODE=='SMTP') $this->mail->IsSMTP();
    else                        $this->mail->IsMail();
    $this->mail->IsHTML(true);
    $this->mail->Host    =SMTPSERVER;
    $this->mail->Sender  =$fromUser->getEmailFromAddr();
    $this->mail->From    =$fromUser->getEmailFromAddr();
    $this->mail->FromName=$fromUser->getEmailFromName();
    $this->mail->Subject =$email->subject;
    $this->mail->Body    =$email->body;
    $personalEmail=$email->body;
   
    $person=$this->cdb->getPerson($toEmail);
    if ($person) {
    $this->unsubscribeFound=false;
    $this->disclaimerFound=false;
		

// following was in the foreach loop
//        $personalEmail=$this->replaceField($entry["tag"], $entry["default"], $entry["replaceWith"], $personalEmail, $person, $email->id, $type);

			$personalEmail = $this->replaceField($personalEmail, $person, $email->id, $type);

			if (!$this->unsubscribeFound || !$this->disclaimerFound) {
				$personalEmail.="<br><br>\n";
			}
		
/*	
			if (!$this->unsubscribeFound) {
        $personalEmail.='<font face="Arial" size="2" color="#000000">'.FieldList::generateDefaultUnsubscribeURL($person, $email->id, $type)."</font><br>\n";
      }

			if (!$this->disclaimerFound) {
        $personalEmail.='<font face="Arial" size="2" color="#000000">'.FieldList::generateDefaultDisclaimerURL($person, $email->id, $type)."</font><br>\n";
      }
*/
      $this->mail->Body=$personalEmail."<br>";
      $this->mail->AddAddress($person->email, $person->name." ".$person->surname);
      $result=$this->sendMail($email, false);
    } else {
      $this->localErrorMessage='Person record not found';
    }
    return $result;
  }

	// run via all the input tags and replace them
  private function replaceField($email_body, $person, $batchId, $type) {

		$fieldLookup = array();
		foreach (FieldList::$fields as $key => $entry) {  // make an easy to lookup array
		  $fieldLookup[$entry["tag"]] = $key;
    }

		$tags = new htmlTags ($email_body);

		$tags->setTagName ("input");

		foreach ($tags as $key => $value) {
			if ($tags->getArrtib("type") == "contacts") {
				$defaultText = ($tags->getArrtib("value")>'')?($tags->getArrtib("value")):(FieldList::$fields[$fieldLookup[$tags->getArrtib("name")]]['default']);
				$method = FieldList::$fields[$fieldLookup[$tags->getArrtib("name")]]['replaceWith'];
				$replaceText = FieldList::$method($person, $batchId, $type);
				switch ($tags->getArrtib("name")) {
					case 'unsubscribe':
						$replaceWith=FieldList::generateUnsubscribeURL($replaceWith, $person, $batchId, $type);
						$this->unsubscribeFound=true;
						break;
					case 'updateinfo':
					 	$replaceWith=FieldList::generateUpdateInfoURL($replaceWith, $person, $batchId, $type);
						break;
					case 'disclaimer':
						$replaceWith=FieldList::generateDisclaimerURL($replaceWith, $person, $batchId, $type);
						$this->disclaimerFound=true;
						break;
				}
				
				$tags->replaceTag ($replaceText);
			}
		}

		
		return ($tags->getHTML());
  }

	
/*
  // Search for $tag in $search_str and replace it with $replaceWith (if empty then the onscreen default else the default default)
  private function replaceField($tag, $default, $method, $search_str, $person, $batchId, $type) {
    $search_string=$this->mail->Body;
    $fields=array();
    // get the default values
    $pattern="/{".$tag.",([^}]*)}|{".$tag."}/";
    preg_match_all($pattern, $search_string, $fields);
   
    // The format of the onscreen field is {field[0][,field[1]]}
    // if the default values from the screen is empty use the default default value
    foreach ($fields[1] as &$field) 
      if ($field=='') $field=$default;
    unset($field);

    if ($tag=='unsubscribe') {
      $unsubscribeFound=count($fields[0])>0;
    }

    // replace the { , } with the actual value
    $ctr=0;
    foreach ($fields[0] as $field) {
      $field=addcslashes($field,'()');
      $replaceWith=FieldList::$method($person, $batchId, $type);
      if ($replaceWith=='') $replaceWith=$fields[1][$ctr];
      if ($tag=='unsubscribe') $replaceWith=FieldList::generateUnsubscribeURL($replaceWith, $person, $batchId, $type);
      else if ($tag=='updateinfo') $replaceWith=FieldList::generateUpdateInfoURL($replaceWith, $person, $batchId, $type);
      else if ($tag=='disclaimer') $replaceWith=FieldList::generateDisclaimerURL($replaceWith, $person, $batchId, $type);
      $search_str=preg_replace("/".$field."/", $replaceWith, $search_str);
      $ctr++;
    }
    return $search_str;
  }
*/

	
  // Send the mail $email to the recipients indicated in $email->toIdList from the user $fromUser
  // Queue if necessary (to allow individual sending later)
  public function doEmail($email, $fromUser, $queueing=true) {
    $this->queueing=$queueing;
    $this->mail = new phpmailer();
    $this->mail->SetLanguage("en", "../phpmailer/language/");
    if (MAIL_SEND_MODE=='SMTP') $this->mail->IsSMTP();
    else                        $this->mail->IsMail();
    $this->mail->IsHTML(true);
    $this->mail->Host    =SMTPSERVER;
    $this->mail->Sender  =$fromUser->getEmailFromAddr();
    $this->mail->From    =$fromUser->getEmailFromAddr();
    $this->mail->FromName=$fromUser->getEmailFromName();
    $this->mail->Subject =$email->subject;
		$this->mail->Body    =$email->body;
	    
    $this->count=0;
    $this->cdb->addEmail($email);

    if (!$this->queueing) { // not queueing so explode the list and send the mail
      $this->explodeList($email);
      if (!$this->sendMail($email, true)) {
        echo "ERROR sending email : ".$this->mail->ErrorInfo;
      }
    } else { // queue so upload the attachments and queue the batch for later sending
      $this->uploadAttachments($email);
      $this->cdb->addEmailBatchToQueue($email->id);
    }
  }

	// expand groups and recursive groups to the actual people in these lists
  // and either queue in the db or add as a recipient to the email to send.
  // Also check for groups that includes other groups and explode them
  public function explodeList($email) {
    $msgTo=explode(",", $email->toIdList);
    $this->addressList=array();
    $this->groupList=array();
    foreach ($msgTo as $val) {
      if ($val > "") {
        $to = explode(":", $val);
        if ($to[0]=='P') {
          $person=$this->cdb->getPersonFromId($to[1]);
          if ($person) $this->addAddress($person, $email->id, 'P');
        } else if ($to[0]=='G') { $this->addPeopleFromGroup($to[1], $email->id, false, 'G');
        } else if ($to[0]=='R') { $this->addPeopleFromGroup($to[1], $email->id, true, 'G');
        }
      }
    }
      
    if ($this->count>0) {
      $email->noOfRcpt=$this->count;
      $this->cdb->updateEmailRecipients($email->id, $email->noOfRcpt);
    }
    return $this->count;
  }
 
  // Returns a list of groups from the toIdList to which the personId is linked.
  // Todo : expand to also work for included groups
  public function inWhichGroupsIsPerson($personId, $toIdList) {
    $this->groupList=array();
    $msgTo=explode(",", $toIdList);
    $groups=array();
    foreach ($msgTo as $val) {
      if ($val > "") {
        $to=explode(":", $val);
        if ($to[0]=='G') $this->isPersonInGroup($groups, $personId, $to[1]);
        else if ($to[0]=='R') $this->isPersonInGroup($groups, $personId, $to[1], true);
      }
    }
    return $groups;
  }

  private function isPersonInGroup(&$groups, $personId, $folderId, $recurse=false) {
    if (!isset($this->groupList[$folderId])) { // make sure a group only gets checked once
      $this->groupList[$folderId]=true;
      $people=$this->cdb->getPeopleInFolder($folderId, false);
      foreach ($people as $person) {
        if ($person->id==$personId) {
          $groups[]=$folderId;
          break;
        }
      }
      if ($recurse) {
        $folders=$this->cdb->getChildFolders($folderId); 
        foreach ($folders as $folder) 
          $this->isPersonInGroup($groups, $personId, $folder->id, true);
      }
    }
  }
  
  private function addPeopleFromGroup($folderId, $emailId, $recurse, $type) {
    if (!isset($this->groupList[$folderId])) { // make sure a group only gets exploded once
      $this->groupList[$folderId]=true;
  
      // first send to all the people in the list
      $people=$this->cdb->getPeopleInFolder($folderId, false);
      foreach ($people as $person) $this->addAddress($person, $emailId, $type);

      // now explode any groups in the list
      $groups=$this->cdb->getGroupsInFolder($folderId, false);
      foreach ($groups as $group) {
        if ($group->type=='G')      $this->addPeopleFromGroup($group->id, $emailId, false, 'P');
        else if ($group->type=='R') $this->addPeopleFromGroup($group->id, $emailId, true, 'P');
      }
    
      // now recurse if this group was indicated as recursive
      if ($recurse) {
        $folders=$this->cdb->getChildFolders($folderId); 
        foreach ($folders as $folder) 
          $this->addPeopleFromGroup($folder->id, $emailId, true, $type);
      }
    }
  }

  private function addAddress($person, $batchId, $type) {
    if (!isset($this->addressList[$person->email])) {
      $this->addressList[$person->email]=true;
      if ($this->queueing) {
        $this->cdb->addRecipientToEmailQueue($batchId, $person->email, $type);
       } else {
        $this->mail->AddAddress($person->email, $person->name." ".$person->surname);
      }
      $this->count++;
    }
  }

  private function uploadAttachments($email) {
    if ($email->attach1!='') $this->uploadAttachment($email->id, $email->file1);
    if ($email->attach2!='') $this->uploadAttachment($email->id, $email->file2);
    if ($email->attach3!='') $this->uploadAttachment($email->id, $email->file3);
  }
  
  private function uploadAttachment($batchId, $file) {
    $target=EMAIL_QUEUE_FOLDER.$batchId;
    if (!file_exists($target)) mkdir($target, 0777);
    $target.="/".$file['name'];
    if (file_exists($target)) unlink($target);
    if (!rename($file['tmp_name'], $target)) die ('could not copy file '.$file['tmp_name'].' to '.$target);
  }

  // sends mail, returns true on success else returns false
  private function sendMail($email, $fromTemp) {
    // adding attachments
    if ($fromTemp) {
      if ($email->attach1!='') $this->mail->AddAttachment($email->file1['tmp_name'], $email->attach1);
      if ($email->attach2!='') $this->mail->AddAttachment($email->file2['tmp_name'], $email->attach2);
      if ($email->attach3!='') $this->mail->AddAttachment($email->file3['tmp_name'], $email->attach3);
    } else {
      if ($email->attach1!='') $this->mail->AddAttachment(EMAIL_QUEUE_ABS_FOLDER.$email->id."/".$email->attach1, $email->attach1);
      if ($email->attach2!='') $this->mail->AddAttachment(EMAIL_QUEUE_ABS_FOLDER.$email->id."/".$email->attach2, $email->attach2);
      if ($email->attach3!='') $this->mail->AddAttachment(EMAIL_QUEUE_ABS_FOLDER.$email->id."/".$email->attach3, $email->attach3);
    }
    if ($this->mail->IsError()) return false;

		// Set-up image type
    $attachment_list = array ("jpg"=>"image/jpeg", "gif"=>"image/gif", "png"=>"image/png");

    // replace image URLs that is on the web with local URRLs
    $tags = new htmlTags ($this->mail->Body);
    $tags->setTagName ("img");

    foreach ($tags as $key => $value) {
			// check if we have local file that we need to embedd
			if (!strncmp ($tags->getArrtib('src'), getUserImageRelPath($email->fromUserId), strlen (getUserImageRelPath($email->fromUserId)) )) {

		    // now embed them in the email
				$src = $tags->getArrtib("src");
				$imgPath = str_replace (getUserImageRelPath($email->fromUserId),  getUserImageDiskPath($email->fromUserId), $src);
				$path_parts = pathinfo($src);
				$imgName = basename($src);
				$cidName = strtr ($imgName, '.', '_');
				$tags->setArrtib('src', 'cid:'.$cidName);

	      $att_type = "application/octet-stream";
				if (in_array($path_parts['extension'], array_keys($attachment_list))) {
	        $att_type = $attachment_list[$path_parts['extension']];
				}
				$this->mail->AddEmbeddedImage($imgPath, $cidName, $imgName, "base64", $att_type);
			}
    }

		// $tags->stripJavascript ();
    $this->mail->Body = $tags->getHTML();
		unset($tags);
      
    if ($this->mail->IsError()) return false;
  
    // change the ascii x39 back to normal "'"  
    $search_string=$this->mail->Body;
    $pattern="|&#39;|U";
    $replaceWith="'";
    $this->mail->Body = preg_replace($pattern, $replaceWith, $search_string);
    
    // create aternative plain text
    $h2t =& new html2text($this->mail->Body);
    $this->mail->AltBody =  $h2t->get_text();
    
    return $this->mail->Send();
  }

}
?>
