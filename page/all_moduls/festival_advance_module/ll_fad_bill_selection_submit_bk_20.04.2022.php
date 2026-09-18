<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];

$enc_session=md5('369'.$session_token);
$cryptoGraph=new cryptography();

$yeye=(date("Y")-1).date("Y");

$db=new database();
$fad_min_max_amount_fetch=$db->fetch_table(" SELECT * FROM prd_master_bonus WHERE id_pk='2' ");

$fad_min_amount=$fad_min_max_amount_fetch[0]['lower_limit'];
$fad_max_amount=$fad_min_max_amount_fetch[0]['upper_limit'];

$paychange = $db->fetch_table("SELECT paychange_da FROM prd_admin_paychange WHERE flag = 'TRUE' and ropa_year='2009' ");
						
if(count($paychange)>0)
{			
	 $da_per = $paychange[0]['paychange_da']; 
}
else
{
	$da_per = 0;
}


if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('Location:arrear_module_view.php');
	exit(0);
}

$type=$cryptoGraph->decode($_REQUEST['type'],4); 
$type2=$cryptoGraph->decode($_REQUEST['type2'],4);

if($type!=1 && $type2!=2)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Wrong Selection.</strong></div>';
	header('Location:ll_fad_module_view.php');
	exit(0);
}

function fun_grade_pay($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
	return $dist_data2[0]['grade_amount'];
}



