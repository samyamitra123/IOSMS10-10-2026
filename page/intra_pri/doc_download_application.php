<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
session_start();
 require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require_once '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
$db=new database();
//echo 222; die;
 $crypto=new cryptography();
 $app_id=$crypto->decode($_GET['app_id'],4); 
 $Query = "select msg_file from intra_pri_forwarding where forwarding_id_pk='".$app_id."'";
 //print_r($Query); exit;
 $file=$db->fetch_table($Query);
 $file_name = $file[0]['msg_file'];
 //$location = '../../readwrite/intra_pri_upload/comments/'.$fileName;
 $fileurl = $config['base_url'].'readwrite/intra_pri_upload/comments/'.$file_name;
 header('Location:'.$fileurl);
 //print_r($file); 