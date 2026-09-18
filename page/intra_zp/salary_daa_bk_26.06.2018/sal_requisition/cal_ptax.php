<?php
session_start();

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}



error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$gross=$_GET['gross'];

$crypto = new cryptography();
$db = new database();	

$arr=$db->fetch_table("select mn_amount,mx_amount,ptax_amount from prd_ptax_deduction as mad_ded
									INNER JOIN prd_ptax_order_file as mad_od
									ON mad_od.ptax_orderfile_pk=mad_ded.ptax_order_id_fk
									where mn_amount <= '$gross' and mx_amount >= '$gross'
									and active_status='1'");
			echo $arr[0]['ptax_amount'];


@pg_close($con);
?>