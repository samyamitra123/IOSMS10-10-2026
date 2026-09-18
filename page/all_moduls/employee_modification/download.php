<?php
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once 'employeeChange.class.php';

$cryptoGraph=new cryptography();	
$ech_id = $cryptoGraph->decode($_GET['load'],4);
global $db;
$db=new database();
$employeeChangeObject = new employeeChange();
$fileName = $employeeChangeObject->getSingleRequest($ech_id); 
$pathInfo = pathinfo($fileName,PATHINFO_EXTENSION);
//print($pathInfo); exit;
//$fileName = '387035Ashok Raj.pdf';
$filePath = '../../../readwrite/intra_pri_upload/employee/'.$fileName;
$fileurl = $config['base_url'].'readwrite/intra_pri_upload/employee/'.$fileName;
header('Location:'.$fileurl);

?>