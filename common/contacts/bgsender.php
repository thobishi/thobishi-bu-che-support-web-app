<?php

  ini_set ("max_execution_time", 0);
  require_once('contacts/globals.php');
  
  echo "Background sender started\n";
  $cdb=new ContactsDB();

  if (file_exists(LOCK_FILE_NAME)) {
    echo "Lock file found.\n";
    if (checkForActivity($cdb)) {
      echo "Activity on email queues detected.  Terminating.\n";
    } else {
      echo "No activity on email queues detected - sending email to administrator at ".ADMIN_EMAIL.".\n";
      mymail(ADMIN_EMAIL, "bgsender lock file found", 
        "bgsender.php is trying to run but a lock file exists.".
        "\n\nThe contents of lock file is as follows :".
        "\n\n".file_get_contents(LOCK_FILE_NAME),
        "From: ".ADMIN_EMAIL);
    }
    return;
  }
  file_put_contents(LOCK_FILE_NAME, "bgsender started at ".date("Y-m-d H:i:s")); 

  $mails_sent_ok=0;
  $mails_failed=0;
  $mails_processed=0;
  $running=true;
  $firstTime=true;

  while ($running) {
    // explode all batches
    $sql="Select * from batch_queue where status='Ready' order by batchId";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $batchesExploded=0;
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $batchesExploded++;
      $batchId=$row["batchId"];
      $nrOfRecipients=explodeBatch($cdb, $batchId);
      $queueSize=$cdb->getEmailQueueSizeForBatch($batchId, 'Ready');
      if ($nrOfRecipients==$queueSize) {
        $cdb->updateEmailBatchQueueStatus($batchId, "Done", "");
      } else {
        $cdb->updateEmailBatchQueueStatus($batchId, "Failed", $queueSize." emails were queued but it should have been ".$nrOfRecipients);
      }
    }
    if ($batchesExploded==0 && !$firstTime) {
      $running=false;
    }
    // send all emails in batch queue
    processEmailsInQueue($cdb, $mails_sent_ok, $mails_failed, $mails_processed);
    $firstTime=false;
  }
  

  echo "\nBackground sender done";
  echo "\nMails processed : ".$mails_processed;
  echo "\nMails sent : ".$mails_sent_ok;
  echo "\nMails failed : ".$mails_failed;
  echo "\n";
  unlink(LOCK_FILE_NAME);

// ------------------------ functions

  // Returns true if activity on email queues detected 
  function checkForActivity($cdb) {
    echo "Checking for activity...\n";
    $emailQueueSize1=$cdb->getEmailQueueSize();
    $emailReadySize1=$cdb->getEmailQueueSize('Ready');
    for ($i=0; $i<12; $i++) { // check every 5 seconds for activity
      sleep(5);
      $emailQueueSize2=$cdb->getEmailQueueSize();
      $emailReadySize2=$cdb->getEmailQueueSize('Ready');
      if ($emailQueueSize1!=$emailQueueSize2 || $emailReadySize1!=$emailReadySize2) return true;
      echo "No activity detected after ".(($i+1)*5)." seconds...\n";
    }
    return false;
  }

  function explodeBatch($cdb, $batchId) {
    $exploder=new SendEmail();
    $exploder->setQueueing();
    $email=$cdb->getEmail($batchId);
    $result=0;
    $start=time();
    echo "exploding starts at ".$start."\n";
    if ($email) $result=$exploder->explodeList($email);
    echo "exploding took ".(time()-$start)."\n";
    return $result;
  }

  function processEmailsInQueue($cdb, &$mails_sent_ok, &$mails_failed, &$mails_processed) {
    $sql="SELECT * FROM email_queue where status='Ready' order by batchid";
    $rs=mysqli_query($sql) or die(mysqli_error()." : ".$sql);
    $last_loaded_batch=0;
    while ($row=mysqli_fetch_array($rs, MYSQL_ASSOC)) {
      $orderId=$row["orderid"];
      $batchId=$row["batchid"];
      $toEmail=$row["to_email"];
      $recordType=$row["type"];
      $mails_processed++;
      $sent_ok=false;
      $reason="Unknown problem";
   
      if ($batchId!=$last_loaded_batch) {
        $email=$cdb->getEmail($batchId);
        if ($email) {
          echo "\nStarting batch : ".$batchId;
          $last_loaded_batch=$email->id;
        } else {
          echo "\nProblem starting batch : ".$batchId;
          $reason="Batch not found";
        }
      }

      echo "\nProcessing : ".$batchId."::".$toEmail;
      if ($email) {
        $mailer=new SendEmail();
        $sent_ok=$mailer->sendOneMail($toEmail, $email, $cdb->getUserById($email->fromAliasId), $recordType); 
        if (!$sent_ok) $reason=$mailer->getErrorReason();
      }
      if ($sent_ok) {
        $mails_sent_ok++;
        $cdb->updateRecipientStatus($orderId, "Done", ""); 
      } else {
        $mails_failed++;
        $cdb->updateRecipientStatus($orderId, "Failed", $reason); 
      }
    }
  }

?>
