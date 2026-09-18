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
 $emp_id_fk=$crypto->decode($_POST['emp_id_k'],4);
	//$district_gp_id_fk=$_POST['district_gp'];
	 $stake_level1=$_SESSION['user_info']['stake_level']; 
	 $stake_level=$crypto->encode($stake_level1,4); 
	
if($stake_level1==36)
{
	$id=$crypto->encode(2,4);
	$gp_id=0;
	$gp_id_fk=0;
	$user='ps';
	
}
else if($stake_level1==35)
{
	$id=$crypto->encode(4,4);
	$gp_id=$_POST['gp_id'];
	$gp_id_fk=$crypto->decode($gp_id,4);
	$user='gp';
}

else if($stake_level1==51)
{
	$id=$crypto->encode(7,4);
	$gp_id=0;
	$gp_id_fk=0;
	$user='zp';
}



if($crypto->decode($_POST['flag'],4)=='approve' )
{

			
			//echo $emp_id_pk; die;
	$db=new database();
	$arr=$db->fetch_table("select  mas.emp_pension_status,stake.*
	from prd_employee_master as mas
	inner join prd_stake_epension_employee_profile as stake
	on mas.emp_id_pk=stake.emp_id_fk where stake.emp_id_fk='".$emp_id_fk."' and mas.emp_status in('1','9') ");

	 $momo_no= $arr[0]['first_momo_no'];
	$wef_date=$arr[0]['first_momo_wef_date'];
	$gp_code=$arr[0]['gp_code'];
	$ps_code=$arr[0]['ps_code'];
	$district_id_fk=$arr[0]['district_id_fk'];
	$zp_id_fk=$arr[0]['zp_id_fk'];
	$pension_stat=$arr[0]['emp_pension_status'];
	$present_memo_no=$arr[0]['present_memo_no'];
	$present_momo_date=$arr[0]['presnt_memo_wef_date'];
	
	 if($arr[0]['zp_id_fk']!='0')
	 {
		$first_gp_ps_zp_code=$zp_id_fk;
	 }
	 else if($gp_code!='0')
	 {
		  $first_gp_ps_zp_code=$gp_code;
	 }
	 else if($ps_code!='0')
	 {
		  $first_gp_ps_zp_code=$ps_code;
	 }
	if($pension_stat=='0')
	{
		$update_pension='0';
	}
	elseif($pension_stat=='1' || $pension_stat=='2')
	{
		$update_pension='2';
	}
	
	
	
	//emp_first_gp_ps_zp_code
	
		
			 $security =$db->update ("UPDATE prd_stake_epension_employee_profile SET
	                 status='3'
					 where emp_id_fk='".$emp_id_fk."'
					 and status in('2','3')
					  ");
									

 			 $query_unlock=$db->update("UPDATE prd_employee_master SET
		update_status='3',emp_pension_status='".$update_pension."',emp_first_memo_no='".$momo_no."',emp_first_memo_date='".$wef_date."',emp_first_gp_ps_zp_code='".$first_gp_ps_zp_code."',emp_present_memo_no='".$present_memo_no."',emp_present_memo_date='".$present_momo_date."'
		where emp_id_pk='".$emp_id_fk."'
		and update_status in('2','3')
		"); 

if($security && $query_unlock && $gp_id=='0')
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Approve Successfully...</strong></div>';
		
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id);
}
else if ($security && $query_unlock && $gp_id!='0')
{

$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Approve Successfully...</strong></div>';
		
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id.'&gp_id='.$gp_id);
}

else
{

	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Approve Failed. Please Try again...</strong></div>';
	
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id);

}
}

if($crypto->decode($_POST['flag'],4)=='reject' )
{

 
 $db=new database();
 
  $security =$db->update("UPDATE prd_stake_epension_employee_profile SET
	                 status='4'
					 where emp_id_fk='".$emp_id_fk."'
					 and status='2'
					  ");
									
						
			
 			 $query_unlock=$db->update("UPDATE prd_employee_master SET
		update_status='4'
		where emp_id_pk='".$emp_id_fk."'
		and update_status='2'
		"); 

if($security && $query_unlock &&  $gp_id=='0' )
{
	
	
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Reject Successfully...</strong></div>';
		
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id);
}

else if($security && $query_unlock &&  $gp_id!='0' )
{
	
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Reject Successfully...</strong></div>';
		
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id.'&gp_id='.$gp_id);
}


else
{

	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Rejection Failed. Please try again...</strong></div>';
	
	header('Location:up_employee_profile_approve_list.php?stake_level='.$stake_level.'&id='.$id);

}
}

?>
