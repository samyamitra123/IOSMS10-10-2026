<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

$crypto = new cryptography();

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

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
function func_gradepay($val)
{

	$db = new database();
	$arr = $db->fetch_table("select grade_amount from prd_dise_gradepay_master where grade_code='$val'");
	return $arr[0]['grade_amount'];
}
//---------------------------------Salary Type Fun----------------------------------
/*function salaryType($sal_type)
{
	$db = new database();
	$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
	return $arr[0]['salary_type'];
}*/
function getGPF($basic){
	
	$gpf_amt = ($basic/100)*6;
	//echo $gpf_amt; die;
			return round($gpf_amt);
}


/*******************************************************Changed by ANJAN 03/09/2019 **************************************************/
function festival_adv_recovery($emp_id_pk)
{
	
	$db = new database();
	$Query = "SELECT b.festival_advance_instalment_amount,b.festival_advance_instalment_last_amount,
									b.deduction_counter,b.festival_advance_instalment_no 
									FROM prd_festival_advance_employee_details a
									INNER JOIN prd_festival_advance_entry_sal b
									ON a.festival_advance_id_pk=b.festival_advance_id_fk
									WHERE 
									a.festival_advance_status in ('5') AND a.emp_id_fk = '".$emp_id_pk."'  
									AND b.deduction_start_monthyear <= '".date("Ym")."'";
  
	$fa_deduction_data = $db->fetch_table($Query);
	
	
	if(count($fa_deduction_data)>0 &&  $fa_deduction_data[0]['deduction_counter']!=$fa_deduction_data[0]['festival_advance_instalment_no'])
	{
		$festival_advance_instalment_no_chk=$fa_deduction_data[0]['festival_advance_instalment_no']-1;
		
		
		if($fa_deduction_data[0]['deduction_counter']==$festival_advance_instalment_no_chk)
		{
			$fa_instlmnt_amt=$fa_deduction_data[0]['festival_advance_instalment_last_amount'];
		}
		else
		{
			$fa_instlmnt_amt=$fa_deduction_data[0]['festival_advance_instalment_amount'];
		}
										
		if($fa_instlmnt_amt > 0)
		{
			return $fa_instlmnt_amt;
		}
		else
		{
			return 0;	
		}
	}
	else
	{
		return 0;
	}
  

}

