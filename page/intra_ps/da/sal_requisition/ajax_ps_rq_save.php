<?php

	
	header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	session_start();
	require_once '../../../../includes/config/config.php';
	require_once '../../../../includes/config/database.config.php';
	require_once '../../../../includes/library/database.class.php';
	
	require_once '../../../../includes/library/cryptography.class.php';
	require_once '../../../../includes/library/myvalidation.class.php';
	$f=0;
	$crypto = new cryptography();
	$db = new database();
	
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	$requisition_type=$requisition[0]['code'];
		
	$dise = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['ps_id'];
	$empcd = $crypto->decode($_GET['empcd'],4);
	$emp_id_pk=$crypto->decode($_GET['emp_id_pk'],4);
	$gpcd = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['ps_id'];
	$sec_time_token=$_REQUEST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session)
	{
		echo $f=1;
	}
	else{

	$arr = $db->fetch_table("select pay_payband,emp_id_fk from prd_employee_salary_save where status_flag=1 AND delete_status=1 AND ps_id_fk='$gpcd' AND salary_monthyear='".date('Ym')."' AND emp_id_fk='$emp_id_pk' AND requisition_type='".$requisition_type."' AND ropa_status='2' ");
	
	$emp_desig_fetch = $db->fetch_table("select emp_desig, emp_group, emp_pay_scale, emp_pay_band from prd_employee_master where 
	emp_id_pk= '$emp_id_pk' AND ropa_status='2' ");
	$emp_desig = $emp_desig_fetch[0]['emp_desig'];

	if($arr[0]['emp_id_fk']){

		$upd = $db->update("UPDATE prd_employee_salary_save SET is_saved=1 WHERE ps_id_fk='$gpcd' AND delete_status=1 AND emp_id_fk='$emp_id_pk' AND code='".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."' AND requisition_type='".$requisition_type."' AND ropa_status='2'");
		if($upd){
			
			/*$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1)
													AND emp_id_fk = '".$emp_id_pk."'");*/	
		
	    	$f=2;				
				pg_query("commit");
		}
		else{
			$f=3;
			pg_query("rollback");
		    }
	        }
			else{
		
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$salary_monthyear = date('Ym');

		
		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, ps_id_fk, empcd, 
		bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
		i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
		spl_alo, status_flag, salary_monthyear, category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
		hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,overdrawn, 
		salary_type, cause,part_day,gsli,delete_status,consolidated_pay,festival_loan,festival_loan_cause,interim_relief,
		requisition_type,gp_id_fk,hra_deduction, emp_desig, emp_group, emp_pay_scale, emp_pay_band, ropa_status)
		VALUES ('0', 'now()', '$user_ip', '".$gpcd."', '$empcd', 
		'".$crypto->decode($_SESSION['bankname'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['accountno'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['basic'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['da'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['hra'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ma'.$emp_id_pk],4)."', '0', 
		'".$crypto->decode($_SESSION['pfl'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['itax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['net_salary'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['bank_ifsc'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['salary_source'.$emp_id_pk],4)."', '0', 
		'".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."','0', '1', 
		'$salary_monthyear','1', '0', '".$crypto->decode($_SESSION['emp_id_pk'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['pay_in_band'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['grade_pay'.$emp_id_pk],4)."',
		'".$crypto->decode($_SESSION['hill_allowance'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['gpf'.$emp_id_pk],4)."', '0',
		'".$crypto->decode($_SESSION['gross_salary'.$emp_id_pk],4)."', '1', '".$crypto->decode($_SESSION['conveyance_allowance'.$emp_id_pk],4)."',
		'".$crypto->decode($_SESSION['ovd'.$emp_id_pk],4)."','1','','','".$crypto->decode($_SESSION['gsli'.$emp_id_pk],4)."','1',
		'".$crypto->decode($_SESSION['consolidated_pay'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['festival_loan'.$emp_id_pk],4)."',
		'".$crypto->decode($_SESSION['festival_loan_cause'.$emp_id_pk],4)."','0',
		'".$requisition_type."','0','".$crypto->decode($_SESSION['hra_deduc'.$emp_id_pk],4)."',$emp_desig,
		'".$emp_desig_fetch[0]['emp_group']."', '".$emp_desig_fetch[0]['emp_pay_scale']."', '".$emp_desig_fetch[0]['emp_pay_band']."',
		'2')");
		
		
		
		
		
		
		if($ins){
			/*$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1)
													AND emp_id_fk = '".$emp_id_pk."'");*/
													
			$f=2;	
					
				pg_query("commit"); 
			//	$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '.$crypto->decode($_SESSION['tchname'.$emp_id_pk],4).' has been successfully saved.</strong></div>';
				unset($_SESSION['tchname'.$emp_id_pk]);
				unset($_SESSION['accountno'.$emp_id_pk]);
				unset($_SESSION['bank_ifsc'.$emp_id_pk]);
				unset($_SESSION['salary_source'.$emp_id_pk]);
				unset($_SESSION['code'.$emp_id_pk]);
				unset($_SESSION['teacher_id_pk'.$emp_id_pk]);
				unset($_SESSION['basic'.$emp_id_pk]);
				unset($_SESSION['pay_in_band'.$emp_id_pk]); 
				unset($_SESSION['grade_pay'.$emp_id_pk]);
				unset($_SESSION['da'.$emp_id_pk]);
				unset($_SESSION['interim_relief'.$emp_id_pk]);
				unset($_SESSION['hra'.$emp_id_pk]);
				unset($_SESSION['ma'.$emp_id_pk]);
				unset($_SESSION['conveyance_allowance'.$emp_id_pk]);
				unset($_SESSION['hill_allowance'.$emp_id_pk]);
				unset($_SESSION['cpf'.$emp_id_pk]);
				unset($_SESSION['gross_salary'.$emp_id_pk]);
				unset($_SESSION['gpf'.$emp_id_pk]);
				unset($_SESSION['pfl'.$emp_id_pk]);
				unset($_SESSION['cpf_deduct'.$emp_id_pk]);
				unset($_SESSION['ptax'.$emp_id_pk]);
				unset($_SESSION['itax'.$emp_id_pk]);
				unset($_SESSION['net_salary'.$emp_id_pk]);
				unset($_SESSION['ovd'.$emp_id_pk]);
				unset($_SESSION['cooperative_loan'.$emp_id_pk]);
				unset($_SESSION['hbl_loan'.$emp_id_pk]);
				unset($_SESSION['festival_loan'.$emp_id_pk]);
				unset($_SESSION['cooperative_loan_cause'.$emp_id_pk]);
				unset($_SESSION['hbl_loan_cause'.$emp_id_pk]);
				unset($_SESSION['festival_loan_cause'.$emp_id_pk]);
				unset($_SESSION['ropa_status'.$emp_id_pk]);
				
				
				//exit(0);
		}else{
			$f=5;
			
				pg_query("rollback");
				//$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Salary Requisition Not Saved. Please try again.</strong></div>';
				unset($_SESSION['tchname'.$emp_id_pk]);
				unset($_SESSION['accountno'.$emp_id_pk]);
				unset($_SESSION['bank_ifsc'.$emp_id_pk]);
				unset($_SESSION['salary_source'.$emp_id_pk]);
				unset($_SESSION['code'.$emp_id_pk]);
				unset($_SESSION['teacher_id_pk'.$emp_id_pk]);
				unset($_SESSION['basic'.$emp_id_pk]);
				unset($_SESSION['pay_in_band'.$emp_id_pk]); 
				unset($_SESSION['grade_pay'.$emp_id_pk]);
				unset($_SESSION['da'.$emp_id_pk]);
				unset($_SESSION['interim_relief'.$emp_id_pk]);
				unset($_SESSION['hra'.$emp_id_pk]);
				unset($_SESSION['ma'.$emp_id_pk]);
				unset($_SESSION['conveyance_allowance'.$emp_id_pk]);
				unset($_SESSION['hill_allowance'.$emp_id_pk]);
				unset($_SESSION['cpf'.$emp_id_pk]);
				unset($_SESSION['gross_salary'.$emp_id_pk]);
				unset($_SESSION['gpf'.$emp_id_pk]);
				unset($_SESSION['pfl'.$emp_id_pk]);
				unset($_SESSION['cpf_deduct'.$emp_id_pk]);
				unset($_SESSION['ptax'.$emp_id_pk]);
				unset($_SESSION['itax'.$emp_id_pk]);
				unset($_SESSION['net_salary'.$emp_id_pk]);
				unset($_SESSION['cooperative_loan'.$emp_id_pk]);
				unset($_SESSION['hbl_loan'.$emp_id_pk]);
				unset($_SESSION['festival_loan'.$emp_id_pk]);
				unset($_SESSION['cooperative_loan_cause'.$emp_id_pk]);
				unset($_SESSION['hbl_loan_cause'.$emp_id_pk]);
				unset($_SESSION['festival_loan_cause'.$emp_id_pk]);
				unset($_SESSION['ropa_status'.$emp_id_pk]);
			
				//exit(0);
		}
	}

	
	$sal_emp_count = $db->fetch_table("SELECT count(emp_id_pk) as emp_cnt 
FROM prd_employee_master as prd_emp
inner join prd_location_master_panchayat_samiti ps on ps.ps_id_pk=prd_emp.ps_id_fk
									WHERE
									prd_emp.emp_status=1 and ps.ps_id_pk='".$_SESSION['location']['ps_id']."'
									AND prd_emp.ropa_status = '2'
									");
										
										
								
										
	$sal_saved = $db->fetch_table("SELECT count(emp_id_fk) as sal_cnt FROM 
	                                              prd_employee_master as prd_emp
						      INNER JOIN prd_employee_salary_save as sal on prd_emp.emp_id_pk=sal.emp_id_fk
											WHERE
											         prd_emp.emp_status=1
												 and sal.status_flag=1
												 and sal.delete_status=1
												 and sal.is_saved=1
												 and sal.salary_monthyear='".date('Ym')."'
												 and sal.ps_id_fk='".$_SESSION['location']['ps_id']."'
												 AND sal.ropa_status='2'
										");				
									
						
	echo json_encode(array($f,count($sal_saved),$sal_emp_count[0]['emp_cnt'],$sal_saved[0]['sal_cnt']));
 }
 
?>