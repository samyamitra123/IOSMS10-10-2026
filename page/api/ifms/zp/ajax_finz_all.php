<?php

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

$crypto = new cryptography();

$all_emp_id_list=$crypto->decode($_GET['all_id'],4);
$benf_id=$crypto->decode($_GET['benf_id'],4);

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

$db = new database();

if($all_emp_id_list!='' && $benf_id!='')
{
	pg_query('BEGIN');
	$upd_failure=$db->update(" UPDATE prd_sftp_benf_failure_details arr SET active_status=3
						WHERE emp_id_fk in (".$all_emp_id_list.") AND active_status=2 ");
						
	$upd_benf=$db->update(" UPDATE prd_sftp_benf_upload_response SET active_status='2' WHERE sftp_benf_id_pk='".$benf_id."' AND active_status='1' ");
						
	if($upd_failure && $upd_benf)
	{
		pg_query('COMMIT');
		echo 1;
	} 
	else 
	{
		pg_query('ROLLBACK');
		echo 0;
	}	
}
else
{
	echo 0;
}


i ?>
