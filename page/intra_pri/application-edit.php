<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

header("Strict-Transport-Security: max-age=63072000");

session_start();

require '../../includes/config/config.php';
require '../../includes/config/database.config.php';

require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';
$crypto=new cryptography();
$app_no = $crypto->decode($_GET['app'],4);
$db=new database();
$Query =" SELECT * FROM intra_pri_forwarding WHERE application_id='".$app_no."' ORDER BY forwarding_id_pk ASC LIMIT 1 OFFSET 0";
$app_owner = $db->fetch_table($Query); 
$app_owner = $app_owner[0];
//print($app_owner['sub_menu']); exit;
if($app_owner['from_officer_id_const'] == $_SESSION['user_info']['officer_id_const'])
{
	switch ($app_owner['sub_menu']) {
		case '5A':
		   $_SESSION['user_info']['application_id'] = $app_owner['application_id'];	
		   header('Location:intre_pri_9008_form.php?val=myForm_addi_A');
		   exit;
	    break;
		case '5B':
		   $_SESSION['user_info']['application_id'] = $app_owner['application_id'];	
		   header('Location:intre_pri_9008_form.php?val=myForm_addi_B');
		   exit;
	    break;
		case '5C':
		   $_SESSION['user_info']['application_id'] = $app_owner['application_id'];	
		   header('Location:intre_pri_9008_form.php?val=myForm_addi_C');
		   exit;
	    break;
		case '5':
		   $_SESSION['user_info']['application_id'] = $app_owner['application_id'];	
		   header('Location:intre_pri_9008_form.php?val=myForm');
		   exit;
	    break;		
		default:
			// code...
			break;
	}
}
//print_r($app_owner); 




?>