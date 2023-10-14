<?php
	$message = $_POST["reminder_email"];

	if (isset($_POST["id_manager"]) && (count($_POST["id_manager"] > 0))) {

		$manager_arr =  $_POST["id_manager"];
		foreach ($manager_arr as $mgr_id){

			$to = $this->getValueFromTable("users","user_id",$mgr_id,"email");
			$this->misMailByName ($to, "Rate contract progress and performance", $message);

		}

	}
 ?>