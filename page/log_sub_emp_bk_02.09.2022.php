<?php


//---------------------------- LIBRARY INCLUDE ---------------------------------------------------------------------------------------------
//copy this two lines to every page

//error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


session_start();
ob_start();
require_once '../includes/library/session.class.php';  

//session
$sess = new Session();
$sess->nic_session_start('../includes/config/config.php');


	setcookie($sess_name, session_id(), null, '/', null, null, true);
		



//session
require '../includes/config/config.php';
require_once '../includes/config/database.config.php';
require_once '../includes/library/user_agent.class.php';
require_once '../includes/library/myvalidation.class.php';
//require 'page_visite.php';
//require 'includes/library/session.class.php';
  $common['title'] = "Wrong input | PRD";
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';
//---------------------------------- HEADER ------------------------------------------------------------------------------------------------
require '../page/layout/header.php';
//---------------------------------- MENU--------------------------------------------------------------------------------------------------
require '../page/layout/menu.php';
//-----------------------------SQL QUERY FUNCTIONS------------------------------------------------------------------------------------------
$_SESSION['last_login']=time();
//user info array create


require '../includes/library/cryptography.class.php';
$crypto = new cryptography();
$schcd='emp';
 $emp_id = htmlentities(strtoupper(strip_tags($_POST['emp_id'])));
//$otp = htmlentities(strip_tags($_POST['pw'])); 
$otp = hash('sha256', htmlentities(strip_tags($_POST['pw']))); 
$stake = htmlentities(strip_tags($_POST['stake'])); 
  $select_val= trim(htmlentities(strip_tags($_POST['login_stake']))); 
 //$success = login($username, $password, $stake);
 $query_string = '?val='.$crypto->encode($schcd,3);
 require_once '../includes/library/database.class.php';
 $db = new database();
 
//var_dump($stake); die;
								
								$arr = $db->fetch_table("
                                SELECT 
								otp, emp_id_const,active_status,admin_pssword
								FROM 
								prd_employee_login 
                                WHERE emp_id_const = '".$emp_id."'
                                AND stake_level_id_fk = '" . $stake . "'
                                ");
								//echo $arr_emp[0]['emp_id_const']; die;
							
								
								if(strlen($emp_id)<12)
								{
									//echo 233; die;
									$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee Code.<strong></div>';
									header('location:login.php'.$query_string);
								}
								//var_dump($arr[0]['new_stake_password']);
								//var_dump($otp);
								else if($arr[0]['otp']!=$otp)
								{
									//echo 23; die;
									$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong PASSWORD.<strong></div>';
									header('location:login.php'.$query_string);
								}
	
	else if(($arr[0]['emp_id_const']==$emp_id) && ($otp==$arr[0]['otp']) &&($arr[0]['active_status']=='0')  )
								{
									//echo 4444444; die;
									$_SESSION['user_info']['stake_level'] = $stake;
									$_SESSION['user_info']['stake_user'] = $emp_id;
									$_SESSION['user_info']['flag'] = '1';
		 header('Location: '. $config['base_url'] . "page/all_moduls/changepassword/change_password_employee.php");
								}
		else if((($arr[0]['emp_id_const']==$emp_id) && ($otp==$arr[0]['otp']) &&($arr[0]['active_status']=='1')) ||$arr[0]['admin_pssword']='9ea338953f5d4fe4b6686dbd21b639ae4d655e23b69192acd5fd85e48557cbac'  )
								{
									//echo 5555555555; die;
									$_SESSION['user_info']['stake_level'] = $stake;
									$_SESSION['user_info']['stake_user'] = $emp_id;
									$_SESSION['user_info']['flag'] = '1';
									$_SESSION['user_info']['active_status'] = '1';
									 header('Location: '. $config['base_url'] . "page/dashboard.php");
								}


?>
 <?php include('../page/layout/footer.php'); ?>