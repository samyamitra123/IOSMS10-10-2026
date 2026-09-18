<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
error_reporting(0);
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/myvalidation.class.php';
require '../../../../includes/library/qrtstr_encrp.php';
require '../../../../includes/library/cryptography.class.php';
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
						$redate=$tmp[0]."-".$tmp[1]."-".($tmp[2]-1);
				}
  return  $redate;
 } 
 
?>
<?php
$sec_time_token=$_POST['sec_tok'];
    	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		$error_msg='<span style="color:#D83118; font-weight:bolder;">Time Out!..Please Try Again.</span>';
		include 'paychange_order_insert.php';
		exit;
	}else{
	if(!isset($_POST['paychange_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php");
		exit;
	}else if(isset($_POST['paychange_submit'])){
		
		$msg="";
		
		$frm_date=$_POST['frm_date'];
		$da=$_POST['da'];
		$hra=$_POST['hra'];
		$ma=$_POST['ma'];
		$cpf=$_POST['cpf'];
		$date=date("Y-m-d");
		$todate=date_subs($frm_date);
		
		
	$validation= new Validation();
	$cryptography= new cryptography();
		
		/*if( $validator->blank_validation($nonschool[0])==FALSE ){
			if( $validator->blank_validation($nonschoolDate[0])==FALSE){
				$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Date for T.V. No.</span>';
				include 'treasury_head_insert_form.php';
				exit;
			}
		}
		
		/*if(    ( $validator->blank_validation($nonschool[1]) ) && ( $validator->blank_select($nonschoolDate[1]) )    ){
			$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Date for Memo No.</span>';
			include 'treasury_head_insert_form.php';
			exit;
		}*/
		/*if($validator->pattern_match_thi($frm_date)==FALSE || $validator->pattern_match_thi($to_date)==FALSE || $validator->pattern_match_thi($da)==FALSE ||  $validator->pattern_match_thi($ma)==FALSE ||  $validator->pattern_match_thi($hra)==FALSE ||  $validator->pattern_match_thi($cpf)==FALSE)
		{
			$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Value Without Special Character.</span>';
			include 'treasury_head_insert_form.php';
			exit;
		}*/
		//--------------------------------------------------------------------------------------------------------------------------------------------		
				
		if((!$validation->blank_select($da))|| (!$validation->float_val($da))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid DA</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('Please Enter Valid DA',3);
			header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
//--------------------------------------------------------------------------------------------------------------------------------------------		
       if((!$validation->blank_select($hra))|| (!$validation->float_val($hra))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid HRA.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('Please Enter Valid HRA',3);
			header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
		if((!$validation->blank_select($ma))|| (!$validation->pattern_number($ma))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valid MA.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('Please Enter Valid MA',3);
			header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}

		
		if((!$validation->blank_select($cpf))|| (!$validation->float_val($cpf))){
			//$error_msg='<span style="color:#D83118; font-weight:bolder;">Please Enter Valide CPF.</span>';
			//include 'paychange_insert.php';
			$error_msg=$cryptography->encode('Please Enter Valid CPF',3);
			header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php?error_msg=".$error_msg);
			exit;
		}
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//========================non ssa school======18101=======================================================================================		
	 $db=new database();
	
		$insert=$db->insert("		
		 UPDATE ehrms_admin_paychange
									 SET entrydate='".$date."',
									 paychange_hra='".$hra."',
									 paychange_da='".$da."',
									 paychange_ma='".$ma."',
									 paychange_cpf='".$cpf."',
									 paychange_ip='".$_SESSION['user_agent']['USER_IP']."'
									 WHERE paychange_fromdate='".$frm_date."' 
									
							");	
							

				
										
				if($insert){
			 								$update_flag=$db->update("
											UPDATE ehrms_admin_paychange SET paychange_todate='".$todate."' WHERE flag='TRUE' 
		
																	");
										
					
				}
				if($update_flag){
				$msg=$cryptography->encode('<div id="sucess">Data Successfully Entered</div>',3);
				}
				else
				{
				$msg=encode('<div id="error">Data Is Not Entered Successfully</div>',3);
				}
	}
//========================non ssa madrasah======= 18102=======================================================================================		
//========================ssa madrasah======= 18202=======================================================================================		
	
	
//========================new setup======= 18300=======================================================================================		
	
	   
		header('Location: '. $config['base_url'] . "page/intra_ehrms/state/paychange/paychange_insert.php?msg=".$msg);
		exit;
	
	}

?>