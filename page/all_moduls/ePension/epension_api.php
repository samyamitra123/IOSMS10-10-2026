<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

set_time_limit(0);
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
include( '../../all_function/fun_store/ifms_functions.php');
$crypto = new cryptography();
 $emp_id_fk =$crypto->decode($_GET['emp_id'],4);
  $emp_id_const =$crypto->decode($_GET['emp_id_const'],4);
 $flag =$crypto->decode($_GET['flag'],4);
 $type=$crypto->decode($_GET['user'],4);
 $api=$crypto->decode($_GET['f'],4); 
 //$api=$_GET['f'];  


 function dateshow_slash($dateval) 
{
	$date = substr($dateval, 0, 10);
	if ($date == '')
	{
		return '';
	}
	if ($date == '0001-01-01') 
	{
		return '';
	}
	$datearr = explode('-', $date);
	$dob = $datearr['0'] . '-' . $datearr['1'] . '-' . $datearr['2'];
	return $dob == '' ? '' : $dob;
}
 function findDistCode($dist_id, $state) 
{
	if ($state == '99') 
	{
		return '3200';
	} 
	else 
	{
		if ($dist_id == '')
		{
			return '3200';
		} 
		else
		{
			$db=new database();
			$sql_dist_code = $db->fetch_table("SELECT district_code FROM prd_location_master_district WHERE district_id_pk='" . $dist_id . "'");
			
			
			return $sql_dist_code[0]['district_code'];
		}
	}
}

