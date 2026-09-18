<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$str=$_SESSION['user_info']['stake_user'];
$state10=substr($str,0,4);
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$dise = !empty($_GET['zp_id'])?$crypto->decode($_GET['zp_id'],3):$_SESSION['location']['district_id'];


		//---------------------------------Gread Pay Fun----------------------------------
		function func_gradepay($val){
			$db = new database();
			$arr = $db->fetch_table("select grade_amount from prd_dise_gradepay_master where grade_code='$val'");
			return $arr[0]['grade_amount'];
		}
		//---------------------------------Salary Type Fun----------------------------------
		function salaryType($sal_type){
			$db = new database();
			$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}
		//--------------------------------All Type Deduction fun----------------------------------
		function getAmount($dise,$empcd,$type,$basic){
			$db = new database();
			
			/////////////////////////////----CPF----////////////////////////
			if($type=='cpf'){
				$arr = $db->fetch_table("select cpf from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['cpf']){
					return $arr[0]['cpf'];
				}else{
					return 0;
				}
			}
			/////////////////////////////----GPF----////////////////////////
			if($type=='gpf'){
				$arr = $db->fetch_table("select gpf from prd_employee_salary_save where status_flag=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['gpf']){
					return $arr[0]['gpf'];
				}else{
					
					$gpf_amt = ($basic/100)*6;
					return round($gpf_amt);
				}
			}
			/////////////////////////////----PF LOAN----////////////////////////
			if($type=='pfl'){
				$arr = $db->fetch_table("select pf_loan from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['pf_loan']){
					return $arr[0]['pf_loan'];
				}else{
					return 0;
				}
			}
			/////////////////////////////----I-Tax----////////////////////////
			if($type=='itax'){
				
				$arr = $db->fetch_table("select i_tax from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['i_tax']){
					return $arr[0]['i_tax'];
				}else{
					return 0;
				}
			}
			/////////////////////////////----OVERDRAWN----////////////////////////
			if($type=='ovd'){
				$arr = $db->fetch_table("select overdrawn from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['overdrawn']){
					return $arr[0]['overdrawn'];
				}else{
					return 0;
				}
			}
			/////////////////////////////----GSLI----////////////////////////
			if($type=='gsli'){
				$arr = $db->fetch_table("select gsli from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['gsli']){
					return $arr[0]['gsli'];
				}else{
					return 0;
				}
			}
		}
		//--------------------------------P-TAX fun----------------------------------
				function empPtax($amount){
			$db = new database();
			$arr=$db->fetch_table("select mn_amount,mx_amount,ptax_amount from psemp_ptax_deduction as prd_ded
									INNER JOIN psemp_ptax_order_file as prd_od
									ON prd_od.ptax_orderfile_pk=prd_ded.ptax_order_id_fk
									where mn_amount <= '$amount' and mx_amount >= '$amount'
									and active_status='1'");
			return $arr[0]['ptax_amount'];
		}
		   
		 //--------------------------------Pay in Pay Band & Gread Pay fun----------------------------------
		function getEmpAmount($type,$dise,$emp_id_pk){
	$db = new database();
	/////////////////////////////----Pay in pay band----////////////////////////
	if($type == 'pay_in_pay_band'){
	$arr = $db->fetch_table("select emp_pay_in_payband from prd_employee_master where emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$dise."'");
		if($arr[0]['emp_pay_in_payband']){
			return $arr[0]['emp_pay_in_payband'];
		}else{
			return 0;
		}
	}
	
	/////////////////////////////----Gread Pay----////////////////////////
	if($type == 'grade_pay'){
	//$arr = $db->fetch_table("select emp_grade_pay from prd_employee_master where empcd='".$empcd."' AND ps_id_fk='".$dise."'");
	$arr = $db->fetch_table("select emp_grade_pay,grade_amount from prd_employee_master as emp
	INNER JOIN prd_dise_gradepay_master as gd 
	ON trim(emp.emp_grade_pay)=gd.grade_code
	where emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$dise."'");
		if($arr[0]['grade_amount']){
			 $arr[0]['grade_amount']; 
			return $arr[0]['grade_amount'];
		}else{
			return 0;
		}
	}
}
		//-------------------------------- fun End----------------------------------
		$tch_final = array();
		$tch_final = $db->fetch_table("
								SELECT
									zp_id_fk
								FROM
									prd_employee_salary_save
								WHERE 
									(status_flag = 2 OR status_flag = 3 OR status_flag = 4) AND category_id=1 AND delete_status=1 AND
									ps_id_fk = '".$dise."' and salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'
								");
	
	if(count($tch_final) == 0 ){
		$tch = array();
		
		//Added New Start
		$employee_for_promotion_increment = $db->fetch_table("SELECT 
															tch.emp_id_pk,
															tch.emp_pay_in_payband
															
															FROM
															prd_employee_master as tch
															
															WHERE
															tch.ps_id_fk = '".$dise."'
															AND (tch.emp_status='1' OR tch.emp_status='9')");	
																
																		
		//print_r($employee_for_promotion_increment);
		foreach($employee_for_promotion_increment as $employees_for_promotion_increment)
		{
			//Promotion Start
			$promotion_data = $db->fetch_table("SELECT emp_grade_pay,
												increment_type,
												dop_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay,
												emp_pay_in_payband,
												pre_emp_desig,
												cas_type
												FROM prd_employee_promotion_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND approval_status in('4') AND delete_status in('1')");
																			
			//print_r($promotion_data);
			if(count($promotion_data) > 0)
			{
				if($promotion_data[0]['cas_type'] == 0)
				{
					//$emp_desig_part1 = ', emp_desig';
					//$emp_desig_part2 = $promotion_data[0]['emp_desig'];
					$update_emp_desig = ", emp_desig = '".$promotion_data[0]['emp_desig']."'";
				}
				else
				{
					//$emp_desig_part1 = ', emp_desig';
					//$emp_desig_part2 = $promotion_data[0]['pre_emp_desig'];
					$update_emp_desig = "";
				}
				
				$promotion_eff_date = $promotion_data[0]['effective_date'];
				$get_promotion_eff_month_year = explode("-", $promotion_eff_date);
				$promotion_eff_yearmonth = $get_promotion_eff_month_year[0].$get_promotion_eff_month_year[1];
				if((date("Ym") >= $promotion_eff_yearmonth))    //Date of promotion
				{																								
					if(($promotion_data[0]['dop_doi'] == 4) && ($promotion_data[0]['effective_date'] == $promotion_data[0]['annual_increment_date']))
					{	
							$round_promotional_increment = $promotion_data[0]['emp_pay_in_payband'];
					
						/*if($employee_master_archive_delete)
						{*/
							$update_employee_data = $db->update("UPDATE prd_employee_master 
																SET emp_pay_in_payband = '".$round_promotional_increment."',
																emp_pay_scale = '".$promotion_data[0]['emp_pay_scale']."',
																emp_pay_band = '".$promotion_data[0]['emp_pay_band']."',
																emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."'
																".$update_emp_desig."
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																");
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE prd_employee_promotion_details
															SET approval_status='6'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND approval_status in('4') AND delete_status in('1')");
							}
						//}
					}
					else if(($promotion_data[0]['dop_doi'] == 3) && ($promotion_data[0]['annual_increment_date'] != $promotion_data[0]['effective_date']))
					{
						//$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
						/*if($employee_master_archive_delete)
						{*/
							$update_employee_data = $db->update("UPDATE prd_employee_master 
																SET emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."',
																emp_pay_scale = '".$promotion_data[0]['emp_pay_scale']."',
																emp_pay_band = '".$promotion_data[0]['emp_pay_band']."'
																".$update_emp_desig."
																
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																");
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE prd_employee_promotion_details
															SET approval_status='5'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND approval_status in('4') AND delete_status in('1')");
							}
						//}
					}
					else if(($promotion_data[0]['dop_doi'] == 5) && ($promotion_data[0]['annual_increment_date'] != $promotion_data[0]['effective_date']))
					{
						//$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
							$round_promotional_increment = $promotion_data[0]['emp_pay_in_payband'];
					
						/*if($employee_master_archive_delete)
						{*/
							$update_employee_data = $db->update("UPDATE prd_employee_master 
																SET emp_pay_in_payband = '".$round_promotional_increment."',
																emp_pay_scale = '".$promotion_data[0]['emp_pay_scale']."',
																emp_pay_band = '".$promotion_data[0]['emp_pay_band']."',
																emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."'
																".$update_emp_desig."
																
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																");
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE prd_employee_promotion_details
															SET approval_status='6'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND approval_status in('4') AND delete_status in('1')");
							}
						//}
					}
				}
			}
			//Promotion End
			
			//Increments for DOI start
			$promotion_increment_data = $db->fetch_table("SELECT 
												dop_doi,
												effective_date,
												annual_increment_date,
												increment_amount,
												emp_pay_in_payband
												FROM prd_employee_promotion_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND approval_status in('5')
												AND delete_status in('1')
												AND dop_doi in('3')");
			if(count($promotion_increment_data) > 0)
			{
				
				$get_pro_inc_eff_month_year	= explode("-", $promotion_increment_data[0]['annual_increment_date']);
				$pro_inc_eff_month_year = $get_pro_inc_eff_month_year[0].$get_pro_inc_eff_month_year[1];
				if(date("Ym") >= $pro_inc_eff_month_year)
				{
					 $round_promotional_increment2 = $promotion_increment_data[0]['emp_pay_in_payband'];
					
					//$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
					/*if($employee_master_archive_delete)
					{*/
						$update_employee_data = $db->update("UPDATE prd_employee_master 
															SET emp_pay_in_payband = '".$round_promotional_increment2."'
															
															where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
															");
						if($update_employee_data)		
						{
							$inactive_promotion_data = $db->update("UPDATE prd_employee_promotion_details
														SET approval_status = '6'
														WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
														AND approval_status in('5')
														AND delete_status in('1')
														AND dop_doi in('3')");
						}
					//}
				}
			}
			//Increments for DOI End
		}
		//Added New End
		
		$tch= $db->fetch_table("
								SELECT 
										tch.emp_id_pk,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_system_code,
										tch.emp_pay_in_payband,
										tch.emp_grade_pay,
										tch.emp_pay_band,
										tch.emp_spouse_hra,
										tch.emp_diff_able,
										tch.emp_spouse_res,
										tch.emp_bank_name,
										tch.emp_acc_no,
										tch.emp_ifsc_no,
										tch.emp_id_pk,
										tch.empcd,
										emp_retirement_date,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.ps_id_fk,
										tch.emp_diff_able,
										tch.emp_first_join_date,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status,
										tch.zp_id_fk
										
									FROM
										prd_employee_master as tch
									WHERE
											tch.zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND tch.emp_status in('1','9')
											");
		
		
$sal_saved = $db->fetch_table("SELECT save.empcd,prd_emp.emp_desig,save.emp_id_fk FROM prd_employee_salary_save 
											as save 
											INNER JOIN prd_employee_master as prd_emp
											ON save.emp_id_fk=prd_emp.emp_id_pk
											WHERE
												save.status_flag = 1 AND save.delete_status=1 AND save.category_id=1 
												AND save.is_saved=1 
												AND save.ps_id_fk = '".$dise."' AND (prd_emp.emp_status=1 or prd_emp.emp_status=9)
												AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'
										");
	$tch_count = count($sal_saved);
		
	if(count($tch)){
				$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM psemp_admin_paychange
							WHERE flag = 'TRUE';
						");
						
			if(count($paychange)>0){			
			$da_per = $paychange[0]['paychange_da'];
			$max_ma = $paychange[0]['paychange_ma'];
			$hra_per = $paychange[0]['paychange_hra'];
			$cpf_per = $paychange[0]['paychange_cpf'];
			$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
			$hill_allowance_per = $paychange[0]['hill_allowance'];
			}
			else{
			$da_per = 0;
			$max_ma = 0;
			$hra_per = 0;
			$cpf_per = 0;
			$conveyance_allowance_max = 0;
			$hill_allowance_per = 0;
				
			}
		}

?>
			<style>
				#wait{
					display: none;
				}
				.headRow > div{
					 text-align:center !important;
				}
				#divcol > div{
					text-align:center !important;
				}
			</style>
           
<table width="100%">

            <tr>
            <th></th>
            <th colspan="8"><strong>PAY & ALLOWANCE</strong></th>
            
            <th colspan="1"></th>
            <th></th>
            <th colspan="9"><strong>DEDUCTION</strong></th>
            <th></th>
            <th colspan="2"><strong>ACTION</strong></th>
            </tr>
            <tr>
               <th style="width: 5%;">SL NO.</th>
            <th>EMPLOYEE NAME</th>
<!--            <th>Consolidated Pay</th>-->
            <th>PAY IN <br>PAY BAND</th>
            <th>GRADE<br>PAY</th>
            <th>DA(<?php echo $da_per; ?>%)</th>
            
            <th>HRA (<?php echo $hra_per; ?>%)</th>
            <th>MA</th>
            <!--<th>C.P.F</th>-->
            <th>CONV<br>ALLOW</th>
             <th>HILL AllOW<span  style="font-size:9px;">(min 15%)</span></th>
             <th>INTERIM RELIEF (<?php echo $interim_relief_per ?>%)</th>
            <th>GROSS<br>SALARY</th>
            <th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
            
            <th>PF LOAN RECOVERY</th>
            <!--<th>CPF</th>-->
            <th>P.Tax</th>
            <th>I.Tax</th>
            <th>GSLI</th>
	    	<th>HRA DEDUCTION</th>
            <th>OVER<br>DRAWN</th>
           <!-- <th>Co-operative Loan Recovery</th>
             <th>HBL Recovery</th>-->
            <th>FESTIVAL ADVANCE RECOVERY</th>
            <th>TOTAL LOAN DEDUCTION</th>
            <th>NET SALARY</th>
            <th>EDIT</th>
            <th>SAVE</th>
            </tr>

			<?php
			 
			if(count($tch)){
				$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM psemp_admin_paychange
							WHERE flag = 'TRUE';
						");
			if(count($paychange)>0){			
			$da_per = $paychange[0]['paychange_da'];
			$max_ma = $paychange[0]['paychange_ma'];
			$hra_per = $paychange[0]['paychange_hra'];
			$cpf_per = $paychange[0]['paychange_cpf'];
			$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
			$hill_allowance_per = $paychange[0]['hill_allowance'];
			}
			else{
			$da_per = 0;
			$max_ma = 0;
			$hra_per = 0;
			$cpf_per = 0;
			$conveyance_allowance_max = 0;
			$hill_allowance_per = 0;
				
			}
			?>
			
            
            
            <?php $count = 1; 
			foreach ($tch as $key) {
				
				$empcd = $key['empcd'];
				$emp_id_pk=$key['emp_id_pk'];
			
				$sal_chk = $db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, save.ps_id_fk, save.empcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day, gsli,consolidated_pay,other_deduction,festival_loan,festival_loan_cause,save.interim_relief,save.hra_deduction from 
       prd_employee_master inner join
prd_employee_salary_save save on emp_id_pk=emp_id_fk
where emp_status=1 and status_flag=1 AND delete_status=1 AND salary_monthyear='".date('Ym')."'  AND emp_id_fk='$emp_id_pk' AND save.ps_id_fk='".$key['ps_id_fk']."' AND requisition_type='".$requisition_type."'");
	   

	   
	   

	        
	   
	        //---------- Start Gpf=0 before retirement--------------------
			 $retirement_date=$key['emp_retirement_date'];
			 $date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date))); 	
			 $emp_first_join_date=$key['emp_first_join_date'];
			 $emp_first_join_match_date=date('Y-m-30', strtotime('+12 month',strtotime($emp_first_join_date)));
		//----------- End Gpf=0 before retirement ---------------------
	   
				if($sal_chk[0]['ps_id_fk'] && $sal_chk[0]['empcd']){ 
				
				if($key['emp_desig']=='1120'){
					$consolidated_pay=$sal_chk[0]['consolidated_pay'];	
					$pay_in_band = 0;
					$grade_pay = 0;
					$basic = 0;
					$da = 0;
					$hra = 0;
					$ma = 0;
					$interim_relief=0;
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					$gross_salary = $sal_chk[0]['gross_salary'];
					$gpf = 0;
					$pfl = 0;
					$cpf_deduct = 0;
					$ptax = $sal_chk[0]['p_tax'];
					$itax = 0;
					$gsli =0;
					$hra_deduction=0;
					
					/*$operative_loan=0;
					$hbl_loan=0;
					$festival_loan=0;*/
					//$cooperative_loan= $sal_chk[0]['cooperative_loan']; 
					//$hbl_loan= $sal_chk[0]['hbl_loan']; 
					$festival_loan= $sal_chk[0]['festival_loan']; 
					$overdrawn = $sal_chk[0]['overdrawn'];
				    //$other_deduction= $sal_chk[0]['$other_deduction']; 
					$net_salary = $sal_chk[0]['net'];
				}
				else
				{ 
				if($sal_chk[0]['salary_type']=='1'){
					$promotion_data_cal = $db->fetch_table("SELECT emp_grade_pay,
												emp_id_fk,
												increment_type,
												dop_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												emp_pay_in_payband,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay
												FROM prd_employee_promotion_details
												WHERE emp_id_fk ='".$emp_id_pk."'
												AND promotion_effective_status in('1') 
												AND approval_status in(5,6)");
											
		$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
		$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
		$pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
		
		 $emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
		$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
		$emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
		$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
		$emp_effective_year=substr ($emp_effective_date_for_promotion_cal,0,4);
		$emp_effective_month=substr ($emp_effective_date_for_promotion_cal,5,2);
		$max_days_of_current_month = date(t);
		$dop_doi = $promotion_data_cal[0]['dop_doi'];
		
		$promotion_eff_date = explode("-",$promotion_data_cal[0]['effective_date']);
		$promotion_eff_yr_mnth = $promotion_eff_date[0].$promotion_eff_date[1];
		
		 if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $promotion_eff_yr_mnth == date("Ym"))
		 {
			
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 //$part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 //$part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $full_grade_pay = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $full_pay_band = $key['emp_pay_in_payband'];
			 $full_basic = $full_pay_band+$full_grade_pay;
			 $full_da = round(($full_basic/100)*$da_per);
			 $full_interim_relief = $key['interim_relief'];
		 }
		 else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5)   && $promotion_eff_yr_mnth == date("Ym"))
		 { 
		
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 
			 $full_grade_pay = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $full_pay_band = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days;
			 $full_basic = $full_pay_band+$full_grade_pay;
			 $full_da = round(($full_basic/100)*$da_per);
			 $full_interim_relief = $key['interim_relief'];
			 
		 }
		 else
		 {
						   
				 	$emp_suspend=$db->fetch_table("
								SELECT 
										sus.slno,
										sus.suspend_effect_date,
										sus.suspend_withdrawn_date,
										sus.pencentage_basic,
										sus.suspend_start_date
										
									FROM
										prd_suspend_dts as sus
										INNER JOIN prd_employee_master   em1 on sus.emp_id_fk=em1.emp_id_pk
									WHERE
											sus.ps_id_fk = '".$_SESSION['location']['ps_id']."' and sus.emp_id_fk='$emp_id_pk' and em1.emp_status='9' and sus.delete_status='0'
		                      ");
							  
					 $suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
					 
				     if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						  $full_pay_in_band_sus =getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
						  $full_pay_band = round(($full_pay_in_band_sus*$pencentage_basic)/100);
						  $full_grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						  $full_grade_pay=round(($full_grade_pay_sus*$pencentage_basic)/100);
						  $full_basic = $full_pay_in_band_sus+$full_grade_pay_sus;
						  $full_basic_sus = $full_pay_band+$full_grade_pay;
						  $full_da_sus = round(($full_basic/100)*$da_per);
						  $full_da = round(($full_basic_sus/100)*$da_per);
						  $full_interim_relief =0;
						 
				      
			          }else{
						  
					     $full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
				             $full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
			                     $full_basic = $full_pay_band+$full_grade_pay;
					     $full_da = round(($full_basic/100)*$da_per);
					     $full_interim_relief = $key['interim_relief'];
				       }
				
				 }
					 $consolidated_pay=0;
				
	                             if($key['emp_spouse_res']=='251'){
						$full_hra = 0; 		
					}else{
						if($key['emp_spouse_hra']=='0' || $key['emp_spouse_hra']=='' || !$key['emp_spouse_hra']){
							$hra_emp = ($full_basic/100)*$hra_per;
							if($hra_emp > 6000){
								$full_hra = 6000;
							}else{
								$full_hra = round($hra_emp);
							}
						}else if($key['emp_spouse_hra'] >= 6000){
							//echo "HRA";
							$full_hra = 0;
						}else if($key['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
							$hra_emp = ($full_basic/100)*$hra_per;
							//echo $hra_emp;
							if($hra_emp >= 6000){
								$valid_hra  = (6000-$key['emp_spouse_hra']);
								$full_hra = round($valid_hra);
							}else{
								$mix_hra = $hra_emp+$key['emp_spouse_hra'];
								if($mix_hra > 6000){
									
										if($key['emp_spouse_hra'] > $hra_emp){
											$valid_hra = 6000-$key['emp_spouse_hra'];
											
											if($valid_hra>$hra_emp){
												$valid_hra = $hra_emp;
											}
											$full_hra = round($valid_hra);
										}else if($key['emp_spouse_hra'] <= $hra_emp){
											//$valid_hra = $hra_emp-$tch[0]['spouse_hra'];
											$valid_hra = 6000-$key['emp_spouse_hra'];
											if($valid_hra>$hra_emp){
												$valid_hra=$hra_emp;
											}
											$full_hra = round($valid_hra);
										}
									
								}else{
									$full_hra = round($hra_emp);
								}
							}
						}
					}
					
				
					
					//$full_hra=$hra;
				
					if($key['spouse_medical_allowance']=='1'){
						
						$full_ma = 0;
					}else{
						$full_ma = $max_ma;
					}
					
					
				if($key['emp_diff_able']=='1'){
					if($key['conv_allow_status']==1)
					{
					 $cal_ma=round(($full_basic*5)/100); 
					if($cal_ma>=400){
						
						$full_conveyance_allowance = $conveyance_allowance_max;
						}
						else{
						 $full_conveyance_allowance=round($cal_ma);
						}
					}
					else{
								$full_conveyance_allowance = 0;
						 }
				}
				else{
					$full_conveyance_allowance = 0;
				}	
				
			
					if($state10=='3219'){
						$hill_p = ($full_pay_band/100)*$hill_allowance_per;
						$hill_g = ($full_grade_pay/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500){
							 $full_hill_allowance = 1500;
						}else{
						 $full_hill_allowance = $hill_allowance_amt;
						}
					}else{
						 $full_hill_allowance = 0;
					}
					$pay_in_band =$full_pay_band;
					$grade_pay =$full_grade_pay;
					$basic=$full_basic;
					$interim_relief=$full_interim_relief;
					$hill_allowance = $full_hill_allowance; 
					$da=$full_da;
					$hra=round($full_hra);
					$ma=round($full_ma);
				    $conveyance_allowance=$full_conveyance_allowance;
					
					
					$full_gross_salary =round($pay_in_band+$grade_pay+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance);
					
					if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
				{
					$full_gpf=0;
				}	
				else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
			          {
				      $full_gpf=0;
			          }
			          else
			              {
			     	       if($sal_chk[0]['gpf']!='')
					       {
					       $full_gpf = $sal_chk[0]['gpf']; 
						 
					       }
					        else{
						    $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);
					        }
						  }
				
					
					 if($key['emp_diff_able']=='1')
					 {
					
					 $full_ptax = 0;
				    }else{
						 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0){
							
						$full_gross_salary_sus =round($full_pay_in_band_sus+$full_grade_pay_sus+$full_da_sus+$interim_relief+$hra+$ma+$conveyance_allowance);
					
					    $full_ptax = empPtax($full_gross_salary_sus);
					  }
					  else{
						  $full_ptax = empPtax($full_gross_salary);
					  }
					
					
					}
					
			if($sal_chk[0]['i_tax'])
					{
					$full_itax=$sal_chk[0]['i_tax'].'';
					}else{
					$full_itax = $sal_chk[0]['i_tax'];
					}
					
					 $gpf=$full_gpf;
					$ptax=$full_ptax;
					$gross_salary=$full_gross_salary;
					$itax=$full_itax;
					$gsli = $sal_chk[0]['gsli'];
					$pfl = $sal_chk[0]['pf_loan'];
					$hra_deduction=$sal_chk[0]['hra_deduction'];
					$overdrawn = $sal_chk[0]['overdrawn'];
				        $festival_loan = $sal_chk[0]['festival_loan']; 
					$total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction;
				        $full_net_salary = $full_gross_salary-$total_deduct;
					$net_salary=$full_net_salary;
				}
					if($sal_chk[0]['salary_type']=='2'){
				
					$pay_in_band = $sal_chk[0]['pay_payband'];
					$consolidated_pay=0;
					$grade_pay = $sal_chk[0]['tch_grade_pay'];
					$basic = $sal_chk[0]['basic'];
					$da = $sal_chk[0]['da'];
					$hra = $sal_chk[0]['hra'];
					$interim_relief = $sal_chk[0]['interim_relief'];
					$ma = $sal_chk[0]['ma'];
					$conveyance_allowance = $sal_chk[0]['conv_allow'];
					$hill_allowance = $sal_chk[0]['hill_allowance'];
					$cpf = $sal_chk[0]['cpf'];
					$gross_salary = $sal_chk[0]['gross_salary'];
					$gpf = $sal_chk[0]['gpf'];
					$pfl = $sal_chk[0]['pf_loan'];
					$cpf_deduct = $sal_chk[0]['cpf_deduct'];
					$ptax = $sal_chk[0]['p_tax'];
					$itax = $sal_chk[0]['i_tax'];
					$gsli = $sal_chk[0]['gsli'];
					$hra_deduction=$sal_chk[0]['hra_deduction'];
					$overdrawn = $sal_chk[0]['overdrawn']; 
					$festival_loan = $sal_chk[0]['festival_loan'];
				        $net_salary = $sal_chk[0]['net'];
					}
					if($sal_chk[0]['salary_type']=='8'){
				        $pay_in_band = $sal_chk[0]['pay_payband'];
					$consolidated_pay=0;
					$grade_pay = $sal_chk[0]['tch_grade_pay'];
					$basic = $sal_chk[0]['basic'];
					$da = $sal_chk[0]['da'];
					$hra = $sal_chk[0]['hra'];
					$interim_relief = $sal_chk[0]['interim_relief'];
					$ma = $sal_chk[0]['ma'];
					$conveyance_allowance = $sal_chk[0]['conv_allow'];
					$hill_allowance = $sal_chk[0]['hill_allowance'];
					$cpf = $sal_chk[0]['cpf'];
					$gross_salary = $sal_chk[0]['gross_salary'];
					$gpf = $sal_chk[0]['gpf'];
					$pfl = $sal_chk[0]['pf_loan'];
					$cpf_deduct = $sal_chk[0]['cpf_deduct'];
					$ptax = $sal_chk[0]['p_tax'];
					$itax = $sal_chk[0]['i_tax'];
					$gsli = $sal_chk[0]['gsli'];
					$hra_deduction=$sal_chk[0]['hra_deduction'];
					$overdrawn = $sal_chk[0]['overdrawn']; 
					$festival_loan = $sal_chk[0]['festival_loan'];
				        $net_salary = $sal_chk[0]['net'];
					}
					
				}
				}else{
					

				$sal_save=	$db->fetch_table("select ps_id_fk, empcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, hra_deduction,
       overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause,interim_relief  from prd_employee_salary_save 
	   where
	    status_flag=3 AND delete_status=1 AND salary_monthyear='".date('Ym',strtotime('-1 month'))."' AND ps_id_fk='".$key['ps_id_fk']."' 
		AND emp_id_fk='$emp_id_pk' AND requisition_type='".$requisition_type."'");	
	   
	 	//Added New Start
		$promotion_data_cal = $db->fetch_table("SELECT emp_grade_pay,
												emp_id_fk,
												increment_type,
												dop_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												emp_pay_in_payband,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay
												FROM prd_employee_promotion_details
												WHERE emp_id_fk ='".$emp_id_pk."'
												AND promotion_effective_status in('1') 
												AND approval_status in(5,6)");
												
		$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
		$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
		$pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
		
		$emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
		$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
		$emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
		$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
		$max_days_of_current_month = date(t);
		$dop_doi = $promotion_data_cal[0]['dop_doi'];
		$promotion_eff_date = explode("-",$promotion_data_cal[0]['effective_date']);
		$promotion_eff_yr_mnth = $promotion_eff_date[0].$promotion_eff_date[1];
		
		if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $promotion_eff_yr_mnth == date("Ym"))
		 {
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 //$part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 //$part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
		 }
		else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5)  && $promotion_eff_yr_mnth == date("Ym"))
		 { 
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 
			 $count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $count_days_total_pay_pay_band_for_promotion = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days;
		 }
		 else
		 {
			 $count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
			 $count_days_total_grade_pay_for_promotion = func_gradepay($key['emp_grade_pay']);
		 }
		//Added New End
	   	
	   	$emp_suspend=$db->fetch_table("
								SELECT 
										sus.slno,
										sus.suspend_effect_date,
										sus.suspend_withdrawn_date,
										sus.pencentage_basic,
										sus.suspend_start_date
										
									FROM
										prd_suspend_dts as sus
										INNER JOIN prd_employee_master   em1 on sus.emp_id_fk=em1.emp_id_pk
									WHERE
											sus.ps_id_fk = '".$_SESSION['location']['ps_id']."' and sus.emp_id_fk='$emp_id_pk' and em1.emp_status='9' and sus.delete_status='0'
		                      ");
							  
					 $suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
				
	   

										
				if($key['emp_desig']=='1120'){
					
					$consolidated_pay=$key['emp_cosolidated_pay'];	
					$pay_in_band = 0;
					$grade_pay = 0;
					$basic = 0;
					$bas=$consolidated_pay+$grade_pay;
					$da = 0;
					$interim_relief=0;
					$hra = 0;
					$ma = 0;
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					//$gross_salary = round($basic+$da+$hra+$ma+$conveyance_allowance+$cpf);
					$gpf = 0;
					$pfl = 0;
					$cpf_deduct = 0;
					$hra_deduction=0;
					$gross_salary = round($bas+$da+$hra+$ma+$interim_relief+$conveyance_allowance+$cpf+$hill_allowance);
					if($key['emp_diff_able']=='1'){
					$ptax = 0;
					}else{
						$ptax = empPtax($gross_salary);
					}
					$itax = 0;
					$gsli =0;
					$overdrawn = getAmount($dise,$empcd,'ovd','');
					//$cooperative_loan = 0;
				   // $hbl_loan = 0;
				    $festival_loan = 0;
					
				
					$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction;
					$net_salary = $gross_salary-$total_deduct;
				}
				else
				{	
							
					 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						  //$pay_in_band_sus = $key['emp_pay_in_payband'];
						  $pay_in_band_sus = $count_days_total_pay_pay_band_for_promotion;
						  $pay_in_band = round(($pay_in_band_sus*$pencentage_basic)/100);
						  //$grade_pay_sus = func_gradepay($key['emp_grade_pay']);
						  $grade_pay_sus = $count_days_total_grade_pay_for_promotion;
						  $grade_pay=round(($grade_pay_sus*$pencentage_basic)/100);
						  $basic = round($pay_in_band_sus+$grade_pay_sus);
						  $basic_sus =round($pay_in_band+$grade_pay);
						  $da_sus = round(($basic/100)*$da_per);
						  $da = round(($basic_sus/100)*$da_per);
						  $interim_relief =0;
						 
				     
			          }else{
						  
					     //$pay_in_band = $key['emp_pay_in_payband'];
						 $pay_in_band = $count_days_total_pay_pay_band_for_promotion;
						 //$grade_pay = func_gradepay($key['emp_grade_pay']);
						 $grade_pay = $count_days_total_grade_pay_for_promotion;
						 $basic = $pay_in_band+$grade_pay;
						 $da = round(($basic/100)*$da_per);
						 $interim_relief = $key['interim_relief'];
						 	  /* if($sal_save[0]['interim_relief']!='') {
				           $interim_relief = $sal_save[0]['interim_relief'];
					         }
					         else{
				             $interim_relief = $key['interim_relief'];
					         }*/
						 
					 	 }
				
				
				
				
				
				$consolidated_pay=0;
				//$cooperative_loan=0;
				//$hbl_loan=0;
				$festival_loan=0;
					/*$operative_loan = $sal_chk[0]['operative_loan'];
						$hbl_loan = $sal_chk[0]['hbl_loan'];
						$festival_loan = $sal_chk[0]['festival_loan'];*/
				
				
			
			if($key['emp_spouse_res']=='251'){
						 $hra = 0; 
					}else{
						if($key['emp_spouse_hra']=='0' ||$key['emp_spouse_hra']=='' || !$key['emp_spouse_hra']){
							$hra_emp = ($basic/100)*$hra_per;
							if($hra_emp > 6000){
								$hra = 6000;
							}else{
								$hra = round($hra_emp);
							}
  }else if($key['emp_spouse_hra'] >= 6000){
							//echo "HRA";
							$hra = 0;
						}
							else if($key['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
							$hra_emp = ($basic/100)*$hra_per;
							
							if($hra_emp >= 6000){
								$valid_hra  = (6000-$key['emp_spouse_hra']);
								$hra = round($valid_hra);
							
						}/*else{
							$hra_emp = (($basic/100)*$hra_per)+$key['emp_spouse_hra'];
							if($hra_emp > 6000){
								$valid_hra  = (6000-$key['emp_spouse_hra']);
								$hra = round($valid_hra);
							}
*/							else{
								$mix_hra = $hra_emp+$key['emp_spouse_hra'];
						if($mix_hra > 6000){
	                  if($key['emp_spouse_hra'] > $hra_emp){
							
							//$valid_hra = $key['spouse_hra']-$hra_emp;
											$valid_hra = 6000-$key['emp_spouse_hra'];
							                 if($valid_hra>$hra_emp){
												$valid_hra=$hra_emp;
											   }
							               
							$hra = round($valid_hra);
							}else if($key['emp_spouse_hra'] <= $hra_emp){
							$valid_hra = 6000-$key['emp_spouse_hra'];
							if($valid_hra>$hra_emp){
												$valid_hra=$hra_emp;
											}
								$hra = round($valid_hra);
										}
									}else{
									$hra = round($hra_emp);
								}
							}
						}
							}
					
				if($key['spouse_medical_allowance']=='1'){
					$ma = 0;
				}else{
					$ma = $max_ma;
				}
				
				if($key['emp_diff_able']=='1'){
					if($key['conv_allow_status']=='1')
					{
					 $cal_ma=round(($basic*5)/100);
					if($cal_ma>=400){
						$conveyance_allowance = $conveyance_allowance_max;
						}
						else{
							$conveyance_allowance=round($cal_ma);
						}
					}
					else{
								$conveyance_allowance = 0;
						 }
				}else{
					$conveyance_allowance = 0;
				}
				
				
				if($state10=='3219'){
						$hill_p = ($pay_in_band/100)*$hill_allowance_per;
						$hill_g = ($grade_pay/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500){
							 $hill_allowance = 1500;
						}else{
						 $hill_allowance = round($hill_allowance_amt);
						}
					}else{
						 $hill_allowance = 0;
					}
					
				$cpf = getAmount($dise,$empcd,'cpf','');
				
			    //$gross_salary = round($pay_in_band+$grade_pay+$da+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
				//Deduction part
					
				if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
				{
					 $gpf=0;
					
				}	
				
				     else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
			          {
				       $gpf=0;
			          }
			          else
			              {
			     	        if($sal_save[0]['gpf']!=0)
					        {
					        $gpf=$sal_save[0]['gpf'];	
					        }
					        else{
						   $gpf = getAmount($dise,$empcd,'gpf',$basic);
					        }
						  }
			
			
				if($sal_save[0]['pf_loan']){
					$pfl=$sal_save[0]['pf_loan'];
					}else{
					$pfl = getAmount($dise,$empcd,'pfl','');
					}
				$cpf_deduct = $cpf*2;
				
				
				
				
				
				
				
				
				if($sal_save[0]['i_tax']){
					 $itax=$sal_save[0]['i_tax'].'';
					//echo 1;
					}else{
					 $itax = getAmount($dise,$empcd,'itax','').'';
					//echo 2;
					}
					//die;
					if($sal_save[0]['gsli']){
					$gsli=$sal_save[0]['gsli'];
					
					}else{
					$gsli = getAmount($dise,$empcd,'gsli','');
					}
				$hra_deduction=0;	
				$overdrawn = getAmount($dise,$empcd,'ovd','');
				
			    $total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$hra_deduction;
				$net_salary = $gross_salary-$total_deduct;
				
				$gross_salary = round($pay_in_band+$grade_pay+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
				
				
				if($key['conv_allow_status']=='1'){
					$ptax = 0;
				}else{
					  if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0){
					 $gross_salary_sus=round($pay_in_band+$grade_pay+$da_sus+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
					   $ptax = empPtax($gross_salary_sus);
					  }
					  else{
						   $ptax = empPtax($gross_salary);
					  }
					
					
				}
				$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$reduct1+$reduct2+$reduct3+$hra_deduction;
				$net_salary = $gross_salary-$total_deduct;
				}
				}
				
				if(($cooperative_loan)>0)
				{
					$cooperative_loan_cause=1;	
				}
				else
				{
					$cooperative_loan_cause=0;	
				}
				
				if(($hbl_loan)>0)
				{
					$hbl_loan_cause=1;	
				}
				else
				{
					$hbl_loan_cause=0;	
				}
				
				if(($festival_loan)>0)
				{
					$festival_loan_cause=1;	
				}
				else
				{
					$festival_loan_cause=0;	
				}
				
				
				$_SESSION['tchname'.$emp_id_pk] = $crypto->encode($key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'],4);
				$_SESSION['bankname'.$emp_id_pk] = $crypto->encode($key['emp_bank_name'],4);
				$_SESSION['accountno'.$emp_id_pk] = $crypto->encode($key['emp_acc_no'],4);
				$_SESSION['bank_ifsc'.$emp_id_pk] = $crypto->encode($key['emp_ifsc_no'],4);
				$_SESSION['salary_source'.$emp_id_pk] = $crypto->encode($key['salary_source'],4);
				$_SESSION['code'.$emp_id_pk] = $crypto->encode($key['emp_system_code'],4);
				$_SESSION['emp_id_pk'.$emp_id_pk] = $crypto->encode($key['emp_id_pk'],4);
				if($key['rank']){
					$_SESSION['rank'.$emp_id_pk] = $crypto->encode($key['rank'],4);
				}else{
					$_SESSION['rank'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['basic'.$emp_id_pk] = $crypto->encode($basic,4);
				if($pay_in_band!=0){ 
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($pay_in_band,4);
				}else{
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode(0,4);
					//$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}
				if($consolidated_pay!=0){
				$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}else{
					
					$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode(0,4);
					//$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}
				if($grade_pay){ 
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode($grade_pay,4);
				}else{
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['da'.$emp_id_pk] = $crypto->encode($da,4);
				$_SESSION['interim_relief'.$emp_id_pk] = $crypto->encode($interim_relief,4);
				$_SESSION['hra'.$emp_id_pk] = $crypto->encode($hra,4);
				$_SESSION['ma'.$emp_id_pk] = $crypto->encode($ma,4);
				$_SESSION['conveyance_allowance'.$emp_id_pk] = $crypto->encode($conveyance_allowance,4);
				$_SESSION['hill_allowance'.$emp_id_pk] = $crypto->encode($hill_allowance,4);
				$_SESSION['cpf'.$emp_id_pk] = $crypto->encode($cpf,4);
				$_SESSION['gross_salary'.$emp_id_pk] = $crypto->encode($gross_salary,4);
				$_SESSION['gpf'.$emp_id_pk] = $crypto->encode($gpf,4);
				$_SESSION['pfl'.$emp_id_pk] = $crypto->encode($pfl,4);
				$_SESSION['cpf_deduct'.$emp_id_pk] = $crypto->encode($cpf_deduct,4);
				$_SESSION['ptax'.$emp_id_pk] = $crypto->encode($ptax,4);
				$_SESSION['itax'.$emp_id_pk] = $crypto->encode($itax,4);
				$_SESSION['hra_deduc'.$emp_id_pk] = $crypto->encode($hra_deduction,4);
				$_SESSION['ovd'.$emp_id_pk] = $crypto->encode($overdrawn,4);
				$_SESSION['gsli'.$emp_id_pk] = $crypto->encode($gsli,4);
				$_SESSION['festival_loan'.$emp_id_pk] = $crypto->encode($festival_loan,4); 
				$_SESSION['festival_loan_cause'.$emp_id_pk] = $crypto->encode($festival_loan_cause,4);
				$_SESSION['net_salary'.$emp_id_pk] = $crypto->encode($net_salary,4); 
				
				
				
				
				//For Loan Deduction Calcution Start
				$loan_deductions = $db->fetch_table("SELECT installment_amount,no_of_installment, counter,reminder_amount FROM prd_loan_deduction WHERE status in (2) AND approval_status in (3) AND emp_id_fk='".$emp_id_pk."'");
				 $total_loan_deduction = 0;
				 //echo $sal_type_for_leave;
				//if($sal_type_for_leave != '8')    //THIS IS FOR NO SALARY FOR LEAVE TAKEN 
				//{
					foreach($loan_deductions as $deductions)
					{
						if($deductions['counter'] < $deductions['no_of_installment'])
						{
							 $total_loan_deduction = $total_loan_deduction + $deductions['installment_amount'];
						}
						else
						{
							$total_loan_deduction = $total_loan_deduction + $deductions['reminder_amount'];
						}
					}
				//}
				//else
				//{  
					 //$total_loan_deduction = 0;				//THIS IS FOR NO SALARY FOR LEAVE TAKEN 
				//}
				//echo  $total_loan_deduction;
				$total_deduct = $total_deduct+$total_loan_deduction;
				if($sal_chk[0]['salary_type']!='8' && $sal_chk[0]['salary_type']!='2') // by nirupam on 28_03_2017 for no salary and part salary not showing the net salary
				{
				$net_salary = $gross_salary-$total_deduct; // comment by nirupam on 29_03_2017 for full salary showing the net salary wrong
				}
				$_SESSION['total_loan_deduction'.$emp_id_pk] = $crypto->encode($total_loan_deduction,4);
				$_SESSION['net_salary'.$emp_id_pk] = $crypto->encode($net_salary,4); 
				
				$_SESSION['salary_type'.$emp_id_pk] = $crypto->encode($sal_type_for_leave,4);
				$_SESSION['part_day'.$emp_id_pk] = $crypto->encode($insert_val_for_working_days,4); 
				$_SESSION['no_salary_cause'.$emp_id_pk] = $crypto->encode($insert_no_salary_cause,4); 
				$_SESSION['part_salary_cause'.$emp_id_pk] = $crypto->encode($insert_part_salary_cause,4); 
				if($arrear_amount != '')
				{			
					$_SESSION['total_arrear_deduction'.$emp_id_pk] = $crypto->encode($arrear_amount,4);
				}
				else
				{
					$_SESSION['total_arrear_deduction'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['suspend_salary_status'.$emp_id_pk] = $crypto->encode($suspend_salary_status,4);
				//For Loan Deduction Calcution End
			?>
  
            
            <tr>
            <td id="show"><?php echo $count; ?></td>
            <td><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?></td>
<!--            <td><?php echo round($consolidated_pay); ?></td>-->
            <td><?php echo round($pay_in_band); ?></td>
           
            <td><?php echo round($grade_pay); ?></td>
            <td><?php echo round($da); ?></td>
            <td><?php echo round($hra); ?></td>
            <td><?php echo $ma; ?></td>
            <td><?php echo $conveyance_allowance; ?></td>
             <td><?php echo round($hill_allowance); ?></td>
             <td><?php echo round($interim_relief); ?></td>
            <td><?php echo round($gross_salary); ?></td>
            <td><?php echo $gpf;  ?></td>
          	<td><?php echo $pfl; ?></td>
          <!-- <td><?php //echo round($cpf_deduct); ?></td>-->
            <td><?php echo $ptax; ?></td>
            <td><?php echo $itax; ?></td>
            <td><?php echo $gsli; ?></td> 
	       <td><?php echo $hra_deduction; ?></td> 
            <td><?php echo $overdrawn; ?></td>
           
            <!--<td><?php //if($cooperative_loan){echo $cooperative_loan;} else {echo 0;} ?></td>
             <td><?php //if( $hbl_loan){echo $hbl_loan;} else {echo 0;} ?></td>-->
             <td><?php if($festival_loan){echo $festival_loan;} else {echo 0;} ?></td>
              <td class="loan"><a data-toggle="modal" data-target=".bs-example-modal-lg-loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> ">
				<?php 
			
				echo $total_loan_deduction; 
				//echo "0";
				//echo $emp_id_pk;
				 ?></a></td>
            <td><?php echo round($net_salary); ?></td>
            
            <input type="hidden" name="zp_id" id="zp_id" value="<?php echo $crypto->encode($key['zp_id_fk'],4) ?>" />
            
            <td class="edit"><a data-toggle="modal" data-target=".bs-example-modal-lg" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit"></a></td>
            
            

            
               
                <?php
				
                	$saved = 0;
				 
                	for($i =0 ; $i < $tch_count ; $i++){
						
						if($key['emp_id_pk'] == $sal_saved[$i]['emp_id_fk'])
						
						{
							$saved = 1; 
							
				?>
				<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit"></td>
				<?php }
                	}
					 
				if($saved == 0){ ?>
               
                                      
                 
				 <td class="save" id="save"><div class="save_id<?=$count?>"><a emp_id_pk="<? echo $crypto->encode($key['emp_id_pk'],4) ?>"empcd="<?php echo $crypto->encode( $key['empcd'],4) ?>"sec_tok="<?=$enc_token?>" name="<?= $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'] ?>" show="<?=$count?>"><img id="img_id<?=$count?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Edit"></a>
                 </div></td>
                
				<?php //$count=$count+1;
				}
					//echo $sal_saved[1]['empcd'];
					
                ?>
           
            <?php $count += 1 ; ?></tr> <? } } else {?> <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
            </table>
        
         <style>
  #save
{
    cursor: pointer;
}
         .finalize{
         	text-align: center;
         	margin-left: 370px;
         }
         .finalize ul {
				list-style-type:none;
				margin:0;
				padding:0;
				overflow:hidden;
			}
			.finalize li {
				float:left;
			}
			.finalize a:link, .finalize a:visited {
				display:block;
				width:133px;
				font-weight:bold;
				color:#FFFFFF;
				text-align:center;
				height:32px;
				text-decoration:none;
				text-transform:uppercase;
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
			}
			.finalize a:hover, .finalize a:active {
				
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
				background-position: 0px 32px;
			}
         </style>
        
      
            <div class="form-group" id="sal_save" style="display:none;">
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <a href="<?= $config['base_url'] ?>page/intra_ps/da/sal_requisition/ajax_ps_rq_finalize.php" class="btn btn-success btn-sm">Salary Finalize</a>
            </div>
          </div>
          
            <?php 
		 if(count($tch) == count($sal_saved)){ 
         	if(count($tch)){
         	?>
            <div class="form-group" id="sal_save" >
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <a href="<?= $config['base_url'] ?>page/intra_ps/da/sal_requisition/ajax_ps_rq_finalize.php" class="btn btn-success btn-sm">Salary Finalize</a>
            </div>
          </div>
         	
		<?php } }}  else { ?>
            <script>
					$('.emplist').css('display','none');
					 //$("#wait").css("display", "none");
					window.setTimeout(function(){
							window.location.replace("<?php echo $config['base_url'] . 'page/intra_ps/da/sal_requisition/view_ps_salary_requisition.php'; ?>");
					},3000);
            </script>
			
			<?php } ?>
	   
<!-----------------------------------------------------------------MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 1000px;margin-left: -5.5%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Salary Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<!--For total loan deduction-->
<div class="modal fade bs-example-modal-lg-loan" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 850px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="loanmbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<!--End of For total loan deduction-->

<script>
		$(document).ready(function(){
		var tch=(<?=count($tch)?>);
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
		  $(".edit a").click(function() {	
                  //var link = $(this).attr('href');
		var link = $(this).attr('id');
		var link1= $("#zp_id").val();
		
		$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_requisition_edit.php?id='+link+'&zp_id='+link1, function(data){                    
		$("#mbody").html(data);
		       });
		    });
			
		// for loan details
		
		 $(".loan a").click(function() {
				 //alert('gg');	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//alert(link);
		var link1= $("#gp_id").val();
		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_emp_loan_details_vew.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_emp_loan_details_vew.php?id='+link+'&mu_id='+link1, function(data){
				// alert(data);
				 $("#loanmbody").html(data);
		       });
		    });
		
		// end of for loan details
		
		$(".save a").click(function() {	
		$("#sal_save").hide();
		var emp_id_pk = $(this).attr('emp_id_pk');
		var name=$(this).attr('name');
		var empcd = $(this).attr('empcd');
		var sec_tok = $(this).attr('sec_tok');
		var show = $(this).attr('show');
		//alert(show);
		
		$.post('<?= $config['base_url'] ?>page/intra_ps/da/sal_requisition/ajax_ps_rq_save.php?emp_id_pk='+emp_id_pk+'&empcd='+empcd+'&sec_tok='+sec_tok, function(data){
	//alert(data);
		var result = $.parseJSON(data);
		//alert(result);
				//alert(result[0]);
				//alert(result[1]);
				var sal_save=result[1];
				/*alert(sal_save);
				alert(tch);*/
				//$("#mbody").html(data);
				if(parseInt(result[2])==parseInt(result[3]))
				{
					$("#sal_save").show();
					
					}
					else{
						$("#sal_save").hide();
						}
				if(data==1)
				{ 
			$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.<strong></div>');
				}
				
				 if(result[0]==2)
				{ 
					$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '+name+' has been successfully saved.</strong></div>');
					$('.save_id'+show+'').html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit">');
					
				}
					
					if(result[0]==3)
				{
					$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Salary Requisition Not Saved. Please try again.</strong></div>');
				}
				
				 if(result[0]==5)
				{
					$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Salary Requisition Not Saved. Please try again.</strong></div>');
					}
					
					
					
					
		       });
		    });
			
			
			
			
		$("#finalize").click(function() {	
		//alert('in function');
		$.post('<?= $config['base_url'] ?>page/intra_ps/da/sal_requisition/ajax_ps_rq_finalize.php', function(data){
				//alert(data);
				 //$("#mbody").html(data);
		       });
		    });
			
		});
       </script>
 
<!-----------------------------------------------------------------MODAL END---------------------------------------------------->
