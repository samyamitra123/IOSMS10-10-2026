
<?php

ob_start();
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
session_start();
$str=$_SESSION['user_info']['stake_user'];
$state10=substr($str,0,4); 
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../../includes/library/cryptography.class.php';
$cryp = new cryptography();

 $emp_id_pk=$cryp->decode($_GET['id'],4);
 $dise=$cryp->decode($_GET['ps_id'],4);
 $time_token=time();
 $_SESSION['security_token']=$time_token;
 $enc_token=md5('371371371'.$time_token);

$db = new database();


$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

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
	$arr = $db->fetch_table("select emp_grade_pay,grade_amount from prd_employee_master as emp
	INNER JOIN prd_dise_gradepay_master as gd 
	ON trim(emp.emp_grade_pay)=gd.grade_code
	where emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$dise."'");
		if($arr[0]['grade_amount']){
			return $arr[0]['grade_amount'];
		}else{
			return 0;
		}
	}
}
//---------------------------------Consolidate Pay Fun----------------------------------
function getEmpConsolidated($type,$dise,$emp_id_pk){
	$db = new database();
	if($type == 'consolidated_pay'){
		
	$arr = $db->fetch_table("select emp_cosolidated_pay from prd_employee_master where emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$dise."'");
		if($arr[0]['emp_cosolidated_pay']){
			return $arr[0]['emp_cosolidated_pay'];
		}else{
			return 0;
		}
	}
}
//--------------------------------All Type Deduction fun----------------------------------
function getAmount($dise,$emp_id_pk,$type,$basic){
			$db = new database();
	/////////////////////////////----CPF----////////////////////////		
			if($type=='cpf'){
				$arr = $db->fetch_table("select cpf from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['cpf']){
					return $arr[0]['cpf'];
				}else{
					return 0;
				}
			}
	/////////////////////////////----GPF----////////////////////////		
			if($type=='gpf'){
				$arr = $db->fetch_table("select gpf from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['gpf']){
					return $arr[0]['gpf'];
				}else{
					$gpf_amt = ($basic/100)*6;
					return round($gpf_amt);
				}
			}
	/////////////////////////////----PF LOAN----////////////////////////		
			if($type=='pfl'){
				$arr = $db->fetch_table("select pf_loan from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['pf_loan']){
					return $arr[0]['pf_loan'];
				}else{
					return 0;
				}
			}
	/////////////////////////////----I-Tax----////////////////////////		
			if($type=='itax'){
				$arr = $db->fetch_table("select i_tax from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['i_tax']){
					return $arr[0]['i_tax'];
				}else{
					return 0;
				}
			}
	/////////////////////////////----OVERDRAWN----////////////////////////		
			if($type=='ovd'){
				$arr = $db->fetch_table("select overdrawn from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['overdrawn']){
					return $arr[0]['overdrawn'];
				}else{
					return 0;
				}
			}
	/////////////////////////////----GSLI----////////////////////////		
			if($type=='gsli'){
				$arr = $db->fetch_table("select gsli from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['gsli']){
					return $arr[0]['gsli'];
				}else{
					return 0;
				}
			}
	/////////////////////////////----Convyence Aloow----////////////////////////		
			if($type=='conv'){
				$arr = $db->fetch_table("select conv_allow from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
				if($arr[0]['conv_allow']){
					return $arr[0]['conv_allow'];
				}else{
					return 0;
				}
			}
		}
//--------------------------------P-TAX fun----------------------------------  
                function empPtax($amount){
			$db = new database();
			$arr = $db->fetch_table("select ptax_amount from prd_ptax_deduction as amnt inner join psemp_ptax_order_file as file on amnt.ptax_order_id_fk=file.ptax_orderfile_pk where amnt.mn_amount <= '$amount' and amnt.mx_amount >= '$amount' AND file.active_status='1'");
			return $arr[0]['ptax_amount'];
		}
$tch = $db->fetch_table("SELECT 
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
										emp_retirement_date,
										tch.emp_id_pk,
										tch.empcd,
										tch.ps_id_fk,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_spouse_hra,
										tch.emp_pan_no,
										tch.emp_first_join_date,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status
									FROM
										prd_employee_master as tch
									WHERE
											tch.ps_id_fk = '".$_SESSION['location']['ps_id']."'
											AND tch.emp_id_pk = '".$emp_id_pk."'
											AND (tch.emp_status='1' OR tch.emp_status='9')
											
								");
							
							
	//$gross_sal=$arr[0]['basic'] + $arr[0]['da'] + $arr[0]['hra'] + $arr[0]['ma'] + $arr[0]['cpf'] + $arr[0]['spl_pay'] + $arr[0]['spl_pay'];
						
$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM psemp_admin_paychange
							WHERE flag = 't'
						");
			$da_per = $paychange[0]['paychange_da']; 
			$max_ma = $paychange[0]['paychange_ma'];
			$hra_per = $paychange[0]['paychange_hra'];
			$cpf_per = $paychange[0]['paychange_cpf'];
			$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
			$hill_allowance_per = $paychange[0]['hill_allowance'];
				//Pay and allowance part
				$tchname =$tch[0]['emp_first_name'].' '. $tch[0]['emp_second_name'].' '. $tch[0]['emp_last_name'] ;
				$empcd = $tch[0]['empcd'];
				$emp_id_pk=$tch[0]['emp_id_pk'];
			
     
				
		$sal_chk= $db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, ps_id_fk, empcd, 
       bankname, accountno, basic, da,interim_relief, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow,hra_deduction, 
       overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause from prd_employee_salary_save where status_flag=1 AND delete_status=1 AND salary_monthyear='".date('Ym')."'  AND emp_id_fk='$emp_id_pk' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
	   
	   
				 //---------- Start Gpf=0 before retirement--------------------
			$retirement_date=$tch[0]['emp_retirement_date'];
			$date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date))); 	
			$emp_first_join_date=$tch[0]['emp_first_join_date'];
			$emp_first_join_match_date=date('Y-m-30', strtotime('+12 month',strtotime($emp_first_join_date)));	
			//----------- End Gpf=0 before retirement ---------------------
				
				if($sal_chk[0]['ps_id_fk'] && $sal_chk[0]['emp_id_fk'] && ($sal_chk[0]['salary_type']=='1' || $sal_chk[0]['salary_type']=='2')){
					
					// This condition for return full salary of employee and display saved salary of employee in edit mode
					
					   if($tch[0]['emp_desig']=='1120'){
						   
						$pay_in_band = $sal_chk[0]['pay_payband'];
						$full_consolidated_pay=getEmpConsolidated('consolidated_pay',$dise,$emp_id_pk);
						$consolidated_pay=$sal_chk[0]['consolidated_pay'];
						$grade_pay = $sal_chk[0]['tch_grade_pay'];
						$basic = $sal_chk[0]['basic'];
						$da = $sal_chk[0]['da'];
						$interim_relief = $sal_chk[0]['interim_relief'];
						$hra = $sal_chk[0]['hra'];
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
						//$other_deduction = $sal_chk[0]['other_deduction'];
						//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
					//	$hbl_loan = $sal_chk[0]['hbl_loan'];
						$festival_loan = $sal_chk[0]['festival_loan'];
						$net_salary = $sal_chk[0]['net'];	
						//$cooperative_loan_cause = $sal_chk[0]['cooperative_loan_cause'];
						//$hbl_loan_cause = $sal_chk[0]['hbl_loan_cause'];
						$festival_loan_cause = $sal_chk[0]['festival_loan_cause'];
					}
					else{
						  
			foreach ($tch as $key) {
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
						  $full_pay_in_band_sus = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
						  $full_pay_band = round(($full_pay_in_band_sus*$pencentage_basic)/100);
						  $full_grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						  $full_grade_pay=round(($full_grade_pay_sus*$pencentage_basic)/100);
						  $full_basic = round($full_pay_in_band_sus+$full_grade_pay_sus);
						  $full_basic_sus = round($full_pay_band+$full_grade_pay);
						  $full_da_sus = round(($full_basic/100)*$da_per);
						  $full_da = round(($full_basic_sus/100)*$da_per);
						  $full_interim_relief =0;
						 
				      
			          }else{
						  
					     $full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
				         $full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
			             $full_basic = $full_pay_band+$full_grade_pay;
                         $full_da = round(($full_basic/100)*$da_per);
						  $full_interim_relief = $tch[0]['interim_relief'];
						// $full_interim_relief =$sal_chk[0]['interim_relief'];
						
						 /*	if($sal_chk[0]['interim_relief']!='')
				            {
					         $full_interim_relief =$sal_chk[0]['interim_relief'];
					        }
					        else{
				             $full_interim_relief = $key['interim_relief'];
					        }*/
						 
					  }
				
				
				
				}
				
				
							
					 //$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
				   //  $full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					// $full_basic = $full_pay_band+$full_grade_pay;
					 $consolidated_pay=0;
					// $full_gpf = getAmount($dise,$tch[0]['emp_id_pk'],'gpf',$basic);
					
					 
					/* $full_cooperative_loan = $sal_chk[0]['cooperative_loan'];
					 $full_hbl_loan = $sal_chk[0]['hbl_loan'];
					 $full_festival_loan = $sal_chk[0]['festival_loan'];*/
					
					//$full_da = ($full_basic/100)*$da_per;
					
			    	//$full_interim_relief =$sal_chk[0]['interim_relief'];
						  
	           if($tch[0]['emp_spouse_res']=='251'){
						$full_hra = 0; 		
					}else{
						if($tch[0]['emp_spouse_hra']=='0' || $tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra']){
							$hra_emp = ($full_basic/100)*$hra_per;
							if($hra_emp > 6000){
								$full_hra = 6000;
							}else{
								$full_hra = round($hra_emp);
							}
						}else if($tch[0]['emp_spouse_hra'] >= 6000){
							//echo "HRA";
							$full_hra = 0;
						}else if($tch[0]['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
							$hra_emp = ($full_basic/100)*$hra_per;
							//echo $hra_emp;
							if($hra_emp >= 6000){
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$full_hra = round($valid_hra);
							}else{
								$mix_hra = $hra_emp+$tch[0]['emp_spouse_hra'];
								if($mix_hra > 6000){
									
										if($tch[0]['emp_spouse_hra'] > $hra_emp){
											$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
											
											if($valid_hra>$hra_emp){
												$valid_hra = $hra_emp;
											}
											$full_hra = round($valid_hra);
										}else if($tch[0]['emp_spouse_hra'] <= $hra_emp){
											//$valid_hra = $hra_emp-$tch[0]['spouse_hra'];
											$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
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
					if($tch[0]['spouse_medical_allowance']=='1'){
						$full_ma = 0;
					}else{
						$full_ma = $max_ma;
					}
					
					
					if($tch[0]['emp_diff_able']=='1'){
						if($tch[0]['conv_allow_status']==1){
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
				}else{
					$full_conveyance_allowance = 0;
				}
				
					if($state10=='3219'){
						 
						$hill_p = ($full_pay_band/100)*$hill_allowance_per;
						$hill_g = ($full_grade_pay/100)*$hill_allowance_per;
						$full_hill_allowance_amt = $hill_p+$hill_g;
						if($full_hill_allowance_amt > 1500){
							 $full_hill_allowance = 1500; 
						}else{
						   $full_hill_allowance = $full_hill_allowance_amt; 
						}
					}else{
						 $full_hill_allowance = 0;
					}
					$pay_in_band =$full_pay_band;
					$grade_pay =$full_grade_pay;
					$basic=$full_basic;
					$da=round($full_da);
					$interim_relief=round($full_interim_relief);
					$hill_allowance = round($full_hill_allowance); 
					$hra=round($full_hra);
					$ma=round($full_ma);
					$conveyance_allowance=$full_conveyance_allowance;
					//$hill_allowance = $sal_chk[0]['hill_allowance'];
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
			     	       if($sal_chk[0]['gpf']!=0)
					       {
					       $full_gpf = $sal_chk[0]['gpf']; 
					       }
					        else{
						    $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);
					        }
						  }
					
					//$full_gpf = getAmount($dise,$empcd,'gpf',$full_basic); 
					/* if($sal_chk[0]['gpf']!='')
					 {
					 $full_gpf = $sal_chk[0]['gpf']; 
					 }
					else
					{
						
						 $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic); 
					
					}*/
					
					
					 if($key['emp_diff_able']=='1')
					 {
						// $full_ptax = empPtax($full_gross_salary);
					 $full_ptax = 0;
				    }else{
						 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0){
							
							 $full_gross_salary_sus =round($full_pay_in_band_sus+$full_grade_pay_sus+$full_da_sus+$interim_relief+$hra+$ma+$conveyance_allowance);
					
					    $full_ptax = empPtax($full_gross_salary_sus);
					  }
					  else{
						  $full_ptax = empPtax($full_gross_salary);
					  }
					
					//$full_ptax = 0;
					}
					
					
					if($sal_chk[0]['i_tax'])
					{
					$full_itax=$sal_chk[0]['i_tax'].'';
					}else{
					 $full_itax = getAmount($dise,$empcd,'itax','').'';
					//$full_itax = $sal_chk[0]['i_tax'];
					}
					
					$gpf=$full_gpf;
					$ptax=$full_ptax;
					//$itax = $sal_chk[0]['i_tax'];
					$gross_salary=$full_gross_salary;
					$itax=$full_itax;
					$gsli = $sal_chk[0]['gsli'];
					$pfl = $sal_chk[0]['pf_loan'];
					$hra_deduction=$sal_chk[0]['hra_deduction'];
					$overdrawn = $sal_chk[0]['overdrawn'];
					//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
				  //  $hbl_loan = $sal_chk[0]['hbl_loan'];
				    $festival_loan = $sal_chk[0]['festival_loan']; 
					
					//$total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$cooperative_loan+$hbl_loan+$festival_loan;
					//$full_net_salary = $full_gross_salary-$total_deduct;
					
					 $total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction;
				         $full_net_salary = $full_gross_salary-$total_deduct;
					 $net_salary=$full_net_salary;
					 
					if($sal_chk[0]['salary_type']=='2'){
				 
					$pay_in_band = $sal_chk[0]['pay_payband'];
					$consolidated_pay=0;
					$grade_pay = $sal_chk[0]['tch_grade_pay'];
					$basic = $sal_chk[0]['basic'];
					$da = $sal_chk[0]['da'];
					$interim_relief = $sal_chk[0]['interim_relief'];
					$hra = $sal_chk[0]['hra'];
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
					//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
				   // $hbl_loan = $sal_chk[0]['hbl_loan'];
				    $festival_loan = $sal_chk[0]['festival_loan'];
				    $net_salary = $sal_chk[0]['net'];
					}
				  
					
			}
					
				}
				}
				
				else if($sal_chk[0]['salary_type']=='8'){
					
				
					 // This condition for return full salary of employee zero salary and display saved salary of employee in edit mode
					
					/*$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
					$full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					$full_basic = $full_pay_band+$full_grade_pay;
					$full_da = ($full_basic/100)*$da_per;*/
					
					$pay_in_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
					$consolidated_pay=0;
					$grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					$basic = $pay_in_band+$grade_pay;
					$da = round($basic/100)*$da_per;
					$interim_relief = $sal_chk[0]['interim_relief'];
					
					$full_basic=$basic;
					
					
				
					 if($tch[0]['emp_spouse_res']=='251'){
						$full_hra = 0; 		
					}else{
						if($tch[0]['emp_spouse_hra']=='0' || $tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra']){
							$hra_emp = ($full_basic/100)*$hra_per;
							if($hra_emp > 6000){
								$full_hra = 6000;
							}else{
								$full_hra = round($hra_emp);
							}
						}else if($tch[0]['emp_spouse_hra'] >= 6000){
							//echo "HRA";
							$full_hra = 0;
						}else if($tch[0]['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
							$hra_emp = ($full_basic/100)*$hra_per;
							//echo $hra_emp;
							if($hra_emp >= 6000){
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$full_hra = round($valid_hra);
							}else{
								$mix_hra = $hra_emp+$tch[0]['emp_spouse_hra'];
								if($mix_hra > 6000){
									
										if($tch[0]['emp_spouse_hra'] > $hra_emp){
											$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
											
											if($valid_hra>$hra_emp){
												$valid_hra = $hra_emp;
											}
											$full_hra = round($valid_hra);
										}else if($tch[0]['emp_spouse_hra'] <= $hra_emp){
											//$valid_hra = $hra_emp-$tch[0]['spouse_hra'];
											$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
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
					
					$hra=$full_hra;
					
					
					
					
					
				if($tch[0]['spouse_medical_allowance']=='1'){
						$ma = 0;
					}else{
						$ma = $max_ma;
					}
					if($tch[0]['emp_diff_able']=='1'){
						if($tch[0]['conv_allow_status']==1){
							$conveyance_allowance = $conveyance_allowance_max;
						 }else{
								$conveyance_allowance = 0;
						 }
					 }else{
						$conveyance_allowance = 0;
					 }
					if($state10=='3219'){
						$hill_p = ($pay_in_band/100)*$hill_allowance_per;
						$hill_g = ($pay_in_band/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500){
							$hill_allowance = 1500;
						}else{
							$hill_allowance = $hill_allowance_amt;
						}
					}else{
						$hill_allowance = 0;
					}
					
					
					
					$cpf = getAmount($dise,$tch[0]['emp_id_pk'],'cpf','');
					
					$gross_salary = round($basic+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
					//Deduction part
					//$gpf_amt = ($basic/100)*6;
					$gpf = getAmount($dise,$tch[0]['emp_id_pk'],'gpf',$basic);
					
					$pfl = getAmount($dise,$tch[0]['emp_id_pk'],'pfl','');
					$cpf_deduct = $cpf*2;
					
					if($tch[0]['emp_diff_able']=='1'){
						$ptax = 0;
						
					}else{
						
						$ptax = empPtax($gross_salary);
					}
					
					//$max_ptax = empPtax($gross_salary);
					$itax = getAmount($dise,$tch[0]['emp_id_pk'],'itax','');
					$gsli = getAmount($dise,$tch[0]['emp_id_pk'],'gsli','');
					$hra_deduction=0;
					$overdrawn = getAmount($dise,$tch[0]['emp_id_pk'],'ovd','');
					
					$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$hra_deduction;
					$net_salary = $gross_salary-$total_deduct;
				
				
					
				}
				else
				{
					
					 $sal_save=	$db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, ps_id_fk, empcd, 
       bankname, accountno, basic, da,interim_relief, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause,hra_deduction from prd_employee_salary_save where status_flag=3 AND delete_status=1 AND salary_monthyear='".date('Ym',strtotime('-1 month'))."'  AND emp_id_fk='$emp_id_pk' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
	   
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
			 $count_days_total_pay_pay_band_for_promotion = $tch[0]['emp_pay_in_payband'];
		 }
		 else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5) && $promotion_eff_yr_mnth == date("Ym"))
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
			 $count_days_total_pay_pay_band_for_promotion = $tch[0]['emp_pay_in_payband'];
			 $count_days_total_grade_pay_for_promotion = func_gradepay($tch[0]['emp_grade_pay']);
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
										INNER JOIN prd_employee_master em1 on sus.emp_id_fk=em1.emp_id_pk
									WHERE
										sus.ps_id_fk = '".$dise."' and sus.emp_id_fk='".$cryp->decode($_REQUEST['id'],4)."' and em1.emp_status='9' and sus.delete_status='0'
		                      ");
							  
				   $suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
	   
	                 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						
						  //$pay_in_band_sus =  $tch[0]['emp_pay_in_payband'];
						  $pay_in_band_sus = $count_days_total_pay_pay_band_for_promotion;
						  $pay_in_band = round(($pay_in_band_sus*$pencentage_basic)/100);
						  //$grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						  $grade_pay_sus = $count_days_total_grade_pay_for_promotion;
						  $grade_pay=round(($grade_pay_sus*$pencentage_basic)/100);
						  $basic = round($pay_in_band_sus+$grade_pay_sus);
						  $basic_sus = $pay_in_band+$grade_pay;
						  $da_sus=$da = round(($basic/100)*$da_per);
						  $da = round(($basic_sus/100)*$da_per);
						  $interim_relief =0;
						 
				      
			          }else{
						  
					    //$pay_in_band =  $tch[0]['emp_pay_in_payband'];
						//$grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						$pay_in_band = $count_days_total_pay_pay_band_for_promotion;
						$grade_pay = $count_days_total_grade_pay_for_promotion;
			            $basic = $pay_in_band+$grade_pay;
						$da = round(($basic/100)*$da_per);
						   $interim_relief = $tch[0]['interim_relief'];
						 /*	if($sal_save[0]['interim_relief']!=''){
					         $interim_relief = $sal_save[0]['interim_relief'];
					        }
					         else{
			     	          $interim_relief = $tch[0]['interim_relief'];
					        }*/
					  }
				
				
	   
	   
	   			    $consolidated_pay=0;
	               //  $pay_in_band = $tch[0]['emp_pay_in_payband'];
					// $grade_pay  = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					// $basic = $pay_in_band+$grade_pay;
					// $da = ($basic/100)*$da_per; 
	             /*  if($sal_save[0]['interim_relief']!='')
			       	{
					 $interim_relief = $sal_save[0]['interim_relief'];
					}
					else{
			     	$interim_relief = $tch[0]['interim_relief'];
					}*/
					
					
	          if($tch[0]['emp_spouse_res']=='251'){
						 $hra = 0; 
					}else{
						if($tch[0]['emp_spouse_hra']=='0' ||$tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra']){
							$hra_emp = ($basic/100)*$hra_per;
							if($hra_emp > 6000){
								$hra = 6000;
							}else{
								$hra = round($hra_emp);
							}
  }else if($tch[0]['emp_spouse_hra'] >= 6000){
							//echo "HRA";
							$hra = 0;
						}
							else if($tch[0]['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
							
						$hra_emp = ($basic/100)*$hra_per;
							
							if($hra_emp >= 6000){
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$hra = round($valid_hra);
							
						}/*else{
							$hra_emp = (($basic/100)*$hra_per)+$tch[0]['emp_spouse_hra'];
							if($hra_emp > 6000){
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$hra = round($valid_hra);
							}
*/							else{
								$mix_hra = $hra_emp+$tch[0]['emp_spouse_hra'];
						if($mix_hra > 6000){
	                  if($tch[0]['emp_spouse_hra'] > $hra_emp){
							
							//$valid_hra = $key['spouse_hra']-$hra_emp;
											$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
							                 if($valid_hra>$hra_emp){
												$valid_hra=$hra_emp;
											   }
							               
							$hra = round($valid_hra);
							}else if($tch[0]['emp_spouse_hra'] <= $hra_emp){
							$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
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
							
					
					if($tch[0]['spouse_medical_allowance']=='1'){
						$ma = 0;
					}else{
						$ma = $max_ma;
					}
					
					/*if($tch[0]['emp_diff_able']=='1'){
						if($sal_save[0]['conv_allow']!=''){
						$conveyance_allowance = $sal_save[0]['conv_allow'];	
						}else{
						$conveyance_allowance = $conveyance_allowance_max;
						}
					}else{
						$conveyance_allowance = 0;
					}*/
					if($tch[0]['emp_diff_able']=='1'){
						if($tch[0]['conv_allow_status']==1){
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
						 $hill_allowance = $hill_allowance_amt;
						}
					}else{
						 $hill_allowance = 0;
					}
					//$cpf = ($basic/100)*$cpf_per;
					//$cpf = getAmount($dise,$tch[0]['tchcd'],'cpf','');
					 $gross_salary = round($pay_in_band+$grade_pay+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$hill_allowance);
					//Deduction part
					//$gpf_amt = ($basic/100)*6;
					
					/*$gpf = getAmount($dise,$tch[0]['tchcd'],'gpf',$basic);
					if($gpf!=0){
					if($sal_save[0]['gpf']){
					$gpf=$sal_save[0]['gpf'];
					}else{
					$gpf = getAmount($dise,$tch[0]['tchcd'],'gpf',$basic);
					}
				   }*/
				   
				 //  $check_gpf=$db->fetch_table("select tch_first_joining from ehrms_dise_teacher_primary where status=1  AND tchcd='$tchcd' AND schcd='".$dise."'");
					//$join=$check_gpf[0]['tch_first_joining'];
					//$next_dt =date("Y-m-d", strtotime("+1 years", strtotime($join)));
					/*if(strtotime($next_dt)>=strtotime(date('Y-m-d'))){
					$gpf=0;
					}else*/ 
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
				
					//$cpf_deduct = $cpf*2;
					
				if($key['emp_diff_able']=='1'){
					$ptax = 0;
				}else{
					 
					
					
				}
					
				if($tch[0]['emp_diff_able']=='1'){
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
					
					// $max_ptax = empPtax($gross_salary);
					if($sal_save[0]['i_tax']){
					$itax=$sal_save[0]['i_tax'];
					
					}else{
					$itax = getAmount($dise,$empcd,'itax','');
					}
					
					if($sal_save[0]['gsli']){
					$gsli=$sal_save[0]['gsli'];
					
					}else{
					$gsli = getAmount($dise,$empcd,'gsli','');
					}
				/*	if($sal_save[0]['overdrawn']){
					$overdrawn=$sal_save[0]['overdrawn'];
					}else{
					$overdrawn = getAmount($dise,$tch[0]['tchcd'],'ovd','');
					}*/
					if($hra_deduction){
					    
					    $hra_deduction= $sal_save[0]['hra_deduction'];
					}else{
					    $hra_deduction=0;
					    
					}
					    
					   
					
					 $overdrawn=0;
					 $total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$hra_deduction; 
					 $net_salary = $gross_salary-$total_deduct;
	
	              if($tch[0]['emp_desig']=='1120'){
					$consolidated_pay=$tch[0]['emp_cosolidated_pay'];	
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
					$gross_salary = round($bas+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
					if($tch[0]['emp_diff_able']=='1'){
					$ptax = 0;
					}else{
						$ptax = empPtax($gross_salary);
					}
					$max_ptax = empPtax($gross_salary);
					$itax = 0;
					$gsli =0;
					$hra_deduction=0;
					
					$overdrawn = getAmount($dise,$tch[0]['emp_id_pk'],'ovd','');
					//$other_deduction=0;
					//$cooperative_loan = 0;
						//$hbl_loan = 0;
						$festival_loan =0;
					$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$hra_deduction;
					$net_salary = $gross_salary-$total_deduct;
				}
	          
					
				}
			
				
				
		   
		 ?>

<style>
input[type="checkbox"] {
	display:inline !important;
}
</style>

	
	<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>
    <script>
$(document).ready(function(e) {
	$('#reduction_type1,#reduction_type2,#reduction_type3').click(function(e) {
	//alert(11);
	
		if($('#reduction_type1').is(':checked')==false)
		{
			
					if($('#hra_deduc').val()!='')
					{
				        var reduct1=$('#hra_deduc').val();
						var net=parseInt($('#net').val())+parseInt(reduct1);
						$('#net').val(net);
						$('#hra_deduc').val(0);
						$('#hra_deduc').attr('readonly','readonly');
					    $('#hra_deduc').css('background-color','#EEE');
						
					}
			
		}
		else
			{
				 
			     $('#hra_deduc').removeAttr('readonly');
			     $('#hra_deduc').css('background-color','#FFF');
			}
	/* if($('#reduction_type2').is(':checked')==false)
		{
			
					if($('#reduct2').val()!='')
					{
				        var reduct2=$('#reduct2').val();
						var net=parseInt($('#net').val())+parseInt(reduct2);
						$('#net').val(net);
						$('#reduct2').val(0);
						$('#reduct2').attr('readonly','readonly');
					    $('#reduct2').css('background-color','#EEE');
						
					}
			
		}
		else
			{
				 
			     $('#reduct2').removeAttr('readonly');
			     $('#reduct2').css('background-color','#FFF');
			}*/
	
	if($('#reduction_type3').is(':checked')==false)
		{
			
					if($('#reduct3').val()!='')
					{
				        var reduct3=$('#reduct3').val();
						var net=parseInt($('#net').val())+parseInt(reduct3);
						$('#net').val(net);
						$('#reduct3').val(0);
						$('#reduct3').attr('readonly','readonly');
					    $('#reduct3').css('background-color','#EEE');
						
					}
			
		}
		else
			{
					 
			     $('#reduct3').removeAttr('readonly');
			     $('#reduct3').css('background-color','#FFF');
			}		
	});

});

</script>
    <script>
	/*function abc(){
	alert(225232332);	
	}*/
	<?php if($tch[0]['emp_desig']=='1120'){?>
	$('#pf_loan').attr('readonly','readonly');
	$('#pf_loan').css('background-color','#EEE');
	$('#i_tax').attr('readonly','readonly');
	$('#i_tax').css('background-color','#EEE');
	$('#gsli').attr('readonly','readonly');
	$('#gsli').css('background-color','#EEE');
	<?php } ?>
	</script>
    <script>
	/*function reduc()
{
	var net = $("#net").val();
	var red=$("#reduct").val();
	//var net=parseInt($("#net").val(),10);
	var red=parseInt($("#reduct").val(),10);
	if(Number(red>=net))
	{ 
	alert('Please enter valid other deduction value.');
	$('#reduct').val(0);
	}
	/*if(Number( $("#reduct").val()) > net)
	{
   alert('Please enter valid other deduction value.');
	}
		
}*/

$(document).ready(function (e1) {
	

					//$('#hra_deduc').val(0);	
	
	                    if($('#reduction').val()=="no_ovd1")
						{
							 $("#reduction_type").hide();
						}  
						
	                  /* if($('#reduct1').val()>0)
					   { 
					       $("#reduction_type").show();
						   $('#reduct1').removeAttr('readonly');
						   $('#reduct1').css('background-color','#FFF'); 
					   }
					    if($('#reduct2').val()>0)
					   {
						   $("#reduction_type").show();
						   $('#reduct2').removeAttr('readonly');
						   $('#reduct2').css('background-color','#FFF'); 
					   }*/
					    if($('#reduct3').val()>0)
					   {
						   $("#reduction_type").show();
						   $('#reduct3').removeAttr('readonly');
						   $('#reduct3').css('background-color','#FFF'); 
					   }
					   
	     /*if( $('#reduct1').val()==0)
		 {
						  // alert("11");
		 $("#reduction_type").hide();
		 }
			 if( $('#reduct2').val()==0)
			{
						  // alert("11");
			  $("#reduction_type").hide();
			 }
				if( $('#reduct3').val()==0)
				 {
						  // alert("11");
			       $("#reduction_type").hide();
				 }*/
      var value1 = $("#net").val();
      //var read1=$('#reduct1').val()
	 // var read2=$('#reduct2').val()
	  var read3=$('#reduct3').val()             
		 
	 $("#reduction").change(function () {
	 if($('#salary_type').val()!='8')
	 { 
		 if ($(this).val() == "yes_ovd1" )
		 {
		 $("#reduction_type").show();
	     }
		 
		/* $('.cls6').change(function(e) {
		 if($('.cls6').is(':checked')==true)
			 {
				  var value1 = $("#net").val();
				 //$('#reduction_type').css('background-color','#EEE');
				 $('#reduct1').removeAttr('readonly');
				 $('#reduct1').css('background-color','white');
				 var read=$('#reduct1').val();
				 var addition = parseInt(read)+parseInt(value1);
				 $('#reduct1').val(read);
				 $('#net').val(addition);
				 }
				 else
				 {
				 var value1 = $("#net").val();
				 var read=$('#reduct1').val()
				 var addition = parseInt(read)+parseInt(value1);
				 $('#reduct1').val(0);
				 $("#net").val(addition);
				 $('#reduct1').attr('readonly','readonly');
				 $('#reduct1').css('background-color','#EEE');
				 }
							   
		 });
							   
							   
							   
							   
		 $('.cls7').change(function(e) {
		 if($('.cls7').is(':checked')==true)
		    {				   
			  var value2 = $("#net").val();
			  //$('#reduction_type').css('background-color','#EEE');
			  $('#reduct2').removeAttr('readonly');
			  $('#reduct2').css('background-color','white');
			  var read2=$('#reduct2').val();
			  var addition2 = parseInt(read)+parseInt(value2);
			  $('#reduct2').val(read1);
			  $('#net').val(addition2);
			 }
		  else
			{
			 var value2 = $("#net").val();
			 var read2=$('#reduct2').val()
			 var addition2 = parseInt(read2)+parseInt(value2);
			 $('#reduct2').val(0);
			 $("#net").val(addition2);
			 $('#reduct2').attr('readonly','readonly');
			 $('#reduct2').css('background-color','#EEE');
		    }
							   
	 });
	 
							   
	 $('.cls8').change(function(e) {
	 if($('.cls8').is(':checked')==true)
	    {
		     var value3 = $("#net").val();
			//$('#reduction_type').css('background-color','#EEE');
			 $('#reduct3').removeAttr('readonly');
			 $('#reduct3').css('background-color','white');
			 var read3=$('#reduct3').val();
			 var addition3 = parseInt(read)+parseInt(value3);
			 $('#reduct3').val(read1);
			 $('#net').val(addition3);
			 }
		else
			 {
			 var value3 = $("#net").val();
			 var read3=$('#reduct3').val();
			 var addition3 = parseInt(read3)+parseInt(value3);
			 $('#reduct3').val(0);
			 $("#net").val(addition3);
		     $('#reduct3').attr('readonly','readonly');
			 $('#reduct3').css('background-color','#EEE');
			 }			   
		 });
		 */
	 } 
	 else
		{    //alert("44");
			// $('#net').val(addition);
			$('#hra_deduc').val(0);
                     //  $("#reduction_type1").hide();
			$('#hra_deduc').attr('readonly','readonly');
			$('#hra_deduc').css('background-color','#EEE');
			//$('#reduct2').val(0);
           //	$('#reduct2').attr('readonly','readonly');
			//$('#reduct2').css('background-color','#EEE');
			$('#reduct3').val(0);
			$('#reduct3').attr('readonly','readonly');
			$('#reduct3').css('background-color','#EEE');
			}
					
					
					
					
					 if ($(this).val() == "no_ovd1")
					 {
						//alert("55");
				   // var reduct1 = $('#reduct1').val();
					//var reduct2 = $('#reduct2').val();
					var reduct3 = $('#reduct3').val();
					var net = $('#net').val();
					
					var addition =  parseInt(reduct3)+parseInt(net);
					//var addition = parseInt(reduct1)+ parseInt(reduct2)+ parseInt(reduct3)+parseInt(net);
					$('#net').val(addition);
					//$('#net').val();
                    $("#reduction_type1").prop("checked", false);
					//$("#reduction_type2").prop("checked", false);
					$("#reduction_type3").prop("checked", false);
					//$('#reduct').removeAttr('writeonly');
					 //$('#reduct').val(0);
				        $('#hra_deduc').val(0);
				//	$('#reduct2').val(0);
					$('#reduct3').val(0);
					$("#reduction_type").hide();
					//$('#overdrawn').attr('readonly');
					$('#hra_deduc').attr('readonly','readonly');
					$('#hra_deduc').css('background-color','#EEE');
				//	$('#reduct2').attr('readonly','readonly');
			      //  $('#reduct2').css('background-color','#EEE');
					$('#reduct3').attr('readonly','readonly');
					$('#reduct3').css('background-color','#EEE');
			                 //$('#reduct').removeAttr('writeonly');
                              //var value = $( this ).val();
	                          //var value1 = $("#net").val();
	                          //var c=value1-value;
                            //$( "#net" ).val( c );
                         }
					  
					
                   });
				   
			
               });
			
			
			
			$(document).ready(function(e) {
				//$( "#working_days" ).removeClass( "input[type=text]" );
				
			$('#is_overdrawn').change(function(e) {
				//alert($('#salary_type').val());
				//alert($('#is_overdrawn').val());
                if($('#is_overdrawn').val()=='no_ovd'){
					var overdrawn = $('#overdrawn').val();
					var net = $('#net').val();
					var addition = parseInt(overdrawn)+parseInt(net);
					$('#net').val(addition);
					$('#cause_holder').hide();
					$('#cause_msg').val('');
					/*if($('#salary_type').val()=='2' || $('#salary_type').val()=='8'){
					$('#cause_holder').show();
					} else {
						$('#cause_holder').hide();
					}*/
					$('#overdrawn').val(0);
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					
					//$('#part_day_holder').hide();
					//$('#working_days').val('');
				}
				if($('#is_overdrawn').val()=='yes_ovd' ){
					//alert('true');
					var cause=$('#cause_holder').val();
					var overdrawn = $('#overdrawn').val();
					var net = $('#net').val();
					var addition = parseInt(overdrawn)+parseInt(net);
					$('#net').val(addition);
					$('#cause_holder').show();
					$('#cause_msg').show();
					/*if($('#cause_msg').val()!=''){
					$('#overdrawn').removeAttr('readonly');
					$('#overdrawn').css('background-color','#FFF');
					}*/
					//$('#part_day_holder').hide();
					//$('#working_days').val('');
				}
            });
				
				
				
				
			$('#salary_type').change(function(e) 
			{
				var id=$(this).val();
				if($('#is_overdrawn').val()=='no_ovd')
				{
					var overdrawn = $('#overdrawn').val();
					var net = $('#net').val();
					var addition = parseInt(overdrawn)+parseInt(net);
					$('#net').val(addition);
					$('#cause_holder').hide();
					$('#cause_msg').hide();
					$('#overdrawn').val(0);
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					$('#part_day_holder').hide();
					$('#working_days').val('');
				}
				if($('#is_overdrawn').val()=='yes_ovd')
				{
					//alert('true');
					$('#cause_msg').show();
					
					var cause=$('#cause_holder').val();
					var overdrawn = $('#overdrawn').val();
					var net = $('#net').val();
					var addition = parseInt(overdrawn)+parseInt(net);
					$('#net').val(addition);
					$('#cause_holder').show();
					$('#overdrawn').removeAttr('readonly');
					$('#overdrawn').css('background-color','#FFF');
					$('#part_day_holder').hide();
					$('#working_days').val('');
				}
				if(id == '8')
				{
				    //alert(55);
				var a=$('#no_ovd').val();
				//$('#is_overdrawn').val(a);
				var b=$('#no_ovd1').val();
				$('#reduction').val(b);
				$('#reduction_type').hide();
			    $('#overdrawn').attr('readonly','readonly');
			    $('#overdrawn').css('background-color','#EEE');
				  $('#reduct1').attr('readonly','readonly');
			    $('#reduct1').css('background-color','#EEE');
				  $('#reduct2').attr('readonly','readonly');
			    $('#reduct2').css('background-color','#EEE');
				  $('#reduct3').attr('readonly','readonly');
			    $('#reduct3').css('background-color','#EEE');
			            $('#hra_deduc').css('background-color','#EEE');
				  $('#hra_deduc').attr('readonly','readonly');
				 document.getElementById("is_overdrawn").disabled = true;
				 document.getElementById("reduction").disabled = true;
				 
				}
				else
				{
				$('#ovd_is').show();
				$('#is_overdrawn').show();	
				 document.getElementById("is_overdrawn").disabled = false;
				 document.getElementById("reduction").disabled = false;
					}
		if($('#exist_part_day').val()!=''){
			if(id == '1')
			{
				 //$('#reduct').val(0);
				//alert(111);
				var desig=<?=$tch[0]['emp_desig']?>;
				//alert(desig);
			$('#part_salary_cause_holder').hide();
			$('#part_salary_cause_msg').val('');
			
			$('#no_salary_cause_holder').hide();
			$('#no_salary_cause_msg').val('');
			$('#is_reduct').show();
			$('#ovd_is').show();
				$('#is_overdrawn').show();
				//alert(11);
				//$('#conv_allow').removeAttr('readonly');
				//$('#conv_allow').css('background-color','#FFF');
				$('#cpf').removeAttr('readonly');
				$('#cpf').css('background-color','#FFF');
				 if($('#retirement_gpf').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				 else if($('#emp_first_join_date').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				else{
					$('#gpf').removeAttr('readonly');
					$('#gpf').css('background-color','#FFF');
					}
				
				$('#pf_loan').removeAttr('readonly');
				$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
				if(desig!='1120'){
					// alert(11212346);
						$('#pay_in_band').val('<?php echo $full_pay_band; ?>');
						$('#consolidated_pay').val(0);
						$('#grade_pay').val('<?php echo $full_grade_pay; ?>');
						var basic = parseInt($('#pay_in_band').val())+parseInt($('#grade_pay').val());
						$('#basic').val(basic);
						$('#da').val('<?php echo $full_da; ?>');
						$('#interim_relief').val('<?php echo $full_interim_relief; ?>');
						$('#hra').val('<?php echo $full_hra; ?>');
						$('#ma').val('<?php echo $full_ma; ?>');
						$('#conv_allow').val('<?php echo $full_conveyance_allowance; ?>');
						$('#hill_allow').val('<?php echo $full_hill_allowance; ?>');
						$('#gsli').val('<?php echo $gsli; ?>');
						$('#gpf').val('<?php echo $full_gpf; ?>');
						$('#i_tax').val('<?php echo $full_itax; ?>');
						$('#gross').val('<?php echo $full_gross_salary; ?>');
						$('#p_tax').val('<?php echo $full_ptax; ?>');
						$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
						$('#net').val('<?php echo $full_net_salary; ?>');
						
						 // $full_cooperative_loan = $sal_chk[0]['cooperative_loan'];
						 // $full_hbl_loan = $sal_chk[0]['hbl_loan'];
						 //  $full_festival_loan = $sal_chk[0]['festival_loan'];
						//$('#reduct').val('<?php echo  $full_other_deduction; ?>')
						<?php $full_festival_loan = $sal_chk[0]['festival_loan']; ?>
						//$('#reduct1').val('<?php echo $full_other_deduction; ?>');
						//$('#reduct2').val('<?php echo  $full_hbl_loan; ?>');
						$('#reduct3').val('<?php echo $full_festival_loan; ?>');
						
						} else {
							
					        $('#pay_in_band').val(0);
						$('#consolidated_pay').val('<?php echo $full_consolidated_pay; ?>')
						$('#grade_pay').val(0);
						var basic = parseInt($('#consolidated_pay').val())+parseInt($('#grade_pay').val());
						$('#basic').val(basic);
						$('#da').val(0);
						$('#interim_relief').val(0);
						$('#hra').val(0);
						$('#ma').val(0);
						$('#conv_allow').val(0);
						$('#gsli').val(0)
						$('#hra_deduc').val(0);
				}
						
						var pay_band = $('#pay_in_band').val();
						var grade_pay = $('#grade_pay').val();
						var da = $('#da').val();
						var interim_relief = $('#interim_relief').val();
						var hra = $('#hra').val();
						var ma = $('#ma').val();
						var conv_allow = $('#conv_allow').val();
						var cpf = $('#cpf').val();
						var consolidated_pay=$('#consolidated_pay').val();
						if(desig!='1120'){
						var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
						var min_gpf = Math.round(min_gpf_amt);
						var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
						//alert(min_gpf);
						//var gpf = $('#gpff').val();
						$('#gpf').val(min_gpf);
						} else {
							$('#gpf').val(0);
						}
						var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(consolidated_pay);
						$('#gross').val(gross);
						/*if($('#is_gpf').is(':checked')==true){
							var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
							var min_gpf = Math.round(min_gpf_amt);
						}else{
							var min_gpf = 0;
						}
						
						$('#gpf').val(min_gpf);*/
						var gpf = $('#gpf').val();
						var pfl = $('#pf_loan').val();
						var cpfd = $('#cpf_deduct').val();
						var ptax = $('#p_tax').val();
						var itax = $('#i_tax').val();
						var overdrawn = $('#overdrawn').val();
						var gsli=$('#gsli').val();
						var hra_deduc=$('#hra_deduc').val();
						/*var reduct1=$('#reduct1').val();
						var reduct2=$('#reduct2').val();*/
						var reduct3=$('#reduct3').val();
						var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
						var net = parseInt(gross)-parseInt(deduct);	
						$('#net').val(net);
						//alert($('#pay_in_band').val());
						if(desig=='1120'){
											$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#hra_deduc').attr('readonly','readonly').css('background-color','#EEE;');
				;
						}
			}
			if(id == '2'){
				//alert(1111);
				
				var desig=<?=$tch[0]['emp_desig']?>;
				$('#part_day_holder').show();
				$('#cause_holder').show();
				$('#cause_msg').val('');
				$('#limit').text(500);
				//$('#conv_allow').removeAttr('readonly');
				//$('#conv_allow').css('background-color','#FFF');
				$('#cpf').removeAttr('readonly');
				$('#cpf').css('background-color','#FFF');
				var retirement_gpf=$('#retirement_gpf').val();
				 if($('#retirement_gpf').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
	            	                $('#gpf').css('background-color','#EEE');
				}
				 else if($('#emp_first_join_date').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				else{
					
					 $('#gpf').removeAttr('readonly');
			         $('#gpf').css('background-color','#FFF');
					
					}
				$('#pf_loan').removeAttr('readonly');
				$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				//$('#p_tax').removeAttr('readonly');
				//$('#p_tax').css('background-color','#FFF');
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
				$('#hra_deduc').css('background-color','#FFF');
				$('#hra_deduc').removeAttr('readonly');
				var months_full_day = $('#month_last_date').val();
				var exist_part_day = $('#exist_part_day').val();
				if(desig!='1120'){
				//alert(1122255522);
			$('#pay_in_band').val('<?php echo $full_pay_band; ?>');
						$('#consolidated_pay').val(0);
						$('#grade_pay').val('<?php echo $full_grade_pay; ?>');
						var basic = parseInt($('#pay_in_band').val())+parseInt($('#grade_pay').val());
						$('#basic').val(basic);
						$('#da').val('<?php echo $full_da; ?>');
						$('#interim_relief').val('<?php echo $full_interim_relief; ?>');
						$('#hra').val('<?php echo $full_hra; ?>');
						$('#ma').val('<?php echo $full_ma; ?>');
						$('#conv_allow').val('<?php echo $full_conveyance_allowance; ?>');
						$('#hill_allow').val('<?php echo $full_hill_allowance; ?>');
						$('#gsli').val('<?php echo $gsli; ?>');
						$('#gpf').val('<?php echo $full_gpf; ?>');
						$('#i_tax').val('<?php echo $full_itax; ?>');
						$('#gross').val('<?php echo $full_gross_salary; ?>');
						$('#p_tax').val('<?php echo $full_ptax; ?>');
						$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
						$('#net').val('<?php echo $full_net_salary; ?>');
						
						// $full_cooperative_loan = $sal_chk[0]['cooperative_loan'];
					    // $full_hbl_loan = $sal_chk[0]['hbl_loan'];
					   // $full_festival_loan = $sal_chk[0]['festival_loan'];
					   <?php $full_festival_loan = $sal_chk[0]['festival_loan']; ?>
						//$('#reduct').val('<?php echo  $full_other_deduction; ?>')
						/*$('#reduct1').val('<?php echo $full_other_deduction; ?>');
						$('#reduct2').val('<?php echo  $full_hbl_loan; ?>');*/
						$('#reduct3').val('<?php echo $full_festival_loan; ?>');
				} else {
						$('#pay_in_band').val(0);
						$('#consolidated_pay').val(<?php echo $full_consolidated_pay;?>);
						$('#grade_pay').val(0);
						var basic = parseInt($('#consolidated_pay').val())+parseInt($('#grade_pay').val());
						$('#basic').val(basic);
						$('#da').val(0);
						$('#interim_relief').val(0);
						$('#hra').val(0);
						$('#ma').val(0);
					   
						$('#conv_allow').val(0);
						$('#p_tax').val(<?php echo $ptax; ?>);
						
					}
					var pay_band = $('#pay_in_band').val();
					var consolidated_pay=$('#consolidated_pay').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var hill_allow = $('#hill_allow').val();
					var hill_allow = $('#hill_allow').val();
					/*var reduct1=$('#reduct1').val();
					var reduct2=$('#reduct2').val();*/
					var reduct3=$('#reduct3').val();	
					//var cpf = $('#cpf').val();
					var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					$('#gross').val(gross);
					
					if(desig!='1120'){
					var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
					var min_gpf = Math.round(min_gpf_amt);
					var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
					//alert(min_gpf);
					//var gpf = $('#gpf').val();
					$('#gpf').val(min_gpf);
					} else {
						$('#gpf').val(0);
					}
						
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var reduct = $('#reduct').val();
					var hra_deduc=$('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				//$('#overdrawn').val(0);
				//alert($('#pay_in_band').val());
				if(desig=='1120'){
											$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#hra_deduc').attr('readonly','readonly').css('background-color','#EEE;');
				;
						}
			}
			if(id == '8'){
				//alert(11111111);
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#hra_deduc').attr('readonly','readonly').css('background-color','#EEE;');
			
				$('#part_salary_cause_holder').hide();
				$('#part_salary_cause_msg').val('');
				
				$('#ovd_is').hide();
				$('#is_overdrawn').hide();
				$('#is_overdrawn').val('no_ovd');
				$('#cause_holder').hide();
				$('#cause_msg').val('');
				
				$('#no_salary_cause_holder').show();
				$('#is_reduct').hide();
				
				/*$('#cause_holder').show(0);
				$('#consolidated_pay').val(0);*/
				/*$('#gsli').attr('readonly','readonly');
				$('#gsli').css('background-color','#EEE');
				//$('#cause_msg').val('');
				//('#limit').text(500);
				$('#conv_allow').attr('readonly','readonly');
				$('#conv_allow').css('background-color','#EEE');
				$('#gpf').attr('readonly','readonly');
				$('#gpf').css('background-color','#EEE');
				$('#pf_loan').attr('readonly','readonly');
				$('#pf_loan').css('background-color','#EEE');
				$('#p_tax').attr('readonly','readonly');
				$('#p_tax').css('background-color','#EEE');
				$('#i_tax').attr('readonly','readonly');
				$('#i_tax').css('background-color','#EEE');
				$('#overdrawn').attr('readonly','readonly');
				$('#overdrawn').css('background-color','#EEE');*/
				$('#part_salary_cause_holder').hide();
				$('#pay_in_band').val(0);
				$('#consolidated_pay').val(0);
				$('#grade_pay').val(0);
				$('#da').val(0);
				$('#interim_relief').val(0);
				$('#hra').val(0);
				$('#ma').val(0);
				$('#conv_allow').val(0);
				$('#hill_allow').val(0);
				$('#cpf').val(0);
				$('#gross').val(0);
				$('#gpf').val(0);
				$('#pf_loan').val(0);
				$('#p_tax').val(0);
				$('#i_tax').val(0);
				$('#overdrawn').val(0);
				$('#net').val(0);
				$('#gsli').val(0);
				$('#basic').val(0);
				$('#reduct').val(0);
				$('#hra_deduc').val(0);
				
				
			}
		}
		if($('#exist_part_day').val()==''){
			if(id == '1'){
				//alert(852);
				$('#ovd_is').show();
				$('#is_overdrawn').show();
				
				//alert(45);
			// $('#reduct').val(0);
				$('#net').val();
				$('#part_salary_cause_holder').hide();
				$('#part_salary_cause_msg').val('');
				$('#no_salary_cause_holder').hide();
			    $('#no_salary_cause_msg').val('');
			    $('#is_reduct').show();
			  
				//$('#reduct').val();
                //alert($a);
				//alert(111145454);
				//alert($('#reduct').val(0));
				var desig=<?=$tch[0]['emp_desig']?>;
				
				$('#conv_allow').attr('readonly','readonly');
				$('#conv_allow').css('background-color','#EEE');
				$('#cpf').removeAttr('readonly');
				$('#cpf').css('background-color','#FFF');
				//var retirement_gpf=$('#retirement_gpf').val();
				 if($('#retirement_gpf').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				 else if($('#emp_first_join_date').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				else{
					
					 $('#gpf').removeAttr('readonly');
					 $('#gpf').css('background-color','#FFF');
					
					}
				$('#hra_deduc').removeAttr('readonly');
			        $('#hra_deduc').css('background-color','#FFF');
				$('#pf_loan').removeAttr('readonly');
				$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				$('#p_tax').attr('readonly','readonly');
			        $('#p_tax').css('background-color','#EEE');
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
				$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
				$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>');
				$('#grade_pay').val('<?php echo $grade_pay; ?>');
				$('#basic').val('<?php echo $basic; ?>');
				$('#da').val('<?php echo $da; ?>');
				$('#interim_relief').val('<?php echo $interim_relief; ?>');
				$('#hra').val('<?php echo $hra; ?>');
				$('#ma').val('<?php echo $ma; ?>');
				$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
				$('#hill_allow').val('<?php echo $hill_allowance; ?>');
				$('#cpf').val('<?php echo $cpf; ?>');
				$('#gross').val('<?php echo $gross_salary; ?>');
				$('#gpf').val('<?php echo $gpf; ?>');
				$('#pf_loan').val('<?php echo $pfl; ?>');
				$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
				$('#p_tax').val('<?php echo $ptax; ?>');
				$('#i_tax').val('<?php echo $itax; ?>');
				$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
				$('#overdrawn').val('<?php echo $overdrawn; ?>');
				$('#reduct').val('<?php if($full_other_deduction!=0){echo $full_other_deduction;}else {echo "0";} ?>');
				$('#net').val('<?php echo $net_salary; ?>');
				
				/*if(desig=='1120'){
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
					$('#pf_loan').attr('readonly','readonly');
					$('#pf_loan').css('background-color','#EEE');
					$('#i_tax').attr('readonly','readonly');
					$('#i_tax').css('background-color','#EEE');
				}*/
				if(desig=='1120'){
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#hra_deduc').attr('readonly','readonly')
				.css('background-color','#EEE');
			
				}
			}
			if(id == '2'){
				//alert(1255);
				$('#reduct').val(0);
				//$('#hra_deduc').val(0);
				$('#net').val();
				
				var desig=<?=$tch[0]['emp_desig']?>;
				
				/*if(desig=='1120'){
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly')
				.css('background-color','#EEE');
			}*/
				//alert(1111);
				
				$('#part_day_holder').show();
				$('#part_salary_cause_holder').show();
				$('#no_salary_cause_holder').hide();
			        $('#no_salary_cause_msg').val('');
				$('#is_reduct').show();
				$('#ovd_is').show();
			        $('#is_overdrawn').show();
				/*$('#cause_holder').show();
				$('#cause_msg').val('');
				$('#limit').text(500);*/
				$('#conv_allow').attr('readonly','readonly');
				$('#conv_allow').css('background-color','#EEE');
				$('#cpf').removeAttr('readonly');
				$('#cpf').css('background-color','#FFF');
				$('#hra_deduc').removeAttr('readonly');
				$('#hra_deduc').css('background-color','#FFF');
				var retirement_gpf=$('#retirement_gpf').val();
				
				 if($('#retirement_gpf').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				 else if($('#emp_first_join_date').val()==1)
				{ 
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
				}
				else{
					
					 $('#gpf').removeAttr('readonly');
					 $('#gpf').css('background-color','#FFF');
					
					}
					
				$('#pf_loan').removeAttr('readonly');
				$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				if($('#conv_allow').val() > 0){
					$('#p_tax').removeAttr('readonly');
					$('#p_tax').css('background-color','#FFF');
				}
				
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
				$('#p_tax').attr('readonly','readonly');
				$('#p_tax').css('background-color','#EEE');
				$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
				$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>');
				$('#grade_pay').val('<?php echo $grade_pay; ?>');
				$('#basic').val('<?php echo $basic; ?>');
				$('#da').val('<?php echo $da; ?>');
				$('#interim_relief').val('<?php echo $interim_relief; ?>');
				$('#hra').val('<?php echo $hra; ?>');
				$('#ma').val('<?php echo $ma; ?>');
				$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
				$('#hill_allow').val('<?php echo $hill_allowance; ?>');
				$('#cpf').val('<?php echo $cpf; ?>');
				$('#gross').val('<?php echo $gross_salary; ?>');
			    $('#gpf').val('<?php echo $gpf; ?>');
				$('#pf_loan').val('<?php echo $pfl; ?>');
				$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
				$('#p_tax').val('<?php echo $ptax; ?>');
				$('#i_tax').val('<?php echo $itax; ?>');
				$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
				//alert('<?php echo $hra_deduction; ?>');
				$('#overdrawn').val('<?php echo $overdrawn; ?>');
				$('#reduct').val('<?php if($full_other_deduction!=0){echo $full_other_deduction;}else {echo "0";} ?>');
				$('#net').val('<?php echo $net_salary; ?>');
				/*if(desig=='1120'){
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
					$('#pf_loan').attr('readonly','readonly');
					$('#pf_loan').css('background-color','#EEE');
					$('#i_tax').attr('readonly','readonly');
					$('#i_tax').css('background-color','#EEE');
				}*/
				if(desig=='1120'){
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#hra_deduc').attr('readonly','readonly')
				.css('background-color','#EEE');
				}
			}
			if(id == '8'){
				//alert(1111111);
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly').css('background-color','#EEE;');
				
				$('#part_salary_cause_holder').hide();
				$('#part_salary_cause_msg').val('');
				
				$('#ovd_is').hide();
				$('#is_overdrawn').hide();
				$('#is_overdrawn').val('no_ovd');
				$('#cause_holder').hide();
				$('#cause_msg').val('');
				
				$('#no_salary_cause_holder').show();
				$('#is_reduct').hide();
				
				/*$('#cause_holder').show(0);*/
				//$('#cause_msg').val('');
				//('#limit').text(500);
				/*$('#conv_allow').attr('readonly','readonly');
				$('#conv_allow').css('background-color','#EEE');
				$('#cpf').attr('readonly','readonly');
				$('#cpf').css('background-color','#EEE');
				$('#gpf').attr('readonly','readonly');
				$('#gpf').css('background-color','#EEE');
				$('#pf_loan').attr('readonly','readonly');
				$('#pf_loan').css('background-color','#EEE');
				$('#p_tax').attr('readonly','readonly');
				$('#p_tax').css('background-color','#EEE');
				$('#i_tax').attr('readonly','readonly');
				$('#i_tax').css('background-color','#EEE');
				$('#gsli').attr('readonly','readonly');
				$('#gsli').css('background-color','#EEE');
				$('#overdrawn').attr('readonly','readonly');
				$('#overdrawn').css('background-color','#EEE');*/
				$('#pay_in_band').val(0);
				$('#consolidated_pay').val(0);
				$('#grade_pay').val(0);
				$('#da').val(0);
				$('#interim_relief').val(0);
				$('#hra').val(0);
				$('#ma').val(0);
				$('#conv_allow').val(0);
				$('#hill_allow').val(0);
				$('#cpf').val(0);
				$('#gross').val(0);
				$('#gpf').val(0);
				$('#pf_loan').val(0);
				$('#cpf_deduct').val(0);
				$('#p_tax').val(0);
				$('#i_tax').val(0);
				$('#gsli').val(0);
				$('#hra_deduc').val(0);
				$('#overdrawn').val(0);
				$('#net').val(0);
				$('#basic').val(0);
				$('#reduct1').val(0);
				$('#reduct2').val(0);
				$('#reduct3').val(0);
				//$('#reduct1').removeAttr('writeonly');
				//$('#reduct2').removeAttr('writeonly');
				$('#reduct3').removeAttr('writeonly');
				$("#reduction_type1").prop("checked", false);
				//$("#reduction_type2").prop("checked", false);
				$("#reduction_type3").prop("checked", false);
				$('#reduction_type').hide();
				
				
			}
		
	
	$('#no_ovd').click(function(){
				if($('#salary_type').val() == '1'){
					$('#cause_holder').hide();
					$('#cause_msg').val('');
					$('#limit').text(500);
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					var net_amt = parseInt($('#overdrawn').val())+parseInt($('#net').val());
					$('#net').val(net_amt);
					$('#overdrawn').val(0);
					
				}
				else{ 
				$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
				}
	});
	}
		});
	 });
    </script>
				<style>
				
				input[type=text], textarea{
					padding: 2px;
					-moz-border-radius: 3px;
					border-radius: 3px;
					border: 1px solid #3E4255;
					
				}
				
				</style>
              
                <input type="hidden" name="pan_no" id="pan_no" value="<?=$tch[0]['emp_pan_no']?>" />
				<form id="form" method="post" action="ajax_ps_rq_submit.php" enctype="multipart/form-data">
                <input type="hidden" name="spouse_med_al" id="spouse_med_al" value="<?php echo $tch[0]['spouse_medical_allowance']; ?>" >
                <input type="hidden" name="emp_res_st" id="emp_res_st" value="<?php echo $tch[0]['emp_res_status']; ?>" >
                <input type="hidden" name="exist_part_day" id="exist_part_day" value="<?php echo $sal_chk[0]['part_day']; ?>" >
                <input type="hidden" name="ps_id_fk" id="ps_id_fk" value="<?php echo $dise; ?>" >
                <div class="school">
                <div class="table-responsive">
                <table>
                	<tr>
                    	<td>Salary Type <span class="star_color">*</span>:</td>
                        <td>
                        	<?php 
							$salary_type = $db->fetch_table("
								SELECT type_id, salary_type
								FROM prd_salary_type WHERE active=1
								ORDER BY type_id ASC;
							");
							$sal_caus = $db->fetch_table("select overdrawn,salary_type,cause,part_day,cpf,gpf,gsli,part_salary_cause,no_salary_cause from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='".$tch[0]['emp_id_pk']."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
							?>
                            <select name="salary_type" id="salary_type" class="form-control" style="width:150px;border: 1px solid #3E4255"  >
                        	<!--<option value="">Please Select</option>-->
                            <?php foreach ($salary_type as $keys) { ?>
                            <option value="<?php echo $keys['type_id']; ?>" <?php if($keys['type_id']==$sal_caus[0]['salary_type']){ echo 'selected';} ?>><?php echo $keys['salary_type']; ?></option>
                            <?php } ?>
                        </select>
                        </td>
                    </tr>
                     <tr id="part_salary_cause_holder" <?php if($sal_caus[0]['salary_type']== '2'){ ?><?php }else{ ?> style="display:none;" <?php } ?>>
                    	<td>Part Salary Cause <span class="star_color">*</span>:</td>
                        <td>
                        	<textarea class="form-control" style="height:75px; width:300px; font-size:13px; font-family:Verdana, Geneva, sans-serif; resize:none;border: 1px solid #3E4255" id="part_salary_cause_msg" name="part_salary_cause_msg" onkeyup="return limiter('part');" onkeypress="return keyRestrict(event,' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@._-');"> <?php echo $sal_caus[0]['part_salary_cause']; ?></textarea>
                    <br />
               <span id="limit_holder"> <span id="limit_part" style="font-weight:bold; color:#F00;">500</span><span> <strong>of 500 maximum character.</strong></span></span>
                    
                        </td>
                    </tr>
                     <tr id="no_salary_cause_holder" <?php if($sal_caus[0]['salary_type']== '8'){ ?><?php }else{ ?> style="display:none;" <?php } ?>>
                    	<td>No Salary Cause <span class="star_color">*</span>:</td>
                        <td>
                        	<textarea class="form-control" style="height:75px; width:300px; font-size:13px; font-family:Verdana, Geneva, sans-serif; resize:none;border: 1px solid #3E4255" id="no_salary_cause_msg" name="no_salary_cause_msg" onkeyup="return limiter('no');" onkeypress="return keyRestrict(event,' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@._-');"><?php echo $sal_caus[0]['no_salary_cause']; ?></textarea>
                    <br />
               <span id="limit_holder"> <span id="limit_no" style="font-weight:bold; color:#F00;">500</span><span> <strong>of 500 maximum character.</strong></span></span>
                    </span>
                        </td>
                    </tr>
                     <tr id="part_day_holder" <?php if($sal_caus[0]['part_day']){ ?>  <?php }else{ ?> style="display:none;" <?php } ?>>
                    	<td>Working Days <span class="star_color">*</span>:</td>
                        <td>
                        	<input maxlength="2" class="form-control" type="text" id="working_days" name="working_days" value="<?php echo $sal_caus[0]['part_day']; ?>" size="5" onKeyUp="return workingDaysCal();" onkeypress="return keyRestrict(event,'0123456789')" autocomplete="off" style="width:80px" />
                            <input id="month_last_date" type="hidden" name="month_last_date" value="<?php echo date('t'); ?>">
                        </td>
                    </tr>
                    <!--<tr>
                    	<td>Whether :</td>
                        <td>
                        	<input type="radio" name="is_cgpf" id="is_gpf" value="is_gpf" <?php //if(!$sal_caus[0]['gpf'] || $sal_caus[0]['gpf'] > 0){ ?> checked <?php //} ?> > GPF
                            <input type="radio" name="is_cgpf" id="is_cpf" value="is_cpf" <?php //if($sal_caus[0]['cpf'] > 0){ ?> checked <?php //} ?> > CPF
                        </td>
                    </tr>-->
                    <tr id="ovd_is"  <?php if($sal_caus[0]['salary_type'] !='8'){ ?><?php }else{ ?> style="display:none;" <?php } ?>>
                    	<td >Overdrawn :</td>
                        <td>
                        	
                     <select name="is_overdrawn" id="is_overdrawn" class="form-control" style="width:150px !important;border: 1px solid #3E4255">				
                      <option id="no_ovd" value="no_ovd" <?php if(!$sal_caus[0]['overdrawn']){ echo "selected"; } 
					 ?>>NO</option>
                      <option id="yes_ovd" value="yes_ovd"  <?php if($sal_caus[0]['overdrawn']){ echo "selected"; } ?>>YES</option> 
                      </select> 	
                            <!--<input type="radio" name="is_overdrawn" id="no_ovd" value="no_ovd" <?php //if(!$sal_caus[0]['overdrawn']){ ?> checked <?php //} ?> > No
                            <input type="radio" name="is_overdrawn" id="yes_ovd" value="yes_ovd" <?php //if($sal_caus[0]['overdrawn']){ ?> checked <?php //} ?> > Yes-->
                        </td>
                    </tr>
		    <tr id="cause_holder" <?php if($sal_caus[0]['overdrawn']>0 && $sal_caus[0]['salary_type']!= '8'){ ?><?php }else{ ?> style="display:none;" <?php } ?>>
                    	<td>Overdran Cause <span class="star_color">*</span>:</td>
                        <td>
                        	<textarea class="form-control" style="height:75px; width:300px; font-size:13px; font-family:Verdana, Geneva, sans-serif; resize:none;border: 1px solid #3E4255" id="cause_msg" name="cause_msg" onkeyup="return limiter('over');" onkeypress="return keyRestrict(event,' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@._-');"><?php echo $sal_caus[0]['cause']; ?></textarea>
                    <br />
               <span id="limit_holder"> <span id="limit_over" style="font-weight:bold; color:#F00;">500</span><span> <strong>of 500 maximum character.</strong></span></span>
                    </span>
                        </td>
                    </tr>
                      <tr id="is_reduct"  <?php if($sal_caus[0]['salary_type']!= '8'){ ?><?php }else{ ?> style="display:none;" <?php } ?>><td>Other Deduction :</td><td>
                      <?php 
					 
							$dedact_cause = $db->fetch_table("select festival_loan_cause,hra_loan_cause from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='".$tch[0]['emp_id_pk']."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
							 
							?>
                            
          <select name="reduction" id="reduction" class="form-control" style="width:150px;border: 1px solid #3E4255">
                             <option id="no_ovd1" value="no_ovd1" <?php if($str1[0]==0){ echo 'selected';} ?>>NO</option>
                      <option id="yes_ovd1" value="yes_ovd1" <?php if($dedact_cause[0]['festival_loan_cause']==1 || $dedact_cause[0]['hra_loan_cause']==1){ echo 'selected';} ?> >YES</option>   
                           
                        </select>
                    </td></tr>
                  
                  
               
                
                  
                    <tr id="reduction_type" ><td>Deduction Cause <span class="star_color">*</span>:</td>   
                    <td width="65%" style="text-align:left;"><p><input type="checkbox" name="n7" id="reduction_type1" class="cls6" value="1" <?php  if($dedact_cause[0]['hra_loan_cause']==1) { ?>checked="checked" <?php } ?>>&nbsp;&nbsp;&nbsp;<b>HRA Deduction</b></p>
                   
  <!--<p><input type="checkbox" name="n8" id="reduction_type2" class="cls7" value="1" <?php  if( $dedact_cause[0]['hbl_loan']==1) { ?>checked="checked" <?php } ?>>&nbsp;&nbsp;&nbsp;<b>HBL Recovery</b></p>-->
  
  <p><input type="checkbox" name="n9" id="reduction_type3" class="cls8" value="1" <?php  if($dedact_cause[0]['festival_loan_cause']==1) { ?>checked="checked" <?php } ?>>&nbsp;&nbsp;&nbsp;<b>Festival Advance Recovery
 </b></p>
</td>
 </tr>
 
 
 
 
 
 
                 </table>
               </div>
               </div>
               <div class="school">
				 <div class="table-responsive">
                <table width="100%">
                	<tr>
                    	<th>&nbsp;</th>
                    	<th colspan="7" align="center"><strong>PAY & ALLOWANCE</strong></th>
                        <th colspan="1" align="center"></th>
                        <th>&nbsp;</th>
                        <th colspan="8" align="center"><strong>DEDUCTION</strong></th>
                        <th>&nbsp;</th>
                    </tr>
					<tr>
					<tr>
						<th>NAME OF EMPLOYEE</th>
                    <th>PAY IN <br>PAY BAND</th>
                    <th>GRADE<br>PAY</th>
                    <th>D.A(<?php echo $da_per; ?>%)</th>
                    <th>HRA(<?php echo $hra_per; ?>%)</th>
                    <th>MA</th>
                    <th>CONV<br>ALLOW</th>
                    <th>HILL AllOW<span  style="font-size:9px;">(min 15%)</span></th>
                    <th>INTERIM RELIEF</th>
                    <th>GROSS<br>SALARY</th>
                    <th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
                    <th>PF LOAN RECOVERY</th>
                    <th>P.TAX</th>
                    <th>I.TAX</th>
                    <th>GSLI</th>
                    <th>HRA DEDUCTION</th>
                    <th>OVER<br>DRAWN</th>
                    <th>FESTIVAL ADVANCE RECOVERY</th>
                    <th>NET SALARY</th>
					</tr>
					<tr style="background-color: rgb(221, 247, 255);">
						<td>
                        <b><?php echo $tchname; ?></b>
                        <!--<br>
                <span style="color:#FB1607; font-weight:bold; font-size:11px;">
				<?php 
					/*echo salaryType($tch[0]['salary_type']);
					if($tch[0]['salary_type']==3){
						echo '('.$tch[0]['type_effect'].'%)';
					}
					if($tch[0]['salary_type']==4){
						echo '('.$tch[0]['type_effect'].' Day)';
					}*/
				 ?>
                </span>-->
                        </td>
                        
                   <td>
		       <input maxlength="5" style='background-color: #EEE;' type="hidden" id="consolidated_pay" name="consolidated_pay" value="<?php echo round($consolidated_pay); ?>" readonly size="5" />
                        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                        <input type="hidden" name="rank" id="rank" value="<?php echo $tch[0]['rank']; ?>" />
                        <input type="hidden" name="empcd" id="empcd" value="<?php echo $tch[0]['empcd']; ?>" />
                         <input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<?php echo $tch[0]['emp_id_pk']; ?>" />
                        <input type="hidden" name="ps_id_fk" id="ps_id_fk" value="<?php echo $dise; ?>" />
                        <input type="hidden" name="tchname" id="tchname" value="<?php echo $tch[0]['emp_first_name'].' '.$tch[0]['emp_second_name'].' '.$tch[0]['emp_last_name']; ?>" />
                        <input type="hidden" name="bankname" id="bankname" value="<?php echo $tch[0]['emp_bank_name']; ?>" />
                        <input type="hidden" name="accountno" id="accountno" value="<?php echo $tch[0]['emp_acc_no']; ?>" />
                        <input type="hidden" name="bank_ifsc" id="bank_ifsc" value="<?php echo $tch[0]['emp_ifsc_no']; ?>" />
                        <input type="hidden" name="code" id="code" value="<?php echo $tch[0]['emp_system_code']; ?>" />
                        <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?php echo $tch[0]['emp_id_pk']; ?>" />
                        <input type="hidden" name="basic" id="basic" value="<?php echo $basic; ?>" />
                        <input maxlength="5" style='background-color: #EEE;' type="text" id="pay_in_band" name="pay_in_band" value="<?php echo round($pay_in_band); ?>" readonly size="5" />
                        <input type="hidden" id="retirement_gpf" name="retirement_gpf" autocomplete="off" <?php if($gpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?>  <?php if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0){ ?>   value="<?php echo 1; ?>"      <?php } ?>  />
                          <input type="hidden" id="emp_first_join_date" name="emp_first_join_date" autocomplete="off" <?php if($gpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?>  <?php if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
				{?>   value="<?php echo 1; ?>"      <?php }?>  />
                        </td>
                        
						<td><input maxlength="5" style='background-color: #EEE;' type="text" id="grade_pay" name="grade_pay" value="<?php echo round($grade_pay); ?>" readonly size="5" /></td>
						<td><input maxlength="5" style='background-color: #EEE;' type="text" id="da" name="da" value="<?php echo round($da); ?>" size="5" readonly='readonly' /></td>
						<td><input maxlength="5" style='background-color: #EEE;' type="text" id="hra" name="hra" readonly value="<?php echo round($hra); ?>"size="5" /></td>
						<td><input maxlength="5" style='background-color: #EEE;' type="text" id="ma" name="ma" readonly value="<?php echo round($ma); ?>"size="5" /></td>
						<td><input maxlength="5" style='background-color: #EEE;' type="text" id="conv_allow" readonly name="conv_allow" value="<?php echo round($conveyance_allowance); ?>"size="5" /></td>
						<!--<td><input maxlength="5" style='background-color: #EEE;' type="text" id="hill_allow" readonly name="hill_allow" value="<?php //echo round($hill_allowance); ?>"size="5" /></td>
                        <td><input maxlength="6" type="text" id="cpf" name="cpf" autocomplete="off" <?php if($cpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?> value="<?php echo $cpf; ?>"size="5" onKeyUp="return cpfCal();"  onkeypress="return keyRestrict(event,'0123456789.');"></td>-->
                        <td><input maxlength="5" style='background-color: #EEE;' type="text" id="hill_allow" readonly name="hill_allow" value="<?php echo round($hill_allowance); ?>"size="5" /></td>
						
                        
                         <td><input maxlength="5" style='background-color: #EEE;' type="text" id="interim_relief" name="interim_relief" value="<?php echo round($interim_relief); ?>" size="5" readonly='readonly' /></td>
                        <td><input readonly maxlength="5" type="text" style='background-color: #EEE;' id="gross" name="gross" value="<?php echo round($gross_salary); ?>"size="4" /></td>
					
                        

                        
                        
                      <td><input maxlength="5" type="text" id="gpf" name="gpf" autocomplete="off" <?php if($gpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?> value="<?php echo $gpf; ?>"size="4"  <?php  if((strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0) || (strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)){ ?>  onBlur="return retirement_gpfCal();"     <?php } else{?> onBlur="return gpfCal();" <?php } ?> onkeypress="return keyRestrict(event,'0123456789');" /></td> 
                        
                        
                        
                        
                        
						<td><input maxlength="5" type="text" id="pf_loan" name="pf_loan" value="<?php echo $pfl; ?>"size="5" onKeyUp="return pflCal();" onkeypress="return keyRestrict(event,'0123456789');"/></td>
                        
                        <!--<td><input maxlength="5" style='background-color: #EEE;' readonly type="text" id="cpf_deduct" name="cpf_deduct" value="<?php echo $cpf_deduct; ?>"size="5" /></td>-->
						<td><input readonly maxlength="5" style='background-color: #EEE;' type="text" id="p_tax" name="p_tax" onkeypress="return keyRestrict(event,'0123456789');" value="<?php echo $ptax; ?>"size="5"   /></td>
						<td><input maxlength="5" type="text" id="i_tax" name="i_tax" value="<?php echo $itax; ?>"size="5" onKeyUp="return itaxCal();" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                        <td><input maxlength="3" type="text" id="gsli" name="gsli" value="<?php echo $gsli; ?>"size="5" onKeyUp="return gsliCal();" onkeypress="return keyRestrict(event,'0123456789');" autocomplete="off" /></td>
			
			
				  <td><input type="text"  maxlength="5" style='background-color: #EEE;'  id="hra_deduc" name="hra_deduc" value="<?php if($hra_deduction){echo $hra_deduction; } else{ echo '0';} ?>"size="5" onKeyUp="return hra_deduction();"  onkeypress="return keyRestrict(event,'0123456789');" autocomplete="off"/> </td>
              
			
			
			
                        <td><input type="text" <?php if($sal_caus[0]['salary_type'] == '1' || !$sal_caus[0]['salary_type']){ ?> readonly <?php } ?> maxlength="5"  id="overdrawn" name="overdrawn" value="<?php echo $overdrawn; ?>"size="5" onKeyUp="return ovdCal();" <?php if($sal_caus[0]['salary_type'] == '1' || !$sal_caus[0]['salary_type']){ ?> style='background-color:#EEE;' <?php } ?> onkeypress="return keyRestrict(event,'0123456789');" autocomplete="off"/></td>
                        
                        
                       
                       <!-- <td><input maxlength="5" style='background-color: #EEE;' type="text" name="reduct1" readonly size="5" id="reduct1" value="<?php if($cooperative_loan ==0){echo '0';} else {echo trim($cooperative_loan);} ?> " size="5" autocomplete="off" onKeyUp="return reduc(this.value);" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                        
                        <td><input maxlength="5" style='background-color: #EEE;' type="text" name="reduct2" readonly size="5" id="reduct2" value="<?php if($hbl_loan ==0){echo '0';} else {echo trim($hbl_loan) ;} ?> " size="5" autocomplete="off" onKeyUp="return reduc2(this.value);" onkeypress="return keyRestrict(event,'0123456789');" /></td>-->
                        <td><input maxlength="5" style='background-color: #EEE;' type="text" name="reduct3" readonly size="5" id="reduct3" value="<?php if($festival_loan==0){echo '0';} else {echo trim($festival_loan);} ?> " size="5" autocomplete="off" onKeyUp="return reduc3(this.value);" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                        
                        
                        
						<td><input maxlength="5" type="text" style='background-color: #EEE;' id="net" name="net" value="<?php echo round($net_salary); ?>"size="6" readonly='readonly' /></td>
					</tr>
				</table>
                </div>
                </div>
                <input type="hidden" name="cpf_per" id="cpf_per" value="<?php echo $cpf_per; ?>" >
                <input type="hidden" name="actual_gross" id="actual_gross" value="<?php echo $gross_salary; ?>" >
                <input type="hidden" name="actual_net" id="actual_net" value="<?php echo $net_salary; ?>" >
                 <input type="hidden" name="emp_dif" id="emp_dif" value="<?php echo $tch[0]['emp_diff_able']; ?>" >
                 <input type="hidden" name="emp_dif" id="emp_dif" value="<?php echo $tch[0]['conv_allow_status']; ?>" >
                <br>
				<center><input type="submit" class="btn btn-info" id="submit" value="Submit" onClick="return checkForm();"></center>
                 <p style="color:#F51102;">
                <strong>Note :</strong>
                <ul>
                	<li style="color:#F51102; font-weight:bold;">Dark input field is not editable.</li>

                    <li style="color:#F51102; font-weight:bold;">I-Tax will be disable if employee have no PAN no.</li>
                    <li style="color:#F51102; font-weight:bold;">Overdrawn amount should not be greater than Pay in pay band amount.</li>
                    <li style="color:#F51102; font-weight:bold;">If employee belongs to Govt house scheme than HRA will be zero and non-editable.</li>
                </ul>
                </p>
				</div>
				</form>
		
	
<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 5px 10px;
}

</style>
   
   <script>
   $(document).ready(function(e1) {
	
	   <?php if($sal_chk[0]['salary_type']=='8') { ?>
	     // alert("4645");
	   $('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#overdrawn').attr('readonly','readonly').css('background-color','#EEE;');
	
	   <?php } ?>
    
		if($('#salary_type').val()=='8'){
			
				$('#pay_in_band').val(0);
				$('#consolidated_pay').val(0);
				$('#grade_pay').val(0);
				$('#da').val(0);
				$('#interim_relief').val(0);
				$('#hra').val(0);
				$('#ma').val(0);
				$('#conv_allow').val(0);
				$('#hill_allow').val(0);
				$('#cpf').val(0);
				$('#gross').val(0);
				$('#gpf').val(0);
				$('#pf_loan').val(0);
				$('#cpf_deduct').val(0);
				$('#p_tax').val(0);
				$('#i_tax').val(0);
				$('#overdrawn').val(0);
				$('#net').val(0);
				$('#basic').val(0);
				$('#hra_deduc').val(0);
				//$('#reduct1').val(0);
				//$('#reduct2').val(0);
				$('#reduct3').val(0);
				
			}
			$('#no_ovd').click(function(){
				
				if($('#salary_type').val() == '1'){
					$('#cause_holder').hide();
					$('#cause_msg').val('');
					$('#limit').text(500);
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					var net_amt = parseInt($('#overdrawn').val())+parseInt($('#net').val());
					$('#net').val(net_amt);
					$('#overdrawn').val(0);
					
					
				}
				
				
			
				if($('#salary_type').val() > '1'){
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					var net_amt = parseInt($('#overdrawn').val())+parseInt($('#net').val());
					$('#net').val(net_amt);
					$('#overdrawn').val(0);
				}
			});
		   var count = $('#cause_msg').val().length;
		   $('#limit').text(500-count)
        
});

var ppp=$('#pay_in_band').val();
   </script>


 <?php 
 if($sal_chk[0]['block_code'] && $sal_chk[0]['emp_id_fk'] && $sal_chk[0]['salary_type']=='2'){ 
 ?>
  <script>

 
	
	$('#working_days').keyup(function(e){
		//alert(1111);
		
		var work_days = $('#working_days').val();
			var max_days_of_month = $('#month_last_date').val();
			if($('#working_days').val() >= parseInt($('#month_last_date').val()) || $('#working_days').val() == '0'){
				alert('Please enter the valid working days for part salary calculation');
				$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
					$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>');
					$('#grade_pay').val('<?php echo $grade_pay; ?>');
					$('#da').val('<?php echo $da; ?>');
					$('#interim_relief').val('<?php echo $interim_relief; ?>');
					$('#hra').val('<?php echo $hra; ?>');
					$('#ma').val('<?php echo $ma; ?>');
					$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
					$('#hill_allow').val('<?php echo $hill_allowance; ?>');
					//$('#cpf').val('<?php echo $cpf; ?>');
					$('#gpf').val('<?php echo $gpf; ?>');
					$('#pf_loan').val('<?php echo $pfl; ?>');
					//$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
					$('#p_tax').val('<?php echo $ptax; ?>');
					$('#gsli').val('<?php echo $gsli; ?>');
					$('#i_tax').val('<?php echo $itax; ?>');
					$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
					$('#overdrawn').val('<?php echo $overdrawn; ?>');
					$('#basic').val('<?php echo $basic; ?>');
					var consolidated_pay=$('#consolidated_pay').val();
					var pay_band = $('#pay_in_band').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var hill_allow = $('#hill_allow').val();
					var cpf = $('#cpf').val();
					
					//alert(consolidated_pay);
					
					var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(hill_allow)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
									
					//cal_ptax(gross);
				$('#gross').val(gross);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
				//	var reduct1=$('#reduct1').val();
				//	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				$('#working_days').val('');
				
				$('#working_days').focus();
			}else{
				//alert('ttt');
				if($('#working_days').val()==''){
					$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
					$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>');
					$('#grade_pay').val('<?php echo $grade_pay; ?>');
					$('#da').val('<?php echo $da; ?>');
					$('#interim_relief').val('<?php echo $interim_relief; ?>');
					$('#hra').val('<?php echo $hra; ?>');
					$('#ma').val('<?php echo $ma; ?>');
					$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
					$('#hill_allow').val('<?php echo $hill_allowance; ?>');
					//$('#cpf').val('<?php echo $cpf; ?>');
					$('#gpf').val('<?php echo $gpf; ?>');
					$('#pf_loan').val('<?php echo $pfl; ?>');
					//$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
					$('#p_tax').val('<?php echo $ptax; ?>');
					$('#gsli').val('<?php echo $gsli; ?>');
					$('#i_tax').val('<?php echo $itax; ?>');
					$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
					$('#overdrawn').val('<?php echo $overdrawn; ?>');
					$('#basic').val('<?php echo $basic; ?>');
					var consolidated_pay=$('#consolidated_pay').val();
					var pay_band = $('#pay_in_band').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
						var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var hill_allow = $('#hill_allow').val();
					var cpf = $('#cpf').val();
					
					//alert(consolidated_pay);
					
					var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(hill_allow)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
										
					//cal_ptax(gross);
				$('#gross').val(gross);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
					/*var reduct1=$('#reduct1').val();
					var reduct2=$('#reduct2').val();*/
					var reduct3=$('#reduct3').val();
					var hra_deduc=$('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
					
				}else{
				//alert(145);
				
					
					var exist_part_day = $('#exist_part_day').val();
					var pay_in_pay_band = $('#pay_in_band').val();
					//var consolidated_pay=$('#consolidated_pay').val();
					
//					if(pay_in_pay_band!=0){
//					var part_pay_band = Math.round((parseInt(<?php echo $pay_in_band; ?>)/parseInt(exist_part_day))*parseInt(work_days));
//					//var part_pay_band = Math.round((parseInt(pay_in_pay_band)/parseInt(exist_part_day))*parseInt(work_days));
//					$('#pay_in_band').val(part_pay_band);
//					}else if(consolidated_pay!=0){
//						var part_consolidated_pay = Math.round((parseInt(<?php echo $consolidated_pay; ?>)/parseInt(exist_part_day))*parseInt(work_days));
//						$('#consolidated_pay').val(part_consolidated_pay);
//					}
					var grade_pay = $('#grade_pay').val();
					var part_grade_pay = Math.round((parseInt(<?php echo $grade_pay; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#grade_pay').val(part_grade_pay);
					var basic = parseInt(part_pay_band)+parseInt(part_consolidated_pay)+parseInt(part_grade_pay);
					$('#basic').val(basic);
					
					
					var da = $('#da').val();
					var part_da = Math.round((parseInt(<?php echo $da; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#da').val(part_da);
					
						
					var interim_relief = $('#interim_relief').val();
					var part_interim_relief = Math.round((parseInt(<?php echo $interim_relief; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#interim_relief').val(part_interim_relief);
					
					var hill_allow = $('#hill_allow').val();
					
					var hill_allow = Math.round((parseInt(<?php echo $hill_allowance; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					
					$('#hill_allow').val(hill_allow);
					
					
					
					
					 var hra = Math.round((parseInt(<?php echo $hra; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
						
					$('#hra').val(hra);
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
						var spouse_ma = $('#spouse_med_al').val();
						if(spouse_ma=='Yes'){
							$('#ma').val(0);
						}else{
							//var ma = $('#ma').val();
							var part_ma = Math.round((parseInt(<?php echo $max_ma; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
							$('#ma').val(part_ma);
						}
					
					
					
					
					
					
					
					
					
					
			
					
					var conv_allow = $('#conv_allow').val();
					var part_conv_allow = Math.round((parseInt(<?php echo $conveyance_allowance; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#conv_allow').val(part_conv_allow);
					
					var hill_allow = $('#hill_allow').val();
					var part_hill_allow =Math.round((parseInt(<?php echo $hill_allowance; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#hill_allow').val(part_hill_allow);
					
					var pay_band = $('#pay_in_band').val();
					var consolidated_pay=$('#consolidated_pay').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					//$('#hill_allow');
					//var hill_allow = $('#hill_allow').val();
					//var cpf = $('#cpf').val();
				//alert(hill_allow);
					var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow);
					<?php if($tch[0]['emp_diff_able']!='1'){ ?>
					cal_ptax(gross);
					<?php } else { ?>
					$('#p_tax').val(0);
					<?php } ?>
					
					$('#gross').val(gross);
					var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay)+parseInt(consolidated_pay))/100)*6;
					var min_gpf = Math.round(min_gpf_amt);
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var hra_deduc = $('#hra_deduc').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc); 
					var net = parseInt(gross)-parseInt(deduct);
				    $('#net').val(net);
				}
			}
			
		});

	
    </script>
    <?php }else{ ?>
    <script>
	$('#working_days').keyup(function(e) {
		//alert(582);
			var work_days = $('#working_days').val();
			var max_days_of_month = $('#month_last_date').val();
			
			if($('#working_days').val() >= parseInt($('#month_last_date').val()) || $('#working_days').val() == '0'){
				alert('Please enter the valid working days for part salary calculation');
				$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
					$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>')
					$('#grade_pay').val('<?php echo $grade_pay; ?>');
					$('#da').val('<?php echo $da; ?>');
					$('#interim_relief').val('<?php echo $interim_relief; ?>');
					$('#hra').val('<?php echo $hra; ?>');
					$('#ma').val('<?php echo $ma; ?>');
					$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
					$('#hill_allow').val('<?php echo $hill_allowance; ?>');
					$('#gpf').val('<?php echo $gpf; ?>');
					$('#pf_loan').val('<?php echo $pfl; ?>');
					$('#p_tax').val('<?php echo $ptax; ?>');
					$('#i_tax').val('<?php echo $itax; ?>');
					$('#gsli').val('<?php  echo $gsli; ?>');
					$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
					$('#overdrawn').val('<?php echo $overdrawn; ?>');
					$('#basic').val('<?php echo $basic; ?>');
					var pay_band = $('#pay_in_band').val();
					var consolidated_pay=$('#consolidated_pay').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var hill_allaw=$('#hill_allow').val();
					var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
					$('#gross').val(gross);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
				//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				$('#working_days').val('');
				$('#working_days').focus();
			}else{
				
				if(work_days==''){
				
					
					$('#pay_in_band').val('<?php echo $pay_in_band; ?>');
					$('#consolidated_pay').val('<?php echo $consolidated_pay; ?>')
					$('#grade_pay').val('<?php echo $grade_pay; ?>');
					$('#da').val('<?php echo $da; ?>');
					$('#interim_relief').val('<?php echo $interim_relief; ?>');
					$('#hra').val('<?php echo $hra; ?>');
					$('#ma').val('<?php echo $ma; ?>');
					$('#conv_allow').val('<?php echo $conveyance_allowance; ?>');
					$('#hill_allow').val('<?php echo $hill_allowance; ?>');
					$('#gpf').val('<?php echo $gpf; ?>');
					$('#pf_loan').val('<?php echo $pfl; ?>');
					$('#p_tax').val('<?php echo $ptax; ?>');
					$('#i_tax').val('<?php echo $itax; ?>');
					$('#gsli').val('<?php  echo $gsli; ?>');
					$('#hra_deduc').val('<?php echo $hra_deduction; ?>');
					$('#overdrawn').val('<?php echo $overdrawn; ?>');
					$('#basic').val('<?php echo $basic; ?>');
					var pay_band = $('#pay_in_band').val();
					//var consolidated_pay=$('#consolidated_pay').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var hill_allaw=$('#hill_allow').val();
				
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
					//alert(gross);
					$('#gross').val(gross);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					
					var hra_deduc = $('#hra_deduc').val();
					var gsli=$('#gsli').val();
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn);
				//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					//var reduct3=$('#reduct3').val();
					
					//alert(hra_deduc);
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(hra_deduc);
					
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				}else{
					//alert(123); 
					//$('#consolidated_pay').val(0);
					
					var desig=<?=$tch[0]['emp_desig']?>;
					var pay_in_pay_band = $('#pay_in_band').val();
					var hill_allow = $('#hill_allow').val();
					var hill_allow = Math.round((parseInt(<?php echo $hill_allowance; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
					$('#hill_allow').val(hill_allow);
					//alert(pay_in1);
					
					var consolidated_pay=$('#consolidated_pay').val();
					if(pay_in_pay_band!=0){
					var part_pay_band = Math.round((parseInt(<?php echo $pay_in_band; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
					//var part_pay_band = Math.round((parseInt(pay_in_pay_band)/parseInt(max_days_of_month))*parseInt(work_days));
					$('#pay_in_band').val(part_pay_band);
					part_consolidated_pay=0;
					}
					
					if(desig!='1120'){
					var grade_pay = $('#grade_pay').val();
					var part_grade_pay = Math.round((parseInt(<?php echo $grade_pay; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
					} else {
					part_grade_pay=0;	
					}
					
					$('#grade_pay').val(part_grade_pay);
					var basic = parseInt(part_pay_band)+parseInt(part_grade_pay)+parseInt(part_consolidated_pay);
					$('#basic').val(basic);
					//alert(part_pay_band);
					if(desig!='1120'){
					var da = Math.round(((parseInt(part_pay_band)+parseInt(part_grade_pay))/100)*parseInt('<?php echo $da_per ?>'));
					} else {
						da=0;	
					}
					$('#da').val(da);
					if(desig!='1120'){
						var interim_relief = $('#interim_relief').val();
					var interim_relief =  Math.round((parseInt(<?php echo $interim_relief; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
					} else {
						var interim_relief=0;	
					}
					$('#interim_relief').val(interim_relief);
					//alert(<?php echo $tch[0]['emp_desig']?>);
					
					if(desig=='1120'){
						$('#hra').val(0);
					}
					else{
						
						var emp_spouse_res = parseInt(<?php echo $tch[0]['emp_spouse_res'] ?>);
					   
						if(emp_spouse_res=='251')
						{
						$('#hra').val(0);
						
						}else{
							//alert(44);
					    var hra = Math.round((parseInt(<?php echo $hra; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
						//alert(hra);
						$('#hra').val(hra);
						}
					
					} 
					
					if(desig!='1120'){
						var spouse_ma = $('#spouse_med_al').val();
						if(spouse_ma=='Yes'){
							$('#ma').val(0);
						}else{
							var ma = $('#ma').val();
							var part_ma = Math.round((parseInt(<?php echo $ma; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
							$('#ma').val(part_ma);
						}
					} else {
						$('#ma').val(0);	
					}
					var emp_diff = parseInt(<?php echo $tch[0]['emp_diff_able'] ?>);
					var emp_conv = parseInt(<?php echo $tch[0]['conv_allow_status'] ?>);
					if(emp_diff=='1'){
						var conv_alw = $('#conv_allow').val();
						var part_conv = Math.round((parseInt(<?php echo $conveyance_allowance; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
						$('#conv_allow').val(part_conv);
						//$('#conv_allow').removeAttr('readonly');
						$('#conv_allow').attr('readonly','readonly');
						$('#conv_allow').css('background-color','#EEE');
						$('#p_tax').val(0);
						$('#p_tax').attr('readonly','readonly');
						$('#p_tax').css('background-color','#EEE');
					}else{
						$('#conv_allow').val(0);
						$('#conv_allow').attr('readonly','readonly');
						$('#conv_allow').css('background-color','#EEE');
						$('#p_tax').attr('readonly','readonly');
						$('#p_tax').css('background-color','#EEE');
						
						/*$.post('<?= $config['base_url'] ?>page/intra_prd/gp/sal_requisition/ptax_cal_sal.php?gross='+$('#'), function(data){
				// alert(data);
				 $("#mbody").html(data);
		       });
		    });*/
						
						//$('#p_tax').val(<?php echo $ptax; ?>);
					}
					
					
					var pay_band = $('#pay_in_band').val();
					var consolidated_pay=$('#consolidated_pay').val();
					var grade_pay = $('#grade_pay').val();
					var da = $('#da').val();
					var interim_relief = $('#interim_relief').val();
					var hra = $('#hra').val();
					var ma = $('#ma').val();
					var conv_allow = $('#conv_allow').val();
					var cpf = $('#cpf').val();
					var hill_allaw=$('#hill_allow').val();
					
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
				
					<?php if($tch[0]['emp_diff_able']!='1'){ ?>
					//alert(444);
					
					//cal_ptax(gross);
					
					<?php } else { ?>
					//alert(333);
					$('#p_tax').val(0);
					<?php } ?>
					/*alert(pt);
					$('#p_tax').val(pt);*/
					$('#gross').val(gross);
					
					//alert(96);
					if(desig!='1120'){
						if($("#retirement_gpf").val()=='1')
						{
							
						$('#gpf').val(0);
						}
						else if($("#emp_first_join_date").val()=='1')
						{
						$('#gpf').val(0);
						}
						else{
							
					      var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
					      var min_gpf = Math.round(min_gpf_amt);
				
					       $('#gpf').val(min_gpf);
					        }
				    	} else {
						$('#gpf').val(0);
						
					}
					
					
					 $.get('cal_ptax.php?gross='+gross, function(data){
					//alert(data)
						 var conv_allow=$('#conv_allow').val();
						 if(conv_allow>0)
						 {
							 $("#p_tax").val(0);	
						 }
						 else{
							//alert(99);
							 $("#p_tax").val('');
							$("#p_tax").val(Number(data));
							
					   // $("#p_tax").val(data);	
						 }
						
					var reduct=$("#reduct").val();	
					 var pfl = $('#pf_loan').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val(); 
					var p_tax=$('#p_tax').val();
					var gpf=$('#gpf').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc=$('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(p_tax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(hra_deduc);
					
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);			 
					});
					
					
				
					

					
					}
				}
		});
		
		
    </script>
    <?php } ?>
	
    