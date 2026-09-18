<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
$cryp = new cryptography();
$db = new database();
error_reporting(0);

?>



<?php
 
/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/

$stake_level= $_SESSION['user_info']['stake_abbr']; 
 


if($stake_level=="EO")
{
	
	 $ps_id_fk=$_SESSION['location']['ps_id'];
	 $gp_id_fk='0';
	 $zp_id_fk='0';
	  $block_code='0';
	 $user='ps';
}
else if($stake_level=="BDO")
{
	 $block_code=$_SESSION['location']['block_code']; 
	 $emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
	  $gp_id=$db->fetch_table("SELECT gp_id_fk FROM prd_employee_master
									WHERE emp_status in('1',9) AND emp_id_pk='$emp_id_pk'");
	 $gp_id_fk=$gp_id[0]['gp_id_fk'];
	 $ps_id_fk='0';
	 $zp_id_fk='0';
	 $user='gp';
	
}
else if($stake_level=="DEALING ASSISTANT (Account)")
{
	 $zp_id_fk=$_SESSION['location']['district_id'];
	 $gp_id_fk='0';
	 $block_code='0';
	 $ps_id_fk='0';
	 $user='zp';
}
//$municipality_id_fk = substr($_SESSION['user_info']['stake_user'],0,7);
  $update=$cryp->decode($_GET['update'],4); 

//var_dump( $update); die;
if($update=='inc')
{
	
	$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
	$emp_id_const=$cryp->decode($_GET['emp_id_const'],4);
	
	$emp_type=$db->fetch_table("SELECT zp_emp_type FROM prd_employee_master
									WHERE emp_id_pk= '".$emp_id_pk."'");
	
	$field_cnt_year=$_GET['field_cnt_year']; 
	$field_cnt=$_GET['field_cnt_'.$field_cnt_year];
	
	$pay_band_year=$_GET['ppb_'.$field_cnt_year];
	$grade_pay_year=$_GET['gp_'.$field_cnt_year];
	
	$ason_date=$cryp->decode($_GET['ason_date'],4);
	$to_date=$cryp->decode($_GET['to_date'],4); 
	
	$date_arr=array();
	$date_arr1=array();
	$arr_index=0;
	
	
	if($field_cnt >= 1 && ($_GET['increment_name_'.$field_cnt_year.'_1']!='' || $_GET['incr_date_'.$field_cnt_year.'_1']!='' || $_GET['incr_type_'.$field_cnt_year.'_1']!='' || $_GET['grade_pay_'.$field_cnt_year.'_1']!=''))
	{
		
		$count_for_match=0;
		////////////////////////////////validations////////////////////////////////////////////	
		for($field=1; $field<=$field_cnt; $field++) 
		{
			//print_r($_GET);
			$increment_name=$_GET['increment_name_'.$field_cnt_year.'_'.$field];
			$incr_date=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
			$incr_type=$_GET['incr_type_'.$field_cnt_year.'_'.$field];
			$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
			$current_grade_pay=$_GET['current_grade_pay_'.$field_cnt_year.'_'.$field];
			
			$get_increments_details=$db->fetch_table("SELECT effective_date FROM prd_emp_increment_details_before_2019
									WHERE increment_dtls='1' AND emp_id_fk='$emp_id_pk' AND effective_year='".($field_cnt_year+1)."'");
					
			$date_before_sisth_month=date('Y-m-d', strtotime('-6 month',strtotime($get_increments_details[0]['effective_date'])));
				
				
			if($increment_name=='')
			{
				/*echo 1234;
				echo $increment_name;*/ 
				echo 'Please Select Annual / Promotional Increment.';
				exit();	
			}
			else if($incr_date=='')
			{
				echo 'Please Select Date.';	
				exit();
			}
			else if((strtotime($incr_date) > strtotime($to_date)) || (strtotime($incr_date) < strtotime($ason_date)))
			{
				echo 'Please Select Valid Date.';	
				exit();
			}
			
			else if($incr_type=='')
			{
				echo 'Please Select Increment Type.';	
				exit();
			}
			else if($increment_name!=1 && $grade_pay=='')
			{
				echo 'Please Select Previous Grade Pay.';	
				exit();
			}
			else if($current_grade_pay=='')
			{
				echo 'Please Enter Current Grade Pay.';	
				exit();
			}
			//else if(($increment_name==2 || $increment_name==3) && (strtotime($incr_date) > strtotime($date_before_sisth_month)))
			else if(($increment_name==2 || $increment_name==3) && (strtotime($incr_date) > strtotime($date_before_sisth_month)) && (count($get_increments_details)>0))
			{
				//echo $increment_name.'--'.strtotime($incr_date).'--'.strtotime($date_before_sisth_month).'--'.count($get_increments_details);
				echo 'Please Enter Valid Date For Promotion or CAS.';	
				exit();
			}
		}
		////////////////////////////////validations////////////////////////////////////////////
		
		
		for($field=1; $field<=$field_cnt; $field++)
		{
			//echo 1234;
			//$date_arr[$arr_index]['index']=$field;
		//	//$timestamp = strtotime($date);
		//	$date_arr[$arr_index]['date']=strtotime($_GET['incr_date_'.$field_cnt_year.'_'.$field]);
			$date_arr1['index']=$field;
			//$date_arr1['date']=strtotime($_GET['incr_date_'.$field_cnt_year.'_'.$field]);
			//$date_arr1['date']=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
			$date_arr1['date']=date("d/m/Y h:i:s", strtotime($_GET['incr_date_'.$field_cnt_year.'_'.$field]));
			$date_arr[$arr_index]=$date_arr1;
			
			$arr_index++;
		}
		
		usort($date_arr, function ($a, $b) {
			$dateA = DateTime::createFromFormat('d/m/Y H:i:s', $a['date']);
			$dateB = DateTime::createFromFormat('d/m/Y H:i:s', $b['date']);
			// ascending ordering, use `<=` for descending
			//return $dateA >= $dateB;
			return $dateB >= $dateA;
		});
		
		
		////////////////////////////////validations////////////////////////////////////////////
		$total_no_increments=0;
		$total_double_increments=0;
		foreach($date_arr as $data_fields) 
		{
			$field=$data_fields['index'];
			$increment_name=$_GET['increment_name_'.$field_cnt_year.'_'.$field];
			$incr_date=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
			$incr_type=$_GET['incr_type_'.$field_cnt_year.'_'.$field];
			$current_grade_pay=$_GET['current_grade_pay_'.$field_cnt_year.'_'.$field];
			//$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
			if($_GET['grade_pay_'.$field_cnt_year.'_'.$field]!='')
			{
				$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
			}
			else
			{
				$grade_pay=0;
			}
			
			if($increment_name==1)
			{
				$total_no_increments=$total_no_increments+1; 
				$total_no_increments_date=$incr_date;
				$total_no_increments_date_date_before_sisth_month=date('Y-m-d', strtotime('-6 month',strtotime($total_no_increments_date)));
			}
			
			if($incr_type==2)
			{
				$total_double_increments=$total_double_increments+1; 
			}
			
			if(($total_no_increments==1) && ($increment_name==2 || $increment_name==3) && (strtotime($incr_date) > strtotime($total_no_increments_date_date_before_sisth_month)) && (strtotime($incr_date)==strtotime($total_no_increments_date)))
			{
				echo 'Please Enter Promotion or CAS Details First Then Enter Annual Increment Details.';
				exit();
			}
			else if(($total_no_increments==1) && ($increment_name==2 || $increment_name==3) && (strtotime($incr_date) > strtotime($total_no_increments_date_date_before_sisth_month)) && (strtotime($incr_date)!=strtotime($total_no_increments_date)))
			{
				echo 'Please Enter Valid Date For Promotion or CAS.';	
				exit();
			}
			else if($total_no_increments > 1)
			{
				echo 'Invalid no of Annual Increments.';	
				exit();
			}
			else if($total_no_increments > 0 && $total_double_increments > 0 && $emp_type[0]['zp_emp_type']!='366')
			{
				echo 'Annual Increment and Double Increment Both Cannot be Submitted.';	
				exit();
			}
		}
		 //$cryp->decode($_SESSION['edit_pay_band'],4); die;
		//Newly moved
		if($cryp->decode($_SESSION['edit_pay_band'],4)==0 && $pay_band_year!=$_SESSION['ppb_'.$field_cnt_year])
		{
			echo "Please Enter Valid Pay in Pay Band.";
			exit();	
		}
		else if($cryp->decode($_SESSION['edit_pay_band'],4)==0 && $grade_pay_year!=$_SESSION['gp_'.$field_cnt_year])
		{
			echo "Please Enter Valid Grade Pay.";
			exit();	
		}
		else if($cryp->decode($_SESSION['edit_pay_band'],4)==1 && ($pay_band_year > ($_SESSION['max_pay_inpayband']-1) || $pay_band_year < $_SESSION['min_pay_inpayband']))
		{
			echo "Please Enter Valid Pay in Pay Band.";
			exit();	
		}
		////////////////////////////////validations////////////////////////////////////////////
		
		
		
		
		$field_cal=1;
		foreach($date_arr as $data_fields) 
		{
			$field=$data_fields['index'];
			$increment_name=$_GET['increment_name_'.$field_cnt_year.'_'.$field];
			$incr_date=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
			$incr_type=$_GET['incr_type_'.$field_cnt_year.'_'.$field];
			$current_grade_pay=$_GET['current_grade_pay_'.$field_cnt_year.'_'.$field];
			//$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
			if($_GET['grade_pay_'.$field_cnt_year.'_'.$field]!='')
			{
				$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
			}
			else
			{
				$grade_pay=0;
			}
			// insert into mad_emp_pay_details_before_2019 when click on the check box below the year
			
			
			$query_insert_before_2019=$db->insert("INSERT into prd_emp_increment_details_before_2019
												(											
												 emp_id_fk,
												 increment_dtls,
												 increment_type,   
												 effective_date,
												 previous_grade_pay,
												 gp_id_fk,
												 block_code,
												 ps_id_fk,
												 zp_id_fk,
												 update_time,
												 update_ip,
												 effective_year,
												 emp_unique_id,
												 status,
												 current_grade_pay
												 )
												 VALUES(											
												 '$emp_id_pk',
												 '$increment_name',
												 '$incr_type',
												 '$incr_date',
												 '$grade_pay',
												 '$gp_id_fk',
												 '$block_code',
												 '$ps_id_fk',
												 '$zp_id_fk',
												 now(),
												 '".$_SERVER['REMOTE_ADDR']."',
												 '$field_cnt_year',
												 '$emp_id_const',
												 '1',
												 '$current_grade_pay')");
		 $count_for_match++;
												
			
		}
	}
	else
	{
		$count_for_match=1;	
	}

if($field_cnt==$count_for_match)
	{
	
	$query_insert_before_2019_yearly=$db->insert("INSERT into prd_emp_basic_pay_details_before_2019
											(
												emp_id_fk,
												gp_id_fk,
												block_code,
												ps_id_fk,
												zp_id_fk,
												pay_band,
												grade_pay,
												update_time,
												update_ip,
												year,
												emp_unique_id
												)
												VALUES(											
												'$emp_id_pk',
												'$gp_id_fk',
												'$block_code',
												'$ps_id_fk',
												'$zp_id_fk',
												'$pay_band_year',
												'$grade_pay_year',
												now(),
												'".$_SERVER['REMOTE_ADDR']."',
												'$field_cnt_year',
												'$emp_id_const')");
	}
	
		
											 
	if($query_insert_before_2019_yearly)
	{
		$new_field_cnt_year=$field_cnt_year-1;
		$_SESSION['ppb_'.$new_field_cnt_year]=$pay_band_year;
		$_SESSION['gp_'.$new_field_cnt_year]=$grade_pay_year;
		echo 1;
	}
	else
	{
		echo "Please Submit All Selected Data.";
	}
}
else if($update=='revoke')
{
	
	$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);  
	  
	
	$delete_emp_archieve=$db->delete("DELETE FROM ropa_2019_emp_pay_scale_master WHERE emp_id_fk='$emp_id_pk'");
		
		////////////////////////// revoke////////////////////////////////////
		
	if($delete_emp_archieve)
	{
	$query_update_ropa_2019=$db->update("UPDATE prd_employee_master 
	SET 
	ropa_status='0'
	WHERE emp_id_pk='$emp_id_pk'
	AND ropa_status in('2')");
	}
	
	
	if($query_update_ropa_2019)
	{
		echo 1;
	}
}


else if($update=='finz')
{
	
	//echo 11113;die;
	$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
	$effective_date_ff=$_GET['effective_date_ff']; 
	// $stack_id=$cryp->decode($_GET['stack_id'],4); 
	$basic=$_GET['basic'];
	$level=$_GET['level'];
	
	
	$get_gradepay=$db->fetch_table("SELECT level FROM prd_dise_gradepay_master 
								WHERE CAST (grade_code as integer)=(SELECT CAST (emp_grade_pay as integer) FROM 
								prd_employee_master WHERE emp_id_pk='$emp_id_pk')");
	
	$emp_type=$db->fetch_table("SELECT zp_emp_type FROM prd_employee_master
								WHERE emp_id_pk='$emp_id_pk'");
								
								if($emp_type[0]['zp_emp_type']=='' || $emp_type[0]['zp_emp_type']=='0' || $emp_type[0]['zp_emp_type']=='367')
								{
									$ropa_table='ropa_2019';
								}
								else 
								{
									$ropa_table='ropa_2019_ll';
								}
								
	$get_ropa=$db->fetch_table("SELECT level".$get_gradepay[0]['level']." FROM ".$ropa_table."
								WHERE level".$get_gradepay[0]['level']."='$basic'");
								//echo $get_gradepay[0]['level']; die;
	
	if(($basic != $_SESSION['basic_2020']) || ($level != $_SESSION['level_2020']))
	{
		echo 'Please Enter Valid Basic Pay or Level.';
		exit();
	}							
	else if(($get_ropa[0]["level".$get_gradepay[0]['level']] != $basic) || ("level".$get_gradepay[0]['level'] != $level))
	{
		echo 'Basic Pay and Level as on 01-01-2020 Does Not Match.';
		exit();
	} 
	
	
	/***************************************************** Changed By ANJAN 26-11-2019 ****************************************************/
	
	$reason="ropa_2019_updation";
	$mad_employee_master_archive_delete=$db->insert("INSERT INTO prd_employee_master_archive_ropa_2019 (
			emp_id_fk, gp_id_fk, emp_first_name, emp_second_name, emp_last_name , emp_dob , emp_sex, emp_caste, emp_voter_id , emp_aadhar_no ,
			emp_edu_quali, emp_desig , emp_first_join_date , emp_conf_join_date , emp_join_prsnt_post_date , emp_join_prsnt_office_date ,
			emp_retirement_date , emp_termination_date , emp_status_deputation, emp_group, emp_desig_first_app, emp_next_increment_date ,
			emp_next_increment_amount , emp_cosolidated_pay, emp_pay_band , emp_pay_in_payband, emp_grade_pay_bk , emp_pay_scale , emp_bank_name ,
			emp_bank_branch , emp_branch_code , emp_micr_no, emp_acc_no , emp_ifsc_no , emp_father_name , emp_mother_name , emp_religion,
			emp_mother_tongue , emp_marital_status, emp_spouse_name , emp_spouse_job_status , emp_spouse_details , emp_spouse_pay , emp_spouse_hra,
			emp_spouse_res , emp_spouse_house_schm , emp_pan_no , emp_blood_grp , emp_height, emp_diff_able, emp_disable_status , emp_idf_mark ,
			pre_state , emp_pre_house_no , emp_pre_street_no , emp_pre_vill , emp_pre_post , emp_pre_pin , emp_pre_dist, emp_pre_dist_others ,
			per_state , emp_per_house_no , emp_per_street_no , emp_per_vill , emp_per_post , emp_per_pin , emp_per_dist , emp_per_dist_others ,
			emp_land_no, emp_mobile_no, emp_mail_id , emp_system_code, emp_form_status, emp_status , entry_time, entry_ip, empcd , emp_grade_pay ,
			emp_id_const, bank_upd_status , bank_msg_flag, conf_dt_flag, interim_relief, spouse_medical_allowance, conv_allow_status, ps_id_fk,
			emp_pension_status, zp_emp_type, zp_id_fk, notification_no, emp_accommodation, emp_pre_ps, emp_per_ps, emp_unlock_status, emp_govt_id,
			emp_gpf_acc_no, emp_gvt_hra_type, emp_gvt_hra_ammount, update_status, emp_first_memo_no, emp_first_memo_date, emp_first_gp_ps_zp_code,
			emp_present_memo_no, emp_present_memo_date, reason, stake_user )
			  
			SELECT emp_id_pk, gp_id_fk, emp_first_name, emp_second_name, emp_last_name , emp_dob , emp_sex, emp_caste, emp_voter_id , emp_aadhar_no ,
			emp_edu_quali, emp_desig , emp_first_join_date , emp_conf_join_date , emp_join_prsnt_post_date , emp_join_prsnt_office_date ,
			emp_retirement_date , emp_termination_date , emp_status_deputation, emp_group, emp_desig_first_app, emp_next_increment_date ,
			emp_next_increment_amount , emp_cosolidated_pay, emp_pay_band , emp_pay_in_payband, emp_grade_pay_bk , emp_pay_scale , emp_bank_name ,
			emp_bank_branch , emp_branch_code , emp_micr_no, emp_acc_no , emp_ifsc_no , emp_father_name , emp_mother_name , emp_religion,
			emp_mother_tongue , emp_marital_status, emp_spouse_name , emp_spouse_job_status , emp_spouse_details , emp_spouse_pay , emp_spouse_hra,
			emp_spouse_res , emp_spouse_house_schm , emp_pan_no , emp_blood_grp , emp_height, emp_diff_able, emp_disable_status , emp_idf_mark ,
			pre_state , emp_pre_house_no , emp_pre_street_no , emp_pre_vill , emp_pre_post , emp_pre_pin , emp_pre_dist, emp_pre_dist_others ,
			per_state , emp_per_house_no , emp_per_street_no , emp_per_vill , emp_per_post , emp_per_pin , emp_per_dist , emp_per_dist_others ,
			emp_land_no, emp_mobile_no, emp_mail_id , emp_system_code, emp_form_status, emp_status , entry_time, entry_ip, empcd , emp_grade_pay ,
			emp_id_const, bank_upd_status , bank_msg_flag, conf_dt_flag, interim_relief, spouse_medical_allowance, conv_allow_status, ps_id_fk,
			emp_pension_status, zp_emp_type, zp_id_fk, notification_no, emp_accommodation, emp_pre_ps, emp_per_ps, emp_unlock_status, emp_govt_id,
			emp_gpf_acc_no, emp_gvt_hra_type, emp_gvt_hra_ammount, update_status, emp_first_memo_no, emp_first_memo_date, emp_first_gp_ps_zp_code,
			emp_present_memo_no, emp_present_memo_date, '".$reason."', '".substr($_SESSION['user_info']['stake_user'],0,7)."' 
			FROM prd_employee_master
			where emp_id_pk='".$emp_id_pk."'");
				
	

/********************************************************** END 26-11-2019 ******************************************************/	
				
	//$mad_employee_master_archive_delete='1';
	if($mad_employee_master_archive_delete)
	{
		$level_int=str_replace("level","",$level);
		
		
		
		if($stake_level=='EO' || $stake_level=='BDO')
		{
			/*$emp_data = $db->fetch_table("
								SELECT 
										  emp_pay_in_payband
										  
								FROM prd_employee_master
								WHERE emp_id_pk = '".$emp_id_pk."'
								
		
		");
		$prv_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];*/
	
		/*$upadate_emp_master=$db->insert("UPDATE prd_employee_master
											 SET	
											 emp_pay_in_payband='$basic',   
											 ropa_9_emp_pay_in_payband='$prv_pay_in_payband',
											 ropa_level='$level'
											 WHERE emp_id_pk='$emp_id_pk' and  emp_status in ('1','9')
											 ");*/
											 
											 $upadate_emp_master=1;
		}
		else
		{
			$upadate_emp_master=1;
		}
	
		/*$upadate_emp_master=$db->insert("UPDATE prd_employee_master
											 SET	
											 ropa_basic_pay='$basic',   
											 ropa_level='$level'
											 WHERE emp_id_pk='$emp_id_pk' and  emp_status in ('1','9')
											 ");
											*/
		$query_insert_ropa_2019=$db->insert("UPDATE ropa_2019_emp_pay_scale_master
											 SET	
											 basic_pay='$basic',   
											 level='$level',
											 status='2',
											 sent_status='0',
											 update_time=now(),
											 update_ip='".$_SERVER['REMOTE_ADDR']."'
											 WHERE emp_id_fk='$emp_id_pk'
											 ");	
	}
											 
	if($query_insert_ropa_2019 && $upadate_emp_master)
	{
		echo 'success';	
	}
	else
	{
		echo 'failed';	
	}
}


else if($update=='r2019_epsm')
	{
		//echo 12; die;
		$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
		 $effective_date_ff=$_GET['ropa_date']; 
		 $ropa_reason=$cryp->decode($_GET['ropa_reason'],4); 
		
		$get_employee_data=$db->fetch_table("SELECT emp_first_join_date,emp_status FROM prd_employee_master
		WHERE emp_id_pk='$emp_id_pk'");
	
	if($effective_date_ff=='' || strtotime($effective_date_ff) < strtotime($get_employee_data[0]['emp_first_join_date']))
	{
		//echo 11;die;
		
		echo "Please Select Valid Date.";
		exit();	
	}
	else if($ropa_reason=='')
	{ 
		
		echo "Please Select Valid Reason.";
		exit();	
	}
	/*else if((strtotime($effective_date_ff)==strtotime('01-01-2016') && $ropa_reason!=1) || (strtotime($effective_date_ff)>strtotime('01-01-2016') && $ropa_reason==1))
	{
		
		echo "Please Select Valid Reason.";
		exit();	
	}*/
	//echo $get_employee_data[0]['emp_status'].'---'.$ropa_reason; die;
	else if($ropa_reason!='8' && $get_employee_data[0]['emp_status']=='9')
	{
		
		echo "Please Select Valid Reason.";
		exit();	
	}
	else if($ropa_reason=='8' && $get_employee_data[0]['emp_status']=='1')
	{
		
		
		echo "Please Select Valid Reason.";
		exit();	
	}
	/*else if(strtotime($effective_date_ff)>strtotime('25.09.2019'))
	{
		//echo $effective_date_ff; die;
		echo "Please Select Pay Fixation Date.";
		exit();	
	}*/
		
		
		$get_r2019_epsm=$db->fetch_table("SELECT emp_id_fk FROM ropa_2019_emp_pay_scale_master WHERE emp_id_fk='$emp_id_pk'");
		
		if(count($get_r2019_epsm)<1)
		{
			
		
			$query_insert_ropa_2019=$db->insert("INSERT into ropa_2019_emp_pay_scale_master
													(											
													 emp_id_fk,
													 gp_id_fk,
													 block_code,
													 ps_id_fk,
													 zp_id_fk,
													 status,
													 update_time,
													 update_ip,
													 ropa_2019_effective_date,
													 cause
													 )
													 VALUES(											
													 '$emp_id_pk',
													 '".$gp_id_fk."',
													 '".$block_code."',
													 '".$ps_id_fk."',
													 '".$zp_id_fk."',	 
													 '1',
													 now(),
													 '".$_SERVER['REMOTE_ADDR']."',
													 '$effective_date_ff',
													 '$ropa_reason')");
													 
			if($ropa_reason=='7' || $ropa_reason=='8' || $ropa_reason=='9' || $ropa_reason=='10')
			{
				$query_update_ropa_2019=$db->update("UPDATE prd_employee_master 
												SET 
												ropa_status='2'
												WHERE emp_id_pk='$emp_id_pk'
												AND ropa_status in('0')");
			}
													 
		}
		else
		{
			
			$query_insert_ropa_2019=$db->update("UPDATE ropa_2019_emp_pay_scale_master 
												SET 
												ropa_2019_effective_date='$effective_date_ff',
												cause='$ropa_reason',
												status='1',
												update_time=now(),
												update_ip='".$_SERVER['REMOTE_ADDR']."'
												WHERE emp_id_fk='$emp_id_pk'
												AND status in('0')");
												
												
				if($ropa_reason=='7' || $ropa_reason=='8' || $ropa_reason=='9' || $ropa_reason=='10')
				{
				$query_update_ropa_2019=$db->update("UPDATE prd_employee_master 
				SET 
				ropa_status='2'
				WHERE emp_id_pk='$emp_id_pk'
				AND ropa_status in('0')");
				}						
		}
		
		if($query_insert_ropa_2019)
		{
			echo 1;	
		}
		else
		{
			echo 0;	
		}

}
else if($update=='fi')
{
	
	
	$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
	
	$update_ropa_2019_emp_pay_scale_master=$db->update("UPDATE ropa_2019_emp_pay_scale_master 
														SET basic_pay='0', level='',
														status='1'
														WHERE emp_id_fk='$emp_id_pk'");
	$update_ropa_2019_emp_master='1';
														
	/*$update_ropa_2019_emp_master=$db->update("UPDATE prd_employee_master 
														SET ropa_9_emp_pay_in_payband='0', ropa_level=''
														
														WHERE emp_id_pk='$emp_id_pk' and emp_status in ('1','9')");*/
														
	if($update_ropa_2019_emp_pay_scale_master && $update_ropa_2019_emp_master)
	{
		//$delete_emp_archieve='1';
		
		/******************************** Changed By ANJAN 26-11-2019 ****************************************/
		
		$delete_emp_archieve=$db->delete("DELETE FROM prd_employee_master_archive_ropa_2019 WHERE emp_id_fk='$emp_id_pk'");
		
		/********************************** END 26-11-2019 ********************************************/
	}
	
	if($delete_emp_archieve)
	{
		echo 1;
	}
}
else if($update=='yr')
{
	$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);  
	 $curr_yr=$_GET['curr_yr']; 
	
	$delete_mad_emp_increment_details_before_2019=$db->delete("DELETE FROM prd_emp_increment_details_before_2019 
															WHERE emp_id_fk='$emp_id_pk'
															AND effective_year='$curr_yr'");
														
	$delete_mad_emp_basic_pay_details_before_2019=$db->delete("DELETE FROM prd_emp_basic_pay_details_before_2019 
															WHERE emp_id_fk='$emp_id_pk'
															AND year='$curr_yr'");
	
	if($delete_mad_emp_increment_details_before_2019 && $delete_mad_emp_basic_pay_details_before_2019)
	{
		$get_mad_emp_basic_pay_details_before_2019=$db->fetch_table("SELECT year FROM prd_emp_basic_pay_details_before_2019
													WHERE emp_id_fk='$emp_id_pk'");
													
		if(count($get_mad_emp_basic_pay_details_before_2019) < 1)
		{
			$update_ropa_2019_emp_pay_scale_master=$db->update("UPDATE ropa_2019_emp_pay_scale_master SET 
												status='0'
												WHERE emp_id_fk='$emp_id_pk'");
		}
		echo 1;
	}
}
?>
