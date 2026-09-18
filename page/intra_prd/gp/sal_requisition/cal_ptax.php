<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$gross=$_REQUEST['gross'];

$crypto = new cryptography();
$db = new database();	

$arr=$db->fetch_table("select mn_amount,mx_amount,ptax_amount from prd_ptax_deduction as prd_ded
									INNER JOIN prd_ptax_order_file as prd_od
									ON prd_od.ptax_orderfile_pk=prd_ded.ptax_order_id_fk
									where mn_amount <= '$gross' and mx_amount >= '$gross'
									and active_status='1'");
			echo $arr[0]['ptax_amount'];


?>