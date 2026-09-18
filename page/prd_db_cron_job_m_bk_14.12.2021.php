<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';
require '../includes/library/cryptography.class.php';

//--------------PHP MAIL----------------------------

require '../includes/third-party/PHPMailer/class.phpmailer.php';
require '../includes/third-party/PHPMailer/PHPMailerAutoload.php';
require_once 'all_function/mail/mail_fun.php';


//---------------------------------------------------
$db=new database();	
	
///////////////// mody fy memo///////////////

/*echo (" 
select mas2.* from 
(select mas.emp_id_pk, mas.gp_id_fk ,
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, mas.emp_desig,
mas.emp_first_join_date, 
mas.emp_conf_join_date,
mas.emp_join_prsnt_post_date,
mas.emp_join_prsnt_office_date,
mas.emp_retirement_date, 
mas.emp_termination_date,
mas.emp_group, 
mas.emp_desig_first_app,
mas.emp_pay_in_payband, 
mas.emp_father_name,
mas.emp_religion,
mas.emp_marital_status,
mas.emp_spouse_name,
mas.emp_pan_no,
mas.emp_idf_mark, 
mas.pre_state, 
mas.emp_pre_house_no,
mas.emp_pre_street_no,
mas.emp_pre_vill, mas.emp_pre_post,
mas.emp_pre_pin, mas.emp_pre_dist,
mas.emp_pre_dist_others, mas.per_state,
mas.emp_per_house_no, mas.emp_per_street_no,
mas.emp_per_vill, mas.emp_per_post,
mas.emp_per_pin, mas.emp_per_dist,
mas.emp_per_dist_others, 
mas.emp_mobile_no, mas.emp_mail_id,
mas.emp_status, mas.entry_ip, 
mas.emp_grade_pay, mas.emp_id_const,
mas.ps_id_fk, mas.zp_id_fk, mas.emp_pension_status ,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level,
'' as reason,'' as claimant_name,'' as relationship_incumbent ,'0' as claiment_mobile_no
from 
prd_employee_master mas 
WHERE SUBSTRING(cast(mas.emp_retirement_date as text),1,7)>='2020-04'  and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)<='2020-04'
AND mas.emp_status in('1') AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') AND mas.ps_id_fk NOT IN ('342','343')
AND (mas.zp_id_fk is null or mas.zp_id_fk not in('21')) 
and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
AND mas.emp_id_const!='0'
 AND mas.emp_desig NOT IN('1120','1124','9012','9013','9014','9015','9016','9017','1') 
) mas2
UNION 
(select mas1.*,stop.reason,stop.claimant_name,stop.relationship_incumbent,stop.claiment_mobile_no from 
(select mas.emp_id_pk, mas.gp_id_fk , 
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, 
mas.emp_desig,
mas.emp_first_join_date,
mas.emp_conf_join_date,
mas.emp_join_prsnt_post_date, 
mas.emp_join_prsnt_office_date,
mas.emp_retirement_date, 
mas.emp_termination_date, 
mas.emp_group, mas.emp_desig_first_app,
mas.emp_pay_in_payband, mas.emp_father_name,
mas.emp_religion, mas.emp_marital_status,
mas.emp_spouse_name, mas.emp_pan_no,
mas.emp_idf_mark, mas.pre_state,
mas.emp_pre_house_no,
mas.emp_pre_street_no, 
mas.emp_pre_vill,
mas.emp_pre_post,
mas.emp_pre_pin,
mas.emp_pre_dist,
mas.emp_pre_dist_others,
mas.per_state,
mas.emp_per_house_no,
mas.emp_per_street_no,
mas.emp_per_vill,
mas.emp_per_post,
mas.emp_per_pin,
mas.emp_per_dist,
mas.emp_per_dist_others,
mas.emp_mobile_no,
mas.emp_mail_id,
mas.emp_status,
mas.entry_ip,
mas.emp_grade_pay,
mas.emp_id_const,
mas.ps_id_fk,
mas.zp_id_fk,
mas.emp_pension_status,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level
from prd_employee_master mas  WHERE 
 mas.emp_status in('2') AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403')
  AND(mas.zp_id_fk is null or mas.zp_id_fk not in('21') )
  and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
 AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0' 
 AND mas.emp_desig NOT IN('1120','1124','9012','9013','9014','9015','9016','9017','1') )mas1 
inner JOIN 
(select * from (select emp_id_fk,date,reason,claimant_name,relationship_incumbent,claiment_mobile_no,max(date) over (partition by emp_id_fk) max_my_date from prd_stop_sal_reason)A 
where date = max_my_date and reason='1993' AND (SUBSTRING(cast(date as text),1,7)>='2020-06') and SUBSTRING(cast(date as text),1,7)<='2020-06') as stop ON mas1.emp_id_pk=stop.emp_id_fk )");die;*/



