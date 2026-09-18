<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
include_once ('../../../../includes/library/database.class.php');
include_once '../../../../includes/library/myvalidation.class.php';
include_once '../../../../includes/library/cryptography.class.php';
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
  $cryptography=new cryptography(); 
?>
<?php

	if(!isset($_POST['ptax_deduction_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_deduction_insert.php");
		exit;
	}else if(isset($_POST['ptax_deduction_submit'])){
		
		$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		$error_msg='<div class="alert alert-danger" style="text-align:center;">Time Out!..Please Try Again.</strong></div>';
		include 'ptax_order_insert.php';
		exit;
	}else{
		
		$validation=new Validation();
		
		$msg="";
		
		$i=$_POST['count'];
		$ptax_order_id_fk=array();

 		$activation_date=$_POST['activation_date_val'];		
		
	
		$c=0;
		$chk=0;
		$p=$i;
		
		$max_amount=$_POST['max_amount'];
            $min_amount=$_POST['min_amount'];
			$ptax_amount=$_POST['ptax_amount'];	
		

		
		/*if( $validator->blank_validation($nonschool[0])==FALSE ){
			if( $validator->blank_validation($nonschoolDate[0])==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center;">Please Enter Date for T.V. No.</span>';
				include 'treasury_head_insert_form.php';
				exit;
			}
		}
		
		/*if(    ( $validator->blank_validation($nonschool[1]) ) && ( $validator->blank_select($nonschoolDate[1]) )    ){
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Please Enter Date for Memo No.</span>';
			include 'treasury_head_insert_form.php';
			exit;
		}*/
		/*if($validator->pattern_match_thi($frm_date)==FALSE || $validator->pattern_match_thi($to_date)==FALSE || $validator->pattern_match_thi($da)==FALSE ||  $validator->pattern_match_thi($ma)==FALSE ||  $validator->pattern_match_thi($hra)==FALSE ||  $validator->pattern_match_thi($cpf)==FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Please Enter Value Without Special Character.</span>';
			include 'treasury_head_insert_form.php';
			exit;
		}*/
		if(!$validation->blank_select($activation_date)){
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Insert Date</strong></div>';
			include 'ptax_deduction_form.php';
			exit;
		}
		
	while($i!=0)
		{
			if(!$validation->blank_select($max_amount[$chk])|| !$validation->pattern_number($max_amount[$chk])){
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Insert Max Valid Amount</strong></div>';
			include 'ptax_deduction_form.php';
			exit;
		     }
			 if(!$validation->blank_select($min_amount[$chk])||!$validation->pattern_number($min_amount[$chk])){
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Insert Min Valid Amount</strong></div>';
			include 'ptax_deduction_form.php';
			exit;
		     }
			 if(!$validation->blank_select($ptax_amount[$chk])|| !$validation->pattern_number($ptax_amount[$chk]) ){
			$error_msg='<div class="alert alert-danger" style="text-align:center;">Insert Ptax Valid Amount</strong></div>';
			include 'ptax_deduction_form.php';
			exit;
		     }
		
		$i--;
		$chk++;	 
		}

//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//--------------------------------------------------------------------------------------------------------------------------------------------		
		
//========================non ssa school======18101=======================================================================================		
	 $db=new database();
	
	$ptax_order = $db->fetch_table("select ptax_orderfile_pk from prd_ptax_order_file 
													where activation_date='".$activation_date."'
												");
												
												
												if(count($ptax_order)>0)
												{
	$ptax_order_id_fk= $db->fetch_table("SELECT ptax_id_pk FROM prd_ptax_deduction WHERE ptax_order_id_fk='".$ptax_order[0]['ptax_orderfile_pk']."' ORDER BY ptax_id_pk");	
												}
												
			$order_id_fk=$_POST['order_id_fk'];
			
			//if($ptax_order[0]['ptax_orderfile_pk']!=$order_id_fk[0])
			if(count($ptax_order)>0 && count($ptax_order_id_fk)<1)
			{	
									
while($p!=0)
		{ 
			
					
			
			
		$insert=$db->insert("		
		 INSERT INTO prd_ptax_deduction
									(ptax_order_id_fk,
									 mn_amount,
									 mx_amount,
									 ptax_amount
									 )
									 values (
									 '".$ptax_order[0]['ptax_orderfile_pk']."',
									 '$max_amount[$c]',
									 '".$min_amount[$c]."',
									 '".$ptax_amount[$c]."'
									
									  )
  							");	

			$c++;
			$p--;
		}

$msg=$cryptography->encode('<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Entered.</strong></div>',3);
			}
			else if(count($ptax_order_id_fk)>0){
			
				for($j=0;$j<count($ptax_order_id_fk);$j++)
		{ 
				
				$insert=$db->update("		
									 UPDATE prd_ptax_deduction SET 
									 mn_amount='$max_amount[$c]',
									 mx_amount='".$min_amount[$c]."',
									 ptax_amount='".$ptax_amount[$c]."'
									 WHERE ptax_id_pk='".$ptax_order_id_fk[$j]['ptax_id_pk']."'
									 
									 
  			");
		
			
			$c++;
		
		}
			//$_SESSION['msg']= '<div id="sucess">Treasury Details Has Been Inserted successfully.</div>';	
			$msg=$cryptography->encode('<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Updated.</strong></div>',3);
			}
			else
			{
				$msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Entered Valid Date.</strong></div>',3);
			//$msg=$cryptography->encode('Entered Valid Date',3);	
			}
				
	
//========================non ssa madrasah======= 18102=======================================================================================		
//========================ssa madrasah======= 18202=======================================================================================		
	
	
//========================new setup======= 18300=======================================================================================		
	}
	}
	  
		header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_deduction_insert.php?msg=".$msg);
		exit;
?>