if($logged_user=='DA')
{
	$ps_id_fk = $_SESSION['location']['ps_id'];
	$zp_id_fk = 0;
	$gp_id_fk = 0;
	$block_code = 0;
	
	$fad_status=$db->fetch_table(" SELECT festival_advance_status 
									FROM prd_festival_advance_employee_details fad
									INNER JOIN prd_employee_master emp
									ON emp.emp_id_pk=fad.emp_id_fk
									WHERE substr(fad.fad_monthyear,1,4)='".date('Y')."' AND emp.ps_id_fk='".$_SESSION['location']['ps_id']."'
									AND festival_advance_status!='0' order by festival_advance_status ASC
									  ");
				
				
							  
	$fad_emp_list_generate = $db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									emp.emp_id_const,
									CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
									CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
								FROM prd_employee_master emp
								LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' AND bonus_status in (3,4) AND ps_id_fk='".$_SESSION['location']['ps_id']."') as bon 
								ON emp.emp_id_pk=bon.emp_id_fk 
								LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1) as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								WHERE emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND emp.emp_status=1 AND emp.emp_religion!='0'
								ORDER BY emp.emp_first_name
									");
	
}
if($logged_user=='GP')
{
	$block_code = $_SESSION['location']['block_code'];
	$gp_id_fk =  $_SESSION['location']['gp_id'];
	$zp_id_fk = 0;
	$ps_id_fk = 0;
	
	$fad_status=$db->fetch_table(" SELECT festival_advance_status 
									FROM prd_festival_advance_employee_details fad
									INNER JOIN prd_employee_master emp
									ON emp.emp_id_pk=fad.emp_id_fk
									WHERE substr(fad.fad_monthyear,1,4)='".date('Y')."' AND emp.gp_id_fk='".$_SESSION['location']['gp_id']."'
									AND festival_advance_status!='0' order by festival_advance_status ASC
									  ");
									  
	$fad_emp_list_generate = $db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									emp.emp_id_const,
									CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
									CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
								FROM prd_employee_master emp
								LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' AND bonus_status in (3,4) AND gp_id_fk='".$_SESSION['location']['gp_id']."') as bon 
								ON emp.emp_id_pk=bon.emp_id_fk 
								LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1) as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								WHERE emp.gp_id_fk = '".$_SESSION['location']['gp_id']."' AND emp.emp_status=1 AND emp.emp_religion!='0'
								ORDER BY emp.emp_first_name
									");
	
}
if($logged_user=='zpdaa')
{
	$zp_id_fk = $_SESSION['location']['district_id'];
	$gp_id_fk = 0;
	$block_code = 0;
	$ps_id_fk = 0;
	
	$fad_status=$db->fetch_table(" SELECT festival_advance_status 
									FROM prd_festival_advance_employee_details fad
									INNER JOIN prd_employee_master emp
									ON emp.emp_id_pk=fad.emp_id_fk
									WHERE substr(fad.fad_monthyear,1,4)='".date('Y')."' AND emp.zp_id_fk='".$_SESSION['location']['district_id']."'
									AND festival_advance_status!='0' order by festival_advance_status ASC
									  ");
	
	$fad_emp_list_generate = $db->fetch_table("SELECT 
						distinct(emp.emp_id_pk),
						emp.emp_first_name,
						emp.emp_second_name,
						emp.emp_last_name,
						emp.emp_pay_in_payband,
						emp.emp_grade_pay,
						emp.emp_id_const,
						CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
						CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
					FROM prd_employee_master emp
					LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' 
					AND bonus_status in (3,4) AND zp_id_fk='".$_SESSION['location']['district_id']."') as bon 
					ON emp.emp_id_pk=bon.emp_id_fk 
					LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' 
					AND festival_advance_status=1) as fad 
					ON emp.emp_id_pk=fad.emp_id_fk
					WHERE emp.zp_id_fk = '".$_SESSION['location']['district_id']."' AND emp.emp_status=1 AND emp.emp_religion!='0'
					ORDER BY emp.emp_first_name
						");
											
}


						
			foreach($fad_emp_list_generate as $key)
						{
															
							//$emp_full_name=$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];
							
							
							 /*$emp_basic=$key['emp_pay_in_payband']+fun_grade_pay($key['emp_grade_pay']); 
							$emp_da=round(($emp_basic/100)*$da_per);
							 $emp_emolument=$emp_basic+$emp_da; */ 
							$da_per='3';
							$emp_basic=$key['emp_pay_in_payband']; 
							$emp_da=round(($emp_basic/100)*$da_per);
							$emp_emolument=$emp_basic+$emp_da;
							
							if($emp_emolument>=$fad_min_amount && $emp_emolument<=$fad_max_amount)
							{ 
							
								$emp_emolument_final=$emp_emolument;
								$eligibility_status = 1;
							}
							else
							{ 
								$sal_archive_fetch=$db->fetch_table(" SELECT pay_payband,tch_grade_pay,da FROM prd_monthly_salary_archive_final
													WHERE emp_id_fk='".$key['emp_id_pk']."' AND delete_status='1' AND salary_monthyear='".date('Y')."03'  AND status_flag in (3,4) ");
													
								/*$arc_payband=$sal_archive_fetch[0]['pay_payband'];
								$arc_grade_pay=$sal_archive_fetch[0]['tch_grade_pay'];
								$arc_basic=$arc_payband+$arc_grade_pay;
								$arc_da=$sal_archive_fetch[0]['da'];
								$arc_emolument=	$arc_basic+$arc_da;
								$emp_emolument_final=$arc_emolument;*/
								
								$arc_payband=$sal_archive_fetch[0]['pay_payband'];
								//$arc_grade_pay=$sal_archive_fetch[0]['tch_grade_pay'];
								$arc_basic=$arc_payband;
								$arc_da=$sal_archive_fetch[0]['da'];
								$arc_emolument=	$arc_basic+$arc_da;
								$emp_emolument_final=$arc_emolument;
								
								
								if($emp_emolument_final>=$fad_min_amount && $emp_emolument_final<=$fad_max_amount)
								{ 
									$eligibility_status = 1;
								}
								else{
									$eligibility_status = 0;
								}
								
							}
											
		$emp_check = $db->fetch_table(" SELECT count(*), gp_id_fk, block_code, ps_id_fk, zp_id_fk FROM prd_festival_advance_cron_hit
											WHERE emp_id_fk='".$key['emp_id_pk']."' 
											AND fad_year='".date('Y')."' GROUP BY gp_id_fk, block_code, ps_id_fk, zp_id_fk ");
		
		//var_dump($emp_check[0]['count']); die;								   
		if($emp_check[0]['count']<=0){			
										
		/**************************** Delete query is for Year 2020 **********************************************/
		
		/*$fad_emp_list_insert = $db->delete(" DELETE FROM prd_festival_advance_cron_hit
											WHERE emp_id_fk='".$key['emp_id_pk']."'
										   and fad_year ='".date('Y')."'");*/
		
		$fad_emp_list_insert = $db->insert("INSERT INTO prd_festival_advance_cron_hit
												(
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_id_fk,
													emp_id_const,
													total_basis,
													eligibility_status,
													gp_id_fk,
													block_code,
													ps_id_fk,
													zp_id_fk,
													fad_year
												)
												values(
												'".$key['emp_first_name']."',
												'".$key['emp_second_name']."',
												'".$key['emp_last_name']."',
												'".$key['emp_id_pk']."',
												'".$key['emp_id_const']."',
												'".$emp_emolument_final."',
												'".$eligibility_status."',
												'".$gp_id_fk."',
												'".$block_code."',
												'".$ps_id_fk."',
												'".$zp_id_fk."',
												'".date('Y')."' 
												)"
												);
		}
		else if($emp_check[0]['count']>=1 && 
				$emp_check[0]['gp_id_fk'] != $gp_id_fk && 
				$emp_check[0]['ps_id_fk'] != $ps_id_fk && 
				$emp_check[0]['zp_id_fk'] != $zp_id_fk){
			
			$fad_emp_list_insert=$db->update(" UPDATE prd_festival_advance_cron_hit sal SET
										total_basis='".$emp_emolument_final."',
										eligibility_status='".$eligibility_status."',
										gp_id_fk='".$gp_id_fk."',
										block_code='".$block_code."',
										ps_id_fk='".$ps_id_fk."',
										zp_id_fk='".$zp_id_fk."'
										WHERE emp_id_fk='".$key['emp_id_pk']."' AND fad_year='".date('Y')."'
									");
						}
						
						}						
						
						
//var_dump($fad_status); die;
for($i=0;$i<count($fad_status);$i++)
{ 
	if($fad_status[0]['festival_advance_status']=='4')
	//if($fad_status[$i]['festival_advance_status']=='4')
	{
		$entry_status="FALSE";
	}
	else
	{
		$entry_status="TRUE";
		break;
	}
}
//die;
if($type=='1')
{
	header('Location:ll_fad_view.php');
	exit(0);
}

else if($type2=='2') 
{
	if($entry_status=="TRUE" || count($fad_status)==0)
	{
		header('Location:ll_emp_festival_advance_list.php');
		exit(0);
	}
	else
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Festival Entry can not be possible for this current year again before Bill Generation of Locked Festival Advance.</strong></div>';
		header('Location:ll_fad_module_view.php');
		exit(0);
	}

}
?>