<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$db=new database();
/*$sec_time_token.'-------'.$enc_session; */
$crypto = new cryptography();
 //$emp_id_fk=$crypto->decode($_POST['emp_id_k'],4);
 $emp_id_fk=$_POST['emp_id_k'];
	
	
$stake_level= $_SESSION['user_info']['stake_abbr'];  
 
if($stake_level=="EO")
{
	 $id="ps_id_fk='".$_SESSION['location']['ps_id']."'";
	 $user='ps';
}
else if($stake_level=="BDO")
{
	 $id="block_code='".$_SESSION['location']['block_code']."'";
	 //$gp_id=$_REQUEST['id'];
	 $user='gp';
}
else if($stake_level=="ACCOUNTANT")
{
	
	 $id="zp_id_fk='".$_SESSION['location']['district_id']."'"; 
	 $user='zp';
}
else if($stake_level=="FC&CAO")
{
	
	 $id="zp_id_fk='".$_SESSION['location']['district_id']."'"; 
	 $user='zp';
}



if($crypto->decode($_POST['flag'],4)=='approve' )
{

			
			//echo $emp_id_pk; die;
	$db=new database();
	
	
	
	
	//emp_first_gp_ps_zp_code
	
	$basic_insert=$db->fetch_table("SELECT basic_pay,level FROM ropa_2019_emp_pay_scale_master 
	WHERE emp_id_fk='".$emp_id_fk."'
	and status in('2')  ");
	
	$basic=	$basic_insert[0]['basic_pay'];			 
	$level= $basic_insert[0]['level'];
	
	$emp_data = $db->fetch_table("
								SELECT 
										  emp_pay_in_payband
										  
								FROM prd_employee_master
								WHERE emp_id_pk = '".$emp_id_fk."' 
								
		
		");
		$prv_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
	
	$upadate_emp_master=$db->insert("UPDATE prd_employee_master
	SET	
	emp_pay_in_payband='$basic',
	ropa_9_emp_pay_in_payband='$prv_pay_in_payband',
	ropa_status='1',     
	ropa_level='$level'
	WHERE emp_id_pk='$emp_id_fk' and  emp_status in ('1','9')
	");
	
	
	$security =$db->update ("UPDATE ropa_2019_emp_pay_scale_master SET
	status='3'
	where emp_id_fk='".$emp_id_fk."'
	and status in('2') 
	");
									

 			

if($security==TRUE )
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Pay Fixation Approve Successfully...</strong></div>';
		
	header('Location:up_pay_commission_user_profile_view.php');
}
else 
{

$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee Pay Fixation Approve Failed. Please try again..</strong></div>';
		
	header('Location:up_pay_commission_user_profile_view.php');
}


}






if($crypto->decode($_POST['flag'],4)=='reject' )
{

 
 $db=new database();
 
if($stake_level=="ACCOUNTANT")
{
	$security =$db->update ("UPDATE ropa_2019_emp_pay_scale_master SET
	                 status='4',sent_status='2'
					 where emp_id_fk='".$emp_id_fk."'
					 and status in('2','4')  AND sent_status in('0','3')
					  ");
									
		
					
}
else if($stake_level=="FC&CAO")
{
	$security =$db->update ("UPDATE ropa_2019_emp_pay_scale_master SET
	                 status='4',sent_status='3'
					 where emp_id_fk='".$emp_id_fk."'
					 and status in('2')  AND sent_status='1'
					  ");
}

  			
			
 			

if($security==TRUE  )
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Pay Fixation Reject Successfully...</strong></div>';
		
	header('Location:up_pay_commission_user_profile_view.php');
}




else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Employee Pay Fixation Rejection Failed. Please try again...</strong></div>';
	
	header('Location:up_pay_commission_user_profile_view.php');

}


}









if($crypto->decode($_POST['flag'],4)=='forward' )
{


 
 $db=new database();

  $security =$db->update ("UPDATE ropa_2019_emp_pay_scale_master SET
	                 sent_status='1',status='2'
					 where emp_id_fk='".$emp_id_fk."'
					 and status in('2','4') 
					  ");
									
						
			
 			

if($security==TRUE)
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Pay Fixation Forwarded Successfully...</strong></div>';
		
	header('Location:up_pay_commission_user_profile_view.php');
}




else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Employee Pay Fixation Rejection Failed. Please try again...</strong></div>';
	
	header('Location:up_pay_commission_user_profile_view.php');

}


}

?>
