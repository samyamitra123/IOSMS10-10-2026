<?php


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/cryptography.class.php';



//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

if($type!=1 && $type2!=2)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Wrong Selection.</strong></div>';
	header('Location:arrear_module_view.php');
	exit(0);
}



if($type=='1')
{
	header('Location:ll_bonus_view.php');
	exit(0);
}
/*else if($type2=='2' && $logged_user=='zpdaa' && $_SESSION['user_info']['stake_user']=='3216' )
{
	header('Location:ll_emp_bonus_module_sal_form_zp.php');
	exit(0);
}*/

else if($type2=='2' && $logged_user=='zpdaa')
{
	header('Location:ll_emp_bonus_module_sal_form_zp.php');
	exit(0);
}

else if($type2=='2')
{
	header('Location:ll_emp_bonus_module_sal_form.php');
	exit(0);
}


?>