$sql_pension_employee_data = $db->fetch_table(" 
select mas2.* from 
(select mas.emp_id_pk, mas.gp_id_fk ,
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, mas.emp_desig,
mas.emp_first_join_date, 
mas.emp_conf_join_date,
mas.emp_join_prsnt_post_date,
mas.emp_join_prsnt_office_date,
mas.emp_retirement_date, 
mas.emp_termination_date,
mas.emp_group, 
mas.emp_desig_first_app,
mas.emp_pay_in_payband, 
mas.emp_father_name,
mas.emp_religion,
mas.emp_marital_status,
mas.emp_spouse_name,
mas.emp_pan_no,
mas.emp_idf_mark, 
mas.pre_state, 
mas.emp_pre_house_no,
mas.emp_pre_street_no,
mas.emp_pre_vill, mas.emp_pre_post,
mas.emp_pre_pin, mas.emp_pre_dist,
mas.emp_pre_dist_others, mas.per_state,
mas.emp_per_house_no, mas.emp_per_street_no,
mas.emp_per_vill, mas.emp_per_post,
mas.emp_per_pin, mas.emp_per_dist,
mas.emp_per_dist_others, 
mas.emp_mobile_no, mas.emp_mail_id,
mas.emp_status, mas.entry_ip, 
mas.emp_grade_pay, mas.emp_id_const,
mas.ps_id_fk, mas.zp_id_fk, mas.emp_pension_status ,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level,
'' as reason,'' as claimant_name,'' as relationship_incumbent ,'0' as claiment_mobile_no
from 
prd_employee_master mas 
WHERE SUBSTRING(cast(mas.emp_retirement_date as text),1,7)>='2020-01'  and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)<='2022-08'
AND mas.emp_status in('1')  AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') AND mas.ps_id_fk NOT IN ('342','343')
AND (mas.zp_id_fk is null or mas.zp_id_fk not in('21')) 
and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
AND mas.emp_id_const!='0' and mas.ropa_status='1'
 AND mas.emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1') 
) mas2
UNION 
(select mas1.*,stop.reason,stop.claimant_name,stop.relationship_incumbent,stop.claiment_mobile_no from 
(select mas.emp_id_pk, mas.gp_id_fk , 
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, 
mas.emp_desig,
mas.emp_first_join_date,
mas.emp_conf_join_date,
mas.emp_join_prsnt_post_date, 
mas.emp_join_prsnt_office_date,
mas.emp_retirement_date, 
mas.emp_termination_date, 
mas.emp_group, mas.emp_desig_first_app,
mas.emp_pay_in_payband, mas.emp_father_name,
mas.emp_religion, mas.emp_marital_status,
mas.emp_spouse_name, mas.emp_pan_no,
mas.emp_idf_mark, mas.pre_state,
mas.emp_pre_house_no,
mas.emp_pre_street_no, 
mas.emp_pre_vill,
mas.emp_pre_post,
mas.emp_pre_pin,
mas.emp_pre_dist,
mas.emp_pre_dist_others,
mas.per_state,
mas.emp_per_house_no,
mas.emp_per_street_no,
mas.emp_per_vill,
mas.emp_per_post,
mas.emp_per_pin,
mas.emp_per_dist,
mas.emp_per_dist_others,
mas.emp_mobile_no,
mas.emp_mail_id,
mas.emp_status,
mas.entry_ip,
mas.emp_grade_pay,
mas.emp_id_const,
mas.ps_id_fk,
mas.zp_id_fk,
mas.emp_pension_status,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level
from prd_employee_master mas  WHERE 
 mas.emp_status in('2') and mas.ropa_status='1' AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403')
  AND(mas.zp_id_fk is null or mas.zp_id_fk not in('21') )
  and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
 AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0' 
 AND mas.emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1') )mas1 
inner JOIN 
(select * from (select emp_id_fk,date,reason,claimant_name,relationship_incumbent,claiment_mobile_no,max(date) over (partition by emp_id_fk) max_my_date from prd_stop_sal_reason)A 
where date = max_my_date and reason='1993' AND (SUBSTRING(cast(date as text),1,7)>='2020-01') and SUBSTRING(cast(date as text),1,7)<='2021-11') as stop ON mas1.emp_id_pk=stop.emp_id_fk )");


 
				
foreach ($sql_pension_employee_data as $mas_data) 
{
	
	
	$sql_pension_data= $db->fetch_table("select
									emp_id_fk,
									emp_status
									from 
									prd_pension_employee 
									WHERE emp_id_fk='".$mas_data['emp_id_pk']."'");

	//pg_query('BEGIN');
	if(count($sql_pension_data)==0 && $mas_data['emp_pension_status']==0)
	{
	
	
	
		 pg_query('BEGIN');
		 
		 
		 
		$sql_pension_employee_data_insert=$db->insert("INSERT INTO prd_pension_employee 
( emp_id_fk, gp_id_fk , emp_first_name ,
emp_second_name , 
emp_last_name, emp_dob, emp_sex,
emp_aadhar_no, emp_desig, emp_first_join_date, 
emp_conf_join_date, emp_join_prsnt_post_date,
emp_join_prsnt_office_date, emp_retirement_date,
emp_termination_date, emp_group, emp_desig_first_app, 
emp_pay_in_payband, emp_father_name, emp_religion,
emp_marital_status, emp_spouse_name, emp_pan_no,
emp_idf_mark, pre_state, emp_pre_house_no,
emp_pre_street_no, emp_pre_vill, emp_pre_post,
emp_pre_pin, emp_pre_dist, emp_pre_dist_others,
per_state, emp_per_house_no, emp_per_street_no,
emp_per_vill, emp_per_post, emp_per_pin, emp_per_dist,
emp_per_dist_others, emp_mobile_no, emp_mail_id,
emp_status, entry_ip, emp_grade_pay, emp_id_const,
ps_id_fk,zp_id_fk,emp_first_memo_no,emp_first_memo_date,
emp_first_gp_ps_zp_code,
emp_present_memo_no,
emp_present_memo_date,
zp_emp_type,
emp_pre_ps,
emp_per_ps,
ropa_level,
reason,claimant_name,relationship_incumbent,claiment_mobile_no,update_time,flag,emp_pension_status) 
select b.*,now(),'N','0' from
(select mas2.* from
 (select mas.emp_id_pk, mas.gp_id_fk , mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex, mas.emp_aadhar_no, mas.emp_desig, mas.emp_first_join_date,
mas.emp_conf_join_date, mas.emp_join_prsnt_post_date, mas.emp_join_prsnt_office_date, mas.emp_retirement_date, 
mas.emp_termination_date, mas.emp_group, mas.emp_desig_first_app, mas.emp_pay_in_payband, mas.emp_father_name,
mas.emp_religion, mas.emp_marital_status, mas.emp_spouse_name, mas.emp_pan_no, mas.emp_idf_mark, mas.pre_state,
mas.emp_pre_house_no, mas.emp_pre_street_no, mas.emp_pre_vill, mas.emp_pre_post, mas.emp_pre_pin, mas.emp_pre_dist,
mas.emp_pre_dist_others, mas.per_state, mas.emp_per_house_no, mas.emp_per_street_no, mas.emp_per_vill, mas.emp_per_post,
mas.emp_per_pin, mas.emp_per_dist, mas.emp_per_dist_others, mas.emp_mobile_no, mas.emp_mail_id, mas.emp_status, mas.entry_ip,
mas.emp_grade_pay, mas.emp_id_const, mas.ps_id_fk,mas.zp_id_fk,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level,


'1991','' as claimant_name,'' as relationship_incumbent,'0' as claiment_mobile_no
from prd_employee_master mas WHERE  mas.emp_id_pk='".$mas_data['emp_id_pk']."' and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)>='2020-01'  and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)<='2022-08'
AND mas.emp_status in('1') and mas.ropa_status='1' AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') 
AND (mas.zp_id_fk is null or mas.zp_id_fk not in('21'))
and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
 AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0' 
AND mas.emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1'))
mas2 
UNION 
(select mas1.*,stop.reason,stop.claimant_name,stop.relationship_incumbent,stop.claiment_mobile_no from 
(select mas.emp_id_pk, mas.gp_id_fk , 
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, mas.emp_desig, mas.emp_first_join_date,
mas.emp_conf_join_date, mas.emp_join_prsnt_post_date,
mas.emp_join_prsnt_office_date, mas.emp_retirement_date,
mas.emp_termination_date, mas.emp_group, mas.emp_desig_first_app,
mas.emp_pay_in_payband, mas.emp_father_name, mas.emp_religion,
mas.emp_marital_status, mas.emp_spouse_name, mas.emp_pan_no,
mas.emp_idf_mark, mas.pre_state, mas.emp_pre_house_no,
mas.emp_pre_street_no, mas.emp_pre_vill, mas.emp_pre_post,
mas.emp_pre_pin, mas.emp_pre_dist, mas.emp_pre_dist_others,
mas.per_state, mas.emp_per_house_no, mas.emp_per_street_no,
mas.emp_per_vill, mas.emp_per_post, mas.emp_per_pin,
mas.emp_per_dist, mas.emp_per_dist_others, mas.emp_mobile_no, 
mas.emp_mail_id, mas.emp_status, mas.entry_ip, mas.emp_grade_pay, 
mas.emp_id_const, mas.ps_id_fk,mas.zp_id_fk,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,
mas.zp_emp_type,
mas.emp_pre_ps,
mas.emp_per_ps,
mas.ropa_level
from prd_employee_master mas WHERE  mas.emp_id_pk='".$mas_data['emp_id_pk']."'
AND mas.emp_status in('2') and mas.ropa_status='1' AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') 
AND (mas.zp_id_fk is null or mas.zp_id_fk not in('21'))
and (mas.ps_id_fk!='0' or mas.gp_id_fk!='0' or (mas.zp_id_fk!='0' and mas.zp_emp_type='367'))
 AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0'
 AND mas.emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1'))mas1
inner JOIN (select * from (select emp_id_fk,date,reason,claimant_name,relationship_incumbent,claiment_mobile_no,max(date) over (partition by emp_id_fk) max_my_date
from prd_stop_sal_reason)A where date = max_my_date and reason='1993' AND (SUBSTRING(cast(date as text),1,7)>='2020-01') and SUBSTRING(cast(date as text),1,7)<='2021-11') as stop ON mas1.emp_id_pk=stop.emp_id_fk ))b");										

		/*$sql_pension_employee_data_insert=$db->insert("INSERT INTO prd_pension_employee 
( emp_id_fk, gp_id_fk , emp_first_name ,
emp_second_name , 
emp_last_name, emp_dob, emp_sex,
emp_aadhar_no, emp_desig, emp_first_join_date, 
emp_conf_join_date, emp_join_prsnt_post_date,
emp_join_prsnt_office_date, emp_retirement_date,
emp_termination_date, emp_group, emp_desig_first_app, 
emp_pay_in_payband, emp_father_name, emp_religion,
emp_marital_status, emp_spouse_name, emp_pan_no,
emp_idf_mark, pre_state, emp_pre_house_no,
emp_pre_street_no, emp_pre_vill, emp_pre_post,
emp_pre_pin, emp_pre_dist, emp_pre_dist_others,
per_state, emp_per_house_no, emp_per_street_no,
emp_per_vill, emp_per_post, emp_per_pin, emp_per_dist,
emp_per_dist_others, emp_mobile_no, emp_mail_id,
emp_status, entry_ip, emp_grade_pay, emp_id_const,
ps_id_fk,zp_id_fk,emp_first_memo_no,emp_first_memo_date,
emp_first_gp_ps_zp_code,
emp_present_memo_no,
emp_present_memo_date,
reason,claimant_name,relationship_incumbent,update_time,flag,emp_pension_status) 
select b.*,now(),'N','0' from
(select mas2.* from
 (select mas.emp_id_pk, mas.gp_id_fk , mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex, mas.emp_aadhar_no, mas.emp_desig, mas.emp_first_join_date,
mas.emp_conf_join_date, mas.emp_join_prsnt_post_date, mas.emp_join_prsnt_office_date, mas.emp_retirement_date, 
mas.emp_termination_date, mas.emp_group, mas.emp_desig_first_app, mas.emp_pay_in_payband, mas.emp_father_name,
mas.emp_religion, mas.emp_marital_status, mas.emp_spouse_name, mas.emp_pan_no, mas.emp_idf_mark, mas.pre_state,
mas.emp_pre_house_no, mas.emp_pre_street_no, mas.emp_pre_vill, mas.emp_pre_post, mas.emp_pre_pin, mas.emp_pre_dist,
mas.emp_pre_dist_others, mas.per_state, mas.emp_per_house_no, mas.emp_per_street_no, mas.emp_per_vill, mas.emp_per_post,
mas.emp_per_pin, mas.emp_per_dist, mas.emp_per_dist_others, mas.emp_mobile_no, mas.emp_mail_id, mas.emp_status, mas.entry_ip,
mas.emp_grade_pay, mas.emp_id_const, mas.ps_id_fk,mas.zp_id_fk,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date,


'1991','' as claimant_name,'' as relationship_incumbent
from prd_employee_master mas WHERE  mas.emp_id_pk='".$mas_data['emp_id_pk']."' and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)='".date("Y-m", strtotime("+11 months"))."' 
AND mas.emp_status in('1') AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') AND mas.zp_id_fk NOT IN('21') AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0' 
AND mas.emp_desig NOT IN('1120','1124','9012','9013','9014','9015','9016','9017','1'))
mas2 
UNION 
(select mas1.*,stop.reason,stop.claimant_name,stop.relationship_incumbent from 
(select mas.emp_id_pk, mas.gp_id_fk , 
mas.emp_first_name , mas.emp_second_name ,
mas.emp_last_name, mas.emp_dob, mas.emp_sex,
mas.emp_aadhar_no, mas.emp_desig, mas.emp_first_join_date,
mas.emp_conf_join_date, mas.emp_join_prsnt_post_date,
mas.emp_join_prsnt_office_date, mas.emp_retirement_date,
mas.emp_termination_date, mas.emp_group, mas.emp_desig_first_app,
mas.emp_pay_in_payband, mas.emp_father_name, mas.emp_religion,
mas.emp_marital_status, mas.emp_spouse_name, mas.emp_pan_no,
mas.emp_idf_mark, mas.pre_state, mas.emp_pre_house_no,
mas.emp_pre_street_no, mas.emp_pre_vill, mas.emp_pre_post,
mas.emp_pre_pin, mas.emp_pre_dist, mas.emp_pre_dist_others,
mas.per_state, mas.emp_per_house_no, mas.emp_per_street_no,
mas.emp_per_vill, mas.emp_per_post, mas.emp_per_pin,
mas.emp_per_dist, mas.emp_per_dist_others, mas.emp_mobile_no, 
mas.emp_mail_id, mas.emp_status, mas.entry_ip, mas.emp_grade_pay, 
mas.emp_id_const, mas.ps_id_fk,mas.zp_id_fk,
mas.emp_first_memo_no,
mas.emp_first_memo_date,
mas.emp_first_gp_ps_zp_code,
mas.emp_present_memo_no,
mas.emp_present_memo_date
from prd_employee_master mas WHERE  mas.emp_id_pk='".$mas_data['emp_id_pk']."'
AND mas.emp_status in('2') AND mas.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') AND mas.zp_id_fk NOT IN('21')
 AND mas.ps_id_fk NOT IN ('342','343') AND mas.emp_id_const!='0'
 AND mas.emp_desig NOT IN('1120','1124','9012','9013','9014','9015','9016','9017','1'))mas1
inner JOIN (select * from (select emp_id_fk,date,reason,claimant_name,relationship_incumbent,max(date) over (partition by emp_id_fk) max_my_date
from prd_stop_sal_reason)A where date = max_my_date and reason='1993' AND (SUBSTRING(cast(date as text),1,7)>='".date("Y-m", strtotime("-1 months"))."') and SUBSTRING(cast(date as text),1,7)<='".date("Y-m", strtotime("+1 months"))."') as stop ON mas1.emp_id_pk=stop.emp_id_fk ))b"); up query*/
														
		/*if($sql_pension_employee_data_insert)
		{			 									
			$j++;
		}*/
		
		if($sql_pension_employee_data_insert==1)
		{
		
					$update_employee_master_data_status=$db->update("UPDATE prd_employee_master 
					SET emp_pension_status='1' 
					WHERE emp_id_pk='".$mas_data['emp_id_pk']."' AND emp_status in('1','2') AND emp_id_const!='0' 
					AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')" );	
		
					if($update_employee_master_data_status==1)
					{
						pg_query('COMMIT'); 
					}
					else
					{
						pg_query('ROLLBACK');
					}
		
		}
			
	}
}

///////////////// Insert employee Data/////////////////////////

///////////////// mody fy memo///////////////

$sql_pension_employee_data_check = $db->fetch_table("select emp.emp_id_pk, emp.gp_id_fk , emp.emp_first_name , emp.emp_second_name , 
emp.emp_last_name, emp.emp_dob, emp.emp_sex, emp.emp_aadhar_no,
emp.emp_desig, emp.emp_first_join_date, emp.emp_conf_join_date,
emp.emp_join_prsnt_post_date, emp.emp_join_prsnt_office_date, 
emp.emp_retirement_date, emp.emp_termination_date, emp.emp_group,
emp.emp_desig_first_app, emp.emp_pay_in_payband, emp.emp_father_name,
emp.emp_religion, emp.emp_marital_status, emp.emp_spouse_name, 
emp.emp_pan_no, emp.emp_idf_mark, emp.pre_state, emp.emp_pre_house_no,
emp.emp_pre_street_no, emp.emp_pre_vill, emp.emp_pre_post, emp.emp_pre_pin,
emp.emp_pre_dist, emp.emp_pre_dist_others, emp.per_state, emp.emp_per_house_no,
emp.emp_per_street_no, emp.emp_per_vill, emp.emp_per_post, emp.emp_per_pin, 
emp.emp_per_dist, emp.emp_per_dist_others, emp.emp_mobile_no, emp.emp_mail_id,
emp.emp_status, emp.entry_ip, emp.emp_grade_pay, emp.emp_id_const, 
emp.ps_id_fk,emp.zp_id_fk, emp.emp_pension_status,
emp.emp_first_memo_no,
emp.emp_first_memo_date,
emp.emp_first_gp_ps_zp_code,
emp.emp_present_memo_no,
emp.zp_emp_type,
emp.emp_pre_ps,
emp.emp_per_ps,
emp.emp_present_memo_date,
emp.ropa_level,
pen.update_time, pen.flag, 
pen.error_code, stop.claimant_name,stop.relationship_incumbent,stop.claiment_mobile_no,stop.reason
from prd_employee_master emp 
inner join prd_pension_employee pen 
on pen.emp_id_fk=emp.emp_id_pk
left join (select * from (select emp_id_fk,date,reason,claimant_name,relationship_incumbent,claiment_mobile_no,max(date) over (partition by emp_id_fk) max_my_date
from prd_stop_sal_reason)A where date = max_my_date and reason='1993') as stop ON pen.emp_id_fk=stop.emp_id_fk 
 where emp.emp_pension_status='2' and emp.ropa_status='1'
 and 
 SUBSTRING(cast(emp.emp_termination_date as text),1,7)>='2020-01'  and SUBSTRING(cast(emp.emp_termination_date as text),1,7)<='2022-08'
 
  AND emp.gp_id_fk NOT IN('3355','3380','3383','3400','3401','3402','3403') 
 AND (emp.zp_id_fk is null or emp.zp_id_fk not in('21'))
 and (emp.ps_id_fk!='0' or emp.gp_id_fk!='0' or (emp.zp_id_fk!='0' and emp.zp_emp_type='367'))
  AND emp.ps_id_fk NOT IN ('342','343') AND emp.emp_id_const!='0' 
 AND emp.emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
									
									
	foreach ($sql_pension_employee_data_check as $mas_data_up) 
{
	if($mas_data_up['reason']==1993)
	{
		$retirement_type='1993';
	}
	else
	{
		$retirement_type='1991';
	}
	if($mas_data_up['claiment_mobile_no']=='')
	{
		$claiment_mobile_no='0';
	}
	else
	{
		$claiment_mobile_no=$mas_data_up['claiment_mobile_no'];
	}
	
	if($mas_data_up['ps_id_fk']!='0')
	{
		$ps_id_fk=$mas_data_up['ps_id_fk'];
		$gp_id_fk=0;
		$zp_id_fk=0;
	}
	else if($mas_data_up['gp_id_fk']!='0')
	{
		$ps_id_fk=0;
		$gp_id_fk=$mas_data_up['gp_id_fk'];
		$zp_id_fk=0;
	}
	else if($mas_data_up['zp_id_fk']!='0' )
	{
		$ps_id_fk=0;
		$gp_id_fk=0;
		$zp_id_fk=$mas_data_up['zp_id_fk'];
	}
	$sql_pension_data= $db->fetch_table("select
									emp_id_fk,
									gp_id_fk ,
									flag,emp_pension_status,reason
									from 
									prd_pension_employee 
									WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."'");

	//////////////////////// Employee data cheacking///////////////////////////////
	
	if($mas_data_up['emp_pension_status']==2 && count($sql_pension_data)>=1)
			{
			
	
			$sql_pension_employee_data_insert_archive= $db->insert("INSERT INTO 
																	prd_pension_employee_archive(
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
																	ps_id_fk,update_time,flag,reason,claimant_name,relationship_incumbent,
																	emp_first_memo_no ,
																	emp_first_memo_date ,
																	emp_present_memo_no,
																	emp_present_memo_date,
																	emp_first_gp_ps_zp_code,zp_id_fk,claiment_mobile_no,
																	zp_emp_type,
																	emp_pre_ps,
																	emp_per_ps,
																	ropa_level
																	)
																	SELECT 
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
																	ps_id_fk,now(),flag,reason,claimant_name,relationship_incumbent,
																	emp_first_memo_no ,
																	emp_first_memo_date ,
																	emp_present_memo_no,
																	emp_present_memo_date,
																	emp_first_gp_ps_zp_code,
																	zp_id_fk,
																	claiment_mobile_no,
																	zp_emp_type,
																	emp_pre_ps,
																	emp_per_ps,
																	ropa_level
																	from 
																	prd_pension_employee
																	WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."'
																	");
	
			}
			
			
			if(count($sql_pension_employee_data_insert_archive)==1 && $sql_pension_data[0]['emp_pension_status']==0)
			{
				
				pg_query('BEGIN');
				if($sql_pension_data[0]['flag']=='N')
				{
					
	
				$update_penstion_data=$db->update("UPDATE prd_pension_employee SET
													emp_first_name='".$mas_data_up['emp_first_name']."',
													emp_second_name='".$mas_data_up['emp_second_name']."',
													emp_last_name='".$mas_data_up['emp_last_name']."',
													emp_dob='".$mas_data_up['emp_dob']."',
													emp_sex='".$mas_data_up['emp_sex']."',
													emp_aadhar_no='".$mas_data_up['emp_aadhar_no']."',
													emp_desig='".$mas_data_up['emp_desig']."',
													emp_first_join_date='".$mas_data_up['emp_first_join_date']."',
													emp_conf_join_date='".$mas_data_up['emp_conf_join_date']."',
													emp_join_prsnt_post_date='".$mas_data_up['emp_join_prsnt_post_date']."',
													emp_join_prsnt_office_date='".$mas_data_up['emp_join_prsnt_office_date']."',
													emp_retirement_date='".$mas_data_up['emp_retirement_date']."',
													emp_termination_date='".$mas_data_up['emp_termination_date']."',
													emp_group='".$mas_data_up['emp_group']."',
													emp_desig_first_app='".$mas_data_up['emp_desig_first_app']."',
													emp_pay_in_payband='".$mas_data_up['emp_pay_in_payband']."',
													emp_father_name='".$mas_data_up['emp_father_name']."',
													emp_religion='".$mas_data_up['emp_religion']."',
													emp_marital_status='".$mas_data_up['emp_marital_status']."',
													emp_spouse_name='".$mas_data_up['emp_spouse_name']."',
													emp_pan_no='".$mas_data_up['emp_pan_no']."',
													emp_idf_mark='".$mas_data_up['emp_idf_mark']."',
													pre_state='".$mas_data_up['pre_state']."',
													emp_pre_house_no='".$mas_data_up['emp_pre_house_no']."',
													emp_pre_street_no='".$mas_data_up['emp_pre_street_no']."',
													emp_pre_vill='".$mas_data_up['emp_pre_vill']."',
													emp_pre_post='".$mas_data_up['emp_pre_post']."',
													emp_pre_pin='".$mas_data_up['emp_pre_pin']."',
													emp_pre_dist='".$mas_data_up['emp_pre_dist']."',
													emp_pre_dist_others='".$mas_data_up['emp_pre_dist_others']."',
													per_state='".$mas_data_up['per_state']."',
													emp_per_house_no='".$mas_data_up['emp_per_house_no']."',
													emp_per_street_no='".$mas_data_up['emp_per_street_no']."',
													emp_per_vill='".$mas_data_up['emp_per_vill']."',
													emp_per_post='".$mas_data_up['emp_per_post']."',
													emp_per_pin='".$mas_data_up['emp_per_pin']."',
													emp_per_dist='".$mas_data_up['emp_per_dist']."',
													emp_per_dist_others='".$mas_data_up['emp_per_dist_others']."',
													emp_mobile_no='".$mas_data_up['emp_mobile_no']."',
													emp_mail_id='".$mas_data_up['emp_mail_id']."',
													entry_ip='".$mas_data_up['entry_ip']."',
													emp_status='".$mas_data_up['emp_status']."',
													emp_grade_pay='".$mas_data_up['emp_grade_pay']."',
													emp_id_const='".$mas_data_up['emp_id_const']."',
													update_time='".$mas_data_up['update_time']."',
													flag='N',emp_pension_status='0',
													reason='".$retirement_type."',
													claimant_name='".$mas_data_up['claimant_name']."',
													relationship_incumbent='".$mas_data_up['relationship_incumbent']."',
													claiment_mobile_no='".$claiment_mobile_no."',
													emp_first_memo_no='".$mas_data_up['emp_first_memo_no']."',
													emp_first_memo_date='".$mas_data_up['emp_first_memo_date']."',
													emp_first_gp_ps_zp_code='".$mas_data_up['emp_first_gp_ps_zp_code']."',
													emp_present_memo_no='".$mas_data_up['emp_present_memo_no']."',
													emp_present_memo_date= '".$mas_data_up['emp_present_memo_date']."',
													emp_pre_ps='".$mas_data_up['emp_pre_ps']."',
													emp_per_ps='".$mas_data_up['emp_per_ps']."',
													ropa_level='".$mas_data_up['ropa_level']."',
													gp_id_fk='".$gp_id_fk."',
													ps_id_fk='".$ps_id_fk."',
													zp_id_fk='".$zp_id_fk."'
													WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."' 
													AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
													
				
				}
				else if($sql_pension_data[0]['flag']=='M')
				{
					$update_penstion_data=$db->update("UPDATE prd_pension_employee SET
													emp_first_name='".$mas_data_up['emp_first_name']."',
													emp_second_name='".$mas_data_up['emp_second_name']."',
													emp_last_name='".$mas_data_up['emp_last_name']."',
													emp_dob='".$mas_data_up['emp_dob']."',
													emp_sex='".$mas_data_up['emp_sex']."',
													emp_aadhar_no='".$mas_data_up['emp_aadhar_no']."',
													emp_desig='".$mas_data_up['emp_desig']."',
													emp_first_join_date='".$mas_data_up['emp_first_join_date']."',
													emp_conf_join_date='".$mas_data_up['emp_conf_join_date']."',
													emp_join_prsnt_post_date='".$mas_data_up['emp_join_prsnt_post_date']."',
													emp_join_prsnt_office_date='".$mas_data_up['emp_join_prsnt_office_date']."',
													emp_retirement_date='".$mas_data_up['emp_retirement_date']."',
													emp_termination_date='".$mas_data_up['emp_termination_date']."',
													emp_group='".$mas_data_up['emp_group']."',
													emp_desig_first_app='".$mas_data_up['emp_desig_first_app']."',
													emp_pay_in_payband='".$mas_data_up['emp_pay_in_payband']."',
													emp_father_name='".$mas_data_up['emp_father_name']."',
													emp_religion='".$mas_data_up['emp_religion']."',
													emp_marital_status='".$mas_data_up['emp_marital_status']."',
													emp_spouse_name='".$mas_data['emp_spouse_name']."',
													emp_pan_no='".$mas_data_up['emp_pan_no']."',
													emp_idf_mark='".$mas_data_up['emp_idf_mark']."',
													pre_state='".$mas_data_up['pre_state']."',
													emp_pre_house_no='".$mas_data_up['emp_pre_house_no']."',
													emp_pre_street_no='".$mas_data_up['emp_pre_street_no']."',
													emp_pre_vill='".$mas_data_up['emp_pre_vill']."',
													emp_pre_post='".$mas_data_up['emp_pre_post']."',
													emp_pre_pin='".$mas_data_up['emp_pre_pin']."',
													emp_pre_dist='".$mas_data_up['emp_pre_dist']."',
													emp_pre_dist_others='".$mas_data_up['emp_pre_dist_others']."',
													per_state='".$mas_data_up['per_state']."',
													emp_per_house_no='".$mas_data_up['emp_per_house_no']."',
													emp_per_street_no='".$mas_data_up['emp_per_street_no']."',
													emp_per_vill='".$mas_data_up['emp_per_vill']."',
													emp_per_post='".$mas_data_up['emp_per_post']."',
													emp_per_pin='".$mas_data_up['emp_per_pin']."',
													emp_per_dist='".$mas_data_up['emp_per_dist']."',
													emp_per_dist_others='".$mas_data_up['emp_per_dist_others']."',
													emp_mobile_no='".$mas_data_up['emp_mobile_no']."',
													emp_mail_id='".$mas_data_up['emp_mail_id']."',
													entry_ip='".$mas_data_up['entry_ip']."',
													emp_status='".$mas_data_up['emp_status']."',
													emp_grade_pay='".$mas_data_up['emp_grade_pay']."',
													emp_id_const='".$mas_data_up['emp_id_const']."',
													update_time='".$mas_data_up['update_time']."',
													flag='M',emp_pension_status='0',
													reason='".$retirement_type."',
													emp_pre_ps='".$mas_data_up['emp_pre_ps']."',
													emp_per_ps='".$mas_data_up['emp_per_ps']."',
													claimant_name='".$mas_data_up['claimant_name']."',
													relationship_incumbent='".$mas_data_up['relationship_incumbent']."',
													claiment_mobile_no='".$claiment_mobile_no."',
													emp_first_memo_no='".$mas_data_up['emp_first_memo_no']."',
													emp_first_memo_date='".$mas_data_up['emp_first_memo_date']."',
													emp_first_gp_ps_zp_code='".$mas_data_up['emp_first_gp_ps_zp_code']."',
													emp_present_memo_no='".$mas_data_up['emp_present_memo_no']."',
													emp_present_memo_date= '".$mas_data_up['emp_present_memo_date']."',
													ropa_level='".$mas_data_up['ropa_level']."',
													gp_id_fk='".$gp_id_fk."',
													ps_id_fk='".$ps_id_fk."',
													zp_id_fk='".$zp_id_fk."'
													WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."'
													 AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
													
				}
				
				if(($update_penstion_data)==1)
				{
					
					$update_employee_master_data=$db->update("UPDATE prd_employee_master 
															SET emp_pension_status='1' 
															WHERE emp_id_pk='".$mas_data_up['emp_id_pk']."' AND emp_status in('1','2')
															 AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
					
					if(($update_employee_master_data)==1)
					{
						pg_query('COMMIT'); 
					}
					else
					{
						pg_query('ROLLBACK');
					}
				}
				else
				{
					pg_query('ROLLBACK');
				}
	
	
			}
						
			
			else if(count($sql_pension_employee_data_insert_archive)==1 && $sql_pension_data[0]['emp_pension_status']==2)
			{
				
				pg_query('BEGIN');
				$update_penstion_data=$db->update("UPDATE prd_pension_employee SET
													emp_first_name='".$mas_data_up['emp_first_name']."',
													emp_second_name='".$mas_data_up['emp_second_name']."',
													emp_last_name='".$mas_data_up['emp_last_name']."',
													emp_dob='".$mas_data_up['emp_dob']."',
													emp_sex='".$mas_data_up['emp_sex']."',
													emp_aadhar_no='".$mas_data_up['emp_aadhar_no']."',
													emp_desig='".$mas_data_up['emp_desig']."',
													emp_first_join_date='".$mas_data_up['emp_first_join_date']."',
													emp_conf_join_date='".$mas_data_up['emp_conf_join_date']."',
													emp_join_prsnt_post_date='".$mas_data_up['emp_join_prsnt_post_date']."',
													emp_join_prsnt_office_date='".$mas_data_up['emp_join_prsnt_office_date']."',
													emp_retirement_date='".$mas_data_up['emp_retirement_date']."',
													emp_termination_date='".$mas_data_up['emp_termination_date']."',
													emp_group='".$mas_data_up['emp_group']."',
													emp_desig_first_app='".$mas_data_up['emp_desig_first_app']."',
													emp_pay_in_payband='".$mas_data_up['emp_pay_in_payband']."',
													emp_father_name='".$mas_data_up['emp_father_name']."',
													emp_religion='".$mas_data_up['emp_religion']."',
													emp_marital_status='".$mas_data_up['emp_marital_status']."',
													emp_spouse_name='".$mas_data_up['emp_spouse_name']."',
													emp_pan_no='".$mas_data_up['emp_pan_no']."',
													emp_idf_mark='".$mas_data_up['emp_idf_mark']."',
													pre_state='".$mas_data_up['pre_state']."',
													emp_pre_house_no='".$mas_data_up['emp_pre_house_no']."',
													emp_pre_street_no='".$mas_data_up['emp_pre_street_no']."',
													emp_pre_vill='".$mas_data_up['emp_pre_vill']."',
													emp_pre_post='".$mas_data_up['emp_pre_post']."',
													emp_pre_pin='".$mas_data_up['emp_pre_pin']."',
													emp_pre_dist='".$mas_data_up['emp_pre_dist']."',
													emp_pre_dist_others='".$mas_data_up['emp_pre_dist_others']."',
													per_state='".$mas_data_up['per_state']."',
													emp_per_house_no='".$mas_data_up['emp_per_house_no']."',
													emp_per_street_no='".$mas_data_up['emp_per_street_no']."',
													emp_per_vill='".$mas_data_up['emp_per_vill']."',
													emp_per_post='".$mas_data_up['emp_per_post']."',
													emp_per_pin='".$mas_data_up['emp_per_pin']."',
													emp_per_dist='".$mas_data_up['emp_per_dist']."',
													emp_per_dist_others='".$mas_data_up['emp_per_dist_others']."',
													emp_mobile_no='".$mas_data_up['emp_mobile_no']."',
													emp_mail_id='".$mas_data_up['emp_mail_id']."',
													entry_ip='".$mas_data_up['entry_ip']."',
													emp_status='".$mas_data_up['emp_status']."',
													emp_grade_pay='".$mas_data_up['emp_grade_pay']."',
													emp_id_const='".$mas_data_up['emp_id_const']."',
													update_time='".$mas_data_up['update_time']."',
													flag='M',emp_pension_status='0',
													emp_pre_ps='".$mas_data_up['emp_pre_ps']."',
													emp_per_ps='".$mas_data_up['emp_per_ps']."',
													reason='".$retirement_type."',
													claimant_name='".$mas_data_up['claimant_name']."',
													relationship_incumbent='".$mas_data_up['relationship_incumbent']."',
													claiment_mobile_no='".$claiment_mobile_no."',
													emp_first_memo_no='".$mas_data_up['emp_first_memo_no']."',
													emp_first_memo_date='".$mas_data_up['emp_first_memo_date']."',
													emp_first_gp_ps_zp_code='".$mas_data_up['emp_first_gp_ps_zp_code']."',
													emp_present_memo_no='".$mas_data_up['emp_present_memo_no']."',
													emp_present_memo_date= '".$mas_data_up['emp_present_memo_date']."',
													ropa_level='".$mas_data_up['ropa_level']."',
													gp_id_fk='".$gp_id_fk."',
													ps_id_fk='".$ps_id_fk."',
													zp_id_fk='".$zp_id_fk."'
													WHERE  emp_id_fk='".$mas_data_up['emp_id_pk']."' 
													AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
			
			if(($update_penstion_data)==1)
				{
					
					$update_employee_master_data=$db->update("UPDATE prd_employee_master 
															SET emp_pension_status='1' 
															WHERE emp_id_pk='".$mas_data_up['emp_id_pk']."' AND emp_status in('1','2') 
															AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
					
					if(($update_employee_master_data)==1)
					{
						pg_query('COMMIT'); 
					}
					else
					{
						pg_query('ROLLBACK');
					}
				}
				else
				{
					pg_query('ROLLBACK');
				}
			
			}
			
			
			else if(count($sql_pension_employee_data_insert_archive)==1 && $sql_pension_data[0]['emp_pension_status']==2 ||$sql_pension_data[0]['emp_pension_status']==3)
			{
				pg_query('BEGIN');
				if($sql_pension_data[0]['flag']=='M')
				{
				
			$update_penstion_data=$db->update("UPDATE prd_pension_employee SET
													emp_first_name='".$mas_data_up['emp_first_name']."',
													emp_second_name='".$mas_data_up['emp_second_name']."',
													emp_last_name='".$mas_data_up['emp_last_name']."',
													emp_dob='".$mas_data_up['emp_dob']."',
													emp_sex='".$mas_data_up['emp_sex']."',
													emp_aadhar_no='".$mas_data_up['emp_aadhar_no']."',
													emp_desig='".$mas_data_up['emp_desig']."',
													emp_first_join_date='".$mas_data_up['emp_first_join_date']."',
													emp_conf_join_date='".$mas_data_up['emp_conf_join_date']."',
													emp_join_prsnt_post_date='".$mas_data_up['emp_join_prsnt_post_date']."',
													emp_join_prsnt_office_date='".$mas_data_up['emp_join_prsnt_office_date']."',
													emp_retirement_date='".$mas_data_up['emp_retirement_date']."',
													emp_termination_date='".$mas_data_up['emp_termination_date']."',
													emp_group='".$mas_data_up['emp_group']."',
													emp_desig_first_app='".$mas_data_up['emp_desig_first_app']."',
													emp_pay_in_payband='".$mas_data_up['emp_pay_in_payband']."',
													emp_father_name='".$mas_data_up['emp_father_name']."',
													emp_religion='".$mas_data_up['emp_religion']."',
													emp_marital_status='".$mas_data_up['emp_marital_status']."',
													emp_spouse_name='".$mas_data_up['emp_spouse_name']."',
													emp_pan_no='".$mas_data_up['emp_pan_no']."',
													emp_idf_mark='".$mas_data_up['emp_idf_mark']."',
													pre_state='".$mas_data_up['pre_state']."',
													emp_pre_house_no='".$mas_data_up['emp_pre_house_no']."',
													emp_pre_street_no='".$mas_data_up['emp_pre_street_no']."',
													emp_pre_vill='".$mas_data_up['emp_pre_vill']."',
													emp_pre_post='".$mas_data_up['emp_pre_post']."',
													emp_pre_pin='".$mas_data_up['emp_pre_pin']."',
													emp_pre_dist='".$mas_data_up['emp_pre_dist']."',
													emp_pre_dist_others='".$mas_data_up['emp_pre_dist_others']."',
													per_state='".$mas_data_up['per_state']."',
													emp_pre_ps='".$mas_data_up['emp_pre_ps']."',
													emp_per_ps='".$mas_data_up['emp_per_ps']."',
													emp_per_house_no='".$mas_data_up['emp_per_house_no']."',
													emp_per_street_no='".$mas_data_up['emp_per_street_no']."',
													emp_per_vill='".$mas_data_up['emp_per_vill']."',
													emp_per_post='".$mas_data_up['emp_per_post']."',
													emp_per_pin='".$mas_data_up['emp_per_pin']."',
													emp_per_dist='".$mas_data_up['emp_per_dist']."',
													emp_per_dist_others='".$mas_data_up['emp_per_dist_others']."',
													emp_mobile_no='".$mas_data_up['emp_mobile_no']."',
													emp_mail_id='".$mas_data_up['emp_mail_id']."',
													entry_ip='".$mas_data_up['entry_ip']."',
													emp_status='".$mas_data_up['emp_status']."',
													emp_grade_pay='".$mas_data_up['emp_grade_pay']."',
													emp_id_const='".$mas_data_up['emp_id_const']."',
													update_time='".$mas_data_up['update_time']."',
													flag='M',emp_pension_status='0',
													reason='".$retirement_type."',
													claimant_name='".$mas_data_up['claimant_name']."',
													relationship_incumbent='".$mas_data_up['relationship_incumbent']."',
													claiment_mobile_no='".$claiment_mobile_no."',
													emp_first_memo_no='".$mas_data_up['emp_first_memo_no']."',
													emp_first_memo_date='".$mas_data_up['emp_first_memo_date']."',
													emp_first_gp_ps_zp_code='".$mas_data_up['emp_first_gp_ps_zp_code']."',
													emp_present_memo_no='".$mas_data_up['emp_present_memo_no']."',
													emp_present_memo_date= '".$mas_data_up['emp_present_memo_date']."',
													ropa_level='".$mas_data_up['ropa_level']."',
													gp_id_fk='".$gp_id_fk."',
													ps_id_fk='".$ps_id_fk."',
													zp_id_fk='".$zp_id_fk."'
													WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."'
													 AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
				}
				else if($sql_pension_data[0]['flag']=='N')
				{
					$update_penstion_data=$db->update("UPDATE prd_pension_employee SET
													emp_first_name='".$mas_data_up['emp_first_name']."',
													emp_second_name='".$mas_data_up['emp_second_name']."',
													emp_last_name='".$mas_data_up['emp_last_name']."',
													emp_dob='".$mas_data_up['emp_dob']."',
													emp_sex='".$mas_data_up['emp_sex']."',
													emp_aadhar_no='".$mas_data_up['emp_aadhar_no']."',
													emp_desig='".$mas_data_up['emp_desig']."',
													emp_first_join_date='".$mas_data_up['emp_first_join_date']."',
													emp_conf_join_date='".$mas_data_up['emp_conf_join_date']."',
													emp_join_prsnt_post_date='".$mas_data_up['emp_join_prsnt_post_date']."',
													emp_join_prsnt_office_date='".$mas_data_up['emp_join_prsnt_office_date']."',
													emp_retirement_date='".$mas_data_up['emp_retirement_date']."',
													emp_termination_date='".$mas_data_up['emp_termination_date']."',
													emp_group='".$mas_data_up['emp_group']."',
													emp_desig_first_app='".$mas_data_up['emp_desig_first_app']."',
													emp_pay_in_payband='".$mas_data_up['emp_pay_in_payband']."',
													emp_father_name='".$mas_data_up['emp_father_name']."',
													emp_religion='".$mas_data_up['emp_religion']."',
													emp_marital_status='".$mas_data_up['emp_marital_status']."',
													emp_spouse_name='".$mas_data_up['emp_spouse_name']."',
													emp_pan_no='".$mas_data_up['emp_pan_no']."',
													emp_idf_mark='".$mas_data_up['emp_idf_mark']."',
													pre_state='".$mas_data_up['pre_state']."',
													emp_pre_house_no='".$mas_data_up['emp_pre_house_no']."',
													emp_pre_street_no='".$mas_data_up['emp_pre_street_no']."',
													emp_pre_vill='".$mas_data_up['emp_pre_vill']."',
													emp_pre_post='".$mas_data_up['emp_pre_post']."',
													emp_pre_pin='".$mas_data_up['emp_pre_pin']."',
													emp_pre_dist='".$mas_data_up['emp_pre_dist']."',
													emp_pre_dist_others='".$mas_data_up['emp_pre_dist_others']."',
													per_state='".$mas_data_up['per_state']."',
													emp_per_house_no='".$mas_data_up['emp_per_house_no']."',
													emp_per_street_no='".$mas_data_up['emp_per_street_no']."',
													emp_per_vill='".$mas_data_up['emp_per_vill']."',
													emp_per_post='".$mas_data_up['emp_per_post']."',
													emp_per_pin='".$mas_data_up['emp_per_pin']."',
													emp_per_dist='".$mas_data_up['emp_per_dist']."',
													emp_per_dist_others='".$mas_data_up['emp_per_dist_others']."',
													emp_mobile_no='".$mas_data_up['emp_mobile_no']."',
													emp_mail_id='".$mas_data_up['emp_mail_id']."',
													entry_ip='".$mas_data_up['entry_ip']."',
													emp_status='".$mas_data_up['emp_status']."',
													emp_grade_pay='".$mas_data_up['emp_grade_pay']."',
													emp_id_const='".$mas_data_up['emp_id_const']."',
													update_time='".$mas_data_up['update_time']."',
													flag='N',emp_pension_status='0',
													emp_pre_ps='".$mas_data_up['emp_pre_ps']."',
													emp_per_ps='".$mas_data_up['emp_per_ps']."',
													reason='".$retirement_type."',
													claimant_name='".$mas_data_up['claimant_name']."',
													relationship_incumbent='".$mas_data_up['relationship_incumbent']."',
													claiment_mobile_no='".$claiment_mobile_no."',
													emp_first_memo_no='".$mas_data_up['emp_first_memo_no']."',
													emp_first_memo_date='".$mas_data_up['emp_first_memo_date']."',
													emp_first_gp_ps_zp_code='".$mas_data_up['emp_first_gp_ps_zp_code']."',
													emp_present_memo_no='".$mas_data_up['emp_present_memo_no']."',
													emp_present_memo_date= '".$mas_data_up['emp_present_memo_date']."',
													ropa_level='".$mas_data_up['ropa_level']."',
													gp_id_fk='".$gp_id_fk."',
													ps_id_fk='".$ps_id_fk."',
													zp_id_fk='".$zp_id_fk."'
													WHERE emp_id_fk='".$mas_data_up['emp_id_pk']."' AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
				}
													
				if(($update_penstion_data)==1)
				{
					$update_employee_master_data=$db->update("UPDATE prd_employee_master 
															SET emp_pension_status='1' 
															WHERE emp_id_pk='".$mas_data_up['emp_id_pk']."' AND emp_status in('1','2')
															 AND emp_desig NOT IN('1120','1124','1125','9012','9013','9014','9015','9016','9017','1')");
					
					if(($update_employee_master_data)==1)
					{
						pg_query('COMMIT'); 
					}
					else
					{
						pg_query('ROLLBACK');
					}
				}
				else
				{
					pg_query('ROLLBACK');
				}
			}
	echo "SUCCESS";
}




	


?>