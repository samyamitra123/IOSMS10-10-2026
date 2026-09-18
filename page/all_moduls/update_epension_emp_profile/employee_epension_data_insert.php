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

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
/*$sec_time_token.'-------'.$enc_session; */
$crypto = new cryptography();
	//print_r($_REQUEST); die;
	//$user_name=strtoupper($_POST['user_name']);
	$memo_no=$_POST['momo_no'];
	$momo_date=dateshow($_POST['tch_date']);
	$present_memo_no=$_POST['p_momo_no'];
	 $present_momo_date=dateshow($_POST['p_tch_date']); 
	$stake=$_POST['stake_lvl_select'];
	$emp_id_fk=$crypto->decode($_POST['emp_id_pk'],4);
	$present_memo_no=$_POST['p_momo_no'];
	$present_momo_date= dateshow($_POST['p_tch_date']);
	
	//$district_gp_id_fk=$_POST['district_gp'];
	// $ps_code=$_POST['ps_code'];
	 $check_stake=$_SESSION['user_info']['stake_level'];
	 if($check_stake==37)
{
	 $curreent_gp_ps_zp_code=$_SESSION['location']['ps_id'];
}
else if($check_stake==64)
{
	 $curreent_gp_ps_zp_code=$_SESSION['user_info']['stake_user'];
}
else if($check_stake==52)
{
	$curreent_gp_ps_zp_code=$_SESSION['location']['district_id'];
}
	 
	
	if($stake=='555')
	{
		$block_id_fk=$_POST['block_id'];
		$gp_code= $_POST['gp_code'];  
		$district_id_fk=$_POST['district_gp'];
		
		$zp_id_fk=0;
		$ps_code=0;
	}
	else if($stake=='556')
	{
		$ps_code=$_POST['ps_code'];
		$block_id_fk=0;
		$gp_code= 0;  
		$district_id_fk=$_POST['district_id'];
		$zp_id_fk=0;
		
	}
	else if($stake=='557')
	{
		$district_id_fk=$_POST['district_id'];
		$ps_code=0;
		$block_id_fk=0;
		$gp_code= 0; 
		$zp_id_fk1=$_POST['district_id'];
		$db=new database();
		$zp_id=$db->fetch_table("select district_code from prd_location_master_district where district_id_pk='".$zp_id_fk1."'");
		$zp_id_fk=$zp_id[0]['district_code'];
	}
	
	
	////////////////////////////////////////////////////////////////condisation/////////////////////////////////////
    $check_stake=$_SESSION['user_info']['stake_level'];
	$stake_level=$crypto->encode($check_stake,4);
	if($stake_level==37)
	{
	$id=$crypto->encode(1,4);
	}
	else if($stake_level==37)
	{
		$id=$crypto->encode(1,4);
	}
	
	
	if ($memo_no == '')
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter First Joining Memo Number. </strong></div>';
		header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		exit(0);
		}
		
	 if ($momo_date== '')
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter First Joining Memo Date. </strong></div>';
		header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		exit(0);
		}
		
		
		
		if ($present_memo_no == '')
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Present Joining Memo Number. </strong></div>';
		header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		exit(0);
		}
		
	 if ($present_momo_date== '')
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Present Joining Memo Date. </strong></div>';
		header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		exit(0);
		}
		
		if($stake=='' || $stake=='0' )
	   {
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Stake User. </strong></div>';
		header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		exit(0);
	   }
	   
	   if($stake=='557'  )
	   {
			if($district_id_fk==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
	   }
	   if($stake=='556'  )
	   {
			if($district_id_fk==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
			else if($ps_code==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select PS. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
	   }
	   
	   if($stake=='555'  )
	   {
			if($district_id_fk==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
			else if($block_id_fk==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select BLOCK. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
			else if($gp_code==''  )
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select GP. </strong></div>';
				header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
				exit(0);
			}
			
	   }
	   
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//prd_stake_epension_employee_profile
$checking=$db->fetch_table("select count(emp_id_fk) as count  from prd_stake_epension_employee_profile where emp_id_fk='".$emp_id_fk."'  ");
$db=new database();
 if($checking[0]['count']>=1)
 {


	$query=$db->update("UPDATE prd_stake_epension_employee_profile SET
	                 first_momo_no='".$memo_no."',
					 first_momo_wef_date='".$momo_date."',
					 stake_level_id_fk='".$stake."',
					 gp_code='".$gp_code."',
					 block_id_fk='".$block_id_fk."',
					 ps_code='".$ps_code."',
					 zp_id_fk='".$zp_id_fk."',
					 district_id_fk='".$district_id_fk."',
					 present_memo_no='".$present_memo_no."',
					 presnt_memo_wef_date='".$present_momo_date."',
					 curremt_gp_ps_zp_code='".$curreent_gp_ps_zp_code."',
					 status='1'
					 where emp_id_fk='".$emp_id_fk."'
					  ");
	 
 }
 else
 {
	
	 
	$query=$db->insert("INSERT INTO prd_stake_epension_employee_profile
								(
									first_momo_no,
									first_momo_wef_date ,
									stake_level_id_fk,
									entry_time,
									entry_ip_address,
									gp_code,
									block_id_fk,
									ps_code,
									zp_id_fk ,
									district_id_fk,
									status,
									emp_id_fk,
									present_memo_no,
									presnt_memo_wef_date,
									curremt_gp_ps_zp_code
									)
									VALUES 
										('".$memo_no."',
										'".$momo_date."',
										'".$stake."',
										'now()',
										'".$_SERVER['REMOTE_ADDR']."',
										'".$gp_code."',
										'".$block_id_fk."',
										'".$ps_code."',
										'".$zp_id_fk."',
										'".$district_id_fk."','1','".$emp_id_fk."',
										'".$present_memo_no."',
										'".$present_momo_date."',
										'".$curreent_gp_ps_zp_code."'
										
										)");
 }
 
 if($query)
 {
	$update=$db->update("UPDATE prd_employee_master SET
	                 update_status='1'
					 where emp_id_pk='".$emp_id_fk."'
					 
					  ");
	 
 }
if($update)
		{
			
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Submitted Successfully...</strong></div>';
			header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee Profile Submission failed...</strong></div>';
			header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		}