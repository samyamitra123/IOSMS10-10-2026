<?php
session_start();
error_reporting(0);
//print_r($_SERVER);exit;
//echo $_SESSION['http_referer'];exit;
$_SERVER['HTTP_REFERER']=$_SESSION['http_referer'];
//echo $_SERVER['HTTP_REFERER'];exit;
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//error_reporting(0);
/*$teacher_id_pk=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy these two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//$status='';
$status=isset($_REQUEST['status'])?$_REQUEST['status']:'';

/*$cryptoGraph=new cryptography();
$teacher_id_pk_enc=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$teacher_id_pk=$cryptoGraph->decode($teacher_id_pk_enc, 4);

$tchcd_enc=isset($_GET['tchcd'])?$_GET['tchcd']:' ';
$tchcd=$cryptoGraph->decode($tchcd_enc, 4);
$flag=isset($_GET['flag'])?$_GET['flag']:' ';*/

/*$db=new database();
$emp_exist=$db->fetch_table(
				'
					select * from ehrms_dise_teacher
					where tchcd="'.$tchcd.'" and
						  teacher_id_pk="'.$teacher_id_pk.'"
				'
				);
if(count($emp_exist)==0 || count($emp_exist)==NULL){
	header("Location:../../dashboard.php");
}else if(count($emp_exist)==1){
	if($flag=='edit'){
		if($emp_exist[0]['form_status']!='4'){
			header("Location:../../dashboard.php");
	}else if(!$flag){
		if($emp_exist[0]['form_status']!='3'){
			header("Location:../../dashboard.php");
		}
	}
  }
}*/

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






?>
<!--CONTENT START-->


<?php
//body of the form
include 'entry_prof.php';
?>
