<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$f=0;

$crypto = new cryptography();
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$dise = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
$empcd = $crypto->decode($_GET['empcd'],4);
$emp_id_pk=$crypto->decode($_GET['emp_id_pk'],4);
$zpcd = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['district_id'];

$sec_time_token=$_REQUEST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);

if($sec_time_token!=$enc_session)
{
	echo $f=1;
}
else
{
	
	$arr = $db->fetch_table("SELECT pay_payband 
							FROM prd_employee_salary_save 
							WHERE status_flag=1 AND delete_status=1  
							AND zp_id_fk='".$_SESSION['location']['district_id']."' AND salary_monthyear='".date('Ym')."' 
							AND emp_id_fk='$emp_id_pk' AND requisition_type='".$requisition_type."'");
	
	if($arr[0]['pay_payband'])
	{
		$upd = $db->update("UPDATE prd_employee_salary_save 
							SET is_saved=1  
							WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND delete_status=1 AND emp_id_fk='$emp_id_pk' 
							AND emp_id_fk='$emp_id_pk' 
							AND requisition_type='".$requisition_type."' AND salary_monthyear='".date('Ym')."'");
		if($upd)
		{
			/*$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
			SET promotion_effective_status = '2'
			WHERE promotion_effective_status in(1)
			AND emp_id_fk = '".$emp_id_pk."'");*/	
			
			$f=2;				
			pg_query("commit");
		}
		else
		{
			$f=3;
			pg_query("rollback");
		}
	}
	else
	{
	
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$salary_monthyear = date('Ym');
		
		/*$pay_in_band=$crypto->decode($_SESSION['pay_in_band'.$emp_id_pk],4);
		$grade_pay=$crypto->decode($_SESSION['grade_pay'.$emp_id_pk],4);
		$da=$crypto->decode($_SESSION['da'.$emp_id_pk],4);
		$hra=$crypto->decode($_SESSION['hra'.$emp_id_pk],4);
		$ma=$crypto->decode($_SESSION['ma'.$emp_id_pk],4);
		$conv_allow=$crypto->decode($_SESSION['conveyance_allowance'.$emp_id_pk],4);
		$hill_allow=$crypto->decode($_SESSION['hill_allowance'.$emp_id_pk],4);
		$interim_relief=$crypto->decode($_SESSION['interim_relief'.$emp_id_pk],4);
		$gpf=$crypto->decode($_SESSION['gpf'.$emp_id_pk],4);
		$pf_loan=$crypto->decode($_SESSION['pfl'.$emp_id_pk],4);
		$p_tax=$crypto->decode($_SESSION['ptax'.$emp_id_pk],4);
		$i_tax=$crypto->decode($_SESSION['itax'.$emp_id_pk],4);
		$gsli=$crypto->decode($_SESSION['gsli'.$emp_id_pk],4);
		$overdrawn=$crypto->decode($_SESSION['ovd'.$emp_id_pk],4);
		$festival_val=$crypto->decode($_SESSION['festival_loan'.$emp_id_pk],4);
		$gross=$crypto->decode($_SESSION['gross_salary'.$emp_id_pk],4);
		$net=$crypto->decode($_SESSION['net_salary'.$emp_id_pk],4);
		$total_allowance=$pay_in_band+$grade_pay+$da+$hra+$ma+$conv_allow+$hill_allow+$interim_relief;
		$total_deductions=$gpf+$pf_loan+$p_tax+$i_tax+$gsli+$overdrawn+$festival_val;*/
		
	

		
		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, zp_id_fk, empcd, 
		bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
		i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
		spl_alo, status_flag, salary_monthyear, 
		category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
		hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,overdrawn, salary_type, cause,part_day,gsli,delete_status,consolidated_pay,festival_loan,festival_loan_cause,interim_relief,requisition_type,gp_id_fk,hra_deduction)
		VALUES ('".$crypto->decode($_SESSION['rank'.$emp_id_pk],4)."', 'now()', '$user_ip', '".$zpcd."', '$empcd', 
		'".$crypto->decode($_SESSION['bankname'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['accountno'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['basic'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['da'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['hra'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ma'.$emp_id_pk],4)."', '0', '".$crypto->decode($_SESSION['pfl'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', 
		'".$crypto->decode($_SESSION['itax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['net_salary'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['bank_ifsc'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['salary_source'.$emp_id_pk],4)."', '0', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."','0', '1', '$salary_monthyear','1', '0', '".$crypto->decode($_SESSION['emp_id_pk'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['pay_in_band'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['grade_pay'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['hill_allowance'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['gpf'.$emp_id_pk],4)."', '0', '".$crypto->decode($_SESSION['gross_salary'.$emp_id_pk],4)."', '1', '".$crypto->decode($_SESSION['conveyance_allowance'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['ovd'.$emp_id_pk],4)."','1','','','".$crypto->decode($_SESSION['gsli'.$emp_id_pk],4)."','1','".$crypto->decode($_SESSION['consolidated_pay'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['festival_loan'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['festival_loan_cause'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['interim_relief'.$emp_id_pk],4)."','".$requisition_type."','0','".$crypto->decode($_SESSION['hra_deduc'.$emp_id_pk],4)."')");

//		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, zp_id_fk,  
//							bankname, accountno, basic, da, hra, ma, cpf, p_tax, 
//							i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
//							spl_alo, status_flag, salary_monthyear, 
//							category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
//							hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,overdrawn, 
//							salary_type, cause,gp_code,part_day,gsli,delete_status,consolidated_pay,festival_loan,
//							festival_loan_cause,interim_relief,requisition_type,total_loan_deduction,gp_id_fk,ps_id_fk,empcd)
//							VALUES ('".$crypto->decode($_SESSION['rank'.$emp_id_pk],4)."', 'now()', '$user_ip', '".$zpcd."',  
//							'".$crypto->decode($_SESSION['bankname'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['accountno'.$emp_id_pk],4)."', 
//							'".$crypto->decode($_SESSION['basic'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['da'.$emp_id_pk],4)."', 
//							'".$crypto->decode($_SESSION['hra'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['ma'.$emp_id_pk],4)."', 
//							'0', '".$crypto->decode($_SESSION['ptax'.$emp_id_pk],4)."', 
//							'".$crypto->decode($_SESSION['itax'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['net_salary'.$emp_id_pk],4)."', 
//							'".$crypto->decode($_SESSION['bank_ifsc'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['salary_source'.$emp_id_pk],4)."',
//							 '0', '0', '".$crypto->decode($_SESSION['code'.$emp_id_pk],4)."',
//							 '0', '1', '$salary_monthyear','1', '0', 
//							 '".$crypto->decode($_SESSION['emp_id_pk'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['pay_in_band'.$emp_id_pk],4)."', 
//							 '".$crypto->decode($_SESSION['grade_pay'.$emp_id_pk],4)."', '".$crypto->decode($_SESSION['hill_allowance'.$emp_id_pk],4)."', 
//							 '".$crypto->decode($_SESSION['gpf'.$emp_id_pk],4)."', '0', '".$crypto->decode($_SESSION['gross_salary'.$emp_id_pk],4)."', 
//							 '1', '".$crypto->decode($_SESSION['conveyance_allowance'.$emp_id_pk],4)."','".$crypto->decode($_SESSION['ovd'.$emp_id_pk],4)."',
//							 '1','','0','','".$crypto->decode($_SESSION['gsli'.$emp_id_pk],4)."',
//							 '1','".$crypto->decode($_SESSION['consolidated_pay'.$emp_id_pk],4)."',
//							 '".$crypto->decode($_SESSION['festival_loan'.$emp_id_pk],4)."',
//							 '".$crypto->decode($_SESSION['festival_loan_cause'.$emp_id_pk],4)."',
//							 '".$crypto->decode($_SESSION['interim_relief'.$emp_id_pk],4)."','".$requisition_type."',
//							 '".$crypto->decode($_SESSION['total_loan_deduction'.$emp_id_pk],4)."','0','0','0')");
		
		
		if($ins)
		{
			/*$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
			SET promotion_effective_status = '2'
			WHERE promotion_effective_status in(1)
			AND emp_id_fk = '".$emp_id_pk."'");*/
			$f=2;	
			
			pg_query("commit"); 
			
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
			unset($_SESSION['total_loan_deduction'.$emp_id_pk]);
			//unset($_SESSION['emp_bonus'.$emp_id_pk]);

		}
		else
		{
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
			unset($_SESSION['total_loan_deduction'.$emp_id_pk]);
			//unset($_SESSION['emp_bonus'.$emp_id_pk]);
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
										INNER JOIN prd_location_master_district gp 
										on gp.district_id_pk=prd_emp.zp_id_fk
										WHERE
										prd_emp.emp_status in (1,9) and gp.district_id_pk='".$_SESSION['location']['district_id']."'
										");
										

	
	
	$sal_saved = $db->fetch_table("SELECT count(emp_id_fk) as sal_cnt FROM prd_employee_salary_save as sal
									WHERE
									sal.status_flag=1
									AND delete_status=1
									AND is_saved=1
									AND salary_monthyear='".date('Ym')."'
									AND zp_id_fk='".$_SESSION['location']['district_id']."' 
									AND requisition_type='".$requisition_type."'
									
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