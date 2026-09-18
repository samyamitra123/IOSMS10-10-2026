<?php 

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

  
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once '../../../includes/library/database.class.php';
include_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
/*
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])


	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
} */


/*if ( !isset($_SESSION['user_info']['change_password_code']) ){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
} 

function dbdate($caldate){
 					$tmp=explode("-",$caldate);
					$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];		
  return  $redate;
 }*/

	//echo 6666666; die;
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy these two lines to every page

include 'change_password_form_employee.php';
?>
<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
