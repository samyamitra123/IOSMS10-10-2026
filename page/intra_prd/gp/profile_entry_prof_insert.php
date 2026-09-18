<?
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*error_reporting(0);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$db = new database();
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_REQUEST['emp_id_pk'],4);
	include 'entry_prof.php';
	exit;
}
else{
$emp_id_pk=$_REQUEST['emp_id_pk'];

$Query = "select emp_status from prd_employee_master where emp_id_pk=".$emp_id_pk;
$emp_status = $db->fetch_table($Query);
$emp_status = $emp_status[0]['emp_status'];
//print_r($emp_status); exit;



$vice_desig=!empty($_REQUEST['vice_desig'])?$_REQUEST['vice_desig'] : '0';
$first_join=explode('-',$_REQUEST['first_join_date']);
$first_join_date=$first_join[2].'-'.$first_join[1].'-'.$first_join[0];

if($_REQUEST['conf_dt_applicable']=='1'){
$conf_dt=explode('-',$_REQUEST['confirm_date']);
$confirm_date=$conf_dt[2].'-'.$conf_dt[1].'-'.$conf_dt[0];
}else{
$confirm_date='0001-01-01';
}
$post_date=explode('-',$_REQUEST['present_post_date']);
$present_post_date=$post_date[2].'-'.$post_date[1].'-'.$post_date[0];
$office_date=explode('-',$_REQUEST['present_office_date']);
$present_office_date=$office_date[2].'-'.$office_date[1].'-'.$office_date[0];
$retire_date=explode('-',$_REQUEST['emp_date_retirement']);
$emp_date_retirement=$retire_date[2].'-'.$retire_date[1].'-'.$retire_date[0];
//$emp_date_retirement=date("Y-m-d", strtotime($_REQUEST['emp_date_retirement']));
$termination=explode('-',$_REQUEST['emp_date_retirement']);
$emp_date_termination=$termination[2].'-'.$termination[1].'-'.$termination[0];
$deputation=$_REQUEST['deputation']==''?'0':$_REQUEST['deputation'];
$employee_group=$_REQUEST['employee_group']==''?'0':$_REQUEST['employee_group'];
$first_desig=!empty($_REQUEST['first_desig'])?$_REQUEST['first_desig']:'0';
$increment_date=date("Y-m-d", strtotime($_POST['increment_date']));
$increment_amount=!empty($_REQUEST['increment_amount'])?$_REQUEST['increment_amount']:'0.00';
$conf_dt_applicable=$_REQUEST['conf_dt_applicable'];
$form_status=$_REQUEST['form_status'];
$recruitment_type = $_REQUEST['recruitment_type'];




$memo_no=$_POST['momo_no'];
	 // $momo_date=($_POST['tch_date']); 
	 $present_memo_no=$_POST['p_momo_no'];
	// $present_momo_date=($_POST['p_tch_date']);  
	 $stake=$_POST['stake_lvl_select'];
	 $present_momo=explode('-',$_POST['p_tch_date']);
	 $momo_d=explode('-',$_POST['tch_date']);
	 $present_momo_date= $present_momo[2].'-'. $present_momo[1].'-'. $present_momo[0];
	 $momo_date= $momo_d[2].'-'.$momo_d[1].'-'. $momo_d[0];
	 
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
	
	
	
	
	$pension_stat=$_POST['pension_stat'];
	if($pension_stat=='0')
	{
		$update_pension='0';
	}
	elseif(($pension_stat=='1' || $pension_stat=='2') && $pension_id=='true')
	{
		$update_pension='2';
	}
	elseif($pension_id=='false')
	{
		$update_pension=$pension_stat;
	}
	
if($form_status>2){
	$emp_form_status=$form_status;
}
else{
	$emp_form_status=2;
}

			$db = new database();
			$code_data = $db->fetch_table("
											SELECT code, description
											FROM prd_dise_code_master;
	
										");
		    
			if($validator->blank_select($vice_desig) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Designation.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			else if($recruitment_type == '0')
			{
			//print($recruitment_type); exit;	
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Recruitment Type must be Promoted / Direct.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}			
			/*else if($first_join_date>='2020-01-01')
			{
				
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of First Joining in service.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}*/
			else if($validator->blank_select($first_join_date) == FALSE || $first_join_date == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of First Joining in service.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			
			else if($_REQUEST['conf_dt_applicable']=='1' && ($validator->blank_select($confirm_date) == FALSE || $confirm_date == '0001-01-01')){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Confirmation in service.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			/*else if($_REQUEST['conf_dt_applicable']=='1' && strtotime($confirm_date)<strtotime($first_join_date)){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of Confirmation in service.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
		  }*/
			else if($validator->blank_select($present_post_date) == FALSE || $present_post_date == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of joining in the present post.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			/*else if(strtotime($present_post_date)<strtotime($first_join_date)){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of joining in the present post.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}*/
			else if($validator->blank_select($present_office_date) == FALSE || $present_office_date == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Joining in the Present Office.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			else if($validator->blank_select($increment_date) == FALSE || $increment_date == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Increment .</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			/*else if(strtotime($present_office_date)<strtotime($first_join_date) || strtotime($present_office_date)<strtotime($confirm_date) || strtotime($present_office_date)<strtotime($present_post_date)){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of Joining in the Present Office.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}*/
			else if($validator->blank_select($emp_date_retirement) == FALSE || $emp_date_retirement == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Retirement / Termination.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			
			else if(empty($emp_next_increment_amount)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Increment Ammount.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'entry_prof.php';
			exit;
			
		}
			/*else if($vice_desig!='1120')
			{
			if($validator->blank_select($deputation) == FALSE || $deputation == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Whether on Deputation.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
			else if($validator->blank_select($employee_group) == FALSE || $employee_group == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Employee Group.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
			}
		   }*/
		   
			
			else if($validator->code_match($vice_desig,$code_data) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Designation Selection.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'entry_prof.php';
				exit;
			}
			else if($validator->code_match($first_desig,$code_data) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Designation at First Appointment Selection.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'entry_prof.php';
				exit;
			}
			
			
			if($memo_no == '')
	{
		
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter First Joining Memo Date</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	
	
	
	
	
		
	if ($momo_date== '')
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter First Joining Memo Date. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
		
		
		
		if ($present_memo_no == '')
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Present Joining Memo Number. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
		
	 if ($present_momo_date== '')
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Present Joining Memo Date. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
		
		if($stake=='' || $stake=='0' )
	   {
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Stake User. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	   }
	   
	   if($stake=='557' && $district_id_fk==''  )
	   {
		   
			if($district_id_fk==''  )
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
			}
	   }
	   if($stake=='556' && $district_id_fk='' && $ps_code=='' )
	   {
		  
			if($district_id_fk==''  )
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
			}
			else if($ps_code==''  )
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select PS. </strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
			}
	   }
	   
	   if($stake=='555' && $district_id_fk=='' && $block_id_fk=='' && $gp_code=='' )
	   {
		  // echo $district_id_fk.'--'.$block_id_fk.'--'.$gp_code; die;
		  
		if($district_id_fk==''   )
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select District. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
		else if($block_id_fk==''  )
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select BLOCK. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
		else if($gp_code==''  )
		{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Please Select GP. </strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
		}
	   }
			else
			{

	
/*echo "UPDATE prd_employee_master set
						   	emp_desig='$vice_desig',
							emp_first_join_date='$first_join_date',
							emp_conf_join_date='$confirm_date',
							emp_join_prsnt_post_date='$present_post_date',
							emp_join_prsnt_office_date='$present_office_date',
							emp_retirement_date='$emp_date_retirement',
							emp_termination_date='$emp_date_termination',
							emp_status_deputation='$deputation',
							emp_group='$employee_group',
							emp_desig_first_app='$first_desig',
							emp_next_increment_date='$increment_date',
							emp_next_increment_amount='$increment_amount',
							conf_dt_flag='$conf_dt_applicable',
							emp_form_status='$emp_form_status',
							entry_time='now()',
							entry_ip='".$_SERVER['REMOTE_ADDR']."'
						 WHERE
						     emp_id_pk='$emp_id_pk'";
						exit;*/
						
						
$checking=$db->fetch_table("select count(emp_id_fk) as count  from prd_stake_epension_employee_profile where emp_id_fk='$emp_id_pk'  ");

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
					WHERE
					emp_id_fk='$emp_id_pk'
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
										'".$district_id_fk."','1','$emp_id_pk',
										'".$present_memo_no."',
										'".$present_momo_date."',
										'".$curreent_gp_ps_zp_code."'
										
										)");
					  
           }
		   
if($query)
{
	$update=$db->update("UPDATE prd_employee_master SET
	update_status='1'
	WHERE
	emp_id_pk='$emp_id_pk'
	
	");

}
$Query = "UPDATE prd_employee_master set
						   	emp_desig='$vice_desig',
							emp_first_join_date='$first_join_date',
							emp_conf_join_date='$confirm_date',
							emp_join_prsnt_post_date='$present_post_date',
							emp_join_prsnt_office_date='$present_office_date',
							emp_retirement_date='$emp_date_retirement',
							emp_termination_date='$emp_date_termination',
							emp_status_deputation='$deputation',
							emp_group='$employee_group',
							emp_desig_first_app='$first_desig',
							emp_next_increment_date='$increment_date',
							emp_next_increment_amount='$increment_amount',
							conf_dt_flag='$conf_dt_applicable',
							emp_form_status='$emp_form_status',
							recruitment_type='$recruitment_type',
  						    entry_time='now()',
							entry_ip='".$_SERVER['REMOTE_ADDR']."'
                            WHERE
						    emp_id_pk='$emp_id_pk'";
//print($Query); exit;						     
$query_insert=$db->update($Query);

if($query_insert){
	//echo 'Location:profile_entry_sal.php?desig='.$cryptoGraph->encode($_REQUEST['vice_desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success';
if($emp_status == 11)
  {
    $_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submitted Successfully...</strong></div>';  	
	header('Location:employee_edit_details.php');
	exit(0);

  }	
else 
  {  
header('Location:profile_entry_sal.php?desig='.$cryptoGraph->encode($_REQUEST['vice_desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
exit(0);
  }
}
else{
header('Location:profile_entry_prof.php?desig='.$cryptoGraph->encode($_REQUEST['vice_desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'confirm=false');
exit(0);
}
}
}
?>