function fun_grade_pay($grade)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$grade."'");
		return $dist_data2[0]['grade_amount'];
	}
	
	
	
	
	 function findGPCode($gp,$g) 
{
	if($g=="GP")
	{
		$db=new database();
		$sql_gp_code = $db->fetch_table("SELECT gp_code FROM prd_location_master_gp WHERE gp_id_pk='" .$gp. "'");
		
		return $sql_gp_code[0]['gp_code'];
	}
	else if($g=="PS")
	{
	
		$db=new database();
		$sql_ps_code = $db->fetch_table("SELECT ps_code FROM prd_location_master_panchayat_samiti WHERE ps_id_pk='" .$gp. "'");
		
		return $sql_ps_code[0]['ps_code'];
	}
	if ($g == 'ZP') 
	{
		$db=new database();
		$sql_ps_code = $db->fetch_table("SELECT district_code FROM prd_location_master_district WHERE district_id_pk='" .$gp. "'");
		
		return $sql_ps_code[0]['district_code'];
		$osms_type = 3;
	}
}
	
	if($api=='push')
{
	if ($type == 'GP')
	{
		$emp_pen_type = '01';
		$osms_type = 1;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='2022-03') OR reason='1993')  AND emp_pension_status='6' AND gp_id_fk!='0' and emp_id_fk='".$emp_id_fk."'  AND flag='".$flag."' ");
		
		
	}
	if ($type == 'PS')
	 {
		$emp_pen_type = '02';
		$osms_type = 2;
		$db=new database();	
	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
			prd_pension_employee
			WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='2022-03') OR reason='1993')  AND emp_pension_status='6' AND ps_id_fk!='0' and emp_id_fk='".$emp_id_fk."'  AND flag='".$flag."'");
		
		
    }
	
	if ($type == 'ZP')
	{
		$emp_pen_type = '03';
		$osms_type = 3;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_pre_ps,
  emp_per_ps,zp_id_fk,claiment_mobile_no,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='2022-03') OR reason='1993')  AND emp_pension_status='6' AND flag in('N') AND zp_id_fk IS NOT NULL
		 AND zp_emp_type='367' and emp_id_fk='".$emp_id_fk."'  AND flag='".$flag."'");
		 
	 
		
	}
	

	//$this->dbClose($db);
	 $total_data_count = count($sql_teacher_data); 
	
	//$transaction_id = $this->generate_transactionID(); 
	if($total_data_count>0)
	{
		
	//$xml = new DOMDocument("1.0", "UTF-8");
	//$container = $xml->createElement('RECORDS');
	//$container = $xml->appendChild($container); 
	
	//$row_info = $xml->createElement('INFO');
	//$row_info = $container->appendChild($row_info);
	
	/*$tarGet = $xml->createElement('tarGet', $type);
	$tarGet = $row_info->appendChild($tarGet);
	$requestID = $xml->createElement('requestID', $request_id);
	$requestID = $row_info->appendChild($requestID);
	$transactionID = $xml->createElement('transactionID', $transaction_id);
	$transactionID = $row_info->appendChild($transactionID);
	$transactionDate = $xml->createElement('transactionDate', date('Y-m-d m:i:s'));
	$transactionDate = $row_info->appendChild($transactionDate);
	$dataserved = $xml->createElement('totalData', $total_data_count);
	$dataserved = $row_info->appendChild($dataserved);*/


	$i = 0;
	$j = 1;

	$add_comma = $emp_name; 
	foreach ($sql_teacher_data as $fetch_teacher)
	 {
		
		/*$emp_name='LATE '.($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);
	$myobj->emp_name=$emp_name;
	$myJSON=json_encode($myobj);
	echo $myJSON; die;*/
		
		if ($fetch_teacher['emp_id_fk'] && $fetch_teacher['emp_pre_pin']) 
		{
		
			if ($j != $total_data_count) 
			{
				$add_comma = ",";
			}
			if ($j == $total_data_count)
			{
				$add_comma = ";";
			}
			
			
			if($fetch_teacher['reason']==1993)
			{
			
			
			$emp_name='LATE '.($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);
			}
			else
			{
				$emp_name=($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);  
			}
			
			$db=new database();
			/*$sql_transaction_details =$db->insert( "INSERT INTO prd_pension_transaction_details(request_id, transaction_id, emp_code, data_send_as, data_send_date,osms_type)VALUES ('". $request_id ."' ,'" . $transaction_id . "','" . $fetch_teacher['emp_id_const'] . "',
			'1','now()','" . $osms_type."')");
			if($sql_transaction_details)
			{
			$db=new database();	
			$update_penstion_status=$db->update("UPDATE prd_pension_employee SET
			emp_pension_status='1'
			WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  and  emp_id_fk='".$fetch_teacher['emp_id_fk']."' and flag IN('N','M') ");
			}*/
			//$row = $xml->createElement('RECORD');
			//$row = $container->appendChild($row);
			
			$dob =dateshow_slash($fetch_teacher['emp_dob']);
			 
			$retirement_date = dateshow_slash($fetch_teacher['emp_retirement_date']);
			 
		
			if ($osms_type == 1) 
			{
				$joining_date = dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
				$first_joining_date =dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($osms_type == 2) 
			{
					$tic_allowance = '0.00';
					$joining_date = dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
					$first_joining_date = dateshow_slash($fetch_teacher['emp_first_join_date']);
			
			}
			if ($osms_type == 3) 
			{
				$joining_date = dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
				$first_joining_date = dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($fetch_teacher['emp_religion']) 
			{
				$religion = $fetch_teacher['emp_religion'];
			} 
		
			if ($fetch_teacher['emp_group'])
			{
				$category = $fetch_teacher['emp_group'];
			}
			if ($fetch_teacher['emp_sex'] == 91)
			{
				$gender = '91'; 
			} 
			elseif ($fetch_teacher['emp_sex'] == 92) 
			{
				$gender = '92';
			} 
			elseif ($fetch_teacher['emp_sex'] == 93)
			{
				$gender = '93';
			}
			if ($fetch_teacher['pre_state'] == 'OTHERS' || $fetch_teacher['pre_state'] =='others')
			{
				$present_state = '99';
			}
			else
			{
				$present_state = $fetch_teacher['pre_state']; 
			}
			if ($fetch_teacher['per_state'] == 'OTHERS' || $fetch_teacher['per_state'] == 'others')
			{
				$permanent_state = '99';
			} 
			else 
			{
				$permanent_state = $fetch_teacher['per_state'];
			}
			
			if($fetch_teacher['reason']==1993)
			{
			
			$retire_type_code='D';
			}
			else
			{
			$retire_type_code='S';
			}
			
			if($fetch_teacher['emp_desig_first_app']==0)
			{
				$emp_desig_first_app='';
			
			}
			else
			{
				$emp_desig_first_app=$fetch_teacher['emp_desig_first_app'];
			}
		
		
		
		if($fetch_teacher['gp_id_fk']!=0 )
			{
				$id=$fetch_teacher['gp_id_fk'];
				$g="GP";
				
				
			}
			else if($fetch_teacher['ps_id_fk']!=0 )
			{
				$id=$fetch_teacher['ps_id_fk'];
				$g="PS";
				
			}
			
			else if($fetch_teacher['zp_id_fk']!='0' )
			{
				$id=$fetch_teacher['zp_id_fk'];
				$g="ZP";
				
				
			}
		   $present_memo_no1=$fetch_teacher['emp_present_memo_no'];
		   $present_memo_dt=dateshow_slash($fetch_teacher['emp_present_memo_date']);
		   $first_gp_ps_zp_code=$fetch_teacher['emp_first_gp_ps_zp_code'];
		   $first_memo_no2=$fetch_teacher['emp_first_memo_no'];
		   $first_memo_dt=dateshow_slash($fetch_teacher['emp_first_memo_date']);
		   
		   
		   
			$permanent_dist = findDistCode($fetch_teacher['emp_per_dist'], $permanent_state); 
			$present_dist = findDistCode($fetch_teacher['emp_pre_dist'], $present_state);
			$present_gp_code=findGPCode($id,$g); 
			$grad= fun_grade_pay($fetch_teacher['emp_grade_pay']);
			//$basic_pay_emp = ($fetch_teacher['emp_pay_in_payband'] + $grad);
			
			$basic_pay_emp = ($fetch_teacher['emp_pay_in_payband'] );
			
			
	
	$myobj->emp_id=$fetch_teacher['emp_id_const'];
	$myobj->emp_name=$emp_name;
	$myobj->m_f_gender=$gender;
	$myobj->emp_dob=$dob;
	$myobj->marital_status=$fetch_teacher['emp_marital_status'];
	$myobj->relegion=$religion;
	$myobj->identity_mark=htmlspecialchars($fetch_teacher['emp_idf_mark']);
	$myobj->father_name=$fetch_teacher['emp_father_name'];
	$myobj->dept_cd="13";
	$myobj->sub_dept_code=$emp_pen_type;
	$myobj->retire_type=$retire_type_code;
	if($retire_type_code=='D')
	{
	$myobj->retirement_date=$fetch_teacher['emp_termination_date'];
	}
	else
	{
	$myobj->retirement_date=$retirement_date;
	}
	$myobj->ropa="2019";
	$myobj->band_pay="0.00";
	$myobj->grade_pay="0.00";
	$myobj->additional_grade_pay="0.00";
	$myobj->basic_pay=$basic_pay_emp;
	$myobj->basic_pay_notional="0.00";
	$myobj->special_pay=$tic_allowance;
	$myobj->avg_pay="0.00";
	$myobj->pay_scale_level=$fetch_teacher['ropa_level'];
	
	$myobj->prsnt_house_no=$fetch_teacher['emp_pre_house_no'];
	$myobj->prsnt_street_name=$fetch_teacher['emp_pre_street_no'];
	$myobj->prsnt_twn_vill_name=$fetch_teacher['emp_pre_vill'];
	$myobj->prsnt_post_office=$fetch_teacher['emp_pre_post'];
	$myobj->prsnt_police_stn=$fetch_teacher['emp_pre_ps'];
	$myobj->prsnt_dist=$present_dist;
	$myobj->prsnt_state=$prsnt_state;
	$myobj->permnt_house_no=$fetch_teacher['emp_per_house_no'];
	$myobj->permnt_street_name=$fetch_teacher['emp_per_street_no'];
	$myobj->permnt_twn_vill_name=$fetch_teacher['emp_per_vill'];
	$myobj->permnt_post_office=$fetch_teacher['emp_per_post'];
	$myobj->permnt_police_stn=$fetch_teacher['emp_per_ps'];
	$myobj->permnt_pin_no=$fetch_teacher['emp_per_pin'];
	$myobj->permnt_dist=$permanent_dist;
	$myobj->permnt_state=$permanent_state;
	$myobj->present_gp_ps_zp_code=$present_gp_code;
	$myobj->present_post_held=$fetch_teacher['emp_desig'];
	$myobj->present_appnt_approv_memo_no=$present_memo_no1;
	$myobj->present_appnt_approv_memo_dt=$present_memo_dt;
	$myobj->present_appoint_wef_dt=$joining_date;
	$myobj->first_gp_ps_zp_code=$first_gp_ps_zp_code;
	$myobj->first_appoint_post=$emp_desig_first_app;
	$myobj->first_appnt_approv_memo_no=$first_memo_no2;
	$myobj->first_appnt_approv_memo_dt=$first_memo_dt;
	$myobj->first_appoint_wef_dt=$first_joining_date;
	if($retire_type_code=='D')
	{
	$myobj->mobile_no=$fetch_teacher['claiment_mobile_no'];
	}
	else
	{
	$myobj->mobile_no=$fetch_teacher['emp_mobile_no'];
	}
	$myobj->e_mail_id=$fetch_teacher['emp_mail_id'];
	$myobj->pan_no=$fetch_teacher['emp_pan_no'];
	$myobj->aadhar_no=$fetch_teacher['emp_aadhar_no'];
	if($retire_type_code=='D')
	{
	$myobj->claimant_name=$fetch_teacher['claimant_name'];
	$myobj->relationship_with_incumbent=$prsnt_state;
	}
	else
	{
	$myobj->claimant_name=$emp_name;
	$myobj->relationship_with_incumbent="9";
	}
	$myobj->new_modified_flag=$fetch_teacher['flag'];
	$myobj->transaction_dt=date('Y-m-d');
	
	
	
		
	$myJSON=json_encode($myobj);
	//echo $myJSON; die;
	 

$accessKey_has = 'QWewewTJHFJDBFMDFNJDFHjh459rs';
$accessKey = hash("sha512", $accessKey_has);

$securityKey_has = 'DPPG!@#135';
$securityKey = hash("sha256", $securityKey_has);
$emp_id_const= $fetch_teacher['emp_id_const']; 


			
			
			$i++;
			$j++;
		} // close of if
	  }// close of foreach

			
 }

 
$ben_upload = call_epension($accessKey,$securityKey,$myJSON);
$configData = json_decode($ben_upload, true);
//var_dump($ben_upload); die;
//print_r ($configData); die;
	if($configData['status']=='flase' && $configData['status']!='')
	{
	//echo 1; die;
	$response_code=$configData['status']; 
	}
	else if($configData['status']=='')
	{
	//echo 2; die;
	$response_code='false';  
	}
	else if($configData['status']=='true')
	{
	//echo 3; die;
	//echo "Sorry! Beneficiary Upload Fails";
	$response_code='true'; 
	}
	else
	{
		//echo 4; die;
	$response_code=$configData['status']; 
	}
	
	echo ("UPDATE prd_pension_employee SET
				emp_pension_status='1',response_status='".$response_code."',capture_date='".$configData['ack_time']."'
				WHERE  emp_id_const='".$configData['emp_id']."' and emp_pension_status='6'");die;
	$modify_error_code=$db->update("UPDATE prd_pension_employee SET
				emp_pension_status='1',response_status='".$response_code."',capture_date='".$configData['ack_time']."'
				WHERE  emp_id_const='".$configData['emp_id']."' and emp_pension_status='6'");
}

else if($api=='pull')
{
$ben_upload = call_epension_ack($emp_id_const);
$configData = json_decode($ben_upload, true);
var_dump($ben_upload); die;
}
 


?>