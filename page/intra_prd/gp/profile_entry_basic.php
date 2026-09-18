<?php
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
/*require '../../../../includes/library/qrtstr_encrp.php';
require '../../../page_visite.php';*/
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");



//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
//if($confirm == 'false')
//{ ?>
	<!--/*/*<div class="float_l col_line"><? //= "Primary Details of the Employee cann't be submitted !!!";?></div>
                    <div class="clear"></div> */*/-->
<? //}
require 'profile_entry_basic_form.php';

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
?>