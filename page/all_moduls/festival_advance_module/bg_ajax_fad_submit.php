<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();


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



require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
$db = new database();		

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
	
	$gp_ids=$_POST['gp'];
	$emp_id=$_POST['emp_name'];
	$instl_no=$_POST['instl_no'];
	$instl_amount=$_POST['instl_amount'];
	$instl_amount_last=$_POST['instl_amount_last'];
	$bill_id=$_POST['bill_id'];	
		
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$salary_monthyear = date('Ym');
	$query_string = '?dise='.$crypto->encode($schcd,3);

	///////////////////////////////////////////////////////////////////////// Server side blank validation//////////////////////////////////////////////////////////////////
	
	
	if($emp_id=="")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Employee.</strong></div>';exit;
	}
	elseif($instl_no=="")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Instalment Number.</strong></div>';exit;
	}
	elseif($instl_amount==""  || $instl_amount=="0")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';exit;
	}
	elseif($instl_amount_last=="" || $instl_amount_last=="0")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';exit;
	}
	elseif($bill_id=="")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';exit;
	}		
		

 
 /////////////////////////////////////////////////////////////////////////////// Blank Validation Ends ////////////////////////////////////////////////////////////////////////////////////////
 
 ////////////////////////////////////////////////////////////////////////////// Duplicate Employee Checking //////////////////////////////////////////////////////////////////////////////////
 
	

	$count_insert=0;
					
	if($logged_user=='EO')
	{
		$fad_edit_archive_insert=$db->insert("
												INSERT INTO prd_festival_advance_entry_sal_edit_archive
												(
													festival_advance_sal_id_fk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													update_time,
													update_ip
												)
												SELECT 
													festival_advance_sal_id_pk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													sal.update_time,
													sal.update_ip
												FROM prd_festival_advance_entry_sal sal
												INNER JOIN prd_festival_advance_employee_details fad
												ON fad.festival_advance_id_pk=sal.festival_advance_id_fk
												WHERE fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."' 
												");
		
		if($fad_edit_archive_insert == TRUE)
		{
			$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal sal SET
										festival_advance_instalment_no='".$instl_no."',
										festival_advance_instalment_amount='".$instl_amount."',
										festival_advance_instalment_last_amount='".$instl_amount_last."',
										update_time='now()',
										update_ip='".$_SERVER['REMOTE_ADDR']."'
										FROM prd_festival_advance_employee_details fad
										WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk AND fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."'
									");
		}
	}
	else if($logged_user=='BDO')
	{
		$fad_edit_archive_insert=$db->insert("
												INSERT INTO prd_festival_advance_entry_sal_edit_archive
												(
													festival_advance_sal_id_fk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													update_time,
													update_ip
												)
												SELECT 
													festival_advance_sal_id_pk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													sal.update_time,
													sal.update_ip
												FROM prd_festival_advance_entry_sal sal
												INNER JOIN prd_festival_advance_employee_details fad
												ON fad.festival_advance_id_pk=sal.festival_advance_id_fk
												WHERE fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."' 
												");
		
		if($fad_edit_archive_insert == TRUE)
		{
			$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal sal SET
										festival_advance_instalment_no='".$instl_no."',
										festival_advance_instalment_amount='".$instl_amount."',
										festival_advance_instalment_last_amount='".$instl_amount_last."',
										update_time='now()',
										update_ip='".$_SERVER['REMOTE_ADDR']."'
										FROM prd_festival_advance_employee_details fad
										WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk 
										AND fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."'
									");
		}
	}
	else if($logged_user=='zpacc')
	{
		$fad_edit_archive_insert=$db->insert("
												INSERT INTO prd_festival_advance_entry_sal_edit_archive
												(
													festival_advance_sal_id_fk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													update_time,
													update_ip
												)
												SELECT 
													festival_advance_sal_id_pk,
													festival_advance_id_fk,
													festival_advance_instalment_no,
													festival_advance_instalment_amount,
													festival_advance_instalment_last_amount,
													deduction_start_monthyear,
													deduction_end_monthyear,
													deduction_counter,
													total_amt_given,
													status,
													sal.update_time,
													sal.update_ip
												FROM prd_festival_advance_entry_sal sal
												INNER JOIN prd_festival_advance_employee_details fad
												ON fad.festival_advance_id_pk=sal.festival_advance_id_fk
												WHERE fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."' 
												");
		
		if($fad_edit_archive_insert == TRUE)
		{
			$fad_details_ins=$db->update(" UPDATE prd_festival_advance_entry_sal sal SET
										festival_advance_instalment_no='".$instl_no."',
										festival_advance_instalment_amount='".$instl_amount."',
										festival_advance_instalment_last_amount='".$instl_amount_last."',
										update_time='now()',
										update_ip='".$_SERVER['REMOTE_ADDR']."'
										FROM prd_festival_advance_employee_details fad
										WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk AND fad.bill_id_fk='".$bill_id."' AND fad.emp_id_fk='".$emp_id."'
									");
		}
	}
	
	if($fad_details_ins)
	{
		echo '<div class="alert alert-success" style="text-align:center"><strong>The Festival Advance has been successfully updated.</strong></div>';exit;
	}
	else
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Festival Advance updation Fails.</strong></div>';exit;
	}

	
//}