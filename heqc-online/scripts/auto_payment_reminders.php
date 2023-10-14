<?php
define ('CONFIG', 'CHEDEVTEST');

        require ("/var/www/html/common/_systems/heqc-online.php");
        $dbConnect = new dbConnect();
        $page = new HEQConline (1);
        $page->sendPaymentReminders();
?>
