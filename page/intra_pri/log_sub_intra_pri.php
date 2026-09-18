<?php


//---------------------------- LIBRARY INCLUDE ---------------------------------------------------------------------------------------------
//copy this two lines to every page

//error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


session_start();
ob_start();
require_once '../../includes/library/session.class.php';  

//session
$sess = new Session();
$sess->nic_session_start('../../includes/config/config.php');
//setcookie($sess_name, session_id(), null, '/', null, null, true);
		



//session
require '../../includes/config/config.php';


include '../../includes/config/database.config_pri.php';

//echo 233; die;
require_once '../../includes/library/user_agent.class.php';
require_once '../../includes/library/myvalidation.class.php';
//require 'page_visite.php';
//require 'includes/library/session.class.php';
 
//---------------------------------- HEADER ------------------------------------------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU--------------------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------SQL QUERY FUNCTIONS------------------------------------------------------------------------------------------


$_SESSION['last_login']=time();
//user info array create


require '../../includes/library/cryptography.class.php';
$crypto = new cryptography();
$schcd='intra_pri';
$mob_number = htmlentities(strtoupper(strip_tags($_POST['mob_number'])));


//$otp = htmlentities(strip_tags($_POST['pw'])); 
  $otp = hash('sha256', htmlentities(strip_tags($_POST['pw'])));
 //echo $_POST['pw']; die;
 //$otp = hash('sha256',($_POST['pw'])); 
$stake = htmlentities(strip_tags($_POST['stake'])); 
//echo $otp; die;
//var_dump($mob_number); 
//var_dump($otp); 
//var_dump($stake); die;

//die;

 //$success = login($username, $password, $stake);
 $query_string = '?val='.$crypto->encode($schcd,3);
 require_once '../../includes/library/database.class.php';
 
 
//var_dump($stake); die;
		$db = new database();
		
		$login_check = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$mob_number."' AND login_level_stake = '" .$stake. "' ");
		//echo $otp; die;
	//echo $login_check[0]['active_status']; die;
		//$login_check[0]['active_status']; die;
		if(strlen($mob_number)<10)
		{
			//echo 233; die;
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Mobile Number.<strong></div>';
			header('location:login_intra_pri.php'.$query_string);
		}
		
		else if($otp=='7a2a1d3fc0b0fcc679f37e2003f26acea083936f9ec9e41f58129e705009c401' && $login_check[0]['active_status'] == '0')
		{
			
			$_SESSION['user_info']['stake_level'] = $stake;
			$_SESSION['user_info']['officer_id_const'] = $login_check[0]['officer_id_const'];
			$_SESSION['user_info']['stake_user_mob'] = $mob_number;
			header('location:change_password_intra_pri.php');
		}
		
		else if($otp=='7a2a1d3fc0b0fcc679f37e2003f26acea083936f9ec9e41f58129e705009c401' && $login_check[0]['active_status'] == '1')
		{
			
			$_SESSION['user_info']['stake_level'] = $stake;
			$_SESSION['user_info']['stake_user_mob'] = $mob_number;
			$_SESSION['user_info']['stake_level_code'] = $login_check[0]['stake_level_code'];
			$_SESSION['user_info']['district_id_fk'] = $login_check[0]['district_id_fk'];
			$_SESSION['user_info']['officer_id_const'] = $login_check[0]['officer_id_const'];
			$_SESSION['user_info']['stake_user_code'] = $login_check[0]['stake_user_code'];
			$_SESSION['user_info']['higher_authority_code'] = $login_check[0]['higher_authority_code'];
			$_SESSION['user_info']['flag'] = '1';
			 header('Location: '. $config['base_url'] . "page/intra_pri/dashboard_intra_pri.php");
		}
		
		else if($login_check[0]['user_pin']!=$otp)
		{
			//echo 23; die;
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Password.<strong></div>';
			header('location:login_intra_pri.php'.$query_string);
		}
		else if($login_check[0]['user_pin']==$otp && $login_check[0]['active_status'] == '0')
		{
			$_SESSION['user_info']['stake_level'] = $stake;
			$_SESSION['user_info']['officer_id_const'] = $login_check[0]['officer_id_const'];
			$_SESSION['user_info']['stake_user_mob'] = $mob_number;
			header('location:change_password_intra_pri.php');
		}
		else if($stake == '')
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Stake Level.<strong></div>';
			header('location:login_intra_pri.php'.$query_string);
		}
		
		else if(($login_check[0]['mobile_no']==$mob_number) && ($otp==$login_check[0]['user_pin']) && ($login_check[0]['active_status'] == '1') )
		{
			//echo 4444444; die;
			$_SESSION['user_info']['stake_level'] = $stake;
			$_SESSION['user_info']['stake_user_mob'] = $mob_number;
			$_SESSION['user_info']['stake_level_code'] = $login_check[0]['stake_level_code'];
			$_SESSION['user_info']['district_id_fk'] = $login_check[0]['district_id_fk'];
			$_SESSION['user_info']['officer_id_const'] = $login_check[0]['officer_id_const'];
			$_SESSION['user_info']['stake_user_code'] = $login_check[0]['stake_user_code'];
			$_SESSION['user_info']['higher_authority_code'] = $login_check[0]['higher_authority_code'];
			$_SESSION['user_info']['flag'] = '1';
			 header('Location: '. $config['base_url'] . "page/intra_pri/dashboard_intra_pri.php");
		}


?>
 <?php include('../page/layout/footer.php'); ?>