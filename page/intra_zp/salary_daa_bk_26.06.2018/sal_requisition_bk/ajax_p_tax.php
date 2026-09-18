<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';


$amount=$_REQUEST['gross'];

$db = new database();

$arr = $db->fetch_table("select ptax_amount from prd_ptax_deduction where mn_amount < '$amount' and mx_amount > '$amount' AND ptax_order_id_fk='1'");
echo $arr[0]['ptax_amount']; 


?>