/******************************************** END ********************************************************/
//--------------------------------P-TAX fun----------------------------------
function empPtax($amount)
{
	$db = new database();
	$arr=$db->fetch_table("select mn_amount,mx_amount,ptax_amount from prd_ptax_deduction as prd_ded
	INNER JOIN prd_ptax_order_file as prd_od
	ON prd_od.ptax_orderfile_pk=prd_ded.ptax_order_id_fk
	where mn_amount <= '$amount' and mx_amount >= '$amount'
	and active_status='1'");
	return $arr[0]['ptax_amount'];
}

//--------------------------------Pay in Pay Band & Gread Pay fun----------------------------------
function getEmpAmount($type,$dise,$emp_id_pk)
{
		
	$db = new database();
	/////////////////////////////----Pay in pay band----////////////////////////
	if($type == 'pay_in_pay_band')
	{
		$arr = $db->fetch_table("select emp_pay_in_payband from prd_employee_master where emp_id_pk='".$emp_id_pk."' AND zp_id_fk='".$dise."' AND ropa_status='1'");
		if($arr[0]['emp_pay_in_payband'])
		{
			return $arr[0]['emp_pay_in_payband'];
		}
		else
		{
			return 0;
		}
	}
	
	/////////////////////////////----Gread Pay----////////////////////////
	/*if($type == 'grade_pay')
	{
		
		$arr = $db->fetch_table("select emp_grade_pay,grade_amount from prd_employee_master as emp
									INNER JOIN prd_dise_gradepay_master as gd 
									ON trim(emp.emp_grade_pay)=gd.grade_code
									where emp_id_pk='".$emp_id_pk."' AND zp_id_fk='".$dise."'");
		if($arr[0]['grade_amount'])
		{
			$arr[0]['grade_amount']; 
			return $arr[0]['grade_amount'];
		}
		else
		{
			return 0;
		}
	}*/
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
					zp_id_fk = '".$dise."' and salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'
					AND ropa_status = '1'
					");

if(count($tch_final) == 0 )
{
		
	$tch = array();
	
	$employee_for_promotion_increment = $db->fetch_table("SELECT 
													tch.emp_id_pk,
													tch.emp_pay_in_payband,
													tch.emp_pension_status
													FROM
													prd_employee_master as tch
													inner join prd_employee_promotion_details as p
	on p.emp_id_fk=tch.emp_id_pk
													WHERE
													tch.zp_id_fk = '".$dise."' AND (tch.ropa_status = '1' )
													AND (tch.emp_status='1' OR tch.emp_status='9') ");	
	
	if(!empty($employee_for_promotion_increment))
	{
		
	foreach($employee_for_promotion_increment as $employees_for_promotion_increment)
	{
		//Pension Status Variable
		
		if($employees_for_promotion_increment['emp_pension_status']=='0')
		{
			$update_pension='0';
		}
		else if($employees_for_promotion_increment['emp_pension_status']=='1' || $employees_for_promotion_increment['emp_pension_status']=='2')
		{
			$update_pension='2';
		}
		
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
											cas_type,
											ropa_level,
											increment_type
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
				$update_emp_desig = " emp_desig = '".$promotion_data[0]['emp_desig']."'";
			}
			else
			{
				//$emp_desig_part1 = ', emp_desig';
				//$emp_desig_part2 = $promotion_data[0]['pre_emp_desig'];
				$update_emp_desig = " emp_desig = '".$promotion_data[0]['pre_emp_desig']."'";
			}
			
			$promotion_eff_date = $promotion_data[0]['effective_date'];
			$get_promotion_eff_month_year = explode("-", $promotion_eff_date);
			$promotion_eff_yearmonth = $get_promotion_eff_month_year[0].$get_promotion_eff_month_year[1];
			$ropa_level=$promotion_data[0]['ropa_level'];
			$increment_type=$promotion_data[0]['increment_type'];
			if((date("Ym") >= $promotion_eff_yearmonth))    //Date of promotion
			{																								
				if(($promotion_data[0]['dop_doi'] == 4) && ($promotion_data[0]['effective_date'] == $promotion_data[0]['annual_increment_date']))
				{	
					$round_promotional_increment = $promotion_data[0]['emp_pay_in_payband'];
					
					/*if($employee_master_archive_delete)
					{*/
					$update_employee_data = $db->update("UPDATE prd_employee_master 
														SET emp_pension_status='".$update_pension."',
														emp_pay_in_payband = '".$round_promotional_increment."',
														
														ropa_level='".$ropa_level."',
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
														SET 														
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
//				else if(($promotion_data[0]['dop_doi'] == 5) && ($promotion_data[0]['annual_increment_date'] != $promotion_data[0]['effective_date']))
				else if(($promotion_data[0]['dop_doi'] == 5) )
				{
					
					//$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
					 $round_promotional_increment = $promotion_data[0]['emp_pay_in_payband']; 
					
					/*if($employee_master_archive_delete)
					{*/
					$update_employee_data = $db->update("UPDATE prd_employee_master 
														SET emp_pension_status='".$update_pension."',
														emp_pay_in_payband = '".$round_promotional_increment."',
														ropa_level='".$ropa_level."',
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
														emp_pay_in_payband,ropa_level
														FROM prd_employee_promotion_details
														WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
														AND approval_status in('5')
														AND delete_status in('1')
														AND dop_doi in('3')");
		if(count($promotion_increment_data) > 0)
		{
			
			$get_pro_inc_eff_month_year	= explode("-", $promotion_increment_data[0]['annual_increment_date']);
			$pro_inc_eff_month_year = $get_pro_inc_eff_month_year[0].$get_pro_inc_eff_month_year[1];
			$ropa_level=$promotion_increment_data[0]['ropa_level'];
			if(date("Ym") >= $pro_inc_eff_month_year)
			{
				$round_promotional_increment2 = $promotion_increment_data[0]['emp_pay_in_payband'];
				
				//$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
				/*if($employee_master_archive_delete)
				{*/
				$update_employee_data = $db->update("UPDATE prd_employee_master 
													SET emp_pension_status='".$update_pension."',
													emp_pay_in_payband = '".$round_promotional_increment2."',
													ropa_level='".$ropa_level."'
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
							tch.empcd,
							tch.emp_retirement_date,
							tch.emp_desig,
							tch.emp_cosolidated_pay,
							tch.emp_first_join_date,
							tch.interim_relief,
							tch.spouse_medical_allowance,
							tch.conv_allow_status,
							tch.zp_id_fk,
							tch.zp_emp_type,
							tch.ropa_status,
							tch.ropa_level,
							emp_gpf_acc_no,
							emp_gvt_hra_type,
							emp_gvt_hra_ammount,
							ropa_9_emp_pay_in_payband,
							emp_grade_pay
							
							FROM
							prd_employee_master as tch
							WHERE
							tch.zp_id_fk = '".$_SESSION['location']['district_id']."' AND (tch.ropa_status = '1' OR tch.emp_cosolidated_pay!='0')
							AND tch.emp_status in('1') order by tch.emp_first_name
							");
	
	
	$sal_saved = $db->fetch_table("SELECT prd_emp.emp_desig,save.emp_id_fk FROM prd_employee_salary_save 
									as save 
									INNER JOIN prd_employee_master as prd_emp
									ON save.emp_id_fk=prd_emp.emp_id_pk
									WHERE
									save.status_flag = 1 AND save.delete_status=1 
									AND save.is_saved=1 AND save.ropa_status='1'
									AND save.zp_id_fk = '".$dise."' AND prd_emp.emp_status=1
									AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'
									");	
	$tch_count = count($sal_saved);
	
	
	
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
	<div class="table-responsive">
	<table width="100%" id="example">
      <thead>
        <tr>
	    <th></th>
            <th></th>
            <th colspan="1"><strong>PAY & ALLOWANCE</strong></th>
            <th colspan="9"><strong>DEDUCTION</strong></th>
            <th></th>
            <th colspan="2"><strong>ACTION</strong></th>
           
        </tr>
        <tr>
            <th style="width: 5%;">SL NO.</th>
            <th>EMPLOYEE NAME</th>
            <th>GROSS<br>SALARY</th>
            <th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
            <th>P.Tax</th>
            <th>I.Tax</th>
            <th>GSLI</th>
            <th>HRA DEDUCTION/<br>LICENCE FEES</th>
            <th>OVER<br>DRAWN</th>
            <th>FESTIVAL ADVANCE RECOVERY</th>
            <th>TOTAL LOAN DEDUCTION</th>
            <th>OUT OF ACCOUNT DEDUCTION</th>
            <th>NET SALARY</th>
            <th>EDIT</th>
            <th>SAVE</th>
        </tr>
        </thead>
        <tbody>
        
        <?php
        if(count($tch))
		{
			$paychange = $db->fetch_table(" SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
						entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
						paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
						FROM prd_admin_paychange
						WHERE flag = 'TRUE' AND ropa_year = '2019';
						");
			if(count($paychange)>0)
			{			
				$da_per = $paychange[0]['paychange_da'];
				$max_ma = $paychange[0]['paychange_ma'];
				$hra_per = $paychange[0]['paychange_hra'];
				$cpf_per = $paychange[0]['paychange_cpf'];
				$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
				$hill_allowance_per = $paychange[0]['hill_allowance'];
			}
			else
			{
				$da_per = 0;
				$max_ma = 0;
				$hra_per = 0;
				$cpf_per = 0;
				$conveyance_allowance_max = 0;
				$hill_allowance_per = 0;
			}
			
			$count = 1; 
			foreach ($tch as $key) 
			{
				
				$empcd = $key['empcd'];
				$emp_id_pk=$key['emp_id_pk'];
				$Query = "SELECT 
					slno, latestupdate_time, latestupdate_ip_address,  save.empcd, bankname, 
					accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, i_tax, net, bank_ifsc, sal_source, 
					spl_pay, pf_deduct, code, emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
					category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, hill_allowance, gpf, 
					cpf_deduct, gross_salary, is_saved, conv_allow, overdrawn, salary_type, cause,gp_code, 
					part_day, gsli,consolidated_pay,other_deduction,festival_loan,festival_loan_cause,
					save.interim_relief,save.hra_deduction,save.zp_id_fk,save.total_loan_deduction, save.other_loan_deduction,save.allowance
				FROM prd_employee_master 
				INNER JOIN prd_employee_salary_save save  
				ON emp_id_pk=emp_id_fk
				WHERE emp_status=1 and status_flag=1 AND delete_status=1 AND salary_monthyear='".date('Ym')."'  
				AND emp_id_fk='$emp_id_pk' AND save.zp_id_fk='".$key['zp_id_fk']."' AND save.ropa_status='1'
				AND requisition_type='".$requisition_type."'";
				//print_r($Query);exit;
				$sal_chk = $db->fetch_table($Query);
				//print_r($sal_chk); exit;
				//---------- Start Gpf=0 before retirement--------------------
				$retirement_date=$key['emp_retirement_date'];
				
				if($key['zp_emp_type']=='366')
				{
				$date=date('Y-m-d', strtotime('-3 month',strtotime($retirement_date)));
				}
				else
				{
					$date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date)));
				}
				$emp_first_join_date=$key['emp_first_join_date'];
				$emp_first_join_match_date=date('Y-m-30', strtotime('+11 month',strtotime($emp_first_join_date)));
				//----------- End Gpf=0 before retirement ---------------------
				
				
				if($sal_chk[0]['zp_id_fk'])
				{
					if($key['emp_desig']=='1')
					{
						  $pay_in_band = $sal_chk[0]['pay_payband'];
							$consolidated_pay=$sal_chk[0]['consolidated_pay'];
							$grade_pay = $sal_chk[0]['tch_grade_pay'];
							$basic = $sal_chk[0]['basic'];
							$da = $sal_chk[0]['da'];
							$hra = $sal_chk[0]['hra'];
							$interim_relief=0;
							$ma = $sal_chk[0]['ma'];
							$conveyance_allowance = $sal_chk[0]['conv_allow'];
							$allow = $sal_chk[0]['allowance'];
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
							$other_loan_deduction = $sal_chk[0]['other_loan_deduction'];
							$total_loan_deduction=$sal_chk[0]['total_loan_deduction'];
							$net_salary = $sal_chk[0]['net'];
			
					}
					else
					{
						if($sal_chk[0]['salary_type']=='1')
						{
							
							
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
							
									if(count($promotion_data_cal) >0){	
										$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
										$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
										
										
										$emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
										$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
										
										$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
										$emp_effective_year=substr ($emp_effective_date_for_promotion_cal,0,4);
										$emp_effective_month=substr ($emp_effective_date_for_promotion_cal,5,2);
										$max_days_of_current_month = date("t");
										$dop_doi = $promotion_data_cal[0]['dop_doi'];
										
										$promotion_eff_date = explode("-",$promotion_data_cal[0]['effective_date']);
										$promotion_eff_yr_mnth = $promotion_eff_date[0].$promotion_eff_date[1];
									}	
									else{
										$emp_id_for_promotion_part = "";									
										$pre_emp_pay_band_for_promotion_cal = "";
										
										
										$emp_effective_date_for_promotion_cal =  "";
										$emp_pay_band_for_promotion_cal =  "";
										
										$emp_effective_day_for_promotion_cal = "";
										$emp_effective_year="";
										$emp_effective_month="";
										$max_days_of_current_month = "";
										$dop_doi = "";
										
										$promotion_eff_date = "";
										$promotion_eff_yr_mnth = "";
									}	
						
									if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $promotion_eff_yr_mnth == date("Ym"))
									{
										
										$count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
										$count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
										
										 $full_pay_band = $key['emp_pay_in_payband'];
										$full_basic = $full_pay_band;
										
										$full_interim_relief=0;
									}
									else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5)   && $promotion_eff_yr_mnth == date("Ym"))
									{ 
									
										$count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
										$count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
										
										$part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
										
										$part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
										
										$full_pay_band = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days;
										$full_basic = $full_pay_band;
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
															INNER JOIN prd_employee_master  em1 on sus.emp_id_fk=em1.emp_id_pk
															WHERE
															sus.zp_id_fk = '".$_SESSION['location']['district_id']."' 
															and sus.emp_id_fk='$emp_id_pk' and em1.emp_status='9' and sus.delete_status='0'
															");
										
										$suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
										
										if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
										{
											$pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
											$full_pay_in_band_sus =getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
											$full_pay_band = round(($full_pay_in_band_sus*$pencentage_basic)/100);
											//$full_grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
											//$full_grade_pay=round(($full_grade_pay_sus*$pencentage_basic)/100);
											$full_basic = $full_pay_in_band_sus;
											$full_basic_sus = $full_pay_band;
											$full_da_sus = round(($full_basic/100)*$da_per);
											$full_da = round(($full_basic_sus/100)*$da_per);
											$full_interim_relief =0;
										}
										else
										{
											$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
											//$full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
											$full_basic = $full_pay_band;
											$full_da = round(($full_basic/100)*$da_per);
											
											$full_interim_relief=0;
										}
									}
									
									$consolidated_pay=0;
									
									if($key['emp_spouse_res']=='251')
									{
										
										if($key['emp_gvt_hra_type']=='200' || $key['emp_gvt_hra_type']=='202'  )
										{
											$full_hra = 0;
										}
										else if($key['emp_gvt_hra_type']=='201')
										{
											$full_hra =$key['emp_gvt_hra_ammount'];
										}  	
									}
									else
									{
										if($key['emp_spouse_hra']=='0' || $key['emp_spouse_hra']=='' || !$key['emp_spouse_hra'])
										{
											$hra_emp = ($full_basic/100)*$hra_per;
											if($hra_emp > 12000)
											{
												$full_hra = 12000;
											}
											else
											{
												$full_hra = round($hra_emp);
											}
										}
										else if($key['emp_spouse_hra'] >= 12000)
										{
											
											$full_hra = 0;
										}
										else if($key['emp_spouse_hra'] < 12000)
										{
												
											$hra_emp = ($full_basic/100)*$hra_per;
											
											if($hra_emp >= 12000)
											{
												$valid_hra  = (12000-$key['emp_spouse_hra']);
												$full_hra = round($valid_hra);
											}
											else
											{
												$mix_hra = $hra_emp+$key['emp_spouse_hra'];
												if($mix_hra > 12000)
												{
													if($key['emp_spouse_hra'] > $hra_emp)
													{
														$valid_hra = 12000-$key['emp_spouse_hra'];
														if($valid_hra>$hra_emp)
														{
															$valid_hra = $hra_emp;
														}
														$full_hra = round($valid_hra);
													}
													else if($key['emp_spouse_hra'] <= $hra_emp)
													{
														
														$valid_hra = 12000-$key['emp_spouse_hra'];
														if($valid_hra>$hra_emp)
														{
															$valid_hra=$hra_emp;
														}
														$full_hra = round($valid_hra);
													}
												}
												else
												{
													$full_hra = round($hra_emp);
												}
											}
										}
									}
									
									if($key['spouse_medical_allowance']=='1')
									{
										$full_ma = 0;
									}
									else
									{
										$full_ma = $max_ma;
									}
									
									if($key['emp_diff_able']=='1' && $key['zp_emp_type']=='367')
									{
										if($key['conv_allow_status']==1)
										{
											$cal_ma=round(($full_basic*5)/100); 
											if($cal_ma>=400)
											{
												$full_conveyance_allowance = $conveyance_allowance_max;
											}
											else
											{
												$full_conveyance_allowance=round($cal_ma);
											}
										}
										else
										{
											$full_conveyance_allowance = 0;
										}
									}
									
									
									else if($key['emp_diff_able']=='1' && $key['zp_emp_type']=='366')
									{
										 if($key['conv_allow_status']==1)
										{
											$cal_ma=round(($full_basic*5)/100); 
											if($cal_ma>=800)
											{
												$full_conveyance_allowance = 800;
											}
											else
											{
												$full_conveyance_allowance=round($cal_ma);
											}
										}
										else
										{
											$full_conveyance_allowance = 0;
										}
									}
									
									else
									{
										$full_conveyance_allowance = 0;
									}	
									
									if(($state10=='3219' || $state10=='55')&& $key['zp_emp_type']=='366')
									{
										$hill_p = ($full_pay_band/100)*12;
										//$hill_g = ($full_grade_pay/100)*$hill_allowance_per;
										$hill_allowance_amt = $hill_p;
										if($hill_allowance_amt > 2000)
										{
											$full_hill_allowance = 2000;
										}
										else
										{
											$full_hill_allowance = $hill_allowance_amt;
										}
									}
										
										else if(($state10=='3219' || $state10=='55')&& $key['zp_emp_type']=='367')
									{
										$hill_p = ($full_pay_band/100)*$hill_allowance_per;
										//$hill_g = ($full_grade_pay/100)*$hill_allowance_per;
										$hill_allowance_amt = $hill_p;
										if($hill_allowance_amt > 1500)
										{
											$full_hill_allowance = 1500;
										}
										else
										{
											$full_hill_allowance = $hill_allowance_amt;
										}
									}
									else
									{
										$full_hill_allowance = 0;
									}
									$pay_in_band =$full_pay_band;
									//$grade_pay =$full_grade_pay;
									$basic=$full_basic;
									$interim_relief=$full_interim_relief;
									$hill_allowance = $full_hill_allowance; 
									$da=$full_da;
									$hra=round($full_hra);
									$ma=round($full_ma);
									$conveyance_allowance=$full_conveyance_allowance;
									$allow= $sal_chk[0]['allowance'];
									
									$full_gross_salary =round($pay_in_band+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance+$allow);
									
									
									if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
									{
										
										$full_gpf=0; 
									}	
									else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
									{
										
										$full_gpf=0; 
									}
									else if($key['zp_emp_type']=='366' && ($key['emp_gpf_acc_no']=='' || $key['emp_gpf_acc_no']=='0'))
									{
										
										$full_gpf=0;
									}
									else
									{
										if($sal_chk[0]['gpf']!='')
										{
											$full_gpf = $sal_chk[0]['gpf']; 
										}
										else
										{
											/***************************** Changed by ANJAN 09-12-2019 ***********************************/
											//$full_gpf = getAmount($dise,$emp_id_pk,'gpf',$full_basic);
										
											 $ropa =func_gradepay($key['emp_grade_pay']);
											//$ropa_p =$key['ropa_9_emp_pay_in_payband'];
											$ropa_p =$key['emp_pay_in_payband'];
											//$ropa_2009=$ropa+$ropa_p;
											$ropa_2009=$ropa_p;
											$full_gpf = getGPF($ropa_2009);
											//echo  $full_gpf; 
										}
									}
									
									
									if($key['emp_diff_able']=='1')
									{
										$full_ptax = 0;
									}
									else
									{
										if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
										{
											$full_gross_salary_sus=round($full_pay_in_band_sus+$full_da_sus+$interim_relief+$hra+$ma+$conveyance_allowance);
											$full_ptax = empPtax($full_gross_salary_sus);
										}
										else
										{
											$full_ptax = empPtax($full_gross_salary);
										}
									}
									
									if($sal_chk[0]['i_tax'])
									{
										$full_itax=$sal_chk[0]['i_tax'].'';
									}
									else
									{
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
									$other_loan_deduction = $sal_chk[0]['other_loan_deduction']; 
									$total_loan_deduction=$sal_chk[0]['total_loan_deduction'];
									$total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction+$other_loan_deduction;
									$full_net_salary = $full_gross_salary-$total_deduct;
									$net_salary=$full_net_salary;
								}
								
								if($sal_chk[0]['salary_type']=='2')
								{
									$pay_in_band = $sal_chk[0]['pay_payband'];
									$consolidated_pay=0;
									$grade_pay = $sal_chk[0]['tch_grade_pay'];
									$basic = $sal_chk[0]['basic'];
									$da = $sal_chk[0]['da'];
									$hra = $sal_chk[0]['hra'];
									
									$interim_relief=0;
									$ma = $sal_chk[0]['ma'];
									$conveyance_allowance = $sal_chk[0]['conv_allow'];
									$allow=$sal_chk[0]['allowance'];
									$hill_allowance = $sal_chk[0]['hill_allowance'];
									$cpf = $sal_chk[0]['cpf'];
									$gross_salary = $sal_chk[0]['gross_salary'];
									if($key['zp_emp_type']=='366' && ($key['emp_gpf_acc_no']=='' || $key['emp_gpf_acc_no']=='0'))
									{
										$gpf=0;
									}
									else
									{
										$gpf = $sal_chk[0]['gpf'];
									}
									$pfl = $sal_chk[0]['pf_loan'];
									$cpf_deduct = $sal_chk[0]['cpf_deduct'];
									$ptax = $sal_chk[0]['p_tax'];
									$itax = $sal_chk[0]['i_tax'];
									$gsli = $sal_chk[0]['gsli'];
									$hra_deduction=$sal_chk[0]['hra_deduction'];
									$overdrawn = $sal_chk[0]['overdrawn']; 
									$festival_loan = $sal_chk[0]['festival_loan'];
									$other_loan_deduction=$sal_chk[0]['other_loan_deduction'];
									$total_loan_deduction=$sal_chk[0]['total_loan_deduction'];
									$net_salary = $sal_chk[0]['net'];
									
								}
								if($sal_chk[0]['salary_type']=='8')
								{
									$pay_in_band = $sal_chk[0]['pay_payband'];
									$consolidated_pay=0;
									$grade_pay = $sal_chk[0]['tch_grade_pay'];
									$basic = $sal_chk[0]['basic'];
									$da = $sal_chk[0]['da'];
									$hra = $sal_chk[0]['hra'];
									
									$interim_relief=0;
									$ma = $sal_chk[0]['ma'];
									$conveyance_allowance = $sal_chk[0]['conv_allow'];
									$allow=$sal_chk[0]['allowance'];
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
									$total_loan_deduction=$sal_chk[0]['total_loan_deduction'];
									$other_loan_deduction = $sal_chk[0]['other_loan_deduction'];
									$festival_loan = $sal_chk[0]['festival_loan'];
									$net_salary = $sal_chk[0]['net'];
								}
					}
				}
				else
				{
				  
          $Query = "SELECT 
								ps_id_fk, empcd,bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
								i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, emp_salary_id_pk, 
								spl_alo, status_flag, salary_monthyear, category_id, block_code, emp_id_fk, 
								pay_payband, tch_grade_pay, hill_allowance, gpf, cpf_deduct, gross_salary, 
								is_saved, conv_allow, hra_deduction,overdrawn, salary_type, cause,gp_code, 
						part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause,interim_relief,other_loan_deduction ,allowance 
							FROM prd_employee_salary_save 
							WHERE status_flag IN (3,4) AND delete_status=1 
							AND salary_monthyear='".date('Ym',strtotime('-1 month'))."' 
							AND zp_id_fk='".$dise."' AND ropa_status='1'
							AND emp_id_fk='$emp_id_pk' AND requisition_type='".$requisition_type."'";

					$sal_save=	$db->fetch_table($Query);	
					//print_r($sal_save); 
							
							
							
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
																	
				if(count($promotion_data_cal) > 0){	
					$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
					$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
					//$pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
					$emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
					$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
					//$emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
					$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
					$max_days_of_current_month = date("t");
					$dop_doi = $promotion_data_cal[0]['dop_doi'];
					$promotion_eff_date = explode("-",$promotion_data_cal[0]['effective_date']);
					$promotion_eff_yr_mnth = $promotion_eff_date[0].$promotion_eff_date[1];
				}
				else{
					$emp_id_for_promotion_part = "";									
					$pre_emp_pay_band_for_promotion_cal = "";
					//$pre_emp_grade_pay_for_promotion_cal = "";
					$emp_effective_date_for_promotion_cal =  "";
					$emp_pay_band_for_promotion_cal =  "";
					//$emp_grade_pay_for_promotion_cal =  "";
					$emp_effective_day_for_promotion_cal = "";
					$max_days_of_current_month = "";
					$dop_doi = "";
					$promotion_eff_date = "";
					$promotion_eff_yr_mnth = "";
				}
				
					if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $promotion_eff_yr_mnth == date("Ym"))
					{
						$count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
						$count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
						
						//$count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
						$count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
					}
					else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5)  && $promotion_eff_yr_mnth == date("Ym"))
					{ 
						$count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
						$count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
						
						$part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
						
						$part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
						
						 $count_days_total_pay_pay_band_for_promotion = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days; 
					}
					else
					{
						$count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
						
					}
			
					
					$emp_suspend=$db->fetch_table("SELECT 
													sus.slno,
													sus.suspend_effect_date,
													sus.suspend_withdrawn_date,
													sus.pencentage_basic,
													sus.suspend_start_date
													FROM
													prd_suspend_dts as sus
													INNER JOIN prd_employee_master em1 on sus.emp_id_fk=em1.emp_id_pk
													WHERE
													sus.zp_id_fk = '".$_SESSION['location']['district_id']."' 
													and sus.emp_id_fk='$emp_id_pk' and em1.emp_status='9' and sus.delete_status='0'
					");
					
					$suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
					
					if($key['emp_desig']=='1')
					{ 
						$consolidated_pay=$key['emp_cosolidated_pay'];	
						$pay_in_band = 0;
						$grade_pay = 0;
						$basic = 0;
						$bas=$consolidated_pay;
						$da = 0;
						$interim_relief=0;
						$hra = 0;
						$ma = 0;
						$conveyance_allowance = 0;
						$allow=0;
						$hill_allowance = 0;
						$cpf = 0;
						$gpf = 0;
						$pfl = 0;
						$cpf_deduct = 0;
						$hra_deduction=0;
						$gross_salary = round($bas+$da+$hra+$ma+$interim_relief+$conveyance_allowance+$cpf+$hill_allowance+$allow);
						if($key['emp_diff_able']=='1')
						{
							$ptax = 0;
						}
						else
						{
							$ptax = empPtax($gross_salary);
						}
						$itax = 0;
						$gsli =0;
						$other_loan_deduction=0;
						$overdrawn = 0;
						/***************************** Changed by ANJAN 09-12-2019 ***********************************/
						//$overdrawn = getAmount($dise,$emp_id_pk,'ovd','');
						
						$festival_loan = 0;
						$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction+$other_loan_deduction;
						$net_salary = $gross_salary-$total_deduct;
					}
					else
					{
						if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
						{
							$pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
							
							// new  $pay_in_band_sus = $count_days_total_pay_pay_band_for_promotion;
							
							$pay_in_band_sus = 0;
							$pay_in_band = round(($pay_in_band_sus*$pencentage_basic)/100);
							
							// new $grade_pay_sus = $count_days_total_grade_pay_for_promotion;
							$grade_pay_sus = 0;
							$grade_pay=round(($grade_pay_sus*$pencentage_basic)/100);
							$basic = round($pay_in_band_sus);
							$basic_sus =round($pay_in_band);
							$da_sus = round(($basic/100)*$da_per);
							$da = round(($basic_sus/100)*$da_per);
							$interim_relief =0;
						}
						else
						{
							//$pay_in_band = $key['emp_pay_in_payband'];
							
							$pay_in_band = $count_days_total_pay_pay_band_for_promotion;
							
							//$grade_pay = func_gradepay($key['emp_grade_pay']);
							
							 $basic = $pay_in_band; 
							$da = round(($basic/100)*$da_per);
							
							$interim_relief=0;
							
						}
						$consolidated_pay=0;
						
						$fa = $db->fetch_table("SELECT a.emp_id_fk
									FROM prd_festival_advance_employee_details a
									INNER JOIN prd_festival_advance_entry_sal b
									ON a.festival_advance_id_pk=b.festival_advance_id_fk
									WHERE 
									a.festival_advance_status in ('5') AND a.emp_id_fk = '".$emp_id_pk."'  
									AND b.deduction_start_monthyear <= '".date("Ym")."'");
					//	print_r($fa); exit;
									if($fa[0]['emp_id_fk']=='')
									{
										$festival_loan = 0;
									}
									else
									{
										$festival_loan = festival_adv_recovery($emp_id_pk);
									}
						// print_r($festival_loan); exit;
						//$festival_loan = festival_adv_recovery($emp_id_pk);
						
						if($key['emp_spouse_res']=='251')
						{
							
							if($key['emp_gvt_hra_type']=='200' || $key['emp_gvt_hra_type']=='202'  )
							{
								$hra = 0;
						    }
							else if($key['emp_gvt_hra_type']=='201')
							{
								$hra =$key['emp_gvt_hra_ammount'] ;
						    }    
   
						}
						else
						{
							if($key['emp_spouse_hra']=='0' ||$key['emp_spouse_hra']=='' || !$key['emp_spouse_hra'])
							{
								$hra_emp = ($basic/100)*$hra_per;
								if($hra_emp > 12000)
								{
									$hra = 12000;
								}
								else
								{
									$hra = round($hra_emp);
								}
							}
							else if($key['emp_spouse_hra'] >= 12000)
							{
								$hra = 0;
							}
							else if($key['emp_spouse_hra'] < 12000)
							{
									
								$hra_emp = ($basic/100)*$hra_per;
								if($hra_emp >= 12000)
								{
									$valid_hra  = (12000-$key['emp_spouse_hra']);
									$hra = round($valid_hra);
								}
														
								else
								{
									$mix_hra = $hra_emp+$key['emp_spouse_hra'];
									if($mix_hra > 12000)
									{
										if($key['emp_spouse_hra'] > $hra_emp)
										{
											
											$valid_hra = 12000-$key['emp_spouse_hra'];
											if($valid_hra>$hra_emp)
											{
												$valid_hra=$hra_emp;
											}
											$hra = round($valid_hra);
										}
										else if($key['emp_spouse_hra'] <= $hra_emp)
										{
											$valid_hra = 12000-$key['emp_spouse_hra'];
											if($valid_hra>$hra_emp)
											{
												$valid_hra=$hra_emp;
											}
											$hra = round($valid_hra);
										}
									}
									else
									{
										$hra = round($hra_emp);
									}
								}
							}
						}
						
						if($key['spouse_medical_allowance']=='1')
						{
							$ma = 0;
						}
						else
						{
							$ma = $max_ma;
						}
						
						if($key['emp_diff_able']=='1' && $key['zp_emp_type']=='367')
						{
							if($key['conv_allow_status']=='1')
							{
								$cal_ma=round(($basic*5)/100);
								if($cal_ma>=400)
								{
									$conveyance_allowance = $conveyance_allowance_max;
								}
								else
								{
									$conveyance_allowance=round($cal_ma);
								}
							}
							else
							{
								$conveyance_allowance = 0;
							}
						}
						
						
						
						else if($key['emp_diff_able']=='1' && $key['zp_emp_type']=='366')
						{
							if($key['conv_allow_status']=='1')
							{
								$cal_ma=round(($basic*5)/100);
								if($cal_ma>=800)
								{
									$conveyance_allowance = 800;
								}
								else
								{
									$conveyance_allowance=round($cal_ma);
								}
							}
							else
							{
								$conveyance_allowance = 0;
							}
						}
						else
						{
							$conveyance_allowance = 0;
						}
						
						if(($state10=='3219' || $state10=='55')&& $key['zp_emp_type']=='366')
						{
							$hill_p = ($pay_in_band/100)*12;
							//$hill_g = ($grade_pay/100)*$hill_allowance_per;
							$hill_allowance_amt = $hill_p;
							if($hill_allowance_amt > 2000)
							{
								$hill_allowance = 2000;
							}
							else
							{
								$hill_allowance = round($hill_allowance_amt);
							}
						}
						else if(($state10=='3219' || $state10=='55')&& $key['zp_emp_type']=='367')
						{
							$hill_p = ($pay_in_band/100)*$hill_allowance_per;
							//$hill_g = ($grade_pay/100)*$hill_allowance_per;
							$hill_allowance_amt = $hill_p;
							if($hill_allowance_amt > 1500)
							{
								$hill_allowance = 1500;
							}
							else
							{
								$hill_allowance = round($hill_allowance_amt);
							}
						}
						else
						{
							$hill_allowance = 0;
						}
						$cpf=0;
						/***************************** Changed by ANJAN 09-12-2019 ***********************************/
						//$cpf = getAmount($dise,$emp_id_pk,'cpf','');
						
						
						if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
						{
							$gpf=0; 
						}	
						else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
						{
							$gpf=0;
						}
						else if($key['zp_emp_type']=='366' && ($key['emp_gpf_acc_no']=='' || $key['emp_gpf_acc_no']=='0'))
						{
							$gpf=0;
						}
						else
						{
							if($sal_save[0]['gpf']!=0)
							{	
								$gpf=$sal_save[0]['gpf'];	
							}
							else
							{
							
								     $ropa =func_gradepay($key['emp_grade_pay']);
									//$ropa_p =$key['ropa_9_emp_pay_in_payband'];
									$ropa_p =$key['emp_pay_in_payband'];
									// $ropa_2009=$ropa+$ropa_p;
									 $ropa_2009=$ropa_p;
									$gpf = getGPF($ropa_2009); 
									
								//$gpf = getGPF($basic); 
								/***************************** Changed by ANJAN 09-12-2019 ***********************************/
								//$gpf = getAmount($dise,$emp_id_pk,'gpf',$basic); 
							}
						}
						
						if($sal_save[0]['pf_loan'])
						{
							$pfl=$sal_save[0]['pf_loan'];
						}
						else
						{
							/***************************** Changed by ANJAN 09-12-2019 ***********************************/
							$pfl=0;
							//$pfl = getAmount($dise,$emp_id_pk,'pfl','');
						}
						$cpf_deduct = $cpf*2;
						
						if($sal_save[0]['i_tax'])
						{
							$itax=$sal_save[0]['i_tax'].'';
						}
						else
						{
							$itax=0;
							/***************************** Changed by ANJAN 09-12-2019 ***********************************/
							//$itax = getAmount($dise,$emp_id_pk,'itax','').'';
						}
						if($sal_save[0]['gsli'])
						{
							$gsli=$sal_save[0]['gsli'];
						}
						else
						{
							//$gsli = getAmount($dise,$emp_id_pk,'gsli','');
							/***************************** Changed by ANJAN 09-12-2019 ***********************************/
							$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
														WHERE other_deduction_type_variable = 'gsli'
														AND status in ('2') AND approval_status in ('3')
														AND emp_id_fk = '".$emp_id_pk."' 
														AND zp_id_fk = '".$dise."'");
														
								if($other_deduction_data[0]['deduction_amount'] > 0)
								{
									$gsli = $other_deduction_data[0]['deduction_amount'];
								}
								else
								{
									$gsli = 0;	
								}
						}
						if($sal_save[0]['other_loan_deduction'])
						{
							$other_loan_deduction=$sal_save[0]['other_loan_deduction'];
						}
						else
						{
							$other_loan_deduction = 0;
							/***************************** Changed by ANJAN 09-12-2019 ***********************************/
							//$other_loan_deduction = getAmount($dise,$emp_id_pk,'other_loan_deduction','');
						}
						$hra_deduction=0;	
						$overdrawn=0;
						$allow=$sal_chk[0]['allowance'];
						/***************************** Changed by ANJAN 09-12-2019 ***********************************/
						//$overdrawn = getAmount($dise,$emp_id_pk,'ovd','');
						
						//echo $pay_in_band.'--'.$da.'--'.$interim_relief.'--'.$hra.'--'.$ma.'--'.$conveyance_allowance.'--'.$cpf.'--'.$hill_allowance;
						
						 $gross_salary = round($pay_in_band+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance+$allow);
						
						if($key['conv_allow_status']=='1')
						{
							$ptax = 0;
						}
						else
						{
							if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
							{
								$gross_salary_sus=round($pay_in_band+$da_sus+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance+$allow);
								$ptax = empPtax($gross_salary_sus);
							}
							else
							{
								$ptax = empPtax($gross_salary);
							}
						}
						$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$hra_deduction+$festival_loan+$other_loan_deduction;
						
						
						 $net_salary = $gross_salary-$total_deduct; 
					}
				}
				
			
				
				if(($festival_loan)>0)
				{
					$festival_loan_cause=1;	
				}
				else
				{
					$festival_loan_cause=0;	
				}
				//var_dump($allow); die;
				
				if($allow=='')
				{
					$allow='0';
				}
				else{
					$allow=$allow;
				}	
				
				
				$_SESSION['tchname'.$emp_id_pk] = $crypto->encode($key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'],4);
				$_SESSION['bankname'.$emp_id_pk] = $crypto->encode($key['emp_bank_name'],4);
				$_SESSION['accountno'.$emp_id_pk] = $crypto->encode($key['emp_acc_no'],4);
				$_SESSION['bank_ifsc'.$emp_id_pk] = $crypto->encode($key['emp_ifsc_no'],4);
				
				$_SESSION['code'.$emp_id_pk] = $crypto->encode($key['emp_system_code'],4);
				$_SESSION['emp_id_pk'.$emp_id_pk] = $crypto->encode($key['emp_id_pk'],4);
				
				$_SESSION['basic'.$emp_id_pk] = $crypto->encode($basic,4);
				if($pay_in_band!=0)
				{ 
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($pay_in_band,4);
				}
				else
				{
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode(0,4);
					
				}
				if($consolidated_pay!=0)
				{
					$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}
				else
				{
					$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode(0,4);
					
				}
				/*if($grade_pay)
				{ 
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode($grade_pay,4);
				}else
				{
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode(0,4);
				}*/
					$_SESSION['da'.$emp_id_pk] = $crypto->encode($da,4);
				if($interim_relief=='')
				{
					$_SESSION['interim_relief'.$emp_id_pk] = $crypto->encode(0,4);
				}
				else
				{
					$_SESSION['interim_relief'.$emp_id_pk] = $crypto->encode($interim_relief,4);
				}
				$_SESSION['hra'.$emp_id_pk] = $crypto->encode($hra,4);
				$_SESSION['ma'.$emp_id_pk] = $crypto->encode($ma,4);
				$_SESSION['conveyance_allowance'.$emp_id_pk] = $crypto->encode($conveyance_allowance,4);
				$_SESSION['allow'.$emp_id_pk] = $crypto->encode($allow,4);
				$_SESSION['hill_allowance'.$emp_id_pk] = $crypto->encode($hill_allowance,4);
				
				$_SESSION['gross_salary'.$emp_id_pk] = $crypto->encode($gross_salary,4);
				$_SESSION['gpf'.$emp_id_pk] = $crypto->encode($gpf,4);
				$_SESSION['pfl'.$emp_id_pk] = $crypto->encode($pfl,4);
				
				$_SESSION['ptax'.$emp_id_pk] = $crypto->encode($ptax,4);
				$_SESSION['itax'.$emp_id_pk] = $crypto->encode($itax,4);
				$_SESSION['hra_deduc'.$emp_id_pk] = $crypto->encode($hra_deduction,4);
				$_SESSION['ovd'.$emp_id_pk] = $crypto->encode($overdrawn,4);
				$_SESSION['gsli'.$emp_id_pk] = $crypto->encode($gsli,4);
				$_SESSION['festival_loan'.$emp_id_pk] = $crypto->encode($festival_loan,4);
				
				$_SESSION['festival_loan_cause'.$emp_id_pk] = $crypto->encode($festival_loan_cause,4);
				$_SESSION['other_loan_deduction'.$emp_id_pk] = $crypto->encode($other_loan_deduction,4);
				$_SESSION['net_salary'.$emp_id_pk] = $crypto->encode($net_salary,4); 
				$_SESSION['zp_emp_type'.$emp_id_pk] = $crypto->encode($key['zp_emp_type'],4);
				$_SESSION['emp_desig'.$emp_id_pk] = $crypto->encode($key['emp_desig'],4);
				$_SESSION['ropa_level'.$emp_id_pk] = $crypto->encode($key['ropa_level'],4);
				$_SESSION['ropa_status'.$emp_id_pk] = $crypto->encode($key['ropa_status'],4);
				
				//For Loan Deduction Calcution Start
				$loan_deductions = $db->fetch_table("SELECT installment_amount,no_of_installment, counter,reminder_amount FROM prd_loan_deduction WHERE status in (2) AND approval_status in (3) AND emp_id_fk='".$emp_id_pk."'");
				$total_loan_deduction = 0;
				
					if(!empty($loan_deductions)){
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
					}
				
				
				$total_deduct = $total_deduct+$total_loan_deduction;
				if($sal_chk[0]['salary_type']!='8' && $sal_chk[0]['salary_type']!='2' && $key['emp_desig']!='1') // by nirupam on 28_03_2017 for no salary and part salary not showing the net salary
				{
					$net_salary = $gross_salary-$total_deduct; // comment by nirupam on 29_03_2017 for full salary showing the net salary wrong
				}
				$_SESSION['total_loan_deduction'.$emp_id_pk] = $crypto->encode($total_loan_deduction,4);
				$_SESSION['net_salary'.$emp_id_pk] = $crypto->encode($net_salary,4); 
				
				
				//For Loan Deduction Calcution End
				?>
				
				<tr>
                    <td id="show"><?php echo $count.'-'.$emp_id_pk; ?></td>
                    <td><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?></td>
		    		
                    <td><?php echo round($gross_salary); ?></td>
                    <td><?php echo $gpf;  ?></td>
                    
                    <td><?php echo $ptax; ?></td>
                    <td><?php echo $itax; ?></td>
                    <td><?php echo $gsli; ?></td> 
                    <td><?php echo $hra_deduction; ?></td> 
                    <td><?php echo $overdrawn; ?></td>
                    
                    <td><?php if($festival_loan){echo $festival_loan;} else {echo 0;} ?></td>
                    <td class="loan">
						<?php if($total_loan_deduction != 0 and $sal_chk[0]['salary_type']!='8')
						{ ?>
                            <a data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg-loan" href="" id="<?php echo $crypto->encode($key['emp_id_pk'],4) ?> ">
                                <?php echo $total_loan_deduction; ?>
                            </a>
                        <?php 
						}
						else if( $sal_chk[0]['salary_type']=='8')
						{ 
							echo 0; 
						}else{
						    echo $total_loan_deduction;
						}
						?>
                    </td>
                    <td><?php echo $other_loan_deduction; ?></td>
                    <td><?php echo round($net_salary); ?></td>
                    
                    <input type="hidden" name="zp_id" id="zp_id" value="<?php echo $crypto->encode($key['zp_id_fk'],4) ?>" />
                    
                    <td class="edit"><a data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit"></a></td>
                    
                    <?php
                    $saved = 0;
                    for($i =0 ; $i < $tch_count ; $i++)
					{
						if($key['emp_id_pk'] == $sal_saved[$i]['emp_id_fk'])
						{
							$saved = 1; 
							?>
							<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit"></td>
						<?php 
						}
                    }
                    
                    if($saved == 0)
					{ ?>
                        <td class="save" id="save">
                            <div class="save_id<?=$count?>">
                                <a emp_id_pk="<? echo $crypto->encode($key['emp_id_pk'],4) ?>"empcd="<?php echo $crypto->encode( $key['empcd'],4) ?>"sec_tok="<?=$enc_token?>" name="<?= $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'] ?>" show="<?=$count?>"><img id="img_id<?=$count?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Edit"></a>
                            </div>
                        </td>
                    <?php 
                    }
                   
                    $count += 1 ; ?>
				</tr> 
				<? 
			} 
		} 
		else 
		{?> 
        	<tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> 
		<?php 
		}?>
        </tbody>
	</table>
	</div>
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
		.ul.pagination {
			display: inline-block;
				float: right !important;
			padding: 0;
			margin: 0;
		}
		
		.pagination {
			display: inline-block;
			float: right !important;
			padding-top: 10px;
			padding-bottom: 10px;
			margin: 0;
		}
		
		ul.pagination li {display: inline;}
		
		ul.pagination li a {
			color: black;
			float: left;
			padding: 8px 16px;
			text-decoration: none;
			transition: background-color .3s;
		}
		
		ul.pagination li a.active {
			background-color: #4CAF50;
			color: white;
		}
		
		ul.pagination li a:hover:not(.active) {background-color: #ddd;}
		
		.dataTables_filter{
			float:right !important;
		}
		.dataTables_empty{
			color:red;
			font-weight:bold;
		}
	</style>
	
	
	<div class="form-group" id="sal_save" style="display:none; margin-left: 48%;">
        <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
        	<a href="<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_zp_rq_finalize_ropa_2019.php" class="btn btn-success btn-sm">Salary Finalize</a>
        </div>
	</div>
	
	<?php 
	
	if(count($tch) == count($sal_saved))
	{ 
		if(count($tch))
		{
		?>
            <div class="form-group" id="sal_save" style="margin-left: 48%;">
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
            <a href="<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_zp_rq_finalize_ropa_2019.php" class="btn btn-success btn-sm">Salary Finalize</a>
            </div>
            </div>
		<?php 
		} 
	} 
}  
else 
{  ?>
	<script>
    
        $('.emplist').css('display','none');
        
        window.setTimeout(function(){
            window.location.replace("<?php echo $config['base_url'] . 'page/intra_zp/salary_daa/sal_requisition/view_zp_sal_requisition_ropa_2019.php'; ?>");
        },3000);
    
    </script>

<?php } ?>

<!-----------------------------------------------------------------MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 1000px;margin-left: -5.5%;">
            <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Employee Salary Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
                <div id="mbody"> 
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
            </div>
        </div>
    </div>
</div>

<!--For total loan deduction-->
<div class="modal fade bs-example-modal-lg-loan" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 850px;">
            <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
                <div id="loanmbody"> 
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
            </div>
        </div>
    </div>
</div>

<!--End of For total loan deduction-->

<script>
	$(document).ready(function()
	{
			
		var tch=(<?=count($tch)?>);
		$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
		$( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		$("#wait").css("display","none");
		$("#dialog-confirm").css("display", "none");
		$("#saving").css("display", "none");
		
		$(".edit a").click(function() 
		{	
			
			var link = $(this).attr('id');
			var link1= $("#zp_id").val();
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_requisition_edit_ropa_2019.php?id='+link+'&zp_id='+link1, function(data)
			{                    
				$("#mbody").html(data);
			});
		});
		
		// for loan details
		
		$(".loan a").click(function() 
		{
			
			var link = $(this).attr('id');
			var link1= $("#gp_id").val();
			
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_emp_loan_details_vew_ropa_2019.php?id='+link+'&mu_id='+link1, function(data){
				$("#loanmbody").html(data);
			});
		});
		
		// end of for loan details
		
		$(".save a").click(function() 
		{
			
			$("#sal_save").hide();
			var emp_id_pk = $(this).attr('emp_id_pk');
			var name=$(this).attr('name');
			var empcd = $(this).attr('empcd');
			var sec_tok = $(this).attr('sec_tok');
			var show = $(this).attr('show');
			
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_zp_rq_save_ropa_2019.php?emp_id_pk='+emp_id_pk+'&sec_tok='+sec_tok, function(data)
			{
				
				var result = $.parseJSON(data);
				//alert(result[0]);
				var sal_save=result[1];
				if(parseInt(result[2])==parseInt(result[3]))
				{
					$("#sal_save").show();
				}
				else
				{
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
		
		$("#finalize").click(function() 
		{	
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/ajax_zp_rq_finalize_ropa_2019.php', function(data){
			
			});
		});
		$("#example").DataTable({		
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 10,
		"bFilter": true,
	  	"bSort": false,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});
		
		
	});
</script>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->

