<?php

echo 444; die;
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';


	
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

/*
$logged_user=$_SESSION['user_info']['stake_abbr']; 	
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

*/

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

			$cryptoGraph=new cryptography();
			//$sec_time_token=$_POST['sec_tok'];
			//$session_token=$_SESSION['security_token'];
			//$enc_session=md5('369'.$session_token);
			$expance_amount = $_POST['expance_amount']);
			//$block_count = $_POST['block_count'];
			$disburs_amount = $_POST['disburs_amount'];
			$district_id_pk = $_POST['district_id_pk'];
			$prev_remain_amount = $_POST['prev_remain_amount'];
			$total_amount = $_POST['total_amount'];

echo $disburs_amount; die;

	
		
			$db = new database();
						
				echo ("INSERT INTO prd_commissioner_sub_allotment (
													district_id_fk,
													number_of_blocks,
													disburs_amount,
													allotment_status,
													date,
													ip)
													VALUES (
													'".$district_id_pk."',
													'".$block_count."',
													'".$disburs_amount."',
													'1',
													'now()',
													'".$_SERVER['REMOTE_ADDR']."'
													)")	; die;
													
				$insert_sub_allotment_data = $db->insert("INSERT INTO prd_commissioner_sub_allotment (
													district_id_fk,
													number_of_blocks,
													disburs_amount,
													allotment_status,
													date,
													ip)
													VALUES (
													'".$district_id_pk."',
													'".$block_count."',
													'".$disburs_amount."',
													'1',
													'now()',
													'".$_SERVER['REMOTE_ADDR']."'
													)")	;	
					
					
					
					
						
			
			
	
?>
	


