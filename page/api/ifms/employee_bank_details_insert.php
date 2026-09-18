<?php
set_time_limit(0);
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';


$crypto = new cryptography();
$db = new database();

 echo 'Success'; 
?>