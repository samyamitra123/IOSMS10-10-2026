<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once 'ngipf.class.php';
$redirectUrl = $_SERVER['HTTP_REFERER'];
$crypto = new cryptography();
$Ngipf = new NGIPF_API();
global $db,$ngipfStatus;
$db = new database();
$emp_id = 'PE2017020474';
$Ngipf->SyncyNgiPFData($emp_id);
?>