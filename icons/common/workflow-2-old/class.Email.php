<?php

class Email {

	protected static $_instance = null;

	private $__db = null;

	public $active_processes_id = 0;

	public $currentUserID = 0;

	public $template = '';

	public $flowID = 0;

	public static function getInstance(DB $db, AuditLog $AuditLog) {
		if (empty(self::$_instance)) {
			self::$_instance = new self($db, $AuditLog);
		}
		return self::$_instance;
	}

/**
 * @throws Exception
 */
	protected function __construct(DB $db, AuditLog $AuditLog) {
		if (!empty($this->_instance)) {
			throw new Exception('Thou shalt not construct that which is unconstructable!');
		}

		$this->__db = $db;
		$this->__AuditLog = $AuditLog;
	}

	protected function __clone() {
		//Me not like clones! Me smash clones!
	}

	public function misMailByName($email, $subject, $message, $cc="", $ownFromAdr=true, $filelist="", $isHTML=false){
		$mail = new PHPMailer();

//note that if you have added a CC and are testing, you will not see that it is being cc'd
		if (defined('WRK_ALT_EMAIL')) {
			$email = WRK_ALT_EMAIL;
			$cc = '';
		}

	  // changed from address to persons own address.

		$mail->From = $this->__db->getDBsettingsValue("server_from_address");
		$mail->FromName = $this->__db->getDBsettingsValue("server_from_name");

		if ($ownFromAdr) {
			$FromReplyTo = $this->__db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "email");
			$FromReplyToName = $this->__db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "surname") .", ". $this->__db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "name");
		}
		if (isset($FromReplyTo) && $FromReplyTo != "") {
			$mail->AddReplyTo ($FromReplyTo, $FromReplyToName);
		}

		$signature = $this->__db->getDBsettingsValue("email_che_signature");

		if ($cc > '') {
			$cc_arr = explode(",",$cc);
			foreach ($cc_arr as $c){
				$mail->AddCC ($c);
			}
		}

		$debugText = "";
		if (Settings::get('debug_mode')) {
			$debugText = "(".Settings::get('flowID')."/".Settings::get('template').") ";
		}
		$mail->Subject = $this->__db->getDBsettingsValue("default_email_subject") . " " . $debugText . $subject;

		// add signature to email
		$message = $message . $signature;

		if (Settings::get('mayMail')) {
			$mail->Host      = SMTP_SERVER;
			$mail->Mailer    = "smtp";
			$mail->WordWrap = 75;

			$htmlMessage = $message;

			if ($isHTML != true) {
				$htmlMessage = "<HTML><HEAD><STYLE>BODY {font-family: Verdana;font-size: 10pt;}</STYLE></HEAD>\n<BODY>\n".str_replace ("\n", "<br />\n", htmlentities  ($message))."\n</BODY>\n</HTML>";
				$isHTML = true;
			}

			$mail->Body = $htmlMessage; 

			$mail->IsSMTP();
			$mail->IsHTML($isHTML);
			$mail->AddAddress($email);

			// add attachments
			if (is_array($filelist)) {
				foreach ($filelist AS $filearr) {
					if (! is_array($filearr) ) {
						$filearr = array($filearr);
					}
					$fileatt = $filearr[0]; // Path to the file
					$fileatt_name = ((isset($filearr[1]))?($filearr[1]):(basename($fileatt))); // Filename that will be used for the file as the attachment
					$fileatt_type = ((isset($filearr[2]))?($filearr[2]):("application/octet-stream")); // File Type

					$mail->AddAttachment($fileatt, $fileatt_name, "base64", $fileatt_type);

					unset($fileatt);
					unset($fileatt_type);
					unset($fileatt_name);
				}
			}

			$title = "EMAIL";
			if (!$mail->Send()) {
				$title = "EMAIL NOT SENT";
			}
			$this->__AuditLog->writeLogInfo(10, $title, "An e-mail with subject ".$subject." was sent to ".$email.". The body of the e-mail was:\n\n".$message);
			$this->__AuditLog->writeAuditTrail(Settings::get('active_processes_id'), $title, "Subject-".$subject." Sent-".$email." Body:\n\n".$message);
		}

		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}	
}