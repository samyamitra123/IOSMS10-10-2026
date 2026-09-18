<?php
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require_once '../../../all_function/fun_store/zp_ps_gp_function.php';

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
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
//$status='';
$status=isset($_POST['status'])?$_POST['status']:'';


//body of the form
include 'entry_prof.php';
?>
