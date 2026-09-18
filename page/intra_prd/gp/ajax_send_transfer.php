<?php
//echo "11111";
//exit;
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/cryptography.class.php';
include_once '../../../includes/library/database.class.php';
$cryptoGraph= new cryptography();
function send_to_ddo($pk){
	require_once '../../../includes/library/database.class.php';
	$db  = new database();
	$obj_crpto = new cryptography();
	$data = $db->update("
							UPDATE prd_employee_master
							SET emp_status = 6
							WHERE emp_id_pk = '".$pk."'
								AND gp_id_fk = '".$_SESSION['location']['gp_id']."'
								AND emp_status in (10,7)
								AND emp_form_status = '5'
								AND gp_id_fk='".$_SESSION['location']['gp_id']."'
	
	");
}

   
   if(send_to_ddo($cryptoGraph->decode($_REQUEST['id'], 4)) == TRUE){
	   $_SESSION['sent_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry !! Profile Sent to BDO Fails...</strong></div>';
	   header('Location:'.$config['base_url'].'page/intra_prd/gp/add_transfer_emp.php');
	   exit(0);
	     } else{
	   $_SESSION['sent_msg']='<div class="alert alert-success" style="text-align:center"><strong>Profile Successfully Sent to BDO...</strong></div>';
	   header('Location:'.$config['base_url'].'page/intra_prd/gp/add_transfer_emp.php');
	   exit(0);
		 }
	?>