<?php

session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

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
//print_r($_POST);
  $approve_reject=$cryptoGraph->decode($_POST['apr'],4);
 $emp_id=$cryptoGraph->decode($_POST['emp_id'],4);
 $increment_condisation=$_POST['increment_condisation'];
 $dop_doi=$cryptoGraph->decode($_POST['dop_doi'],4);
 $new_ropa_level=$_POST['new_ropa_level'];
 //var_dump($dop_doi); die;
  $db = new database();
  
  $emp_fetch=$db->fetch_table("select emp_pay_band,emp_pay_scale,emp_desig,emp_grade_pay,emp_pay_in_payband,emp_next_increment_amount,ropa_level, increment_count from prd_employee_master WHERE emp_id_pk = '".$emp_id."'");
  
 $emp_pay_band=$emp_fetch[0]['emp_pay_band']; 
$emp_pay_scale=$emp_fetch[0]['emp_pay_scale'];
$emp_desig=$emp_fetch[0]['emp_desig'];
$ropa_level=$emp_fetch[0]['ropa_level'];
$emp_grade_pay=$emp_fetch[0]['emp_grade_pay'];
$emp_pay_in_payband=$emp_fetch[0]['emp_pay_in_payband'];
$emp_next_increment_amount=$emp_fetch[0]['emp_next_increment_amount'];
$increment_count=$emp_fetch[0]['increment_count'];
		//var_dump($increment_condisation); die;	
if($increment_condisation ==1){
	if($dop_doi == 3){ $increment_condisation = 2; }
	else if($dop_doi == 4){ $increment_condisation = 1; }
}
		
 if($approve_reject=="APPROVE")
 {
	 $db = new database();
	 if($new_ropa_level <= $ropa_level){
		$increment_count = $increment_count+$increment_condisation; 
	 }
	 else if($new_ropa_level > $ropa_level){
		 $increment_count = 0;
	 }
	
	//var_dump($increment_count); die;
	////////////////////////////// New pre_level added////////////////////////////////

		$update_status = $db->update("UPDATE prd_employee_promotion_details SET approval_status='4',pre_emp_pay_in_payband='".$emp_pay_in_payband."',pre_level='".$ropa_level."',
														 pre_emp_desig='".$emp_desig."', increment_condisation='".$increment_condisation."'
														WHERE emp_id_fk = '".$emp_id."' 
														AND delete_status = '1' and approval_status='3'");
														
		$update_master= $db->update("UPDATE prd_employee_master SET increment_count= '".$increment_count."' WHERE emp_id_pk = '".$emp_id."' ");
							
//var_dump($update_status); die;							
		if( $update_status && $update_master)	
		{											
														
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Promotion Details Approve Successfully.</strong></div>';	
					header('location:block_emp_promotion_entry_view.php');
					exit;													
		}
 }
  else if($approve_reject=="REJECT")
 {
		$db = new database();
		 ////////////////////////////// New dop_doi added////////////////////////////////
		$update_status = $db->update("UPDATE prd_employee_promotion_details SET approval_status='1',dop_doi='0', increment_condisation='0'
															WHERE emp_id_fk = '".$emp_id."' 
															AND delete_status = '1' and approval_status in(2,3)");
															
															
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Promotion Details Has Rejected...</strong></div>';
						header('location:block_emp_promotion_entry_view.php');
						exit;													
		 
 }
 