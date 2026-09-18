<?php

session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

	$crypto = new cryptography();
	 $bonusyear=$_REQUEST['bonusyear'];
	 $bonus_cat=$_REQUEST['id']; 
	
		$db=new database();

		
		$data=$db->fetch_table("
		SELECT *
		FROM 
		prd_bonus_type_details
		WHERE  bonus_category='".$bonus_cat."' and bonus_monthyear='". $bonusyear."' and active_status in('0','1') and delete_status='1'");
		
	$db=new database();
	if(empty($data))
	{
		$bonusyear_catagory=$bonus_cat;
	}
	else
	{
		if($data[0]['bonus_name']=='401')
		{
			$arr = $db->fetch_table("select code,description from prd_dise_code_master where code='401' ORDER BY description");
			if($arr[0]['description']=="ID-UL FITRE" && $bonusyear==$data[0]['bonus_monthyear'])
			{
			
				$bonusyear_catagory=167;
			}
		
		}
		else
		{
			$arr = $db->fetch_table("select code,description from prd_dise_code_master where code='400' ORDER BY description");
			if($arr[0]['description']=="DURGA PUJA" && $bonusyear==$data[0]['bonus_monthyear'])
			{
				$bonusyear_catagory=666;
			}
		}
	}
	
	
	//echo $data[0]['user_name']; die;
	//$wef_date=date("d-m-Y",strtotime($data[0]['wef_date']));
	
	echo json_encode(array($bonusyear_catagory,$data[0]['bonus_amount']));
	
	
	
	//$ps_code=substr($_SESSION['user_info']['stake_user'],0,-1);

?>
