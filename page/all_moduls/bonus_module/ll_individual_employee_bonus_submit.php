<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
//print_r($_POST);die;
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}


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


$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];
if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	include 'emp_bonus_module_sal_form.php';
	exit;
}
else
{
	$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_pk'],4);
	$monthyear = $_POST['bon_monthyr'];
	$bonus_name = $_POST['bon_name'];
	$bonus_amount = $_POST['bonus_amount']; 
	$gp_id_fk = $_SESSION['location']['gp_id'];
	$block_code = $_SESSION['location']['block_code'];
	$ps_id_fk = $_SESSION['location']['ps_id'];
	$zp_id_fk = $_SESSION['location']['district_id'];
	$bonus_type_id=$_POST['bon_type_id'];
	$bonus_max_amount=$_POST['bon_max_amount']; 
	
	
if($logged_user=='zpdaa')
{
	
	if($validator->blank_select($monthyear) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
	if($validator->blank_select($bonus_name) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
	if($validator->blank_select($bonus_type_id) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
	if($validator->blank_select($bonus_max_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
	if($validator->blank_select($bonus_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
	if ($bonus_amount == 0 || $validator->pattern_number($bonus_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php'); 
		exit;
	}
	//if ($bonus_amount > $bonus_max_amount || $bonus_amount< $bonus_max_amount)
	if ($bonus_amount > $bonus_max_amount )
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit;
	}
}
else
{
			if($validator->blank_select($monthyear) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
	if($validator->blank_select($bonus_name) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
	if($validator->blank_select($bonus_type_id) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
	if($validator->blank_select($bonus_max_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Security Error.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
	if($validator->blank_select($bonus_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
	if ($bonus_amount == 0 || $validator->pattern_number($bonus_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php'); 
		exit;
	}
	//if ($bonus_amount > $bonus_max_amount || $bonus_amount< $bonus_max_amount)
	if ($bonus_amount > $bonus_max_amount )
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Bonus Amount.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit;
	}
}
	
	$db = new database();	
	
	if($emp_id_pk!="")
	{
		pg_query('BEGIN');
		$query_update=$db->update(" UPDATE prd_employee_bonus_details SET bonus_status=1,delete_status=0 
										WHERE emp_id_fk='".$emp_id_pk."' AND bonus_type_id_fk='".$bonus_type_id."'
										AND bonus_status in ('1','2') AND delete_status='1'");
			
		
		
		if($logged_user=='zpdaa')
		{
											
			$query_insert=$db->insert("INSERT into prd_employee_bonus_details
											(
												bonus_type_id_fk,
												gp_id_fk,
												ps_id_fk,
												zp_id_fk,
												emp_id_fk,
												bonus_amount,
												bonus_name,
												monthyear,
												bonus_status,
												delete_status,
												update_time,
												update_ip 
											)
											VALUES(
												'".$bonus_type_id."',
												0,
												0,
												'".$zp_id_fk."',
												'".$emp_id_pk."',
												'".$bonus_amount."',
												'".$bonus_name."',
												'".$monthyear."',
												1,
												1,
												now(),
												'".$_SERVER['REMOTE_ADDR']."'
											)");
		}
		else if($logged_user=='DA')
		{
			$query_insert=$db->insert("INSERT into prd_employee_bonus_details
											(
												bonus_type_id_fk,
												gp_id_fk,
												ps_id_fk,
												zp_id_fk,
												emp_id_fk,
												bonus_amount,
												bonus_name,
												monthyear,
												bonus_status,
												delete_status,
												update_time,
												update_ip 
											)
											VALUES(
												'".$bonus_type_id."',
												0,
												'".$ps_id_fk."',
												0,
												'".$emp_id_pk."',
												'".$bonus_amount."',
												'".$bonus_name."',
												'".$monthyear."',
												1,
												1,
												now(),
												'".$_SERVER['REMOTE_ADDR']."'
											)");
		}
		else if($logged_user=='GP')
		{
			$query_insert=$db->insert("INSERT into prd_employee_bonus_details
											(
												bonus_type_id_fk,
												gp_id_fk,
												ps_id_fk,
												zp_id_fk,
												emp_id_fk,
												bonus_amount,
												bonus_name,
												monthyear,
												bonus_status,
												delete_status,
												update_time,
												update_ip,
												block_code
											)
											VALUES(
												'".$bonus_type_id."',
												'".$gp_id_fk."',
												0,
												0,
												'".$emp_id_pk."',
												'".$bonus_amount."',
												'".$bonus_name."',
												'".$monthyear."',
												1,
												1,
												now(),
												'".$_SERVER['REMOTE_ADDR']."',
												'".$block_code."'
											)");
		}
	}
		
		//var_dump($query_insert); die;
	if($query_insert && $query_update)
	{
		
		if($logged_user=='zpdaa')
		{
		pg_query('COMMIT');
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Submitted Successfully.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit(0);
		}
		else
		{
			pg_query('COMMIT');
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Submitted Successfully.</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit(0);
		}
	}
	else
	{
		if($logged_user=='zpdaa')
		{
		pg_query('ROLLBACK');
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Submitted. Please Try Again...</strong></div>';
		header('location:ll_emp_bonus_module_sal_form_zp.php');
		exit(0);
		}
		else
		{
			pg_query('ROLLBACK');
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Submitted. Please Try Again...</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit(0);
		}
	}
	
	
}
@pg_close($con);
?>