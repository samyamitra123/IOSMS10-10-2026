<?php

session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	   !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
    ){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
     }

$cryptoGraph=new cryptography();
$emp_id=($cryptoGraph->decode($_GET['postemp'],4));


 
 //echo $emp_id;

$db=new database();

if($emp_id!='' && $emp_id!='0')
{
	$send=$db->update("UPDATE psemp_interim_relief SET ir_status='3' where emp_id_fk='".$emp_id."' 
	AND delete_status='1' AND zp_id_fk='".$_SESSION['location']['district_id']."' and ir_status='2'");
	
	if($send)
	{
		$f=1;
	}
	else
	{
		$f=0;
	}
	
}
else
{
	$f=0;
}	 

 echo $f;

?>