<?php
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once 'employeeChange.class.php';
global $db,$cryptoGraph;
$db=new database();
$cryptoGraph=new cryptography();
$employeeChangeObject = new employeeChange();

?>