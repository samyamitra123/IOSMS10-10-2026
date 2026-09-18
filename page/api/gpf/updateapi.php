<?php
echo "Currently Not available";
exit;
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
$emp_id_pk = $crypto->decode($_REQUEST['emp_id_pk'],4);
$ngipfStatus = $crypto->decode($_REQUEST['status'],4);
$Ngipf->GetEmployeeDetails($emp_id_pk);
$Ngipf->GetNgipfEmployeeDetails();
$Ngipf->GetEmployeeNgipfStructure();
$Ngipf->sendToNgipfApi();
header('Location:'.$redirectUrl);


?>