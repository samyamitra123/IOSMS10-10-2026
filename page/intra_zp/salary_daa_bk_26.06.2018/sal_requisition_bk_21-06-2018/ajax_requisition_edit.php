<?php

ob_start();
session_start();

//echo $str=$_SESSION['location']['stake_user'];
$str=$_SESSION['user_info']['stake_user'];
 $state10=substr($str,0,4);

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}



error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryp = new cryptography();

//$empcd=$cryp->decode($id[4],4);
//$empcd=$cryp->decode($_REQUEST['id'],4);
$emp_id_pk=$cryp->decode($_GET['id'],4);
$emp_id_pkk=$cryp->decode($_GET['id'],4);
$emp_id_for_gsli = $cryp->decode($_GET['id'],4);   //for glsi calculation
//die;
//echo $dise=$cryp->decode($_REQUEST['gp_id'],4);

$dise=$cryp->decode($_GET['zp_id'],4);

$createdby = substr ($_SESSION['location']['block_code'],0,7);


//echo $dise.'<br>';
//echo $empcd.'<br>';
//$dise=$cryp->decode($id[5],4);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


$db = new database();
$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$suspend_salary_status = 0;
function func_gradepay($val){     //Chacked
			$db = new database();
			$arr = $db->fetch_table("select grade_amount from prd_dise_gradepay_master where grade_code='$val'");
			return $arr[0]['grade_amount'];
		}
function salaryType($sal_type){    //Checked
			$db = new database();
			$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}
