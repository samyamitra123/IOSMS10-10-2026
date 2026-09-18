<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$db  = new database();

	function fun_payband($val)
	{
	$db = new database();
	$data = $db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
	return $data[0]['payband_name'];
	}
	function fun_code($val)
	{
	$db= new database();
	$data = $db->fetch_table("select code,description from prd_dise_code_master where code='".$val."'  ");
	return $data[0]['description'];
	}
	
	function fun_desig($val)
	{
	$db= new database();
	$code_data = @$db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where designation_id='".$val."'");
	return $code_data[0]['designation_name'];
	}
	function fun_bank($val){
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
	return $dist_data2[0]['bank_name'];
	}
	function fun_grade_pay($val)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $dist_data2[0]['grade_amount'];
	}	
	
	function date_frmt_change($original_date)
{
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") 
	{
		return "";
	}
	else
	{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}


	$user_name=$_SESSION['location']['district_name'];
	$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];

//$fun_store=new zp_ps_gp_class();

	$arr=$db->fetch_table("select me.emp_first_name,me.emp_second_name,me.emp_last_name,me.emp_dob,me.emp_voter_id,me.emp_aadhar_no,me.emp_first_join_date,
	me.emp_conf_join_date,
	me.emp_join_prsnt_post_date,
	me.emp_join_prsnt_office_date
	,me.emp_retirement_date
	,me.emp_termination_date,
	me.emp_termination_date,
	me.emp_desig_first_app,
	me.emp_next_increment_date,
	me.emp_cosolidated_pay,
	me.emp_pay_band,
	me.emp_pay_in_payband,
	me.emp_bank_branch,
	me.emp_branch_code,
	me.emp_micr_no,
	me.emp_acc_no,
	me.emp_ifsc_no,
	me.emp_father_name,
	me.emp_mother_name,
	me.emp_religion,
	me.emp_desig,
	me.emp_next_increment_amount,
	me.emp_marital_status,
	designation_master.designation_name as designation,
	me.emp_spouse_name,
	me.emp_spouse_job_status ,
	me.emp_spouse_details ,
	me.emp_spouse_pay ,
	me.emp_spouse_hra ,
	me.emp_spouse_res ,
	me.emp_spouse_house_schm,
	me.emp_pan_no ,
	me.emp_height ,
	me.emp_diff_able,
	me.emp_disable_status,
	me.emp_idf_mark ,
	me.pre_state ,
	me.emp_pre_house_no ,
	me.emp_pre_street_no,
	me.emp_pre_vill,
	me.emp_pre_post,
	me.emp_pre_pin ,
	me.emp_pre_dist,
	me.emp_pre_dist_others,
	me.emp_pre_ps,
	me.per_state,
	me.emp_per_house_no,
	me.emp_per_street_no,
	me.emp_per_vill,
	me.emp_per_post,
	me.emp_per_pin,
	me.emp_per_dist,
	me.emp_per_dist_others,
	me.emp_per_ps,
	me.emp_land_no ,
	me.emp_mobile_no ,
	me.emp_mail_id ,
	me.emp_system_code,
	me.emp_status ,
	me.emp_grade_pay,
	me.emp_id_const ,
	me.conf_dt_flag ,
	me.spouse_medical_allowance ,
	me.conv_allow_status ,
	me.zp_emp_type ,
	me.zp_id_fk ,
	me.notification_no ,
	me.emp_accommodation ,
	me.emp_govt_id ,
	me.emp_gpf_acc_no ,
	me.emp_gvt_hra_type ,
	me.emp_gvt_hra_ammount,
	sex.description as sex,
	grou.description as group,
	emp_caste.description as cast,
	emp_quali.description as qualification,
	emp_pay.payscale_range as pay_scale,
	emp_bank_name.bank_name as bank_name,
	emp_mother_tongue.description as mother_tongue,
	emp_marital_status.description as marital_status,
	emp_blood_grp.description as blood_group,
	zp_emp_type.description as employee_type
	
	from prd_employee_master as me
	
	left join 
	(select designation_id,designation_name from zpemp_emp_desig_master )as designation_master
	on cast(me.emp_desig as text)=cast(designation_master.designation_id as text)
	
	left join
	(select code,description from prd_dise_code_master where length(code)=2 and (code like '9%')  order by code)
	as sex
	on cast(me.emp_sex as text)=cast(sex.code as text)
	
	left join
	(select code,description from prd_dise_code_master where length(code)=3 and (code like '6%')  order by code)
	as grou
	on cast(me.emp_group as text)=cast(grou.code as text)
	
	left join
	(select code,description from prd_dise_code_master where length(code)=3  order by code)
	as emp_caste
	on cast(me.emp_caste as text)=cast(emp_caste.code as text)
	
	left join
	(select code,description from prd_dise_code_master   order by code)
	as emp_quali
	on cast(me.emp_edu_quali as text)=cast(emp_quali.code as text)
	
	left join
	(select payscale_code,payscale_range from prd_dise_payscale_master   order by payscale_code)
	as emp_pay
	on cast(me.emp_pay_scale as text)=cast(emp_pay.payscale_code as text)
	
	left join
	(select bank_name,bank_code from prd_dise_bank_master   order by bank_code)
	as emp_bank_name
	on cast(me.emp_bank_name as text)=cast(emp_bank_name.bank_code as text)
	
	left join
	(select code,description from prd_dise_code_master   order by code)
	as emp_mother_tongue
	on cast(me.emp_mother_tongue as text)=cast(emp_mother_tongue.code as text)
	
	left join
	(select code,description from prd_dise_code_master   order by code)
	as emp_marital_status
	on cast(me.emp_marital_status as text)=cast(emp_marital_status.code as text)
	
	left join
	(select code,description from prd_dise_code_master   order by code)
	as emp_blood_grp
	on cast(me.emp_blood_grp as text)=cast(emp_blood_grp.code as text)
	
	left join
	(select code,description from prd_dise_code_master   order by code)
	as zp_emp_type
	on cast(me.zp_emp_type as text)=cast(zp_emp_type.code as text)
	
	where me.zp_id_fk='17' and me.emp_status='1' order by emp_first_name");
		
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);  
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename=Employee_all_report.xls');
	header("Content-Transfer-Encoding: binary");
	?>
    <!--<table width="200" border="1" style="border-color:#3E9B96;">-->
    <table width="200" border="1">
    <tr>
  <th colspan="71" style="text-align:center;"><br /><br />  PASCHIM MEDINIPUR Zill parishad Employee Details  <br />(As on <?= date('d-m-Y h:i A');?>)<br /><br /></th>
  </tr>
    
        <tr style="border-color:#3E9B96;">
            <th scope="col" >SL.NO.</th>
            <th scope="col">EMPLOYEE ID</th>
            <th scope="col">EMPLOYEE NAME</th>
            <th scope="col" >EMPLOYEE TYPE </th>
            <th scope="col" >GOVERNMENT ID</th>
            <th scope="col">DATE OF BIRTH</th>
            <th scope="col">SEX</th>
            <th scope="col">CASTE </th>
            <th scope="col">VOTER ID</th>
            <th scope="col"> AADHAAR ID</th>
            
            <th scope="col">NOTIFICATION NUMBER OF APPOINTMENT</th>
            <th scope="col"> DESIGNATION</th>
            <th scope="col"> DATE OF FIRST JOINING IN SERVICE</th>
            <th scope="col"> DATE OF CONFIRMATION IN SERVICE</th>
            <th scope="col"> DATE OF JOINING IN THE PRESENT POST</th>
            <th scope="col"> DATE OF JOINING IN THE PRESENT OFFICE</th>
            <th scope="col"> DATE OF RETIREMENT / TERMINATION</th>
            <th scope="col"> DESIGNATION AT FIRST APPOINTMENT</th>
            <th scope="col"> EMPLOYEE GROUP</th>
            <th scope="col"> DATE OF NEXT INCREMENT</th>
            
            <th scope="col"> CONSOLIDATED PAY</th>
            <th scope="col"> PAY BAND</th>
            <th scope="col"> PAY SCALE</th>
            <th scope="col"> PAY IN PAY BAND</th>
            <th scope="col"> GRADE PAY</th>
            <th scope="col"> BANK NAME </th>
            <th scope="col"> BRANCH NAME </th>
            <th scope="col"> BRANCH CODE </th>
            <th scope="col"> ACCOUNT NUMBER </th>
            <th scope="col"> MICR CODE </th>
            <th scope="col"> GPF ACCOUNT NO</th>
            <th scope="col"> IFSC CODE</th>
            
            <th scope="col"> FATHER'S NAME</th>
            <th scope="col"> MOTHER'S NAME</th>
            <th scope="col"> RELIGION </th>
            <th scope="col"> MOTHER TONGUE</th>
            <th scope="col"> MARITAL STATUS</th>
            <th scope="col"> SPOUSE NAME</th>
            <th scope="col"> SPOUSE EMPLOYEE DETAILS</th>
            <th scope="col">SPOUSE PAY</th>
            <th scope="col">SPOUSE HRA</th>
            <th scope="col"> OPTED FOR ENROLMENT IN WB HEALTH SCHEME</th>
            <th scope="col"> RESIDENTIAL STATUS</th>
            <th scope="col"> HOUSING SCHEME</th>
            <th scope="col">HRA SCHEME TYPE</th>
            <th scope="col">HRA AMOUNT</th>
            <th scope="col">BLOOD GROUP</th>
            <th scope="col">HEIGHT (IN CM )</th>
            <th scope="col"> IDENTIFICATION MARK</th>
            <th scope="col">DIFFERENTLY ABLE</th>
            <th scope="col"> DESABILITY SATUS</th>
            <th scope="col">ELIGIBLE FOR CONVEYANCE ALLOWANCE</th>
            
            
            
            
           
            
            
            <th scope="col"> PRESENT STATE</th>
            <th scope="col"> PRESENT POLICE STATTION</th>
            <th scope="col"> PRESENT HOUSE NO </th>
            <th scope="col"> PRESENT STREET </th>
            <th scope="col"> PRESENT TOWN/VILLAGE </th>
            <th scope="col"> PRESENT POST OFFICE </th>
            <th scope="col"> PRESENT PIN NUMBER </th>
            <th scope="col"> PRESENT DISTRICT </th>
            
            <th scope="col"> PERMANENT STATE</th>
            <th scope="col"> PERMANENT POLICE STATTION</th>
            <th scope="col"> PERMANENT HOUSE NO </th>
            <th scope="col"> PERMANENT STREET </th>
            <th scope="col"> PERMANENT TOWN/VILLAGE </th>
            <th scope="col"> PERMANENT POST OFFICE </th>
            <th scope="col"> PERMANENT PIN NUMBER </th>
            <th scope="col"> PERMANENT DISTRICT </th>
            <th scope="col"> LAND TEL.NO </th>
            <th scope="col"> MOBILE NO. </th>
            <th scope="col"> EMAIL ID. </th>
    
            
        </tr>
        <? $cnt=1;
        foreach($arr as $key)
        {
				if($key['emp_micr_no']=='' && $key['emp_micr_no']=='0')
				{
					
					$micr_no='';
				}
				else
				{
					$micr_no=$key['emp_micr_no'];
				}
				
				if($key['pre_state']='32')
				{
					
					$pre_state='WEST BENGAL';
					
					$db = new database();
					$district_name = $db->fetch_table("SELECT district_name FROM prd_location_master_district where district_id_pk='".$key['emp_pre_dist']."'");
					
				}
				else
				{
					$pre_state='OTHERS';
					$district_name=$key['emp_pre_dist_others'];
				}
				
				if($key['per_state']='32')
				{
					$db = new database();
					$district_name = $db->fetch_table("SELECT district_name FROM prd_location_master_district where district_id_pk='".$key['emp_per_dist']."'");
					$per_state='WEST BENGAL';
				}
				else
				{
					$per_state='OTHERS';
					$district_name=$key['emp_per_dist_others'];
				}
				
				if($key['emp_land_no']=='0' || $key['emp_land_no']=='')
				{
					$lan_no='';
				}
				else
				{
					$lan_no=$key['emp_land_no'];
				}
				if($key['emp_spouse_house_schm']=='1')
				{
					$medical_scm='YES';
				}
				else
				{
					$medical_scm='NO';
				}
				if($key['emp_diff_able']=='1')
				{
					$d_abble='YES';
				}
				else
				{
					$d_abble='NO';
				}
				if($key['conv_allow_status']=='1')
				{
					$c_allow='YES';
				}
				else
				{
					$c_allow='NO';
				}
				
			
			
		 ?>
			<tr style="border-color:#3E9B96;">
                <td style="text-align:center;" scope="row"><?= $cnt;?></td>
                <td style="text-align:center;"><?=$key['emp_id_const']?></td>
                <td style="text-align:center;"><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
                <td  style="text-align:center;"><?=$key['employee_type']?></td>
                <td style="text-align:center;" ><?=$key['emp_govt_id']?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_dob']);?></td>
                <td style="text-align:center;" ><?=$key['sex']?></td>
                <td style="text-align:center;" ><?=$key['cast']?></td>
                <td style="text-align:center;" ><?=$key['emp_voter_id']?></td>
                <td style="text-align:center;" ><?="&nbsp;".$key['emp_aadhar_no']?></td>
                
                <td style="text-align:center;" ><?=$key['notification_no']?></td>
                <td style="text-align:center;" ><?=$key['designation']?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_first_join_date'])?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_conf_join_date'])?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_join_prsnt_post_date'])?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_join_prsnt_office_date'])?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_retirement_date'])?></td>
                <td style="text-align:center;" ><?=fun_desig($key['emp_desig_first_app'])?></td>
                <td style="text-align:center;" ><?=$key['group']?></td>
                <td style="text-align:center;" ><?=date_frmt_change($key['emp_next_increment_date'])?></td>
                
                <td style="text-align:center;" ><?=fun_payband($key['emp_cosolidated_pay'])?></td>
                <td style="text-align:center;" ><?=fun_payband($key['emp_pay_band'])?></td>
                <td style="text-align:center;" ><?=$key['pay_scale']?></td>
                <td style="text-align:center;" ><?=$key['emp_pay_in_payband']?></td>
                <td style="text-align:center;" ><?=fun_grade_pay($key['emp_grade_pay'])?></td>
                <td style="text-align:center;" ><?=$key['bank_name']?></td>
                <td style="text-align:center;" ><?=$key['emp_bank_branch']?></td>
                <td style="text-align:center;" ><?=$key['emp_branch_code']?></td>
                <td style="text-align:center;" ><?="&nbsp;".$key['emp_acc_no']?></td>
                <td style="text-align:center;"><?=$micr_no?></td>
                <td style="text-align:center;" ><?="&nbsp;".$key['emp_gpf_acc_no']?></td>
                <td style="text-align:center;" ><?="&nbsp;".$key['emp_ifsc_no']?></td>
                
                
                <td style="text-align:center;" ><?=$key['emp_father_name']?></td>
                <td style="text-align:center;" ><?=$key['emp_mother_name']?></td>
                <td style="text-align:center;" ><?= fun_code($key['emp_religion'])?></td>
                <td style="text-align:center;" ><?= $key['mother_tongue']?></td>
                <td style="text-align:center;" ><?= $key['marital_status']?></td>
                <td style="text-align:center;" ><?= $key['emp_spouse_name']?></td>
                <td style="text-align:center;" ><?= $key['emp_spouse_details']?></td>
                <td style="text-align:center;" ><?= $key['emp_spouse_pay']?></td>
                <td style="text-align:center;" ><?= $key['emp_spouse_hra']?></td>
                <td style="text-align:center;" ><?= $medical_scm ?></td>
                <td style="text-align:center;" ><?= fun_code($key['emp_spouse_res']) ?></td>
                <td style="text-align:center;" ><?= $key['emp_spouse_house_schm'] ?></td>
                <td style="text-align:center;" ><?= fun_code($key['emp_gvt_hra_type']) ?></td>
                <td style="text-align:center;" ><?= $key['emp_gvt_hra_ammount'] ?></td>
                <td style="text-align:center;" ><?= $key['blood_group'] ?></td>
                <td style="text-align:center;" ><?= $key['emp_height'] ?></td>
                <td style="text-align:center;" ><?= $key['emp_idf_mark'] ?></td>
                <td style="text-align:center;" ><?= $d_abble ?></td>
                <td style="text-align:center;" ><?= $key['emp_disable_status'] ?></td>
                 <td style="text-align:center;" ><?= $c_allow ?></td>
                
                
                
                
                
                
                
                <td style="text-align:center;" ><?=$pre_state?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_ps']?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_house_no']?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_street_no']?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_vill']?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_post']?></td>
                <td style="text-align:center;" ><?=$key['emp_pre_pin']?></td>
                <td style="text-align:center;" ><?=$district_name[0]['district_name']?></td>
                
                <td style="text-align:center;" ><?=$per_state?></td>
                <td style="text-align:center;" ><?=$key['emp_per_ps']?></td>
                <td style="text-align:center;" ><?=$key['emp_per_house_no']?></td>
                <td style="text-align:center;" ><?=$key['emp_per_street_no']?></td>
                <td style="text-align:center;" ><?=$key['emp_per_vill']?></td>
                <td style="text-align:center;" ><?=$key['emp_per_post']?></td>
                <td style="text-align:center;" ><?=$key['emp_per_pin']?></td>
                <td style="text-align:center;" ><?=$district_name[0]['district_name']?></td>
                <td style="text-align:center;" ><?=$lan_no?></td>
                <td style="text-align:center;" ><?=$key['emp_mobile_no']?></td>
                <td style="text-align:center;" ><?=$key['emp_mail_id']?></td>
                
             
                
                
                
                
                    
			</tr>
			<? $cnt++;
		}  ?>
            
       </table>

	
<style>
tr,td{
	border-color:#3E9B96;
}
</style>
