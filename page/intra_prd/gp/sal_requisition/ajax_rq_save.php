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
	
	$f=0;
	//echo $f;exit;
	$crypto = new cryptography();
	$db = new database();
	
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	$requisition_type=$requisition[0]['code'];
		
	$dise = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
	$empcd = $crypto->decode($_GET['empcd'],4);
	$emp_id_pk=$crypto->decode($_GET['emp_id_pk'],4);
	$gpcd = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
	
	$sec_time_token=$_REQUEST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	 
	if(isset($_SESSION['salary_source']) ==''){
		$salary_source =0;
	} 
	else{
		$salary_source = $crypto->decode($_SESSION['salary_source'.$emp_id_pk],4);
	}
	
	if($sec_time_token!=$enc_session)
	{
		echo $f=1;
	//$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.<strong></div>';
	
	//exit(0);
	}
	else{
	
	$emp_desig_fetch = $db->fetch_table("select emp_desig, emp_group, emp_pay_scale, emp_pay_band from prd_employee_master where emp_id_pk= '$emp_id_pk' AND ropa_status='2' ");
	$emp_desig = $emp_desig_fetch[0]['emp_desig'];
	
	/* echo "select pay_payband from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND gp_id_fk='$gpcd' AND salary_monthyear='".date('Ym')."'";*/

		
	$arr = $db->fetch_table("select count(*) from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND gp_id_fk='$gpcd' AND salary_monthyear='".date('Ym')."' AND emp_id_fk='$emp_id_pk' AND requisition_type='".$requisition_type."' 
	AND ropa_status='2'");
	//echo "select pay_payband from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND gp_id_fk='$gpcd' AND salary_monthyear='".date('Ym')."'";exit;
	//var_dump($arr); die;
	if($arr[0]['count']>=1){
	
		/*echo "UPDATE prd_employee_salary_save SET is_saved=1  WHERE gp_id_fk='$gpcd' AND delete_status=1 AND empcd='$empcd' AND code='".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."'";exit;*/
		$upd = $db->update("UPDATE prd_employee_salary_save SET is_saved=1  WHERE gp_id_fk='$gpcd' AND delete_status=1 AND empcd='$empcd' AND emp_id_fk='$emp_id_pk' AND code='".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."' AND requisition_type='".$requisition_type."' AND salary_monthyear='".date('Ym')."' AND ropa_status='2'");
		if($upd){
		/*	$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1)
													AND emp_id_fk = '".$emp_id_pk."'");	*/
		
	    	$f=2;				
				pg_query("commit");
				//$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '.$crypto->decode($_SESSION['tchname'.$emp_id_pk],4).' has been successfully saved.</strong></div>';
				
				//exit(0);
		}else{
			$f=3;
			pg_query("rollback");
		//	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Salary Requisition Not Saved. Please try again.</strong></div>';
			
			//exit(0);
		}
	}else{
		
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$salary_monthyear = date('Ym');


		
		
		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, gp_id_fk, empcd, 
		bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
		i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
		spl_alo, status_flag, salary_monthyear, category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
		hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,overdrawn, 
		salary_type, cause,gp_code,part_day,gsli,delete_status,consolidated_pay,festival_loan,festival_loan_cause,
		interim_relief,requisition_type, emp_desig, emp_group, emp_pay_scale, emp_pay_band, ropa_status)
		VALUES ('0', 'now()', '$user_ip', '".$gpcd."', '$empcd', 
		'".$crypto->decode($_SESSION['bankname'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['accountno'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['basic'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['da'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['hra'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ma'.$emp_id_pk],4)."', '0', 
		'".$crypto->decode($_SESSION['pfl'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['itax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['net_salary'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['bank_ifsc'.$emp_id_pk],4)."', $salary_source , '0', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."','0', '1', '$salary_monthyear','1', '".$_SESSION['location']['block_code']."', 
		'".$crypto->decode($_SESSION['emp_id_pk'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['pay_in_band'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['grade_pay'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['hill_allowance'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['gpf'.$emp_id_pk],4)."', '0', '".$crypto->decode($_SESSION['gross_salary'.$emp_id_pk],4)."', '1', 
		'".$crypto->decode($_SESSION['conveyance_allowance'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['ovd'.$emp_id_pk],4)."','1','',
		'".$_SESSION['user_info']['stake_user']."','','".$crypto->decode($_SESSION['gsli'.$emp_id_pk],4)."','1',
		'".$crypto->decode($_SESSION['consolidated_pay'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['festival_loan'.$emp_id_pk],4)."',
		'".$crypto->decode($_SESSION['festival_loan_cause'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['interim_relief'.$emp_id_pk],4)."',
		'".$requisition_type."',$emp_desig,'".$emp_desig_fetch[0]['emp_group']."', '".$emp_desig_fetch[0]['emp_pay_scale']."', 
		'".$emp_desig_fetch[0]['emp_pay_band']."', '".$crypto->decode($_SESSION['ropa_status'.$emp_id_pk],4)."')");
		
		
		
		
		
		
		if($ins){
			/*	$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1)
													AND emp_id_fk = '".$emp_id_pk."'"); */
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
/*$sal_emp_count = $db->fetch_table("select 
									count(CASE WHEN save.status_flag = 1 
											AND save.delete_status=1
											AND save.is_saved=1 
											AND save.gp_id_fk = '".$dise."'
											AND salary_monthyear='".date('Ym')."' THEN 1 ELSE null END) as total_save,
											
				count(CASE WHEN  prd_emp.gp_id_fk ='".$dise."' and prd_emp.emp_status in('1','9') THEN 1 ELSE null END) as total_emp

			FROM prd_employee_master as prd_emp 
			LEFT JOIN  prd_employee_salary_save as save on save.emp_id_fk =prd_emp.emp_id_pk
			and prd_emp.emp_status in('1','9') where gp_code='".$_SESSION['user_info']['stake_user']."' and 
									");
					
					
									*/
						$sal_emp_count = $db->fetch_table("SELECT count(emp_id_pk) as emp_cnt 
						FROM
															prd_employee_master as prd_emp
															INNER JOIN prd_location_master_gp gp 
															on gp.gp_id_pk=prd_emp.gp_id_fk
									WHERE
									prd_emp.emp_status in('1','9') and gp.gp_code='".$_SESSION['user_info']['stake_user']."' 
									AND prd_emp.ropa_status='2'
									");
										
										
										
										
	$sal_saved = $db->fetch_table("SELECT count(emp_id_fk) as sal_cnt FROM prd_employee_salary_save as sal
											
											WHERE
												sal.status_flag=1
												AND delete_status=1
												AND is_saved=1
												AND salary_monthyear='".date('Ym')."'
												AND gp_code='".$_SESSION['user_info']['stake_user']."' 
												AND requisition_type='".$requisition_type."'
												AND sal.ropa_status='2'
										");				
									
						

	/*   if($f==1)
	{
	 echo $f;
	
	}
	else if($f==2)
	{ 
		//echo $f;
		
		//$t=count($sal_saved);								
		
	   echo json_encode(array($f,count($sal_saved),$sal_emp_count[0]['total_emp'],$sal_emp_count[0]['total_save']));
		
		}
		else if($f==3)
          {
			
			  echo $f;
			  }
		else if($f==4)
		{
			 echo $f;
			
			} 
			else if($f==5)
			{
			 echo $f;
				}*/
				
		  echo json_encode(array($f,count($sal_saved),$sal_emp_count[0]['emp_cnt'],$sal_saved[0]['sal_cnt']));
 }
 
?>