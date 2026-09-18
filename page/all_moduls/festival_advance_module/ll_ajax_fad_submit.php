<?php 

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

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

//var_dump($logged_user); die;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$crypto = new cryptography();
$db = new database();		
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
/*$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('371371371'.$session_token);
if($sec_time_token!=$enc_session[0])
{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.<strong></div>';
	header('location:employee_lists.php');
	exit(0);
}
else
{*/	
	if($_POST['total_row']!="")
	{
		$total_row=$_POST['total_row'];
	}
	else
	{
		$total_row=0;
	}
	
	$emp_ids=$_POST['emp_name'];
	$festival_advs=$_POST['festival_adv'];
	$instl_no=$_POST['instl_no'];
	$instl_amount=$_POST['instl_amount'];
	$instl_amount_last=$_POST['instl_amount_last'];	
		
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$salary_monthyear = date('Ym');
	$query_string = '?dise='.$crypto->encode($schcd,3);





	///////////////////////////////////////////////////////////////////////// Server side blank validation//////////////////////////////////////////////////////////////////
	
	if($total_row!=0)
	{
		for($k=0;$k<$total_row;$k++)
		{
			if($logged_user=='BDO' && $gp_ids[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select GP.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			elseif($emp_ids[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Employee.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			elseif($festival_advs[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Festival Advance Amount.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			elseif($instl_no[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Instalment Number.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			elseif($instl_amount[$k]==""  || $instl_amount[$k]=="0")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			elseif($instl_amount_last[$k]=="" || $instl_amount_last[$k]=="0")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
				header('location:ll_emp_festival_advance_list.php');
				exit(0);
			}
			
		}
	}
	

 
 /////////////////////////////////////////////////////////////////////////////// Blank Validation Ends ////////////////////////////////////////////////////////////////////////////////////////
 
 ////////////////////////////////////////////////////////////////////////////// Duplicate Employee Checking //////////////////////////////////////////////////////////////////////////////////
 
	$total_no_of_employees=count($emp_ids);
	
	$uniq_check=array_unique($emp_ids);
	if(count($uniq_check)<count($emp_ids))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Festival Advance Submission Fails Due To Duplicate Employee Submission.</strong></div>';
		header('location:ll_emp_festival_advance_list.php');
		exit(0);
	}
	else
	{
		$count_insert=0;
		pg_query("BEGIN");
		for($i=0;$i<$total_no_of_employees;$i++)
		{	
			$current_month_first_date=date("Y-m-01");
			$next_month_date =date("Ym", strtotime("$current_month_first_date +1 month")); 
			$after_instl_month_date=date("Ym", strtotime("$current_month_first_date +$instl_no[0] month"));
			//var_dump($after_instl_month_date); die;

			if($logged_user=='DA')
			{
				$emp_details_fetch=$db->fetch_table("select emp_id_const from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
			
				$check_fad_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status in (1,2)");
				
				if(count($check_fad_details)=='0')
				{
					$fad_emp_ins=$db->insert("
										INSERT INTO prd_festival_advance_employee_details
										(festival_advance_total_amount,
										  emp_id_fk,
										  emp_id_const,
										  festival_advance_status,
										  update_time,
										  update_ip,
										  fad_monthyear,
										  ps_id_fk)
										VALUES
										('".$festival_advs[$i]."',
										'".$emp_ids[$i]."',
										 '".$emp_details_fetch[0]['emp_id_const']."',
										 1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."',
										'".date('Ym')."',
										'".$_SESSION['location']['ps_id']."')");
				
					$fad_id_fetch=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE festival_advance_total_amount='".$festival_advs[$i]."' AND emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1 ");
					
					$fad_details_ins=$db->insert("
										INSERT INTO prd_festival_advance_entry_sal
										(festival_advance_id_fk,
										  festival_advance_instalment_no,
										  festival_advance_instalment_amount,
										  festival_advance_instalment_last_amount,
										  deduction_start_monthyear,
										  deduction_end_monthyear,
										  deduction_counter,
										  total_amt_given,
										  status,
										  update_time,
										  update_ip)
										VALUES
										('".$fad_id_fetch[0]['festival_advance_id_pk']."',
										'".$instl_no[$i]."',
										'".$instl_amount[$i]."',
										'".$instl_amount_last[$i]."',
										'".$next_month_date."',
										'".$after_instl_month_date."',
										0,
										0,
										1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."')");
									
				}
				else
				{
					$fad_emp_ins=$db->update(" UPDATE prd_festival_advance_employee_details SET 
											festival_advance_total_amount='".$festival_advs[$i]."',
											festival_advance_status=1,
											update_time='now()',
											update_ip='".$_SERVER['REMOTE_ADDR']."',
											fad_monthyear='".date('Ym')."'
											WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' 
											AND festival_advance_status in (1,2)");
				
					$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal SET
													festival_advance_instalment_no='".$instl_no[$i]."',
													festival_advance_instalment_amount='".$instl_amount[$i]."',
													festival_advance_instalment_last_amount='".$instl_amount_last[$i]."',
													deduction_end_monthyear='".$after_instl_month_date."',
													update_time='now()',
										  			update_ip='".$_SERVER['REMOTE_ADDR']."'
													WHERE festival_advance_id_fk='".$check_fad_details[0]['festival_advance_id_pk']."'
												");
				}
			}
			else if($logged_user=='GP')
			{
				$emp_details_fetch=$db->fetch_table("select emp_id_const from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");
			
				$check_fad_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status in (1,2)");
				
				if(count($check_fad_details)=='0')
				{
					$fad_emp_ins=$db->insert("
										INSERT INTO prd_festival_advance_employee_details
										(festival_advance_total_amount,
										  emp_id_fk,
										  emp_id_const,
										  festival_advance_status,
										  update_time,
										  update_ip,
										  fad_monthyear,
										  gp_id_fk,
										  block_code)
										VALUES
										('".$festival_advs[$i]."',
										'".$emp_ids[$i]."',
										 '".$emp_details_fetch[0]['emp_id_const']."',
										 1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."',
										'".date('Ym')."',
										'".$_SESSION['location']['gp_id']."',
										'".$_SESSION['location']['block_code']."')");
				
					$fad_id_fetch=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE festival_advance_total_amount='".$festival_advs[$i]."' AND emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1 ");
					
					$fad_details_ins=$db->insert("
										INSERT INTO prd_festival_advance_entry_sal
										(festival_advance_id_fk,
										  festival_advance_instalment_no,
										  festival_advance_instalment_amount,
										  festival_advance_instalment_last_amount,
										  deduction_start_monthyear,
										  deduction_end_monthyear,
										  deduction_counter,
										  total_amt_given,
										  status,
										  update_time,
										  update_ip)
										VALUES
										('".$fad_id_fetch[0]['festival_advance_id_pk']."',
										'".$instl_no[$i]."',
										'".$instl_amount[$i]."',
										'".$instl_amount_last[$i]."',
										'".$next_month_date."',
										'".$after_instl_month_date."',
										0,
										0,
										1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."')");
									
				}
				else
				{
					$fad_emp_ins=$db->update(" UPDATE prd_festival_advance_employee_details SET 
											festival_advance_total_amount='".$festival_advs[$i]."',
											festival_advance_status=1,
											update_time='now()',
											update_ip='".$_SERVER['REMOTE_ADDR']."',
											fad_monthyear='".date('Ym')."'
											WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' 
											AND festival_advance_status in (1,2)");
				
					$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal SET
													festival_advance_instalment_no='".$instl_no[$i]."',
													festival_advance_instalment_amount='".$instl_amount[$i]."',
													festival_advance_instalment_last_amount='".$instl_amount_last[$i]."',
													deduction_end_monthyear='".$after_instl_month_date."',
													update_time='now()',
										  			update_ip='".$_SERVER['REMOTE_ADDR']."'
													WHERE festival_advance_id_fk='".$check_fad_details[0]['festival_advance_id_pk']."'
												");
				}
			}
			else if($logged_user=='zpdaa')
			{
				$emp_details_fetch=$db->fetch_table("select emp_id_const from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");
			
				$check_fad_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status in (1,2)");
				
				if(count($check_fad_details)=='0')
				{
					$fad_emp_ins=$db->insert("
										INSERT INTO prd_festival_advance_employee_details
										(festival_advance_total_amount,
										  emp_id_fk,
										  emp_id_const,
										  festival_advance_status,
										  update_time,
										  update_ip,
										  fad_monthyear,
										  zp_id_fk)
										VALUES
										('".$festival_advs[$i]."',
										'".$emp_ids[$i]."',
										 '".$emp_details_fetch[0]['emp_id_const']."',
										 1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."',
										'".date('Ym')."',
										'".$_SESSION['location']['district_id']."')");
				
					$fad_id_fetch=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details WHERE festival_advance_total_amount='".$festival_advs[$i]."' AND emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1 ");
					
					$fad_details_ins=$db->insert("
										INSERT INTO prd_festival_advance_entry_sal
										(festival_advance_id_fk,
										  festival_advance_instalment_no,
										  festival_advance_instalment_amount,
										  festival_advance_instalment_last_amount,
										  deduction_start_monthyear,
										  deduction_end_monthyear,
										  deduction_counter,
										  total_amt_given,
										  status,
										  update_time,
										  update_ip)
										VALUES
										('".$fad_id_fetch[0]['festival_advance_id_pk']."',
										'".$instl_no[$i]."',
										'".$instl_amount[$i]."',
										'".$instl_amount_last[$i]."',
										'".$next_month_date."',
										'".$after_instl_month_date."',
										0,
										0,
										1,
										'now()',
										'".$_SERVER['REMOTE_ADDR']."')");
									
				}
				else
				{
					$fad_emp_ins=$db->update(" UPDATE prd_festival_advance_employee_details SET 
											festival_advance_total_amount='".$festival_advs[$i]."',
											festival_advance_status=1,
											update_time='now()',
											update_ip='".$_SERVER['REMOTE_ADDR']."',
											fad_monthyear='".date('Ym')."'
											WHERE emp_id_fk='".$emp_ids[$i]."' AND substr(fad_monthyear,1,4)='".date('Y')."' 
											AND festival_advance_status in (1,2)");
				
					$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal SET
													festival_advance_instalment_no='".$instl_no[$i]."',
													festival_advance_instalment_amount='".$instl_amount[$i]."',
													festival_advance_instalment_last_amount='".$instl_amount_last[$i]."',
													deduction_end_monthyear='".$after_instl_month_date."',
													update_time='now()',
										  			update_ip='".$_SERVER['REMOTE_ADDR']."'
													WHERE festival_advance_id_fk='".$check_fad_details[0]['festival_advance_id_pk']."'
												");
				}
			}
				
				
				if($fad_emp_ins && $fad_details_ins)
				{
					$count_insert++;
				}

		}
		
		if($total_no_of_employees==$count_insert)
		{
			pg_query("COMMIT"); 
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The Festival Advance has been successfully submitted.</strong></div>';
			header('location:ll_emp_festival_advance_list.php');
			exit(0);
		}
		else
		{
			pg_query("ROLLBACK");
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Festival Advance Submission Fails.</strong></div>';
			header('location:ll_emp_festival_advance_list.php');
			exit(0);
		}
	}
	
//}