<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require 'page_visite.php';
$crypto = new cryptography();
$db = new database();
 if(isset($_POST['submit']))
	{
	
		$db = new database();
		//$dob=date("Y-m-d", strtotime($_REQUEST['dob']));
		$emp_id=$_REQUEST['emp_name'];
		$month=$_REQUEST['month'];
		$year=$_REQUEST['year'];
		$ropa_status=$_REQUEST['ropa_status'];
	    $monthyear=$_REQUEST['year'].$_REQUEST['month']; 
		//var_dump($ropa_status); die;
		//echo "select dob from ehrms_dise_teacher_primary tch where tch.tch_mob_no='".$mob."'";
		
		if($ropa_status == ""){
			$ropa_status=0;
		}
		
		$chk_data=$db->fetch_table("select emp_dob,emp_id_pk from prd_employee_master where emp_id_pk='".$emp_id."' AND emp_status in('1','2','9')
		AND ropa_status ='".$ropa_status."'");
		
		$Query = "select emp_id_fk from prd_monthly_salary_archive_final sal 
		where sal.emp_id_fk='".$emp_id."' 
		AND sal.salary_monthyear='".$monthyear."' 
		AND sal.delete_status='1'
		AND sal.is_saved='1'
		AND sal.status_flag IN ('3','4')
		AND sal.ropa_status ='".$ropa_status."'
		";
		//print_r($Query); exit;
		$chk_salary=$db->fetch_table($Query);
		//print_r($chk_salary); exit;
//		$chk_salary_archieve=$db->fetch_table("select emp_id_fk from prd_monthly_salary_archive_final sal 
//		where sal.emp_id_fk='".$emp_id."' 
//		AND sal.salary_monthyear='".$monthyear."' 
//		AND sal.delete_status='1'
//		AND sal.status_flag='3'
//		");

		/*if(($validator->blank_select($dob) == FALSE) || $dob == '0001-01-01')
			{
				//echo '1';
				$_SESSION['msg']='<div id="error">Please Enter Valid DOB.</div>';
				header('Location:'.$config['base_url'].'page/intra_prd/gp/employee_payslip.php?dob='.$dob.'&mobile_no='.$mobile_no.'&month='. $month.'&year='.$year);
				exit;
			}
		else if($validator->blank_select($mobile_no) == FALSE)
			{
				//echo '2';
				$_SESSION['msg']='<div id="error">Please Enter Mobile Number.</div>';
				header('Location:'.$config['base_url'].'page/intra_prd/gp/employee_payslip.php?dob='.$dob.'&mobile_no='.$mobile_no.'&month='. $month.'&year='.$year);
				exit;
			}*/
	
		if($validator->blank_select($year) == FALSE)
			{
				$_SESSION['msg']='<div id="error">Please Select Year.</div>';
				header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php?pm_di='.$crypto->encode($emp_id,4).'&nthmo='. $crypto->encode($month,4).'&reay='.$crypto->encode($year,4).'&ropa='.$crypto->encode($ropa_status,4) );
				exit;
			}
		else if($validator->blank_select($month) == FALSE)
			{
				//echo '4';
				$_SESSION['msg']='<div id="error">Please Select Month.</div>';
				header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php?pm_di='.$crypto->encode($emp_id,4).'&nthmo='. $crypto->encode($month,4).'&reay='.$crypto->encode($year,4).'&ropa='.$crypto->encode($ropa_status,4));
				exit;
			}
	      else if(($monthyear)<=(201608)){
			
			$_SESSION['msg']='<div id="error">Invalid Month Year.</div>';
			header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php?pm_di='.$crypto->encode($emp_id,4).'&nthmo='. $crypto->encode($month,4).'&reay='.$crypto->encode($year,4).'&ropa='.$crypto->encode($ropa_status,4));
			exit(0);
		  }
	
	//else if(count($chk_salary)==0 && count($chk_salary_archieve)==0){
	else if(count($chk_salary)==0 ){
			//echo '5';
			//die;
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>No data found.</strong></div>';
			//$_SESSION['msg']='<div id="error">No Data Found.</div>';
			header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php?pm_di='.$crypto->encode($emp_id,4).'&nthmo='. $crypto->encode($month,4).'&reay='.$crypto->encode($year,4).'&ropa='.$crypto->encode($ropa_status,4));
			exit(0);
		}
		else{
			
$_SESSION['payslip_active']='payslip_active';
			header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php?pm_di='.$crypto->encode($emp_id,4).'&nthmo='. $crypto->encode($month,4).'&reay='.$crypto->encode($year,4).'&ropa='.$crypto->encode($ropa_status,4));
			exit(0);
 
} 
} 
?>

