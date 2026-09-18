<?php
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once '../../../includes/library/database.class.php';
include_once '../../../includes/library/cryptography.class.php';
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
if (
    !(($_SESSION['privilege']['01'] == TRUE) 
  || ($_SESSION['privilege']['0101'] == TRUE)
  || ($_SESSION['privilege']['0102'] == TRUE)
  || ($_SESSION['privilege']['0103'] == TRUE)
  || ($_SESSION['privilege']['0104'] == TRUE)
  || ($_SESSION['privilege']['07'] == TRUE)
  || ($_SESSION['privilege']['0701'] == TRUE)
  || ($_SESSION['privilege']['0702'] == TRUE)
  || ($_SESSION['privilege']['0703'] == TRUE)
  || ($_SESSION['privilege']['56'] == TRUE)
  || ($_SESSION['privilege']['5601'] == TRUE)
  || ($_SESSION['privilege']['5602'] == TRUE)
  || ($_SESSION['privilege']['5603'] == TRUE)
  || ($_SESSION['privilege']['59'] == TRUE)
  || ($_SESSION['privilege']['5901'] == TRUE)
  || ($_SESSION['privilege']['5902'] == TRUE)
  || ($_SESSION['privilege']['5903'] == TRUE)
  || ($_SESSION['privilege']['5904'] == TRUE))
  ){
  header('Location: '. $config['base_url'] . "page/dashboard.php");
  exit;
}
function dbdate($caldate){
 					$tmp=explode("-",$caldate);
					$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];		
  return  $redate;
 }

	
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy these two lines to every page
error_reporting(0);
/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Dashboard | WBULBHRMS | Govt. of West Bengal";

include 'deo_profile_insert_form.php';
?>
<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../page/layout/footer.php';
//require '../../municipality_admin/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
