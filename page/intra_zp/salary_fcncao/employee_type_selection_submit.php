<?php

session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/cryptography.class.php';

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];

$enc_session=md5('369'.$session_token);
$cryptoGraph=new cryptography();

if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('Location:arrear_module_view.php');
	exit(0);
}
  
$type=$cryptoGraph->decode($_REQUEST['type'],4); 
$type2=$cryptoGraph->decode($_REQUEST['type2'],4);


if($type=='1')
{
	header('Location:employee_type_selection.php');
	exit(0);
}

else if($type2=='2')
{
	header('Location:employee_type_selection.php');
	exit(0);
}


?>