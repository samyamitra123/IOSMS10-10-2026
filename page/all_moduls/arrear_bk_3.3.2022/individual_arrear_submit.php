<?php
//die;
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$logged_user=$_SESSION['user_info']['stake_abbr'];

if($logged_user=='BDO')
{
$str=$_SESSION['location']['block_code'];
$state10=substr($str,0,4); 
}
else
{
$str=0;
$state10=0; 
}
$crypto = new cryptography();

 $emp_type= $crypto->decode($_POST['zp_emp_type'],4);  
   
   
   if($emp_type=='366')
{
$emp_type1=$crypto->encode('3',4);
}
elseif($emp_type=='367')
{
$emp_type1=$crypto->encode('4',4);
}
else
{
	$emp_type1=$crypto->encode(0,4);
}
$db = new database();		
		
	if($_POST['total_row']!="")
	{
		$total_row=$_POST['total_row'];
	}
	else
	{
		$total_row=0;
	}
	if($_POST['emp_id_fk']!="" && ($_POST['gp']!='' || $logged_user=='EO' || $logged_user=='FC&CAO')){ 
	$arrear_ids=$_POST['arrear_id']; 
	$gp_ids=$_POST['gp']; 
	$emp_ids=$_POST['emp_id_fk']; 
	$arrear_to_dates=$_POST['arrear_to_date'];
	$arrear_fm_dates=$_POST['arrear_fm_date'];
	$total_working_days=$_POST['working_days'];
	$consolidated_pays=$_POST['consolidated_pay'];
	//$empcds=$_POST['empcd'];
	//$tchnames=$_POST['tchname'];
	$pay_in_bands=$_POST['pay_in_band'];
	$grade_pays=$_POST['grade_pay'];
	$das=$_POST['da'];
	$hras=$_POST['hra']; 
	$mas=$_POST['ma'];
	$conv_allows=$_POST['conv_allow'];
	$hill_allows=$_POST['hill_allow'];
	$interim_reliefs=$_POST['interim_relief'];
	$grosss=$_POST['gross'];
	$gpfs=$_POST['gpf'];
	 $pf_loans=$_POST['pf_loan']; 
	$p_taxs=$_POST['p_tax'];
	$i_taxs=$_POST['i_tax'];
	$gsli=$_POST['gsli'];
	//$festival_advs=$_POST['festival_adv'];
	$nets=$_POST['net'];
	
		
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$salary_monthyear = date('Ym');
	//$query_string = '?dise='.$crypto->encode($schcd,3);

	///////////////////////////////////////////////////////////////////////// Server side blank validation//////////////////////////////////////////////////////////////////
	
	
	
	if($total_row!=0)
	{
		for($k=0;$k<$total_row;$k++)
		{
			if($logged_user=='BDO' && $gp_ids[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select GP.</strong></div>';
				header('location:employee_lists.php');
				exit(0);
			}
			elseif($emp_ids[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Employee.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			elseif($arrear_fm_dates[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Arrear From Date.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			elseif($arrear_to_dates[$k]=="")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Arrear To Date.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			elseif($total_working_days[$k]=="" || $total_working_days[$k]=="0")
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Valid Working Days.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			
		if($consolidated_pays[$k]>0)
		{
			//echo 11; die;
			if($consolidated_pays[$k]=="" || $consolidated_pays[$k]=='0')
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Consolidate Pay Amount Insertion Failed.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			else if($grosss[$k]!=$consolidated_pays[$k] || $grosss[$k]=="" || $consolidated_pays[$k]=='0')
			{ //print_r($grosss); 
			 // print_r($consolidated_pays); die;
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Gross Amount Insertion Failed.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			else if(($grosss[$k]-$p_taxs[$k])!=$nets[$k])
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Net Amount Insertion Failed.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			else if($nets[$k]>$grosss[$k] ||$nets[$k]=="" ||$nets[$k]=='0')
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Net Amount Insertion Failed...</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
		}
		else 
		{
			//echo 78; die;
			if($consolidated_pays[$k]==0)
			{
				
				
				$total_allowance=$pay_in_bands[$k]+$grade_pays[$k]+$das[$k]+$hras[$k]+$mas[$k]+$conv_allows[$k]+$hill_allows[$k]+$interim_reliefs[$k];
			
				 $total_deduction=$gpfs[$k]+$pf_loans[$k]+$p_taxs[$k]+$i_taxs[$k]+$gsli[$k]; 
				
				/*if($pay_in_bands[$k]==0 ||$pay_in_bands[$k]=="")
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Pay In Pay Band Amount Insertion Failed.</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				}*/
				/*else if($grade_pays[$k]==0 ||$grade_pays[$k]=="" )
				{ 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Grade Pay Amount Insertion Failed.</strong></div>';             
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				} */
				 if($das[$k]==0 ||$das[$k]=="" )
				{ 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> DA Amount Insertion Failed.</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				}
				else if($grosss[$k]==0 ||$grosss[$k]=="" )
				{ 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Gross Amount Insertion Failed.</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				}	
				else if($nets[$k]==0 ||$nets[$k]=="" ||$nets[$k]>$grosss[$k] )
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Net Amount Insertion Failed.</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);					
				}
				else if($grosss[$k]!=$total_allowance)  			
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Wrong Gross Amount Inserted</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				}
				else if((intval($grosss[$k])-intval($total_deduction))!=$nets[$k])  			
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Wrong Net Amount Inserted</strong></div>';
					header('location:employee_lists.php?emp_type='.$emp_type1);
					exit(0);
				}	
								
			}	
			
		}
			
	}
}
	

 
 ////////////////////////////////////////////////////////////////////////////////// Blank Validation Ends ////////////////////////////////////////////////////////////////////////////////////////
 
 ////////////////////////////////////////////////////////////////////////////// Duplicate Employee Checking //////////////////////////////////////////////////////////////////////////////////
 
	$total_no_of_employees=count($emp_ids); 
	
	$uniq_check=array_unique($emp_ids);
	
	
	
	if($logged_user=='BDO' && $_POST['gp']!="" && $_POST['emp_id_fk']!="")
	{
		$upd=$db->update("UPDATE prd_employee_arrear SET delete_status='0' where emp_id_fk='".$emp_ids[0]."' AND gp_id_fk='".$gp_ids[0]."' and salary_monthyear is null ");
	}
	else if($logged_user=='EO' && $_POST['emp_id_fk']!="")
	{
		$upd=$db->update("UPDATE prd_employee_arrear SET delete_status='0' where emp_id_fk='".$emp_ids[0]."' AND ps_id_fk='".$_SESSION['location']['ps_id']."' and salary_monthyear is null ");
	}
	else if($logged_user=='FC&CAO' && $_POST['emp_id_fk']!="")
	{
		$upd=$db->update("UPDATE prd_employee_arrear SET delete_status='0' where emp_id_fk='".$emp_ids[0]."' AND zp_id_fk='".$_SESSION['location']['district_id']."' and salary_monthyear is null ");
	}
	
	pg_query("BEGIN");
	
		$count_insert=0;
		
		
		for($i=0;$i<$total_no_of_employees;$i++)
		{	
			$to_date=explode('-',$arrear_to_dates[$i]);
			$arr_to_date=$to_date[2].'-'.$to_date[1].'-'.$to_date[0];
			
			$fm_date=explode('-',$arrear_fm_dates[$i]);
			$arr_fm_date=$fm_date[2].'-'.$fm_date[1].'-'.$fm_date[0];
			
			$basics=$pay_in_bands[$i]+$grade_pays[$i]; 
			
			if($logged_user=='BDO')
			{
				$emp_details_fetch=$db->fetch_table("select emp_bank_name,emp_acc_no,emp_ifsc_no,emp_pan_no,emp_id_const from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND gp_id_fk='".$gp_ids[$i]."'"); 
				
			
				$ins=$db->insert("
									INSERT INTO prd_employee_arrear
									(latestupdate_time,
									latestupdate_ip_address,
									block_code,
									gp_id_fk,
									emp_id_fk,
									emp_id_const,
									bankname,
									accountno,
									bank_ifsc,
									consolidated_pay,
									pay_payband,
									grade_pay,
									basic,
									da,
									hra,
									ma,
									conv_allow,
									hill_allowance,
									interim_relief,
									gross_salary,
									gpf,
									cpf,
									pf_loan,
									p_tax,
									i_tax,
									net,
									status_flag,
									is_saved,
									delete_status,
									working_days,
									arrear_to_date,
									arrear_from_date,
									ps_id_fk,zp_id_fk,zp_emp_type)
									VALUES
									( 'now()',
									'".$_SERVER['REMOTE_ADDR']."',
									'".$str."',
									'".$gp_ids[$i]."',
									'".$emp_ids[$i]."',
									'".$emp_details_fetch[0]['emp_id_const']."',
									'".$emp_details_fetch[0]['emp_bank_name']."',
									'".$emp_details_fetch[0]['emp_acc_no']."',
									'".$emp_details_fetch[0]['emp_ifsc_no']."',
									'".$consolidated_pays[$i]."',
									'".$pay_in_bands[$i]."',
									'".$grade_pays[$i]."',
									'".$basics."',
									'".$das[$i]."',
									'".$hras[$i]."',
									'".$mas[$i]."',
									'".$conv_allows[$i]."',
									'".$hill_allows[$i]."',
									'".$interim_reliefs[$i]."',
									'".$grosss[$i]."',
									'".$gpfs[$i]."',
									'0',
									'".$pf_loans[$i]."',
									'".$p_taxs[$i]."',
									'".$i_taxs[$i]."',
									'".$nets[$i]."',
									'1',
									'0',
									'1',
									'".$total_working_days[$i]."',
									'".$arr_to_date."',
									'".$arr_fm_date."',
									'0','0','0')");
				
				if($ins)
				{
					$count_insert++;
				}
				
			}
			else if($logged_user=='EO')
			{
			
				$emp_details_fetch=$db->fetch_table("select emp_bank_name,emp_acc_no,emp_ifsc_no,emp_pan_no,emp_id_const from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'"); 
				
				

				$ins=$db->insert("
									INSERT INTO prd_employee_arrear
									(latestupdate_time,
									latestupdate_ip_address,
									block_code,
									gp_id_fk,
									emp_id_fk,
									emp_id_const,
									bankname,
									accountno,
									bank_ifsc,
									consolidated_pay,
									pay_payband,
									grade_pay,
									basic,
									da,
									hra,
									ma,
									conv_allow,
									hill_allowance,
									interim_relief,
									gross_salary,
									gpf,
									cpf,
									
									p_tax,
									i_tax,
									net,
									status_flag,
									is_saved,
									delete_status,
									working_days,
									arrear_to_date,
									arrear_from_date,
									ps_id_fk,zp_emp_type)
									VALUES
									( 'now()',
									'".$_SERVER['REMOTE_ADDR']."',
									'',
									'0',
									'".$emp_ids[$i]."',
									'".$emp_details_fetch[0]['emp_id_const']."',
									'".$emp_details_fetch[0]['emp_bank_name']."',
									'".$emp_details_fetch[0]['emp_acc_no']."',
									'".$emp_details_fetch[0]['emp_ifsc_no']."',
									'".$consolidated_pays[$i]."',
									'".$pay_in_bands[$i]."',
									'".$grade_pays[$i]."',
									'".$basics."',
									'".$das[$i]."',
									'".$hras[$i]."',
									'".$mas[$i]."',
									'".$conv_allows[$i]."',
									'".$hill_allows[$i]."',
									'".$interim_reliefs[$i]."',
									'".$grosss[$i]."',
									'".$gpfs[$i]."',
									'0',
									
									'".$p_taxs[$i]."',
									'".$i_taxs[$i]."',
									'".$nets[$i]."',
									'1',
									'0',
									'1',
									'".$total_working_days[$i]."',
									'".$arr_to_date."',
									'".$arr_fm_date."',
									'".$_SESSION['location']['ps_id']."','0')");
				
				if($ins)
				{
					$count_insert++;
				}
				
			}
			
			else if($logged_user=='FC&CAO')
			{
			
				$emp_details_fetch=$db->fetch_table("select emp_bank_name,emp_acc_no,emp_ifsc_no,emp_pan_no,emp_id_const,zp_emp_type from prd_employee_master where emp_id_pk='".$emp_ids[$i]."' AND zp_id_fk='".$_SESSION['location']['district_id']."'"); 
				if($emp_details_fetch[0]['zp_emp_type']=='366')
				{
				$emp_type=$crypto->encode('3',4);
				}
				elseif($emp_details_fetch[0]['zp_emp_type']=='367')
				{
				$emp_type=$crypto->encode('4',4);
				}
			
				$ins=$db->insert("
									INSERT INTO prd_employee_arrear
									(latestupdate_time,
									latestupdate_ip_address,
									block_code,
									gp_id_fk,
									emp_id_fk,
									emp_id_const,
									bankname,
									accountno,
									bank_ifsc,
									consolidated_pay,
									pay_payband,
									grade_pay,
									basic,
									da,
									hra,
									ma,
									conv_allow,
									hill_allowance,
									interim_relief,
									gross_salary,
									gpf,
									cpf,
									pf_loan,
									p_tax,
									i_tax,
									gsli,
									net,
									status_flag,
									is_saved,
									delete_status,
									working_days,
									arrear_to_date,
									arrear_from_date,
									ps_id_fk,
									zp_id_fk,
									zp_emp_type)
									VALUES
									( 'now()',
									'".$_SERVER['REMOTE_ADDR']."',
									'',
									'0',
									'".$emp_ids[$i]."',
									'".$emp_details_fetch[0]['emp_id_const']."',
									'".$emp_details_fetch[0]['emp_bank_name']."',
									'".$emp_details_fetch[0]['emp_acc_no']."',
									'".$emp_details_fetch[0]['emp_ifsc_no']."',
									'".$consolidated_pays[$i]."',
									'".$pay_in_bands[$i]."',
									'".$grade_pays[$i]."',
									'".$basics."',
									'".$das[$i]."',
									'".$hras[$i]."',
									'".$mas[$i]."',
									'".$conv_allows[$i]."',
									'".$hill_allows[$i]."',
									'".$interim_reliefs[$i]."',
									'".$grosss[$i]."',
									'".$gpfs[$i]."',
									'0',
									'0',
									'".$p_taxs[$i]."',
									'".$i_taxs[$i]."',
									'".$gsli[$i]."',
									'".$nets[$i]."',
									'1',
									'0',
									'1',
									'".$total_working_days[$i]."',
									'".$arr_to_date."',
									'".$arr_fm_date."',
									'0',
									'".$_SESSION['location']['district_id']."','".$emp_details_fetch[0]['zp_emp_type']."')");
				
				if($ins)
				{
					$count_insert++;
				}
				
			}
		
			
		}
		
		
		
		
		
		if($logged_user=='FC&CAO')
		{
		
			if($total_no_of_employees==$count_insert)
			{
				pg_query("commit"); 
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The arrear has been successfully submitted.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			else
			{
				pg_query("rollback");
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Arrear Submission Fails.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
	  }
	  else
	  {
		  if($total_no_of_employees==$count_insert)
			{
				pg_query("commit"); 
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The arrear has been successfully submitted.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
			else
			{
				pg_query("rollback");
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Arrear Submission Fails.</strong></div>';
				header('location:employee_lists.php?emp_type='.$emp_type1);
				exit(0);
			}
	  }
	  
	}
	
	//}
	
//}

?>