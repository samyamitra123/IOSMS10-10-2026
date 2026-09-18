<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
//error_reporting(0);
session_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../page_visite.php';
function dbdate($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 } 

 function date_subs($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[0]."-".$tmp[1]."-".($tmp[2]);
				}
  return  $redate;
 } 
 
?>
<?php
$sec_time_token=$_POST['sec_tok'];
    	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		$error_msg='<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>';
		include 'paychange_order_insert.php';
		exit;
	}else{
	if(!isset($_POST['paychange_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php");
		exit;
	}else if(isset($_POST['paychange_submit'])){
		
		//print_r($_POST);
		
		$msg="";
		
		$paychange_id_pk=$_POST['paychange_id_pk'];
		$frm_date=$_POST['frm_date'];
		$da=$_POST['da'];
		$hra=$_POST['hra'];
		$ma=$_POST['ma'];
		$conveyance_allowance=$_POST['conveyance_allowance'];
		$cpf=$_POST['cpf'];
		$date=date("Y-m-d");
		$todate=date_subs($frm_date);
		//echo $todate;exit;
		
	$validation= new Validation();
	$cryptography= new cryptography();
	
	
		
		
		//--------------------------------------------------------------------------------------------------------------------------------------------		
				
		if((!$validation->blank_select($da))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid DA</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Valid DA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
		if(!$validation->float_val($da)){
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid DA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
			/*header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit;*/
		}
//--------------------------------------------------------------------------------------------------------------------------------------------		
       if((!$validation->blank_select($hra))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid HRA.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Valid HRA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
		if(!$validation->float_val($hra)){
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid HRA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
			/*header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit;*/
		}
		
		if((!$validation->blank_select($ma))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid MA.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Valid MA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
		if(!$validation->float_val($ma)){
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid MA.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
			/*header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit;*/
		}
		
		if($validation->blank_select($conveyance_allowance)==FALSE || $validation->pattern_number($conveyance_allowance)==FALSE || $validation->float_val($conveyance_allowance)==FALSE)
		{
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid CONV ALLOW.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
			
		}
		
		/*if((!$validation->blank_select($cpf))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valide CPF.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Valid CPF.</strong></div>',3);
			header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
		if(!$validation->float_val($cpf)){
			header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit;
		}*/
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//========================non ssa school======18101=======================================================================================		
	 $db=new database();
	 
		$insert=$db->insert("		
		 							 UPDATE prd_admin_paychange
									 SET entrydate='".$date."',
									 paychange_hra='".$hra."',
									 paychange_da='".$da."',
									 paychange_ma='".$ma."',
									 paychange_cpf='0',
									 conveyance_allowance='".$conveyance_allowance."',
									 paychange_ip='".$_SESSION['user_agent']['USER_IP']."'
									 WHERE paychange_id_pk='".$paychange_id_pk."' 
									
							");	
							
				
				
										
				if($insert){
			 								$update_flag=$db->update("
											UPDATE prd_admin_paychange SET paychange_todate='".$todate."' WHERE flag='t' 
		
																	");
										
					
				}
				if($insert){
				$msg=$cryptography->encode('<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Entered.</strong></div>',3);
				}
				else
				{
				$msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Data Is Not Entered Successfully.</strong></div>',3);
				}
	}
//========================non ssa madrasah======= 18102=======================================================================================		
//========================ssa madrasah======= 18202=======================================================================================		
	
	
//========================new setup======= 18300=======================================================================================		
	
	   
		header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_insert.php?msg=".$msg);
		exit;
	
	}

?>