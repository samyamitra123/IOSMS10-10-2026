<?php

session_start();
ob_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';



$db=new database();

  $id=$_GET['id']; 
  
  $check = $db->fetch_table("SELECT officer_id_const,mobile_no FROM intra_pri_master WHERE officer_id_const='" .$id ."' ");
 
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$password =  substr(str_shuffle($str_result), 0, 8);

echo json_encode(array($check[0]['officer_id_const'],$password,$check[0]['mobile_no']));


?>