function getEmpAmount($type,$dise,$emp_id_pk){
	$db = new database();  
	if($type == 'pay_in_pay_band'){     //Checked
	$arr = $db->fetch_table("select emp_pay_in_payband from prd_employee_master where emp_id_pk='".$emp_id_pk."'");
		if($arr[0]['emp_pay_in_payband']){
			return $arr[0]['emp_pay_in_payband'];
		}else{
			return 0;
		}
	}
	
	
	if($type == 'grade_pay'){     //Checked
	$arr = $db->fetch_table("select emp_grade_pay,grade_amount from prd_employee_master as emp
	INNER JOIN prd_dise_gradepay_master as gd 
	ON trim(emp.emp_grade_pay)=gd.grade_code
	where emp_id_pk='".$emp_id_pk."'");
		if($arr[0]['grade_amount']){
			return $arr[0]['grade_amount'];
		}else{
			return 0;
		}
	}
	//Function for interim relief when salary type == 8 by  Start
	if($type == 'interim_relief'){     //Checked
	$arr = $db->fetch_table("select interim_relief from prd_employee_master where emp_id_pk='".$emp_id_pk."'");
		if($arr[0]['interim_relief']){
			return $arr[0]['interim_relief'];
		}else{
			return 0;
		}
	}
	//Function for interim relief when salary type == 8 by  End
}

$arr_gp_val = $db->fetch_table("select interim_relief,emp_pay_in_payband,emp_grade_pay,grade_amount from prd_employee_master as emp
	INNER JOIN prd_dise_gradepay_master as gd 
	ON trim(emp.emp_grade_pay)=gd.grade_code
	where emp_id_pk='".$emp_id_pk."'");         //Checked   //interim_relief, emp_pay_in_payband has been added by 

function getEmpConsolidated($type,$dise,$emp_id_pk){
	$db = new database();
	if($type == 'consolidated_pay'){    //Checked
		
	$arr = $db->fetch_table("select emp_cosolidated_pay from prd_employee_master where emp_id_pk='".$emp_id_pk."'");
		if($arr[0]['emp_cosolidated_pay']){
			return $arr[0]['emp_cosolidated_pay'];
		}else{
			return 0;
		}
	}
}

function getAmount($dise,$emp_id_pk,$type,$basic){
			$db = new database();
			if($type=='cpf'){     //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				$arr = $db->fetch_table("select cpf from prd_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1  AND emp_id_fk='$emp_id_pkk' AND salary_monthyear='".date('Ym')."' AND zp_id_fk='".$dise."'");
				
				if($arr[0]['cpf']){
					return $arr[0]['cpf'];
				}else{
					return 0;
				}
			}
			
			if($type=='gpf'){    //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select gpf from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."' AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['gpf'];
				}else{*/
					$crypp = new cryptography();

					//$empcd=$cryp->decode($id[4],4);
					//$empcd=$cryp->decode($_REQUEST['id'],4);
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
												WHERE other_deduction_type_variable = 'gpf'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
												
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  //}
			}
			
			if($type=='ssl'){    //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select sal_savings_lic from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."' AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['sal_savings_lic'];
				}else{*/
					$crypp = new cryptography();

					//$empcd=$cryp->decode($id[4],4);
					//$empcd=$cryp->decode($_REQUEST['id'],4);
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
												WHERE other_deduction_type_variable = 'ssl'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
												
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  //}
			}
			
			if($type=='scl'){    //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select staff_coop_lic from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."' AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['staff_coop_lic'];
				}else{*/
					$crypp = new cryptography();

					//$empcd=$cryp->decode($id[4],4);
					//$empcd=$cryp->decode($_REQUEST['id'],4);
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
												WHERE other_deduction_type_variable = 'scl'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  //}
			}
			
			if($type=='scc'){    //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select staff_coop_contribution from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."' AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['staff_coop_contribution'];
				}else{*/
					$crypp = new cryptography();

					//$empcd=$cryp->decode($id[4],4);
					//$empcd=$cryp->decode($_REQUEST['id'],4);
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
												WHERE other_deduction_type_variable = 'scc'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
												
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  //}
			}
			
			/*if($type=='pfl'){    //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				$arr = $db->fetch_table("select pf_loan from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."' AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['pf_loan'];
				}else{
					$crypp = new cryptography();

					//$empcd=$cryp->decode($id[4],4);
					//$empcd=$cryp->decode($_REQUEST['id'],4);
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'pfl'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}*/
			
			
			if($type=='itax'){      //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				$arr = $db->fetch_table("select i_tax from prd_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1  AND emp_id_fk='$emp_id_pkk' AND salary_monthyear='".date('Ym')."' AND zp_id_fk='".$dise."'");
				
				if($arr[0]['i_tax']){
					return $arr[0]['i_tax'];
				}else{
					return 0;
				}
			}
			if($type=='ovd'){      //Chacked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				$arr = $db->fetch_table("select overdrawn from prd_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1  AND emp_id_fk='$emp_id_pkk' AND salary_monthyear='".date('Ym')."' AND zp_id_fk='".$dise."'");
				
				if($arr[0]['overdrawn']){
					return $arr[0]['overdrawn'];
				}else{
					return 0;
				}
			}
			
				
				//for glsi calculation
				/*$arr = $db->fetch_table("select deduction_amount from mad_other_deduction where status='2' AND approval_status='3' AND edit_status='1' AND emp_id_fk='".$emp_id_pk."' AND municipal_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'"); 
				if($arr[0]['deduction_amount']){
					return $arr[0]['deduction_amount'];
				}else{
					return 0;
				}
			}*/
			
			if($type=='gsli'){             					   //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select gsli from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."'  AND salary_type != '8'");
				if(count($arr) > 0){
					return $arr[0]['gsli'];
				}else{*/
					$crypp = new cryptography();
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM prd_other_deduction
												WHERE other_deduction_type_variable = 'gsli'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
					if($other_deduction_data[0]['deduction_amount'] > 0)
					{
						return $other_deduction_data[0]['deduction_amount'];
					}
					else
					{
						return 0;	
					}
			  //}
			}
			
			//ARREAR DEDUCTION AMOUNT START 
			if($type=='arrear'){
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
					$arrear_amount = $db->fetch_table("SELECT remaning_amt FROM prd_arrear_calculation
												WHERE emp_id_fk='".$emp_id_pkk."' 																												
											    AND status in ('1')
											    AND delete_status in ('1')
											    AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
												
				if($arrear_amount[0]['remaning_amt'] > 0)
				{
					return $arrear_amount[0]['remaning_amt'];
				}
				else
				{
					return 0;	
				}
			  //}
			}
			//ARREAR DEDUCTION AMOUNT END 
			
			// for festival advance by nd on 04072017
			
			if($type=='festival_loan'){  
			//die;           
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				/*$arr = $db->fetch_table("select festival_loan from mad_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$emp_id_pkk."'  AND salary_type != '8'");
			
				if(count($arr) > 0){
					return $arr[0]['festival_loan'];
				}else{*/
					$crypp = new cryptography();
					$emp_id_pkk=$crypp->decode($_GET['id'],4);
					$other_deduction_data = $db->fetch_table("SELECT festival_advance_installment_amount FROM prd_festival_advance_entry_sal
												WHERE 
												status in ('2') AND approval_status in ('3') AND delete_status in ('1')
												AND emp_id_fk = '".$emp_id_pkk."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."'
												AND deduction_start_monthyear <= '".date("Ym")."'");
					
																		
					if($other_deduction_data[0]['festival_advance_installment_amount'] > 0)
					{
						return $other_deduction_data[0]['festival_advance_installment_amount'];
					}
					else
					{
						return 0;	
					}
			  //}
			}
			
			//End of for festival advance by nd on 04072017
			
			if($type=='conv'){     //Checked
				$crypp = new cryptography();
				$emp_id_pkk=$crypp->decode($_GET['id'],4);    //emp_id_not_getting
				
				$arr = $db->fetch_table("select conv_allow from prd_employee_salary_save where (status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1  AND emp_id_fk='$emp_id_pkk' AND salary_monthyear='".date('Ym')."' AND zp_id_fk='".$dise."'");
				if($arr[0]['conv_allow']){
					return $arr[0]['conv_allow'];
				}else{
					return 0;
				}
			}
		}

                 /* $dedact = $db->fetch_table("select id,deduction_type from mad_salary_deduction_master where id=1"); 
                   $dedact[0]['id']; */
                  
                
function empPtax($amount){      //Checked
			$db = new database();
			$arr = $db->fetch_table("select ptax_amount from prd_ptax_deduction where mn_amount <= '$amount' and mx_amount >= '$amount' AND ptax_order_id_fk='1'");
			return $arr[0]['ptax_amount'];
		}
		
/*function empBonus($emp_id_fk){
	//-------------------------------
	//CHECK FOR BONUS DATA
	//-------------------------------
	$db = new database();
	$emp_bonus_details = $db->fetch_table("SELECT bonus_amount FROM prd_bonus_entry_sal 
											WHERE emp_id_fk = '".$emp_id_fk."' AND monthyear = '".date("Ym")."'
											AND zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND status in ('2') AND approval_status in ('3')");
											
	if($emp_bonus_details[0]['bonus_amount'] > 0)
	{
		return $emp_bonus_details[0]['bonus_amount'];
	}
	else
	{
		return 0;	
	}
}*/
		
		$tch = $db->fetch_table("SELECT 
										tch.emp_id_pk,
										tch.empcd,
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
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_spouse_hra,
										tch.emp_pan_no,
										tch.emp_first_join_date,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status,
										de.designation_name
									FROM
										prd_employee_master as tch, zpemp_emp_desig_master as de
									WHERE
											tch.emp_id_pk = '".$emp_id_pk."'
											AND (tch.emp_status='1' OR tch.emp_status='9')
											AND de.designation_id = tch.emp_desig
											
								");
								
						
							
	//$gross_sal=$arr[0]['basic'] + $arr[0]['da'] + $arr[0]['hra'] + $arr[0]['ma'] + $arr[0]['cpf'] + $arr[0]['spl_pay'] + $arr[0]['spl_pay'];
						
$paychange = $db->fetch_table("SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM prd_admin_paychange
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
				//$emp_id_pk=$tch[0]['emp_id_pk'];
			
       
				
		$sal_chk= $db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, zp_id_fk, empcd, 
       bankname, accountno, basic, da,interim_relief, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause from prd_employee_salary_save
	    where 
		(status_flag=1 or status_flag=2 or status_flag=3) AND delete_status=1 AND salary_monthyear='".date('Ym')."'  
		AND emp_id_fk='$emp_id_pk' AND zp_id_fk='".$dise."'");
	  
	   
	   
				 //---------- Start Gpf=0 before retirement--------------------
			 $retirement_date=$tch[0]['emp_retirement_date'];
			 $date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date))); 	
			 $emp_first_join_date=$tch[0]['emp_first_join_date'];
			 $emp_first_join_match_date=date('Y-m-30', strtotime('+12 month',strtotime($emp_first_join_date)));
			 //$emp_bonus = empBonus($emp_id_pk);	
			//----------- End Gpf=0 before retirement ---------------------
				
				if($sal_chk[0]['zp_id_fk'] && $sal_chk[0]['emp_id_fk'] && ($sal_chk[0]['salary_type']=='1' || $sal_chk[0]['salary_type']=='2')){
					//die;
					// This condition for return full salary of employee and display saved salary of employee in edit mode
					  if($tch[0]['emp_desig']=='1120') //by nirupam     //1120 -> 999999 Final Check 121217
					  // if($tch[0]['emp_desig']!='')
					  {	
						$pay_in_band = $sal_chk[0]['pay_payband'];
						$full_consolidated_pay=getEmpConsolidated('consolidated_pay',$dise,$emp_id_pk);
						$consolidated_pay=$sal_chk[0]['consolidated_pay'];
						$grade_pay = $sal_chk[0]['tch_grade_pay'];
						//$grade_pay = $arr_gp_val[0]['grade_amount'];
						$basic = $sal_chk[0]['basic'];
						$da = $sal_chk[0]['da'];
						if($sal_chk[0]['interim_relief'] != ''){
							$interim_relief = $sal_chk[0]['interim_relief'];
						}
						else
						{
							$interim_relief = 0;
						}
						$hra = $sal_chk[0]['hra'];
						$ma = $sal_chk[0]['ma'];
						$conveyance_allowance = $sal_chk[0]['conv_allow'];
						$hill_allowance = $sal_chk[0]['hill_allowance'];
						$cpf = $sal_chk[0]['cpf'];
						$gross_salary = $sal_chk[0]['gross_salary'];
						$gpf = getAmount($dise,$empcd,'gpf','');     
						$cpf_deduct = $sal_chk[0]['cpf_deduct'];
						$ptax = $sal_chk[0]['p_tax'];
						$itax = $sal_chk[0]['i_tax'];
						$gsli = getAmount($dise,$empcd,'gsli','');    
						$ssl = getAmount($dise,$empcd,'ssl','');
						$scl = getAmount($dise,$empcd,'scl','');
						$scc = getAmount($dise,$empcd,'scc','');
						$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  
						$overdrawn = $sal_chk[0]['overdrawn'];
						$festival_loan = getAmount($dise,$empcd,'festival_loan','');  
						$total_deduct = $gpf+$ssl+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$scl+$scc+$arrear_amount;
						$net_salary_sal_save = $sal_chk[0]['net'];
						$festival_loan_cause = $sal_chk[0]['festival_loan_cause'];
						$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk); 
						$full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						$full_basic = $full_pay_band+$full_grade_pay;						
						$full_da = ($full_basic/100)*$da_per;
						$full_interim_relief = getEmpAmount('interim_relief',$dise,$emp_id_pk);
						
						
						if($tch[0]['emp_spouse_res']=='251'){
							$full_hra = 0; 		//If obtain any govt. housing scheme.
						}else{
							if($tch[0]['emp_spouse_hra']=='0' || $tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra']){
								$hra_emp = ($full_basic/100)*$hra_per;	
								if($hra_emp > 6000){
									//die;
									$full_hra = 6000;
								}else{
									  $full_hra = round($hra_emp);
								}
							}else if($tch[0]['emp_spouse_hra'] >= 6000){
								$full_hra = 0;
							}else if($tch[0]['emp_spouse_hra'] < 6000){ //spouse HRA < 6000
								$hra_emp = ($full_basic/100)*$hra_per;
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
					if($tch[0]['spouse_medical_allowance']=='1'){
							$full_ma = 0;
						}else{
							$full_ma = $max_ma;
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
					$full_gross_salary = round($full_basic+$full_da+$full_interim_relief+$full_hra+$full_ma+$full_conveyance_allowance+$full_hill_allowance);
						$full_gpf = getAmount($dise,$emp_id_fk,'gpf','');
						if($tch[0]['emp_diff_able']=='1'){
							$full_ptax = 0;
							
						}else{
							
							$full_ptax = empPtax($full_gross_salary);
						}
						
						$full_itax = getAmount($dise,$tch[0]['emp_id_pk'],'itax','');
						$full_gsli = getAmount($dise,$tch[0]['emp_id_pk'],'gsli','');
						$full_gsli = getAmount($dise,$tch[0]['emp_id_pk'],'gsli','');
						$full_ssl = getAmount($dise,$tch[0]['emp_id_pk'],'ssl','');
						$full_scl = getAmount($dise,$tch[0]['emp_id_pk'],'scl','');
						$full_scc = getAmount($dise,$tch[0]['emp_id_pk'],'scc','');
						$full_arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  
						$full_deductions = $full_gpf+$full_ptax+$full_itax+$full_gsli+$full_festival_loan+$full_ssl+$full_scl+$full_scc+$full_arrear_amount;
						$full_net_salary = $full_gross_salary-$full_deductions;
						
					}
					else
					{	
					foreach ($tch as $key) 
					{
						
				 	$emp_suspend=$db->fetch_table("SELECT 
										sus.slno,
										sus.suspend_effect_date,
										sus.suspend_withdrawn_date,
										sus.pencentage_basic,
										sus.suspend_start_date
										
									FROM
										mprd_suspend_dts as sus
										INNER JOIN prd_employee_master   em1 on sus.emp_id_fk=em1.emp_id_pk
									WHERE
											sus.zp_id_fk = '".$dise."' and sus.emp_id_fk='$emp_id_pk' 
											and em1.emp_status='11' and sus.delete_status='0' ORDER BY slno DESC
		                      ");
							  
					 $suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
					 
				     if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  //07_11_2017 To Fetch From Save Table for promotion
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						  $full_pay_in_band_sus = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
						  $full_pay_band = ($full_pay_in_band_sus*$pencentage_basic)/100;
						  $full_grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						  $full_grade_pay=($full_grade_pay_sus*$pencentage_basic)/100;
						  //07_11_2017 To Fetch From Save Table for promotion
						  
						  /*$full_pay_in_band_sus = $sal_chk[0]['pay_payband'];
						  $full_pay_band = $sal_chk[0]['pay_payband'];
						  $full_grade_pay_sus = $sal_chk[0]['tch_grade_pay'];
						  $full_grade_pay= $sal_chk[0]['tch_grade_pay'];*/
						  
						  $full_basic = $full_pay_in_band_sus+$full_grade_pay_sus;
						  $full_basic_sus = $full_pay_band+$full_grade_pay;
						  $full_basic = $full_basic_sus;
						  $full_da_sus = ($full_basic/100)*$da_per;
						  $full_da = ($full_basic_sus/100)*$da_per;
						  $full_interim_relief =0;
				      
			          }else{
						  
					     //$full_pay_band = $sal_chk[0]['pay_payband'];
						 //$full_grade_pay= $sal_chk[0]['tch_grade_pay'];
			             //$full_basic = $full_pay_band+$full_grade_pay;
                         $full_da = round(($full_basic/100)*$da_per);    //round by 
						 if($tch[0]['interim_relief'] != '')
						 {
						 	$full_interim_relief = $tch[0]['interim_relief'];
						 }
						 else
						 {
							 $full_interim_relief = 0;
						 }
						 }
				
			
					 $full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
				   	 $full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					 $full_basic = $full_pay_band+$full_grade_pay;
					 
					 $full_da = round(($full_basic/100)*$da_per);    //round by 
					 
					 $consolidated_pay=0;
					 $full_gpf = getAmount($dise,$tch[0]['emp_id_pk'],'gpf',$basic);
					
					 
					/* $full_cooperative_loan = $sal_chk[0]['cooperative_loan'];
					 $full_hbl_loan = $sal_chk[0]['hbl_loan'];
					 $full_festival_loan = $sal_chk[0]['festival_loan'];*/
					
					//$full_da = ($full_basic/100)*$da_per;
					
			    	//$full_interim_relief =$sal_chk[0]['interim_relief'];
						  
	           if($tch[0]['emp_spouse_res']=='251'){ 
						$full_hra = 0; 		//If obtain any govt. housing scheme.
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
						// Comment by ND on 16_10_2017_for fonal_salary_code_edit 
						if($sal_save[0]['conv_allow']!=''){
						 $conveyance_allowance = $sal_save[0]['conv_allow'];	
						}else{
						//if($tch[0]['conv_allow_status']==1){
					  $cal_ma=round(($full_basic*5)/100); 
					  $conveyance_allowance_max;
					 	if($cal_ma>=400){
						 	$conveyance_allowance_max;
							$full_conveyance_allowance = $conveyance_allowance_max;
						}
						else
						{
							$full_conveyance_allowance=round($cal_ma);
						}
						
						 // End of Comment by ND on 16_10_2017_for final_salary_code_edit
					
						}
						}
						
				else{
					$full_conveyance_allowance = 0;
				}
				
					if($state10=='1120'){									//1120 -> 999999 Final Check 121217
						 
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
					$da=$full_da;
					$interim_relief=$full_interim_relief;
					$hill_allowance = $full_hill_allowance; 
					$hra=$full_hra;
					$ma=$full_ma;
					$conveyance_allowance=$full_conveyance_allowance;
					//$hill_allowance = $sal_chk[0]['hill_allowance'];
					$full_gross_salary =round($pay_in_band+$grade_pay+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance); 
					$full_gross_salary =round($pay_in_band+$grade_pay+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance);
					//Commented on 10.07.2017 Start
					/*if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
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
					       //$full_gpf = $sal_chk[0]['gpf']; 
						   $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);
					       }
							else{
							$full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);       //unchanged
							}
						  }*/
					//Commented on 10.07.2017 End
					$full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);
					
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
					
					    $full_ptax = empPtax($full_gross_salary);  //Changed from full_gross_salary_sus to full_gross_salary by 
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
					//$gsli = $sal_chk[0]['gsli'];
					$gsli = getAmount($dise,$empcd,'gsli','');     //changed 
					$full_gsli = $gsli;     					   //Added by 
					
					$ssl = getAmount($dise,$empcd,'ssl','');
					$full_ssl = $ssl;  
					$scl = getAmount($dise,$empcd,'scl','');
					$full_scl = $scl;  
					$scc = getAmount($dise,$empcd,'scc','');
					$full_scc = $scc;  
					$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added 
					$full_arrear_amount = $arrear_amount; 
					//$pfl = $sal_chk[0]['pf_loan'];
					//$pfl = getAmount($dise,$empcd,'pfl','');       //changed 
					
					//$full_pfl = $pfl;                              //Added by 
					$overdrawn = $sal_chk[0]['overdrawn'];
					//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
				  //  $hbl_loan = $sal_chk[0]['hbl_loan'];
				    $festival_loan = getAmount($dise,$empcd,'festival_loan','');     //changed 
					$full_festival_loan = $festival_loan;
				    //$festival_loan = $sal_chk[0]['festival_loan']; 
					
					//$total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$cooperative_loan+$hbl_loan+$festival_loan;
					//$full_net_salary = $full_gross_salary-$total_deduct;
					 $ptax;
				     $total_deduct = $gpf+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$ssl+$scl+$scc+$arrear_amount;
					 
					 $total_deductions = $total_deduct;   //Done by 
				     $full_net_salary = $full_gross_salary-$total_deduct;
					 //$net_salary_sal_save=$full_net_salary;  //Commented By 
					 $net_salary = $full_net_salary;
					 
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
					//$gpf = $sal_chk[0]['gpf'];                    //changed    
					$gpf = getAmount($dise,$empcd,'gpf','');
					//$pfl = $sal_chk[0]['pf_loan'];
					$pfl = getAmount($dise,$empcd,'pfl','');       //changed 
					$cpf_deduct = $sal_chk[0]['cpf_deduct'];
					$ptax = $sal_chk[0]['p_tax'];
					$itax = $sal_chk[0]['i_tax'];
					//$gsli = $sal_chk[0]['gsli'];
					$gsli = getAmount($dise,$empcd,'gsli','');     //changed 
					
					$ssl = getAmount($dise,$empcd,'ssl','');
					$scl = getAmount($dise,$empcd,'scl','');
					$scc = getAmount($dise,$empcd,'scc','');
					$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added 
					
					$overdrawn = $sal_chk[0]['overdrawn']; 
					//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
				   // $hbl_loan = $sal_chk[0]['hbl_loan'];
				   $festival_loan = getAmount($dise,$empcd,'festival_loan','');     //changed 
				    $total_deduct = $gpf+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$ssl+$scl+$scc+$arrear_amount;
				   // $festival_loan = $sal_chk[0]['festival_loan'];
				    $net_salary_sal_save = $sal_chk[0]['net'];
					}
				   
					
			      }
					
		        }
	         }
				
				else if($sal_chk[0]['salary_type']=='8')
				{
					
					
					 // This condition for return full salary of employee zero salary and display saved salary of employee in edit mode
					
					/*$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
					$full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
					$full_basic = $full_pay_band+$full_grade_pay;
					$full_da = ($full_basic/100)*$da_per;*/
					
					$pay_in_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);  //07_11_2017 To Fetch From Save Table for promotion
					$consolidated_pay=0;
					$grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);   		//07_11_2017 To Fetch From Save Table for promotion
					//$pay_in_band = $sal_chk[0]['pay_payband'];
					//$grade_pay = $sal_chk[0]['tch_grade_pay'];
					$basic = $pay_in_band+$grade_pay;
					$da = round(($basic/100)*$da_per);
					//$interim_relief = $sal_chk[0]['interim_relief'];
					$interim_relief = getEmpAmount('interim_relief',$dise,$emp_id_pk); 	//Changed by 
					
					  if($tch[0]['emp_spouse_res']=='251'){
						$full_hra = 0; 		//If obtain any govt. housing scheme.
					}else{
						if($tch[0]['emp_spouse_hra']=='0' || $tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra']){
							$hra_emp = ($basic/100)*$hra_per;			//changed from $full_basic this by 
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
					$hra = $full_hra;    //added this by 
				if($tch[0]['spouse_medical_allowance']=='1'){
						$ma = 0;
					}else{
						$ma = $max_ma;
					}
					
					/*if($tch[0]['emp_diff_able']=='1'){
						// Comment by ND on 16_10_2017_for fonal_salary_code_edit
						
						//if($tch[0]['conv_allow_status']==1){
							 $conveyance_allowance = $conveyance_allowance_max;
						// }else{
							//	$conveyance_allowance = 0;
						 //}
						 
						 // End of Comment by ND on 16_10_2017_for fonal_salary_code_edit
						 
					 }else{
						 $conveyance_allowance = 0;
					 }*/
					 if($tch[0]['emp_diff_able']=='1'){
						// Comment by ND on 16_10_2017_for fonal_salary_code_edit 
						if($sal_save[0]['conv_allow']!=''){
						 	$conveyance_allowance = $sal_save[0]['conv_allow'];	
						}else{
						//if($tch[0]['conv_allow_status']==1){
					  $cal_ma=round(($full_basic*5)/100); 
					  $conveyance_allowance_max;
					 	if($cal_ma>=400){
						 	$conveyance_allowance_max;
							$full_conveyance_allowance = $conveyance_allowance_max;
						}
						else
						{
							$full_conveyance_allowance=round($cal_ma);
						}
						
						 // End of Comment by ND on 16_10_2017_for final_salary_code_edit
					
						}
						}
						
					else{
						$full_conveyance_allowance = 0;
					}
					
					$conveyance_allowance = $full_conveyance_allowance;
					 
					if($state10=='1120'){								//1120 -> 999999 Final Check 121217
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
					
	
					/*if($tch[0]['emp_spouse_res']=='251'){
						$hra = 0;
					}else{
						if($tch[0]['spouse_hra']=='0' || $tch[0]['spouse_hra']=='' || !$tch[0]['spouse_hra']){
							$hra_emp = ($basic/100)*$hra_per;
							if($hra_emp > 6000){
								$hra = 6000;
							}else{
								$hra = round($hra_emp);
							}
						}else{
							$hra_emp = ($basic/100)*$hra_per;
							if($hra_emp > 6000){
								$valid_hra  = (6000-$tch[0]['spouse_hra']);
								$hra = round($valid_hra);
							}else{
								$mix_hra = $hra_emp+$tch[0]['spouse_hra'];
								if($mix_hra > 6000){
									if($tch[0]['spouse_hra']!='6000'){
										if($tch[0]['spouse_hra'] > $hra_emp){
											$valid_hra = $tch[0]['spouse_hra']-$hra_emp;
											$hra = round($valid_hra);
										}else{
											$valid_hra = $hra_emp-$tch[0]['spouse_hra'];
											$hra = round($valid_hra);
										}
									}else{
										$hra = 0;
									}
								}else{
									$hra = round($hra_emp);
								}
							}
						}
					}*/
					
					/*if($tch[0]['spouse_medical_allowance']=='Yes'){
						$ma = 0;
					}else{
						$ma = $max_ma;
					}
					if($tch[0]['emp_diff_able']=='1'){
					$conveyance_allowance = $conveyance_allowance_max;
					      $conveyance_allowance = getAmount($dise,$tch[0]['emp_id_pk'],'conv','');
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
					}*/
					//$cpf = ($basic/100)*$cpf_per;
					$cpf = getAmount($dise,$tch[0]['emp_id_pk'],'cpf','');
					
					$gross_salary = round($basic+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
					//Deduction part
					//$gpf_amt = ($basic/100)*6;
					$gpf = getAmount($dise,$tch[0]['emp_id_pk'],'gpf','');
					
					//$pfl = getAmount($dise,$tch[0]['emp_id_pk'],'pfl','');
					$pfl = getAmount($dise,$emp_id_fk,'pfl',''); // changed on 22.03.2017
					$cpf_deduct = $cpf*2;
					
					if($tch[0]['emp_diff_able']=='1'){
						$ptax = 0;
						
					}else{
						
						$ptax = empPtax($gross_salary);
					}
					//$max_ptax = empPtax($gross_salary);
					$itax = getAmount($dise,$tch[0]['emp_id_pk'],'itax','');
					$gsli = getAmount($dise,$tch[0]['emp_id_pk'],'gsli','');
					
					$ssl = getAmount($dise,$tch[0]['emp_id_pk'],'ssl','');
					$scl = getAmount($dise,$tch[0]['emp_id_pk'],'scl','');
					$scc = getAmount($dise,$tch[0]['emp_id_pk'],'scc','');
					$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added 
					
					$overdrawn = getAmount($dise,$tch[0]['emp_id_pk'],'ovd','');
					$festival_loan = getAmount($dise,$empcd,'festival_loan','');     //changed 
				    $total_deduct = $gpf+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$ssl+$scl+$scc+$arrear_amount;
					$net_salary_sal_save = $gross_salary-$total_deduct;
					$net_salary = $net_salary_sal_save;                //by  on 28_03_2017 Because javascript is calling $net_salary;
		
		}
		else
		{
			// Calculation for monthly leave by nd on 10072017
				$extra_monthly_leave = $db->fetch_table("select extra_leave_taken from mad_monthly_employee_leave where status='2' AND lock_status='2' AND delete_status='1' AND edit_status='1' AND approval_status='3' AND emp_id_fk='".$emp_id_pk."' AND month='".date('m')."' AND year='".date('Y')."'");
				
				   $total_extra_leave = $extra_monthly_leave[0]['extra_leave_taken'];
				   $days_in_month = date('t');
				   $total_working_days = $days_in_month - $total_extra_leave;
		
					if($extra_monthly_leave[0]['extra_leave_taken'] > 0 && $extra_monthly_leave[0]['extra_leave_taken'] < $days_in_month)
					{
						$sal_type_for_leave = "2";
					}
					else if ($extra_monthly_leave[0]['extra_leave_taken'] == $days_in_month)
					{
						$sal_type_for_leave = "8";
					}
					else  
					{
						$sal_type_for_leave = "1";
					
					}
					
			// previous month and year calculation	
			$month_by_n = date('m');
			$year_by_n = date('Y');
			//$month_by_n = 12;
			//$year_by_n = 2017;
			$last_month = $month_by_n-1%12;
			if($last_month ==0)
			{
				$new_last_month = '01';
			}
			if($last_month >0 && $last_month <10)
			{
				$new_last_month = '0'.$last_month;
			}
			else
			{
			  $new_last_month = $last_month;	
			}
			if($month_by_n == 01)
			{
			$pre_yr = $year_by_n - 1; //echo removed by  06.04.2017
			}	
			else
			{
			$pre_yr = $year_by_n;	
			}
			$sal_mnth_yr = $pre_yr.$new_last_month;
			// End of previous month and year calculation
					 //die;
				
					 $sal_save=	$db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, zp_id_fk, empcd, 
       bankname, accountno, basic, da,interim_relief, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause 
	   from 
	   prd_employee_salary_save where status_flag=4 AND delete_status=1 AND salary_monthyear='".$sal_mnth_yr."'  AND emp_id_fk='$emp_id_pk' AND zp_id_fk='".$dise."'");
	   //Changed status_flag from 3 to 4.
	   //".date('Ym',strtotime('-1 month'))."
	   //print_r($sal_save);
	   
	   //Added New Start
		/*$promotion_data_cal = $db->fetch_table("SELECT emp_grade_pay,
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
												FROM 
												prd_employee_promotion_details
												WHERE emp_id_fk ='".$emp_id_pk."'
												AND promotion_effective_status in('1') 
												AND approval_status in('5','6')");
												
												
		$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
		$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
		$pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
		
		$emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
		$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
		$emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
		$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
		$max_days_of_current_month = date(t);
		$dop_doi = $promotion_data_cal[0]['dop_or_doi'];
		
		$eff_date = explode('-', $emp_effective_date_for_promotion_cal);
		$eff_year_month = $eff_date[0].$eff_date[1];
		
		 if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $eff_year_month == date("Ym"))
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
		 else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5) && $eff_year_month == date("Ym"))
		 {
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 
			 $count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $count_days_total_pay_pay_band_for_promotion = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days;
		 }*/
		 
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
			 //$full_basic = $full_pay_band+$full_grade_pay;
			 //$full_da = round(($full_basic/100)*$da_per);
			 //$full_interim_relief = $key['interim_relief'];
			 
			 $count_days_total_pay_pay_band_for_promotion = $full_pay_band;
			 $count_days_total_grade_pay_for_promotion = $full_grade_pay;
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
			 //$full_basic = $full_pay_band+$full_grade_pay;
			 //$full_da = round(($full_basic/100)*$da_per);
			 //$full_interim_relief = $key['interim_relief'];
			 
			 $count_days_total_pay_pay_band_for_promotion = $full_pay_band;
			 $count_days_total_grade_pay_for_promotion = $full_grade_pay;
			 
		 }
		 else
		 {
			 $count_days_total_pay_pay_band_for_promotion = $tch[0]['emp_pay_in_payband'];
			 $count_days_total_grade_pay_for_promotion = func_gradepay($tch[0]['emp_grade_pay']);
		 }
		//Added New End
	   
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
										sus.zp_id_fk = '".$dise."' 
										and sus.emp_id_fk='".$cryp->decode($_GET['id'],4)."' and em1.emp_status='11' 
										and sus.delete_status='0' ORDER BY slno DESC");
							  
				     $suspend_effect_date=$emp_suspend[0]['suspend_effect_date'];
					 
					 // For first time part salary
				   	if($sal_type_for_leave=='2')
			          {
						 	//echo "niru";
			    			$pay_in_band = ($tch[0]['emp_pay_in_payband'] * $total_working_days)/ $days_in_month;
							//$consolidated_pay=0;
							$grade_pay = (func_gradepay($tch[0]['emp_grade_pay']) * $total_working_days)/ $days_in_month;
				
							$basic = $pay_in_band + $grade_pay;
							$da = ($basic/100)*$da_per;
							//$interim_relief = $tch[0]['interim_relief'];
							$interim_relief = ($tch[0]['interim_relief'] * $total_working_days)/ $days_in_month;
							if($tch[0]['spouse_medical_allowance']=='1')
							{
								$ma = 0;
							}
							else
							{
								$ma = ($max_ma  * $total_working_days)/ $days_in_month;
							}
?>
	<script>
		$("#part_salary_cause_holder").show();
		$("#part_salary_cause_msg").show();
		$("#part_salary_cause_msg").val('For <?php echo $total_extra_leave; ?> days extra leave taken.');
		$('#part_salary_cause_msg').attr('readonly','readonly')
		$('#part_salary_cause_msg').css('background-color','#eeeeee');
		
		$("#part_day_holder").show();
		$("#working_days").show();
		$("#working_days").val('<?php echo $total_working_days; ?>');
		$('#working_days').attr('readonly','readonly')
		$('#working_days').css('background-color','#eeeeee');
	</script>


				 
<?php				     
			          } 
	   
	               	  else if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  $suspend_effect_date = 1;
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						  
						  //$pay_in_band_sus =  $tch[0]['emp_pay_in_payband'];
						  $pay_in_band_sus = $count_days_total_pay_pay_band_for_promotion;
						  $pay_in_band = ($pay_in_band_sus*$pencentage_basic)/100;
						  //$grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						  $grade_pay_sus = $count_days_total_grade_pay_for_promotion;
						  $grade_pay=($grade_pay_sus*$pencentage_basic)/100;
						  $basic = $pay_in_band_sus+$grade_pay_sus;
						  $basic_sus = $pay_in_band+$grade_pay;
						  $basic = $basic_sus;					//For calculation with 50% of basic by .
						  $da_sus=$da = ($basic/100)*$da_per;
						  $da = ($basic_sus/100)*$da_per;
						  $interim_relief =0;
						  	if($tch[0]['spouse_medical_allowance']=='1')
							{
								$ma = 0;
							}
							else
							{
								$ma = $max_ma;
							}
			          }
					  else
					  {  
					    //$pay_in_band =  $tch[0]['emp_pay_in_payband'];				//Commented by 
						//$pay_in_band =  $arr_gp_val[0]['emp_pay_in_payband'];
						//$grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);
						$pay_in_band = $count_days_total_pay_pay_band_for_promotion;
						//$grade_pay = $arr_gp_val[0]['grade_amount'];
						$grade_pay = $count_days_total_grade_pay_for_promotion;
			            $basic = $pay_in_band+$grade_pay;
						$da = round(($basic/100)*$da_per);
						if($tch[0]['interim_relief'] != '')
						{
							$interim_relief = $tch[0]['interim_relief'];					//Commented by 
						}
						else
						{
							$interim_relief = 0;
						}
						
						if($tch[0]['spouse_medical_allowance']=='1')
						{
							$ma = 0;
						}
						else
						{
							$ma = $max_ma;
						}
						//$interim_relief = $arr_gp_val[0]['interim_relief'];
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
					
					
	          if($tch[0]['emp_spouse_res']=='251')
			  {
						 $hra = 0; 
			  }
			  else
			  {
						if($tch[0]['emp_spouse_hra']=='0' ||$tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra'])
						{
							$hra_emp = ($basic/100)*$hra_per;
							if($hra_emp > 6000)
							{
								$hra = 6000;
							}
							else
							{
								$hra = round($hra_emp);
							}
  						}
						else if($tch[0]['emp_spouse_hra'] >= 6000)
						{
							//echo "HRA";
							$hra = 0;
						}
						else if($tch[0]['emp_spouse_hra'] < 6000)
						{ //spouse HRA < 6000
							
						$hra_emp = ($basic/100)*$hra_per;
							
							if($hra_emp >= 6000)
							{
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$hra = round($valid_hra);
							
							}/*else{
							$hra_emp = (($basic/100)*$hra_per)+$tch[0]['emp_spouse_hra'];
							if($hra_emp > 6000){
								$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
								$hra = round($valid_hra);
							}*/							
							else
							{
								$mix_hra = $hra_emp+$tch[0]['emp_spouse_hra'];
								if($mix_hra > 6000)
								{
	                  				if($tch[0]['emp_spouse_hra'] > $hra_emp)
									{
							
										//$valid_hra = $key['spouse_hra']-$hra_emp;
										$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
							            if($valid_hra>$hra_emp)
										{
											$valid_hra=$hra_emp;
									    }
							               
										$hra = round($valid_hra);
									}
									else if($tch[0]['emp_spouse_hra'] <= $hra_emp)
									{
									$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
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
					
					/*if($tch[0]['spouse_medical_allowance']=='1')
					{
						$ma = 0;
					}
					else
					{
						$ma = $max_ma;
					}*/
					
					
					// Comment open by ND on 16_10_2017_for final_salary_code_edit  
					/*if($tch[0]['emp_diff_able']=='1'){
						if($sal_save[0]['conv_allow']!=''){
						 $conveyance_allowance = $sal_save[0]['conv_allow'];	
						}else{
						$conveyance_allowance = $conveyance_allowance_max;
						}
					}else{
						$conveyance_allowance = 0;
					}*/
					
					 // End of Comment open by ND on 16_10_2017_for fonal_salary_code_edit
					
					if($tch[0]['emp_diff_able']=='1'){
						if($tch[0]['conv_allow_status']==1){
							$conveyance_allowance = $conveyance_allowance_max;
						 }else{
								$conveyance_allowance = 0;
						 }
					 }else{
						$conveyance_allowance = 0;
					 }
				
					
					
					if($state10=='1120')								//1120 -> 999999 Final Check 121217
					{
						$hill_p = ($pay_in_band/100)*$hill_allowance_per;
						$hill_g = ($grade_pay/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500)
						{
							 $hill_allowance = 1500;
						}
						else
						{
						 $hill_allowance = $hill_allowance_amt;
						}
					}
					else
					{
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
					//Commented on 10.07.2017 By  Start 
					/*if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
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
					    else
						{
						 $gpf = getAmount($dise,$empcd,'gpf',$basic);
					    }
					}*/
					//Commented on 10.07.2017 By 
					
					//if($sal_save[0]['pf_loan'])
					//{
					//$pfl=$sal_save[0]['pf_loan'];
					//}
					//else
					//{
					//$pfl = getAmount($dise,$empcd,'pfl','');     //changed 
					$gpf = getAmount($dise,$empcd,'gpf','');
					//}
				
					//$cpf_deduct = $cpf*2;
					
					if($key['emp_diff_able']=='1')
					{
					$ptax = 0;
					}
					else
					{
					 
					//?????????????????????????????????????????????????????????????????????
					
					}
					
				if($tch[0]['emp_diff_able']=='1')
				{
					$ptax = 0;
				}
				else
				{
				    if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
					{
					     //$gross_salary_sus=round($pay_in_band_sus+$grade_pay_sus+$da_sus+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance+$emp_bonus);
						 //commented by 
						 $gross_salary;   //echo removed by  06.04.2017
					   $ptax = empPtax($gross_salary);     //Done from gross_salary_sus to gross_salary by .
					}
					else
					{
						$ptax = empPtax($gross_salary);
					}
	 
				}
					
					// $max_ptax = empPtax($gross_salary);
				if($sal_save[0]['i_tax'])
				{
					$itax=$sal_save[0]['i_tax'];
					
				}
				else
				{
					$itax = getAmount($dise,$empcd,'itax','');
				}
					
				//if($sal_save[0]['gsli'])
				//{
					//$gsli=$sal_save[0]['gsli'];
				//}
				//else
				//{
					$gsli = getAmount($dise,$empcd,'gsli','');      //changed 
					
					$ssl = getAmount($dise,$empcd,'ssl','');
					$scl = getAmount($dise,$empcd,'scl','');
					$scc = getAmount($dise,$empcd,'scc','');
					$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added 
					$festival_loan = getAmount($dise,$empcd,'festival_loan','');      //changed 
				//}
				/*	if($sal_save[0]['overdrawn']){
					$overdrawn=$sal_save[0]['overdrawn'];
					}else{
					$overdrawn = getAmount($dise,$tch[0]['tchcd'],'ovd','');
					}*/
					 $overdrawn=0;
					 $total_deduct = $gpf+$ptax+$itax+$gsli+$festival_loan+$ssl+$scl+$scc+$arrear_amount; 
					 $net_salary = $gross_salary-$total_deduct;
	
	              if($tch[0]['emp_desig']=='1120')					//1120 -> 999999 Final Check 121217
				  {
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
					if($tch[0]['emp_diff_able']=='1')
					{
					$ptax = 0;
					}
					else
					{
						$ptax = empPtax($gross_salary);
					}
					$max_ptax = empPtax($gross_salary);
					$itax = 0;
					$gsli =0;
					$arrear_amount =0;
					$overdrawn = getAmount($dise,$tch[0]['emp_id_pk'],'ovd','');
					//$other_deduction=0;
					//$cooperative_loan = 0;
						//$hbl_loan = 0;
				   $festival_loan =0;
					$total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$festival_loan;	//echo removed by  06.04.2017
					$net_salary = $gross_salary-$total_deduct;
				}
				
				
				//No Salary for leave Start
				if($sal_type_for_leave=='8')
				{
					$pay_in_band = 0;
					//$consolidated_pay=0;
					$grade_pay = 0;
					
					$basic = 0;
					$da = 0;
					$interim_relief = 0;
					$hra = 0;
					$ma = 0;
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					$gross_salary = 0; 
					$gpf = 0;
					$scc = 0;
					$cpf_deduct = 0;
					$ptax = 0;
					$itax = 0;
					$ovd = 0;
					$gsli = 0;
					$ssl = 0;
					$scl = 0;
					$festival_loan = 0;
					//$emp_bonus = 0;
					$festival_loan = 0;
					$net_salary = 0;
					$total_deduct = 0;
					
?>
					<script>
						$("#no_salary_cause_holder").show();
						$("#no_salary_cause_msg").show();
						$("#no_salary_cause_msg").val('Absent for the whole month.');
						$('#no_salary_cause_msg').attr('readonly','readonly')
						$('#no_salary_cause_msg').css('background-color','#eeeeee');
												
						$("#ovd_is").hide();
						$("#is_reduct").hide();
						$("#is_reduct").hide();
						$("#reduction_type").hide();
					</script>

<?php
				}
				//No Salary for leave End	          
					
			}
			
				
				
		   
		 ?>

<style>
input[type="checkbox"] {
	display:inline !important;
}
</style>

	
	
				<style>
				
				input[type=text], textarea{
					padding: 2px;
					-moz-border-radius: 3px;
					border-radius: 3px;
					border: 1px solid #3E4255;
					
				}
				
				</style>
              
                <input type="hidden" name="pan_no" id="pan_no" value="<?=$tch[0]['emp_pan_no']?>" />
				<form id="form" method="post" action="ajax_zp_rq_submit.php" enctype="multipart/form-data" onsubmit="return validate_sal_req();">
                <input type="hidden" name="spouse_med_al" id="spouse_med_al" value="<?php echo $tch[0]['spouse_medical_allowance']; ?>" >
                <input type="hidden" name="emp_res_st" id="emp_res_st" value="<?php echo $tch[0]['emp_res_status']; ?>" >
                <input type="hidden" name="exist_part_day" id="exist_part_day" value="<?php echo $sal_chk[0]['part_day']; ?>" >
                <input type="hidden" name="zp_id_fk" id="zp_id_fk" value="<?php echo $cryp->encode($dise,4); ?>" >
                
                <div class="">
                
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
							
						
						$sal_caus = $db->fetch_table("select overdrawn,salary_type,cause,part_day,cpf,gpf,gsli,part_salary_cause,no_salary_cause from prd_employee_salary_save 
						where status_flag=1 AND delete_status=1  AND emp_id_fk='".$tch[0]['emp_id_pk']."' AND zp_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");
							
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
					 
							/*$dedact_cause = $db->fetch_table("select festival_loan_cause,hra_loan_cause from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='".$tch[0]['emp_id_pk']."' AND ps_id_fk='".$dise."' AND requisition_type='".$requisition_type."'");*/
							$dedact_cause = $db->fetch_table("select festival_advance_name from prd_festival_advance_entry_sal WHERE 
												status in ('2') AND approval_status in ('3') AND delete_status in ('1')
												AND emp_id_fk = '".$tch[0]['emp_id_pk']."' 
												AND zp_id_fk = '".$_SESSION['location']['district_id']."' AND deduction_start_monthyear <= '".date("Ym")."'");
							 
							?>
                            
          <select name="reduction" id="reduction" class="form-control" style="width:150px;border: 1px solid #3E4255 background-color:#eeeeee;" disabled="disabled">
                             <option id="no_ovd1" value="no_ovd1" <?php if($str1[0]==0){ echo 'selected';} ?>>NO</option>
                      <option id="yes_ovd1" value="yes_ovd1" <?php if($dedact_cause[0]['festival_advance_name']!=""){ echo 'selected';} ?> >YES</option>   
                           
                        </select>
                    </td></tr>
 
 
 
 
 
 
                 </table>
<style>
.sal_heading p{
    font-size: 20px;
    background-color: #3E9B96;
    font-style: inherit;
    color: #FFF;
    font-weight: 600;
    
    margin-right: 2px;
}
.sal_details_heading {
	font-weight: 600;
}
th{
	width: 50%;
	color: #FFFFFF;
	padding:4px;
}
input[type="text"] {
width : 100%;	
}
.row_heading{
	padding: 4px !important;
    text-align: left !important;
    padding-left: 10px !important;
    font-weight: 900 !important;
}
.total{
	border-top: solid 1px black;
	background-color: #3E9B96;
	color: #FFFFFF;
	margin-bottom:5px;
	width:17%;
}
</style>
                 <br /> <br />
                 
                 <div style="width:100%; float:left;">
                	<div style="width:30%; float:left; text-align:left;">
                    	<table width="99%" >
                        	<tr>
                            	<td colspan="2" class="sal_heading">
                                 <p>EMPLOYEE DETAILS</p>
                                </td>
                            </tr>
                            <tr>
                            	<td class="row_heading">Name of Employee</td>
                         		<td><b><?php echo $tchname; ?></b></td>
                            </tr>
                            
                            <tr>
                            	<td class="row_heading">Employee Designation</td>
                         		<td><b><?php echo strtoupper($tch[0]['designation_name']); ?></b></td>
                            </tr>
                        </table>
                    </div>
                    <div style="width:35%; float:left; text-align:left; margin-bottom:5px;"">
                        <table width="99%">
                        	<tr>
                            	<td colspan="2" class="sal_heading">
                                 <p>PAY & ALLOWANCE</p>
                                </td>
                            </tr>
                            <!--<tr>
                            	<td class="row_heading">Consolidated Pay</td>
                                <td> <input maxlength="5" style='background-color: #EEE;' type="text" id="consolidated_pay" name="consolidated_pay" value="<?php echo round($consolidated_pay); ?>" readonly size="5" />
                                </td>
                            </tr>-->
                            <tr>
                            	<td class="row_heading">Pay In Pay Band</td>
                                <td>
                                <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                                <input type="hidden" name="rank" id="rank" value="<?php echo $tch[0]['rank']; ?>" />
                                <input type="hidden" name="empcd" id="empcd" value="<?php echo $cryp->encode($tch[0]['empcd'],4); ?>" />
                                 <input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<?php echo $cryp->encode($emp_id_pk,4); ?>" />
                               
                                <input type="hidden" name="tchname" id="tchname" value="<?php echo $tch[0]['emp_first_name'].' '.$tch[0]['emp_second_name'].' '.$tch[0]['emp_last_name']; ?>" />
                                <input type="hidden" name="bankname" id="bankname" value="<?php echo $tch[0]['emp_bank_name']; ?>" />
                                <input type="hidden" name="accountno" id="accountno" value="<?php echo $tch[0]['emp_acc_no']; ?>" />
                                <input type="hidden" name="bank_ifsc" id="bank_ifsc" value="<?php echo $tch[0]['emp_ifsc_no']; ?>" />
                                <input type="hidden" name="code" id="code" value="<?php echo $cryp->encode($tch[0]['emp_system_code'],4); ?>" />
                                <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?php echo $cryp->encode($tch[0]['emp_id_pk'],4); ?>" />
                                <input type="hidden" name="basic" id="basic" value="<?php echo $basic; ?>" />
                                <input maxlength="5" style='background-color: #EEE;' type="text" id="pay_in_band" name="pay_in_band" value="<?php echo round($pay_in_band); ?>" readonly size="5" />
                                <input type="hidden" id="retirement_gpf" name="retirement_gpf" autocomplete="off" <?php if($gpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?>  <?php if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0){ ?>   value="<?php echo 1; ?>"      <?php } ?>  />
                                  <input type="hidden" id="emp_first_join_date" name="emp_first_join_date" autocomplete="off" <?php if($gpf==0){ ?> readonly style='background-color: #EEE;' <?php } ?>  <?php if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
                        {?>   value="<?php echo 1; ?>"      <?php }?>  />
                                </td>
                            </tr>
                            <tr>
                            	<td class="row_heading">Grade Pay</td>
                               	<!-- Changed by  For Grade Pay Value Start-->
                        		<!--<td><input maxlength="5" style='background-color: #EEE;' type="text" id="grade_pay" name="grade_pay" value="<?php /*echo round($grade_pay);*/ echo round($arr_gp_val[0]['grade_amount']); ?>" readonly size="5" /></td>-->
                                <td><input maxlength="5" style='background-color: #EEE;' type="text" id="grade_pay" name="grade_pay" value="<?php echo round($grade_pay); // echo round($arr_gp_val[0]['grade_amount']); ?>" readonly size="5" /></td>
                                <!-- Changed by  For Grade Pay Value End-->	
                            </tr>
                            <tr>
                            	<td class="row_heading">D.A (<?php echo $da_per; ?>%)</td>
                        		<td><input maxlength="5" style='background-color: #EEE;' type="text" id="da" name="da" value="<?php echo round($da); ?>" size="5" readonly='readonly' /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">H.R.A (<?php echo $hra_per; ?>%)</td>
                        		<td><input maxlength="5" style='background-color: #EEE;' type="text" id="hra" name="hra" readonly value="<?php echo round($hra); ?>"size="5" /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">M.A </td>
                        		<td><input maxlength="5" style='background-color: #EEE;' type="text" id="ma" name="ma" readonly value="<?php echo round($ma); ?>"size="5" /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">CONV. ALLOW</td>
                       			<td><input maxlength="5" style='background-color: #EEE;' type="text" id="conv_allow" readonly name="conv_allow" value="<?php echo round($conveyance_allowance); ?>"size="5" /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">Interim Relief</td>
                                <td><input maxlength="5" style='background-color: #EEE;' type="text" id="interim_relief" name="interim_relief" value="<?php echo round($interim_relief); ?>" size="5" readonly='readonly' /></td>
                            </tr>
                            <!--<tr>
                            	<td class="row_heading">Bonus</td>
                                <td><input maxlength="5" style='background-color: #EEE;' type="text" id="emp_bonus" name="emp_bonus" value="<?php echo round($emp_bonus); ?>" size="5" readonly='readonly' /></td>
                            </tr>-->
                        </table>
                    </div>
                    
                    <div style="width:35%; float:left; text-align:left; margin-bottom:5px;">
                    	<table width="99%">
                        	<tr>
                            	<td colspan="2" class="sal_heading">
                                  <p>DEDUCTIONS</p>
                                </td>
                            </tr>
                            <tr>
                            	<td class="row_heading">GPF <!--(min 6%)--></td>
                                <td><input  readonly="readonly" maxlength="5" type="text" id="gpf" name="gpf" autocomplete="off"   style='background-color: #EEE;'  value="<?php echo $gpf; ?>"size="4" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>
                            <!--<tr>
                            	<td class="row_heading">Salary Savings LIC</td>
                                <td><input  readonly="readonly" maxlength="5" type="text" id="ssl" name="ssl" autocomplete="off"  style='background-color: #EEE;' value="<?php echo $ssl; ?>"size="4" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>-->
                           <!-- <tr>
                            	<td class="row_heading">Staff Cooperative LIC</td>
                                <td><input readonly="readonly" maxlength="5" type="text" id="scl" name="scl" autocomplete="off"  style='background-color: #EEE;' value="<?php echo $scl; ?>" size="4" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>-->
                            <!--<tr>
                            	<td class="row_heading">Staff Cooperative Contribution</td>
                                <td><input readonly style='background-color: #EEE;' maxlength="5" type="text" id="scc" name="scc" value="<?php echo $scc; ?>"size="5" onkeypress="return keyRestrict(event,'0123456789');"/></td>
                            </tr> -->
                            <tr>
                            	<td class="row_heading">P.Tax</td>
                                <td><input readonly maxlength="5" style='background-color: #EEE;' type="text" id="p_tax" name="p_tax" onkeypress="return keyRestrict(event,'0123456789');" value="<?php echo $ptax; ?>"size="5"   /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">I.Tax</td>
                                <td><input maxlength="5" type="text" id="i_tax" name="i_tax" value="<?php echo $itax; ?>"size="5" onKeyUp="return itaxCal();" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">GSLI</td>
                                <td><input readonly style='background-color: #EEE;' maxlength="3" type="text" id="gsli" name="gsli" value="<?php echo $gsli; ?>"size="5" onKeyUp="return gsliCal();" onkeypress="return keyRestrict(event,'0123456789');" autocomplete="off" /></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">Over Drawn</td>
                                <td><input type="text" <?php if($sal_caus[0]['salary_type'] == '1' || !$sal_caus[0]['salary_type']){ ?> readonly <?php } ?> maxlength="5"  id="overdrawn" name="overdrawn" value="<?php echo $overdrawn; ?>" onKeyUp="return ovdCal();" <?php if($sal_caus[0]['salary_type'] == '1' || !$sal_caus[0]['salary_type']){ ?> style='background-color:#EEE;' <?php } ?> onkeypress="return keyRestrict(event,'0123456789');" autocomplete="off"/></td>
                            </tr>
                            <tr>
                            	<td class="row_heading">Festival Advance Recovery</td>
                                <td><input readonly maxlength="5" style='background-color: #EEE;' type="text" name="reduct3"  size="5" id="reduct3" value="<?php if($festival_loan==0){echo '0';} else {echo trim($festival_loan);} ?>" autocomplete="off" onKeyUp="return reduc3(this.value);" onkeypress="return keyRestrict(event,'0123456789');"/></td>
                            </tr>
                            <!--<tr>
                            	<td class="row_heading">Total Arrear Deduction</td>
                                <td><input maxlength="5" type="text" name="total_arrear_deduction"  size="5" id="total_arrear_deduction" value="<?php echo $arrear_amount; ?>"  autocomplete="off" onkeypress="return keyRestrict(event,'0123456789');"/></td>
                            </tr>-->
                        <?php

						$loan_deductions = $db->fetch_table("SELECT prd_loan_deduction.installment_amount,prd_loan_deduction.no_of_installment,prd_loan_deduction.counter, prd_loan_deduction.reminder_amount, prd_master_loan_type.loan_type,prd_loan_deduction.deduction_loan_type_variable FROM prd_loan_deduction INNER JOIN prd_master_loan_type ON prd_master_loan_type.dise_code = prd_loan_deduction.dise_code_fk WHERE prd_loan_deduction.emp_id_fk = '".$emp_id_pk."' AND prd_loan_deduction.status in (2) AND prd_loan_deduction.approval_status in (3)");
						if(!empty($loan_deductions))
						{	
							//$loan_arr = array();
							$total_loan_amount = 0;
							foreach($loan_deductions as $deductions)
							{
								if($deductions['counter'] < $deductions['no_of_installment'])
								{
									//$loan_arr[''.$deductions['deduction_loan_type_variable'].''] = $deductions['installment_amount'];
									$total_loan_amount = $total_loan_amount + $deductions['installment_amount'];
									$loan_amount = $deductions['installment_amount'];
								}
								else
								{
									//$loan_arr[''.$deductions['deduction_loan_type_variable'].''] = $deductions['reminder_amount'];
									$total_loan_amount = $total_loan_amount + $deductions['reminder_amount'];
									$loan_amount = $deductions['reminder_amount'];
								}
						?>
                        	<!--<tr class="loan_d">
                            	<td class="row_heading"><?php echo $deductions['loan_type']; ?></td>
                                <td><input maxlength="5" style='background-color: #EEE;' type="text" name="<?php echo $deductions['deduction_loan_type_variable']; ?>" readonly size="5" class="loan_values" id="<?php echo $deductions['deduction_loan_type_variable']; ?>" value="<?php if($sal_type_for_leave != '8'){echo $loan_amount;}else{echo 0;} /*THIS IS FOR NO SALARY FOR LEAVE TAKEN*/?> " size="5" autocomplete="off" onKeyUp="return reduc3(this.value);" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>-->
                            <tr class="loan_d" <?php if($sal_type_for_leave == '8' || $sal_chk[0]['salary_type']=='8'){echo "style='display: none;'";}/*THIS IS FOR NO SALARY FOR LEAVE TAKEN*/?>>
                            	<td class="row_heading"><?php echo $deductions['loan_type']; ?></td>
                                <td><input maxlength="5" style='background-color: #EEE;' type="text" name="<?php echo $deductions['deduction_loan_type_variable']; ?>" readonly size="5" class="loan_values" id="<?php echo $deductions['deduction_loan_type_variable']; ?>" value="<?php echo $loan_amount; /*THIS IS FOR NO SALARY FOR LEAVE TAKEN*/?> " size="5" autocomplete="off" onKeyUp="return reduc3(this.value);" onkeypress="return keyRestrict(event,'0123456789');" /></td>
                            </tr>
                        <?php		
							}	
							//print_r($loan_arr);
						}
						if($total_loan_amount == '')
						{
							$total_loan_amount = 0;
						}
						?>
                        <input type="hidden" name="total_loan_deduction" id="total_loan_deduction"  value="<?php if(isset($total_loan_amount) && $sal_type_for_leave != '8'/*THIS IS FOR NO SALARY FOR LEAVE TAKEN*/){ echo $total_loan_amount; } else { echo '0'; } ?>"/>
                        </table>
                    </div>
                </div>
                
                
                <div style="width:100%; float:left;">
                	<div style="width:30%; float:left; text-align:left;">
                    	<table width="99%" style="background-color:transparent;">
                         	<tr >
                            	<td class="row_heading" background="none;">&nbsp;<br /></td>
                            	<td>&nbsp;<br /></td>
                            </tr>
                        </table>
                    </div>
                    <div style="width:35%; float:left; text-align:left; ">
                    	<table width="99%">
                         	<tr class="total">
                            	<td class="row_heading">Gross Salary</td>
                            	<td><input readonly maxlength="5" type="text" style='background-color: #EEE; color:#303' id="gross" name="gross" value="<?php echo round($gross_salary); ?>"size="4" /></td>
                            </tr>
                        </table>
                    </div>
                    <?php $total_deductions = $total_loan_amount + $total_deduct; ?>
                    <div style="width:35%; float:left; text-align:left;">
                        <table width="99%">
                         	<tr class="total">
                            	<td class="row_heading">Deduction</td>
                           		<td><input readonly maxlength="5" type="text" style='background-color: #EEE; color:#303' id="deduct" name="deduct" value="<?php echo round($total_deductions); ?>" size="4" /></td>
                            </tr>
                        </table>
                    </div>
                 </div>
               
                
                <div style="width:100%; float:left; margin-top:4px;">
                    <table width="100%">
                        
                        <tr class="total">
                        	<td width="30%">Net Salary : </td>
                            <td width="35%">
                            
                            <div id="num_to_word"><?php //$number=round($net_salary - $total_loan_amount); echo number_to_words($number); ?><div></td>
                           <?php /*?> <td width="30%"><input maxlength="5" type="text" style='background-color: #EEE;' id="net" name="net" value="<?php echo round($net_salary + $arr_gp_val[0]['grade_amount']); ?>"size="6" readonly='readonly' /> </td><?php */?>
                            <td width="35%"><input maxlength="5" type="text" style='background-color: #EEE; color:#303' id="net" name="net" value="<?php if(isset($net_salary_sal_save)) { echo round($net_salary_sal_save); } else { echo round($net_salary - $total_loan_amount);} ?>"size="6" readonly='readonly' /> </td>
                            
                        </tr>
                    </table>
                </div>
                    
               </div>
               </div>
      
               <!--<div class="school">
				 
                </div>-->
                <!--<input type="hidden" name="cpf_per" id="cpf_per" value="<?php echo $cpf_per; ?>" >-->
                <!--<input type="hidden" name="actual_gross" id="actual_gross" value="<?php echo $gross_salary; ?>" >-->
                <!--<input type="hidden" name="actual_net" id="actual_net" value="<?php echo $net_salary; ?>" >-->
                 <input type="hidden" name="emp_dif" id="emp_dif" value="<?php echo $tch[0]['emp_diff_able']; ?>" >
                 <input type="hidden" name="emp_dif" id="emp_dif" value="<?php echo $tch[0]['conv_allow_status']; ?>" >
                <br>
                <?php
				$extra_monthly_leave_save_butz = $db->fetch_table("select extra_leave_taken from mad_monthly_employee_leave where status='2' AND lock_status='2' AND delete_status='1' AND edit_status='1' AND approval_status='3' AND emp_id_fk='".$tch[0]['emp_id_pk']."' AND month = '".date('m')."' AND year = '".date("Y")."'");
				$loan_details_unlocked_status = $db->fetch_table("SELECT loan_id_pk FROM mad_loan_deduction 
																WHERE status in('2') AND approval_status in('3') AND lock_status in('4')
																AND emp_id_fk = '".$tch[0]['emp_id_pk']."'");
				
				/*if(count($extra_monthly_leave_save_butz) < 1)
				{
				?>
				<center><a class="btn btn-info" id="submit" onclick="alert('Please Upload And Approve Monthly Leave Details And Try Again...');">Submit</a></center>
                <?php
				}*/
				 if(count($loan_details_unlocked_status) > 0)
				{
				?>
				<center><a class="btn btn-info" id="submit" onclick="alert('Please Approve Unlocked Loan Details Of This Employee And Try Again...');">Submit</a></center>
                <?php		
				}
				else
				{
				?>
                <center><input type="submit" class="btn btn-info" id="submit" value="Submit" onClick="return checkForm();"></center>
                <?php
				}
				?>
                 <p style="color:#F51102;">
                <strong>Note :</strong>
                <ul>
                	<li style="color:#F51102; font-weight:bold;">Dark input field is not editable.</li>

                    <!--<li style="color:#F51102; font-weight:bold;">I-Tax will be disable if employee have no PAN no.</li>-->
                    <li style="color:#F51102; font-weight:bold;">Overdrawn amount should not be greater than Pay in pay band amount.</li>
                    <li style="color:#F51102; font-weight:bold;">If employee belongs to Govt house scheme than HRA will be zero and non-editable.</li>
                </ul>
                </p>
				</div>
				</form>

<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>
    <script>
$(document).ready(function(e) {
	$('#reduction_type1,#reduction_type2,#reduction_type3').click(function(e) {
	//alert(11);
	
	/*	if($('#reduction_type1').is(':checked')==false)
		{
			
					if($('#reduct1').val()!='')
					{
				        var reduct1=$('#reduct1').val();
						var net=parseInt($('#net').val())+parseInt(reduct1);
						$('#net').val(net);
						$('#reduct1').val(0);
						$('#reduct1').attr('readonly','readonly');
					    $('#reduct1').css('background-color','#EEE');
						
					}
			
		}
		else
			{
				 
			     $('#reduct1').removeAttr('readonly');
			     $('#reduct1').css('background-color','#FFF');
			}*/
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
						var deduct = $('#deduct').val();
						var net=parseInt($('#net').val())+parseInt(reduct3);
						var tot_deduct = parseInt(deduct)-parseInt(reduct3);
						$('#net').val(net);
						$('#deduct').val(tot_deduct);
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
	//$('#pf_loan').attr('readonly','readonly');
	//$('#pf_loan').css('background-color','#EEE');
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
						  // comment for festival advance field "readonly" by nd 04072017
						  // $('#reduct3').removeAttr('readonly');
						  //$('#reduct3').css('background-color','#FFF'); 
						  //End of comment for festival advance field "readonly" by nd 04072017
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
			$('#reduct1').val(0);
            $("#reduction_type1").hide();
		//	$('#reduct1').attr('readonly','readonly');
			//$('#reduct1').css('background-color','#EEE');
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
					var deduct = $('#deduct').val();
					
					var addition =  parseInt(reduct3)+parseInt(net);
					var tot_deduct = parseInt(deduct)-parseInt(reduct3);
					//var addition = parseInt(reduct1)+ parseInt(reduct2)+ parseInt(reduct3)+parseInt(net);
					$('#net').val(addition);
					$('#deduct').val(tot_deduct);
					//$('#net').val();
                   // $("#reduction_type1").prop("checked", false);
					//$("#reduction_type2").prop("checked", false);
					$("#reduction_type3").prop("checked", false);
					//$('#reduct').removeAttr('writeonly');
					 //$('#reduct').val(0);
				 //   $('#reduct1').val(0);
				//	$('#reduct2').val(0);
					$('#reduct3').val(0);
                    $("#reduction_type").hide();
					//$('#overdrawn').attr('readonly');
				//	$('#reduct1').attr('readonly','readonly');
			     //   $('#reduct1').css('background-color','#EEE');
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
					var deduct = $('#deduct').val();
					var total_deduct = parseInt(deduct)-parseInt(overdrawn);
					$('#net').val(addition);
					$('#deduct').val(total_deduct);
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
				
				
				
			//Salary Type Change Start	
			$('#salary_type').change(function(e) 
			{
				var id=$(this).val();
				if($('#is_overdrawn').val()=='no_ovd')
				{
					var overdrawn = $('#overdrawn').val();
					var net = $('#net').val();
					var deduct = $('#deduct').val();
					var addition = parseInt(overdrawn)+parseInt(net);
					var tot_deduct = parseInt(deduct)-parseInt(overdrawn);
					$('#net').val(addition);
					$('#deduct').val(tot_deduct);
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
				
				//$('#pf_loan').removeAttr('readonly');
				//$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				//$('#p_tax').removeAttr('readonly');
				//$('#p_tax').css('background-color','#FFF');
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
				if(desig!='1120'){
					// alert(11212346);
					$('#pay_in_band').val('<?php echo $full_pay_band; //echo $full_pay_band; ?>');
				
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
						//$('#gsli').val('<?php echo $gsli; ?>');   //By 
						
						$('#gpf').val('<?php echo $full_gpf; ?>');
						//$('#pf_loan').val('<?php echo $full_pfl; ?>');  //By 
						$('#i_tax').val('<?php echo $full_itax; ?>');
						$('#gsli').val('<?php echo $full_gsli; ?>');    //By 
						$('#ssl').val('<?php echo $full_ssl; ?>');
						$('#scl').val('<?php echo $full_scl; ?>');
						$('#scc').val('<?php echo $full_scc; ?>');
						
						$('#gross').val('<?php echo $full_gross_salary; ?>');
						$('#p_tax').val('<?php echo $full_ptax; ?>');
						//$('#emp_bonus').val('<?php echo $emp_bonus; ?>');
						$('.loan_d').show();     //by 
						$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
						$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
						$('#deduct').val('<?php echo $total_loan_amount+$full_deductions; ?>');	 //by 
						$('#net').val('<?php echo $full_net_salary; ?>');
						//$('#festival_loan').val('<?php echo $full_festival_loan; ?>'); 
						// $full_cooperative_loan = $sal_chk[0]['cooperative_loan'];
					    // $full_hbl_loan = $sal_chk[0]['hbl_loan'];
					   //  $full_festival_loan = $sal_chk[0]['festival_loan'];
						//$('#reduct').val('<?php echo  $full_other_deduction; ?>')
						<?php //$full_festival_loan = $sal_chk[0]['festival_loan']; ?>
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
						$('#gsli').val(0);
						$('#ssl').val(0);
						$('#scl').val(0);
						$('#scc').val(0);
						$('#total_arrear_deduction').val(0);     //by 
						$('#reduct3').val(0);     //by 
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
						/*if(desig!='1120'){                             //commented for other deduction module
						var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
						var min_gpf = Math.round(min_gpf_amt);
						var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
						//alert(min_gpf);
						//var gpf = $('#gpff').val();
						$('#gpf').val(min_gpf);
						} else {
							$('#gpf').val(0);
						}*/
						//var bonus = $('#emp_bonus').val();
						var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
						$('#gross').val(gross);
						/*if($('#is_gpf').is(':checked')==true){
							var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
							var min_gpf = Math.round(min_gpf_amt);
						}else{
							var min_gpf = 0;
						}
						
						$('#gpf').val(min_gpf);*/
						var gpf = $('#gpf').val();
						//var pfl = $('#pf_loan').val();
						//var cpfd = $('#cpf_deduct').val();
						var ptax = $('#p_tax').val();
						var itax = $('#i_tax').val();
						
						var overdrawn = $('#overdrawn').val();
						var gsli=$('#gsli').val();
						
						//var ssl=$('#ssl').val();
						//var scl=$('#scl').val();
						//var scc=$('#scc').val();
						//var total_arrear_deduction=$('#total_arrear_deduction').val();
						/*var reduct1=$('#reduct1').val();
						var reduct2=$('#reduct2').val();*/
						var reduct3=$('#reduct3').val();
						var total_loan_amount = '<?php echo $total_loan_amount; ?>'
						var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(total_loan_amount);
						//Loan amount hasbeen added by 
						//var net = parseInt(gross)-parseInt(deduct);	//commented by  for calculation
						$('#deduct').val(deduct);   //by 
						var net = parseInt(gross)-parseInt(deduct);
						//alert('5555');
						$('#net').val(net);
						//alert($('#pay_in_band').val());
						if(desig=='1120'){
											$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly').css('background-color','#EEE;');
				;
						}
			}
			if(id == '2'){
				//alert('Hello');
				//alert(1111);
				//alert('1234');
				var desig=<?=$tch[0]['emp_desig']?>;
				$('#part_day_holder').show();
				$('#part_salary_cause_holder').show();    //Added by  because part salary cause was not coming after changing from full salary from part salary.
				$('#no_salary_cause_holder').hide(); //Added by  for part to no and no to part again.
				$('#is_reduct').show(); //Added by  for part to no and no to part again.
				//$('#cause_holder').show();     //Added by  because part salary cause was coming after changing from full salary from part salary.
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
				//$('#pf_loan').removeAttr('readonly');
				//$('#pf_loan').css('background-color','#FFF');
				$('#gsli').removeAttr('readonly');
				$('#gsli').css('background-color','#FFF');
				//$('#p_tax').removeAttr('readonly');
				//$('#p_tax').css('background-color','#FFF');
				$('#i_tax').removeAttr('readonly');
				$('#i_tax').css('background-color','#FFF');
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
						//$('#hill_allow').val('<?php echo $full_hill_allowance; ?>');
						$('#gsli').val('<?php echo $gsli; ?>');
						$('#gpf').val('<?php echo $full_gpf; ?>');
						$('#i_tax').val('<?php echo $full_itax; ?>');
						$('#gross').val('<?php echo $full_gross_salary; ?>');
						$('#p_tax').val('<?php echo $full_ptax; ?>');
						//$('#pf_loan').val('<?php echo $full_pfl; ?>');    //by 
						$('#ssl').val('<?php echo $full_ssl; ?>');
						$('#scl').val('<?php echo $full_scl; ?>');
						$('#scc').val('<?php echo $full_scc; ?>');
						
						//$('#emp_bonus').val('<?php echo $emp_bonus; ?>'); //by 
						$('#net').val('<?php echo $full_net_salary; ?>');
						$('.loan_d').show();     //by 
						$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
						$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
						$('#deduct').val('<?php echo $total_deductions; ?>');	 //by 
						
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
					/*var reduct1=$('#reduct1').val();
					var reduct2=$('#reduct2').val();*/
					//var reduct3=$('#reduct3').val();	
					//var cpf = $('#cpf').val();
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					$('#gross').val(gross);
					
					/*if(desig!='1120'){     //for other deduction module
					var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
					var min_gpf = Math.round(min_gpf_amt);
					var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
					//alert(min_gpf);
					//var gpf = $('#gpf').val();
					$('#gpf').val(min_gpf);
					} else {
						$('#gpf').val(0);
					}*/
						
					var gpf = $('#gpf').val();
					//var pfl = $('#pf_loan').val();
					//var gsli = $('#gsli').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var reduct = $('#reduct').val();
					
					var gsli = $('#gsli').val();
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(gsli)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					$('#deduct').val(deduct);//by nirupam
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				//$('#overdrawn').val(0);
				//alert($('#pay_in_band').val());
				if(desig=='1120'){
											$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly').css('background-color','#EEE;');
				;
						}
			}
			if(id == '8'){
				//alert(11111111);
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly').css('background-color','#EEE;');
				
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
				
				$('#ssl').val(0);
				$('#scl').val(0);
				$('#scc').val(0);
				$('#total_arrear_deduction').val(0);
				$('#deduct').val(0); //by 
				$('.loan_d').hide();     //by 
				//$('#emp_bonus').val(0); //by 
				$('#reduct3').val(0);     //by 
			}
		}
		if($('#exist_part_day').val()==''){ 
			if(id == '1'){
				//alert('KK');
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
				
				//$('#pf_loan').removeAttr('readonly');
				//$('#pf_loan').css('background-color','#FFF');
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
				//$('#pf_loan').val('<?php echo $pfl; ?>');
				$('#gsli').val('<?php echo $gsli; ?>'); //by nirupam on 28_03_2017 for gsli when full salary is selected
				
				$('#ssl').val('<?php echo $ssl; ?>');
				$('#scl').val('<?php echo $scl; ?>');
				$('#scc').val('<?php echo $scc; ?>');
				$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
				$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
				$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
				$('#p_tax').val('<?php echo $ptax; ?>');
				$('#i_tax').val('<?php echo $itax; ?>');
				$('#overdrawn').val('<?php echo $overdrawn; ?>');
				$('#reduct').val('<?php if($full_other_deduction!=0){echo $full_other_deduction;}else {echo "0";} ?>');
				
				//$('#emp_bonus').val('<?php echo $emp_bonus; ?>'); //by 
				$('#deduct').val('<?php echo $total_deductions; ?>');	 //by 
				$('.loan_d').show();     //by 
				//$('#net').val('<?php echo $net_salary; ?>');  //by 
				$('#net').val('<?php echo $net_salary - $total_loan_amount; ?>');  //by 
				$('#total_loan_deduction').val('<?php echo $total_loan_amount; ?>');
				
				/*if(desig=='1120'){
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
					$('#pf_loan').attr('readonly','readonly');
					$('#pf_loan').css('background-color','#EEE');
					$('#i_tax').attr('readonly','readonly');
					$('#i_tax').css('background-color','#EEE');
				}*/
				if(desig=='1120'){
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly')
				.css('background-color','#EEE');
			
				}
			}
			if(id == '2'){
				//alert('Hi');
				//alert(1255);
				$('#reduct').val(0);
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
					
				//$('#pf_loan').removeAttr('readonly');
				//$('#pf_loan').css('background-color','#FFF');
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
				$('#gsli').val('<?php echo $gsli; ?>'); //by nirupam on 28_03_2017 for gsli when part salary is selected
				$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
				$('#p_tax').val('<?php echo $ptax; ?>');
				$('#i_tax').val('<?php echo $itax; ?>');
				$('#overdrawn').val('<?php echo $overdrawn; ?>');
				
				$('#ssl').val('<?php echo $ssl; ?>');
				$('#scl').val('<?php echo $scl; ?>');
				$('#scc').val('<?php echo $scc; ?>');
				$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
				$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
				$('#reduct').val('<?php if($full_other_deduction!=0){echo $full_other_deduction;}else {echo "0";} ?>');
				
				$('#deduct').val('<?php echo $total_deductions; ?>');	 //by 
				//$('#emp_bonus').val('<?php echo $emp_bonus; ?>'); //by 
				$('.loan_d').show();     //by 
				$('#net').val('<?php echo $net_salary - $total_loan_amount; ?>');  //by 
				$('#deduct').val('<?php echo $total_deductions; ?>');	 //by 
				$('#total_loan_deduction').val('<?php echo $total_loan_amount; ?>');
				/*if(desig=='1120'){
					$('#gpf').attr('readonly','readonly');
					$('#gpf').css('background-color','#EEE');
					$('#pf_loan').attr('readonly','readonly');
					$('#pf_loan').css('background-color','#EEE');
					$('#i_tax').attr('readonly','readonly');
					$('#i_tax').css('background-color','#EEE');
				}*/
				if(desig=='1120'){
				$('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli').attr('readonly','readonly')
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
				$('#overdrawn').val(0);
				$('#net').val(0);
				$('#basic').val(0);
				$('#reduct1').val(0);
				$('#reduct2').val(0);
				$('#reduct3').val(0);
				$('#deduct').val(0);  //
				//$('#emp_bonus').val(0);	 //by 
				
				$('#ssl').val(0);
				$('#scl').val(0);
				$('#scc').val(0);
				$('#total_arrear_deduction').val(0);
				$('#reduct3').val(0);
				$('.loan_d').hide();     //by 
				//$('#reduct1').removeAttr('writeonly');
				//$('#reduct2').removeAttr('writeonly');
				//$('#reduct3').removeAttr('writeonly');
				//$("#reduction_type1").prop("checked", false);
				//$("#reduction_type2").prop("checked", false);
				$("#reduction_type3").prop("checked", false);
				$('#reduction_type').hide();
				$('#total_loan_deduction').val(0);
				
				
			}
		//Salary Type Change End
	
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
             
                

   <script>
   $(document).ready(function(e1) {
	
	   <?php if($sal_chk[0]['salary_type']=='8') { ?>
	      //alert("4645");
	   $('#pay_in_band,#consolidated_pay,#grade_pay,#da,#interim_relief,#hra,#ma,#conv_allow,#gross,#gpf,#pf_loan,#p_tax,#i_tax,#gsli,#overdrawn').attr('readonly','readonly').css('background-color','#EEE;');
	
	   <?php } ?>
    
		if($('#salary_type').val()=='8'){
				//alert('123');
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
				$('#gsli').val(0);
				$('#cpf_deduct').val(0);
				$('#p_tax').val(0);
				$('#i_tax').val(0);
				$('#overdrawn').val(0);
				$('#net').val(0);
				//$('.loan_d').hide();  //FOR SHOW BUT VALUE ZERO FOR LEAVE, NO SALARY
				//$('.loan_values').val(0);	//FOR SHOW BUT VALUE ZERO FOR LEAVE, NO SALARY
				$('#deduct').val(0);
				$('#basic').val(0);
				
				$('#ssl').val(0);
				$('#scl').val(0);
				$('#scc').val(0);
				$('#total_arrear_deduction').val(0);
				$('#reduct3').val(0);
				//$('#emp_bonus').val(0); // add by nirupam on 28_03_2017 for bonus not showing when no salary is selected
				
				//$('#reduct1').val(0);
				//$('#reduct2').val(0);
				$('#reduct3').val(0);
				$('#total_loan_deduction').val(0);
				
			}
			$('#no_ovd').click(function(){
				if($('#salary_type').val() == '1'){
					
					$('#cause_holder').hide();
					$('#cause_msg').val('');
					$('#limit').text(500);
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					var net_amt = parseInt($('#overdrawn').val())+parseInt($('#net').val());
					var net_deduct = parseInt($('#deduct').val())-parseInt($('#overdrawn').val());
					$('#net').val(net_amt);
					$('#deduct').val(net_deduct);
					$('#overdrawn').val(0);

				}
				
				
			
				if($('#salary_type').val() > '1'){
					
					$('#overdrawn').attr('readonly','readonly');
					$('#overdrawn').css('background-color','#EEE');
					var net_amt = parseInt($('#overdrawn').val())+parseInt($('#net').val());
					$('#net').val(net_amt);
					//alert("1234");
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
		//alert('123');
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
					
					$('#ssl').val('<?php echo $ssl; ?>');
					$('#scl').val('<?php echo $scl; ?>');
					$('#scc').val('<?php echo $scc; ?>');
					$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
					$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
					
					$('#i_tax').val('<?php echo $itax; ?>');
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
					
					var gross = parseInt(pay_band)+parseInt(hill_allow)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					//alert("1");
									
					//cal_ptax(gross);
				$('#gross').val(gross);
					var gpf = $('#gpf').val();
					//var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc= $('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
				//	var reduct1=$('#reduct1').val();
				//	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					$('#deduct').val(deduct);
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
					//$('#pf_loan').val('<?php echo $pfl; ?>');
					//$('#cpf_deduct').val('<?php echo $cpf_deduct; ?>');
					$('#p_tax').val('<?php echo $ptax; ?>');
					$('#gsli').val('<?php echo $gsli; ?>');
					
					$('#ssl').val('<?php echo $ssl; ?>');
					$('#scl').val('<?php echo $scl; ?>');
					$('#scc').val('<?php echo $scc; ?>');
					$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
					$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
					
					$('#i_tax').val('<?php echo $itax; ?>');
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
					//var bonus = $('#emp_bonus').val();
					
					//alert(consolidated_pay);
					
					var gross = parseInt(pay_band)+parseInt(hill_allow)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					var gross = 2200;
					//alert("2");					
					//cal_ptax(gross);
				$('#gross').val(gross);
					var gpf = $('#gpf').val();
					//var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
					
					var ssl=$('#ssl').val();
					var scl=$('#scl').val();
					var scc=$('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
					/*var reduct1=$('#reduct1').val();
					var reduct2=$('#reduct2').val();*/
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					$('#deduct').val(deduct);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
					
				}else{
				//alert(145);
					var exist_part_day = $('#exist_part_day').val();
					var pay_in_pay_band = $('#pay_in_band').val();
					var consolidated_pay=$('#consolidated_pay').val();
					if(pay_in_pay_band!=0){
					var part_pay_band = Math.round((parseInt(<?php echo $pay_in_band; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					//var part_pay_band = Math.round((parseInt(pay_in_pay_band)/parseInt(exist_part_day))*parseInt(work_days));
					$('#pay_in_band').val(part_pay_band);
					}else if(consolidated_pay!=0){
						var part_consolidated_pay = Math.round((parseInt(<?php echo $consolidated_pay; ?>)/parseInt(exist_part_day))*parseInt(work_days));
						$('#consolidated_pay').val(part_consolidated_pay);
					}
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
					
					// Add for part salary hra
					var hra = $('#hra').val();
					var part_hra = Math.round((parseInt(<?php echo $hra; ?>)/parseInt(exist_part_day))*parseInt(work_days));
					$('#hra').val(part_hra);
					// End for part salary hra
					// commented for part salary hra, less hra when going part to part salary
					// var hra = Math.round((parseInt(<?php echo $hra; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
						
					//$('#hra').val(hra);
					
					
					
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
					
					//var hill_allow = $('#hill_allow').val(); //Commented by  as it is not available.
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

					//var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow);   //Commented by  as hill_allow is not available.
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					//alert(gross);
					//var ttt = cal_ptax(gross)
					//alert(ttt);
					// for ptax calculation
					
					<?php if($tch[0]['emp_diff_able']!='1'){ ?>
					//alert('eee');
					//cal_ptax(gross);
					//$('#p_tax').val(data);
					
					
					
					
			       // $('#p_tax').val(data);
				$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/cal_ptax.php?gross='+gross, function(data){
					 
					//alert('PTAX VAL: '+data);
					$('#p_tax').val(data);	
				     //var ptax = data;
					 //alert (ptax);
					// var ptax = $('#p_tax').val();
					$('#gross').val(gross);
					//alert("3");
					//cal_ptax(gross);
					//var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay)+parseInt(consolidated_pay))/100)*6;
					//var min_gpf = Math.round(min_gpf_amt);
					//$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					//var pfl = $('#pf_loan').val();
					//var cpfd = $('#cpf_deduct').val();
					
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var loan_amount = '<?php echo $total_loan_amount ?>';
					var deduct = parseInt(gpf)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_amount)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					//alert('ptax:'+ptax); 
					$('#deduct').val(deduct);
					var net = parseInt(gross)-parseInt(deduct);
				    $('#net').val(net);
					
		      		 });

					<?php } else { ?>
					$('#p_tax').val(0);
					//var ptax = $('#p_tax').val();
					$('#gross').val(gross);
					//alert("3");
					//cal_ptax(gross);
					var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay)+parseInt(consolidated_pay))/100)*6;
					var min_gpf = Math.round(min_gpf_amt);
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					//var pfl = $('#pf_loan').val();
					//var cpfd = $('#cpf_deduct').val();
					
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var loan_amount = '<?php echo $total_loan_amount ?>';
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_amount)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					//alert('ptax:'+ptax); 
					$('#deduct').val(deduct);
					var net = parseInt(gross)-parseInt(deduct);
				    $('#net').val(net);
					
		      		 });
					<?php } ?>
					
					// end of ptax calculation
					
					
					
					//Previous code start changed for ptax calculation when calculating part-salary from full-salary of part-salary.
					/*<?php if($tch[0]['emp_diff_able']!='1'){ ?>
					//alert('1');
					//alert(gross);
					cal_ptax(gross);
					
					//var ptax = $('#p_tax').val();
					<?php } else { ?>
					$('#p_tax').val(0);
					<?php } ?>
					
					$('#gross').val(gross);
					//alert("3");
					var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay)+parseInt(consolidated_pay))/100)*6;
					var min_gpf = Math.round(min_gpf_amt);
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var loan_amount = '<?php echo $total_loan_amount ?>';
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_amount);
					alert('ptax:'+ptax); 
					$('#deduct').val(deduct);
					var net = parseInt(gross)-parseInt(deduct);
				    $('#net').val(net);*/
					//Previous code end changed for ptax calculation when calculating part-salary from full-salary of part-salary.
					
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
					
					$('#ssl').val('<?php echo $ssl; ?>');
					$('#scl').val('<?php echo $scl; ?>');
					$('#scc').val('<?php echo $scc; ?>');
					$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
					$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
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
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
					$('#gross').val(gross);
					//alert("4");
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					var total_arrear_deduction=$('#total_arrear_deduction').val();
				//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction);
					$('#deduct').val(deduct);
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);
				$('#working_days').val('');
				$('#working_days').focus();
			}else{
				
				if(work_days==''){
					//alert(2);
					
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
					
					$('#ssl').val('<?php echo $ssl; ?>');
					$('#scl').val('<?php echo $scl; ?>');
					$('#scc').val('<?php echo $scc; ?>');
					$('#total_arrear_deduction').val('<?php echo $arrear_amount; ?>');     //by 
					$('#reduct3').val('<?php echo $festival_loan; ?>');     //by 
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
					//var hill_allaw=$('#hill_allow').val();
				var hill_allaw=0;
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
					$('#gross').val(gross);
					var gpf = $('#gpf').val();
					var pfl = $('#pf_loan').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					

					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn);
				//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					if($('#total_loan_deduction').val()==''){
						var tot_loan_deduction=0;
						}else{
					     var tot_loan_deduction = $("#total_loan_deduction").val();
						}
					
					var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(tot_loan_deduction);
					$('#deduct').val(deduct);
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
					}else if(consolidated_pay!=0){
						var part_consolidated_pay = Math.round((parseInt(<?php echo $consolidated_pay; ?>)/parseInt(max_days_of_month))*parseInt(work_days));
						$('#consolidated_pay').val(part_consolidated_pay);
						part_pay_band=0;
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
						interim_relief=0;	
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
						
						/*$.post('<?= $config['base_url'] ?>page/intra_mad/gp/sal_requisition/ptax_cal_sal.php?gross='+$('#'), function(data){
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
					//var bonus = $('#emp_bonus').val();     //bonus added by 
					//var hill_allaw=$('#hill_allow').val();
				
					var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
					//alert(gross);
					<?php if($tch[0]['emp_diff_able']!='1'){ ?>
					//alert(444);
					cal_ptax(gross);
					<?php } else { ?>
					//alert(333);
					$('#p_tax').val(0);
					<?php } ?>
					/*alert(pt);
					$('#p_tax').val(pt);*/
					$('#gross').val(gross);
					
					//alert("5");
					
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
							//alert('fff');
					      var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
					      var min_gpf = Math.round(min_gpf_amt);
							//alert (min_gpf);
					       $('#gpf').val(min_gpf);
					        }
				    	} else {
						$('#gpf').val(0);
						
					}
					
				
					 $.get('cal_ptax.php?gross='+gross, function(data){
						
						 var conv_allow=$('#conv_allow').val();
						 if(conv_allow>0)
						 {
							 $("#p_tax").val(0);	
						 }
						 else{
							 
					    $("#p_tax").val(data);	
						 }
						
					var reduct=$("#reduct").val();	
					 var pfl = $('#pf_loan').val();
					var itax = $('#i_tax').val();
					var overdrawn = $('#overdrawn').val();
					var gsli=$('#gsli').val(); 
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					 var total_arrear_deduction=$('#total_arrear_deduction').val();
					if($('#total_loan_deduction').val()==''){
						var tot_loan_deduction=0;
						}else{
					     var tot_loan_deduction = $("#total_loan_deduction").val();
						}
						
					var gpf=$('#gpf').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(data)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(tot_loan_deduction);
					
					$('#deduct').val(deduct);
					
					var net = parseInt(gross)-parseInt(deduct);	
					$('#net').val(net);			 
					});

					}
				}
		});
		
    </script>
    <?php } ?>
    

<?php
//Number To Word For Gross Amount
function number_to_words($number)
{
 if ($number > 999999999)
  {
   throw new Exception("Number is out of range");
  }
  $Gn = floor($number / 10000000);  /* Millions (giga) */
  $number -= $Gn * 10000000;
  $Ln = floor($number / 100000);  /* Millions (giga) */
  $number -= $Ln * 100000;
  $kn = floor($number / 1000);     /* Thousands (kilo) */
  $number -= $kn * 1000;
  $Hn = floor($number / 100);      /* Hundreds (hecto) */
  $number -= $Hn * 100;
  $Dn = floor($number / 10);       /* Tens (deca) */
  $n = $number % 10;               /* Ones */
  $cn = round(($number-floor($number))*100); /* Cents */
  $result = ""; 
  if ($Gn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Gn) . " Crore";  } 
  if ($Ln)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Ln) . " Lakh"; } 
  if ($kn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 
  if ($Hn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
        "Nineteen");
   $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety"); 
   if ($Dn || $n)
    {
     if (!empty($result))
      {  $result .= " and ";
      } 
     if ($Dn < 2)
      {  $result .= $ones[$Dn * 10 + $n];
      }
      else
         {  $result .= $tens[$Dn];
          if ($n)
          {  $result .= "-" . $ones[$n];
          }
         }
    }
   if ($cn)
    {
     if (!empty($result))
      {  $result .= ' and ';
      }
       $title = $cn==1 ? 'paisa ': 'paisa';
       $result .= strtolower(number_to_words($cn)).' '.$title;
    }
   if (empty($result))
    {  $result = "zero"; } 
  return $result;
}


@pg_close($con);
?>
<script>
$("#total_arrear_deduction").keyup(function()
{
	/*var pay_band = $('#pay_in_band').val();
	var consolidated_pay=$('#consolidated_pay').val();
	var grade_pay = $('#grade_pay').val();
	var da = $('#da').val();
	var interim_relief = $('#interim_relief').val();
	var hra = $('#hra').val();
	var ma = $('#ma').val();
	var conv_allow = $('#conv_allow').val();
	var hill_allaw=$('#hill_allow').val();
	var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(interim_relief)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allaw);
	$('#gross').val(gross);*/
	//alert("4");
	var gross = $('#gross').val();
	var gpf = $('#gpf').val();
	//var pfl = $('#pf_loan').val();
	var ssl = $('#ssl').val();
	var scl = $('#scl').val();
	var scc = $('#scc').val();
	var ptax = $('#p_tax').val();
	var itax = $('#i_tax').val();
	var gsli=$('#gsli').val();
	var overdrawn = $('#overdrawn').val();
	var reduct3=$('#reduct3').val();
	var total_arrear_deduction=$('#total_arrear_deduction').val();
	var total_loan_deduction=$('#total_loan_deduction').val();
//var reduct1=$('#reduct1').val();
	//var reduct2=$('#reduct2').val();
	
	var deduct = parseInt(gpf)+parseInt(gsli)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(reduct3)+parseInt(ssl)+parseInt(scl)+parseInt(scc)+parseInt(total_arrear_deduction)+parseInt(total_loan_deduction);
	$('#deduct').val(deduct);
	var net = parseInt(gross)-parseInt(deduct);	
	$('#net').val(net);
});
</script>

<?php @pg_close($con); ?>	
<style>
 select.select_deseable {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}
/*select::-ms-expand {
    display: none;
}*/

</style>


<script>
$(document).ready(function(){
	//alert('1234');
	/*var salary_type = $("#salary_type").val();
	var part_salary_cause_msg = $("#part_salary_cause_msg").val();
	var working_days = $("#working_days").val();
	
	var reduction = $("#reduction").val();
	
	var no_salary_cause_msg = $("#no_salary_cause_msg").val();
	
	var pay_in_payband = $("#pay_in_band").val();
	var grade_pay = $("#grade_pay").val();
	var da = $("#da").val();
	var hra = $("#hra").val();
	var ma = $("#ma").val();
	var conv_allow = $("#conv_allow").val();
	var interim_relief = $("#interim_relief").val();
	var emp_bonus = $("#emp_bonus").val();
	
	var gpf = $("#gpf").val();
	var ssl = $("#ssl").val();
	var scl = $("#scl").val();
	var scc = $("#scc").val();
	var p_tax = $("#p_tax").val();
	var gsli = $("#gsli").val();
	var reduct3 = $("#reduct3").val();
	var total_loan_deduction = $("#total_loan_deduction").val();
	
	var total_arrear_deduction = $("#total_arrear_deduction").val();*/
	
	
	//alert(pay_in_payband);
	
	/*function validate_sal_req()
	{
		alert('1234');
		alert(pay_in_payband);
		if(Number($("#pay_in_band").val()) != Number(pay_in_payband))
		{
			alert('Please Enter Valid Pay in Payband.');
			$("#pay_in_band").focus();
			return false;
		}
		return false;
	}*/
	
	$("#form").submit(function(){
		//alert(pay_in_payband);
		//alert('98765');
		alert(salary_type);
		if($("#salary_type").val() == '')
		{
			alert('Please Enter Valid Salary Type.');
			$("#salary_type").focus();
			return false;
		}
		if((salary_type == 2) && ($("#part_salary_cause_msg").val() == ''))
		{
			alert('Please Enter Valid Part Salary Cause.');
			$("#salary_type").focus();
			return false;
		}
		if((salary_type == 2) && ($("#working_days").val() == ''))
		{
			alert('Please Enter Valid Working Days.');
			$("#working_days").focus();
			return false;
		}
		if((salary_type == 2) && ($("#no_salary_cause_msg").val() == ''))
		{
			alert('Please Enter Valid No Salary Cause.');
			$("#no_salary_cause_msg").focus();
			return false;
		}
		if($("#reduction").val() == '')
		{
			alert('Please Enter Valid Other Deduction Status.');
			$("#reduction").focus();
			return false;
		}
		/*if(($("#is_overdrawn").val() == 'yes_ovd') && ($("#cause_msg").val() == '')) 
		{
			alert('Please Enter The Valid Resion  For The Deduction of Overdrawn Amount. ffff');
			$("#cause_msg").focus();
			return false;
		}*/
		
		if(Number($("#pay_in_band").val()) == ''))
		{
			alert('Please Enter Valid Pay in Payband.');
			$("#pay_in_band").focus();
			return false;
		}
		if(Number($("#grade_pay").val()) == '')
		{
			alert('Please Enter Valid Grade Pay.');
			$("#grade_pay").focus();
			return false;
		}
		if(Number($("#da").val()) == '')
		{
			alert('Please Enter Valid DA.');
			$("#da").focus();
			return false;
		}
		if(Number($("#hra").val()) == '')
		{
			alert('Please Enter Valid HRA.');
			$("#hra").focus();
			return false;
		}
		if(Number($("#ma").val()) == '')
		{
			alert('Please Enter Valid MA.');
			$("#ma").focus();
			return false;
		}
		if(Number($("#conv_allow").val()) == '')
		{
			alert('Please Enter Valid CONV ALLOW.');
			$("#conv_allow").focus();
			return false;
		}
		if(Number($("#interim_relief").val()) == '')
		{
			alert('Please Enter Valid Interim Relief.');
			$("#interim_relief").focus();
			return false;
		}
		/*if(Number($("#emp_bonus").val()) == '')
		{
			alert('Please Enter Valid Bonus.');
			$("#emp_bonus").focus();
			return false;
		}*/
		if(Number($("#gpf").val()) == '')
		{
			alert('Please Enter Valid PF Contribution.');
			$("#gpf").focus();
			return false;
		}
		/*if(Number($("#ssl").val()) != Number(ssl))
		{
			alert('Please Enter Valid Salary Savings LIC.');
			$("#ssl").focus();
			return false;
		}
		if(Number($("#scl").val()) != Number(scl))
		{
			alert('Please Enter Valid Staff Cooperative LIC.');
			$("#scl").focus();
			return false;
		}
		if(Number($("#scc").val()) != Number(scc))
		{
			alert('Please Enter Valid Staff Cooperative Contribution.');
			$("#scc").focus();
			return false;
		}*/
		if(Number($("#p_tax").val()) == '')
		{
			alert('Please Enter Valid P.Tax.');
			$("#p_tax").focus();
			return false;
		}
		if(Number($("#gsli").val()) == '')
		{
			alert('Please Enter Valid GSLI.');
			$("#gsli").focus();
			return false;
		}
		if(Number($("#reduct3").val()) == '')
		{
			alert('Please Enter Valid Festival Advance Recovery.');
			$("#reduct3").focus();
			return false;
		}
		if(Number($("#total_loan_deduction").val()) == '')
		{
			alert('Please Enter Valid Loan Amount.');
			//$("#total_loan_deduction").focus();
			return false;
		}
		if(($("#i_tax").val() == '') || (isNaN($("#i_tax").val())))
		{
			alert('Please Enter Valid TDS.');
			$("#i_tax").focus();
			return false;
		}
		if(($("#overdrawn").val() == '') || (isNaN($("#overdrawn").val())))
		{
			alert('Please Enter Valid Over Drawn Amount.');
			$("#overdrawn").focus();
			return false;
		}
		/*if(($("#total_arrear_deduction").val() == '') || (isNaN($("#total_arrear_deduction").val()) || (Number($("#total_arrear_deduction").val()) > Number(total_arrear_deduction))))
		{
			alert('Please Enter Valid Total Arrear Deduction.');
			$("#total_arrear_deduction").focus();
			return false;
		}*/
		
		var gross = Number(pay_in_payband)+Number(grade_pay)+Number(da)+Number(hra)+Number(ma)+Number(conv_allow)+Number(interim_relief)+Number(emp_bonus);
		//alert(gross);
		if(Number($("#gross").val()) != Number(gross))
		{
			alert('Please Enter Valid Total Pay.');
			$("#gross").focus();
			return false;
		}
		
		//var deduct = Number(gpf)+Number(ssl)+Number(scl)+Number(scc)+Number(p_tax)+Number(gsli)+Number(reduct3)+Number(total_loan_deduction)+Number($("#i_tax").val())+Number($("#overdrawn").val())+Number($("#total_arrear_deduction").val());
		//alert(deduct);
		var deduct = Number(gpf)+Number(p_tax)+Number(gsli)+Number(reduct3)+Number(total_loan_deduction)+Number($("#i_tax").val())+Number($("#overdrawn").val());
		if(Number($("#deduct").val()) != Number(deduct))
		{
			alert('Please Enter Valid Total Deductions.');
			$("#deduct").focus();
			return false;
		}
		
		var net = Number(gross)-Number(deduct);
		//alert(net);
		if((Number($("#net").val()) != Number(net)) || (Number($("#net").val()) < 0))
		{
			alert('Please Enter Valid Gross Amount.');
			$("#net").focus();
			return false;
		}
		return true;
	});
	
});
</script>