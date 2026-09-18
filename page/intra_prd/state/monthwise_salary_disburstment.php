<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
$crypto = new cryptography();
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
function GetMonthString($n)
{
$n=(int)$n;
    $timestamp = mktime(0, 0, 0, $n);
    
    return date("F", $timestamp);
}

$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
$db = new database();
/* $stat= $db->fetch_table("select status from prd_block_bill_details where salary_monthyear='".date('Ym')."'"); 
$status=$stat[0]['status'];*/
/*$arr = $db->fetch_table("select lmd.district_id_pk,district_name,count(distinct(school_id_pk)) as sch_cnt,count(distinct(teacher_id_pk)) as emp_cnt,sum(sal.net) as total_net
from
ehrms_dise_location_master_district lmd
left join ehrms_dise_location_master_circle lmc on substring(circle_code,3,2)=lmd.district_code
left join ehrms_dise_location_master_school lms on lms.circle_id_fk=lmc.circle_id_pk and lms.flag='2'
left join ehrms_dise_teacher_primary tch on lms.school_dise_code=tch.schcd and tch.status='1'
left join ehrms_teacher_salary_save_primary sal on sal.teacher_id_fk=tch.teacher_id_pk 
and sal.salary_monthyear='".date('Ym')."' and sal.delete_status='1' 
and sal.is_saved='1' and sal.status_flag='3' 
group by lmd.district_name,lmd.district_id_pk
order by lmd.district_name
");*/
if(date('Ym')==$monthyear){
$arr = $db->fetch_table("select dist.district_id_pk, 
dist.district_name, 
block.block_name,
count(distinct(gp.gp_id_pk)) as total_gp,
count(distinct(emp.emp_id_pk)) as total_emp,
sum(sal.gross_salary) as total_gross,
sum(sal.net) as total_net from 
prd_location_master_district dist 
left join prd_location_master_block block on dist.district_id_pk=block.district_id_fk
left join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk 
left join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
left join prd_employee_salary_save sal on emp.emp_id_pk=sal.emp_id_fk and sal.is_saved=1 and sal.delete_status=1 and sal.status_flag=3
where sal.salary_monthyear='".$monthyear."'
group by dist.district_name,dist.district_id_pk,block.block_name
order by dist.district_name 
");
}
else {
$arr = $db->fetch_table("select dist.district_id_pk, 
dist.district_name, 
block.block_name,
count(distinct(gp.gp_id_pk)) as total_gp,
count(distinct(emp.emp_id_pk)) as total_emp,
sum(sal.gross_salary) as total_gross ,
sum(sal.net) as total_net from 
prd_location_master_district dist 
left join prd_location_master_block block on dist.district_id_pk=block.district_id_fk
left join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk 
left join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
left join prd_monthly_salary_archive_final sal on emp.emp_id_pk=sal.emp_id_fk 
where sal.salary_monthyear='201606'
group by dist.district_name,dist.district_id_pk,block.block_name
order by dist.district_name"); 

}
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=District_Wise_Salary_Disburstment_Report.xls');
header("Content-Transfer-Encoding: binary");
?>

<table border="1" width="60%" style="border-color:#77C4E7">
	<tr style="border-color:#77C4E7">
  <td colspan="7" style="font-weight:bold;font-size:18px;text-align:center;border-color:#77C4E7">District Wise Salary Disburstment For The Month of  <?= GetMonthString($crypto->decode($_GET['mo'],3)).",".$crypto->decode($_GET['ye'],3) ?> <br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
    <tr class="th_color" style="border-color:#77C4E7">
    <th style="border-color:#77C4E7">SL NO.</th>
    <th style="border-color:#77C4E7">DISTRICT NAME</th>
    <th style="border-color:#77C4E7">BLOCK <br /> NAME</th>
    <th style="border-color:#77C4E7">TOTAL <br /> GP</th>
    <th style="border-color:#77C4E7">TOTAL <br /> EMPLOYEE</th>
    <th style="border-color:#77C4E7">TOTAL <br /> GROSS SALARY</th>
    <th style="border-color:#77C4E7">TOTAL <br /> NET SALARY</th>
    </tr>
<? $cnt=1;$total_net=0; $total_gp=0;$total_emp=0;$total_gross=0;
foreach ($arr as $key) {
$not_submit=$tot_cnt=0;
if($key['district_id_pk']=='99' || $key['district_id_pk']=='1'){
	continue;
}
?>
<tr style="border-color:#77C4E7">
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $cnt;?></td>
<td style="border-color:#77C4E7"><?= $key['district_name']?></td>
<td style="border-color:#77C4E7"><?= $key['block_name']?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $total_gp+=$key['total_gp']; echo $key['total_gp'];?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $total_emp+=$key['total_emp']; echo $key['total_emp'];?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $total_gross+=$key['total_gross']; if($key['total_gross']==''){ echo '0';} else{ echo $key['total_gross'];}?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $total_net+=$key['total_net']; if($key['total_net']==''){ echo '0';} else{ echo $key['total_net'];}?></td>
</tr>
<? $cnt++; } ?>
<tr style="border-color:#77C4E7">
<td style="border-color:#77C4E7;font-weight:bold;text-align:right;" colspan="3">TOTAL</td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $total_gp;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $total_emp;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $total_gross;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $total_net;?></td>
</tr>
</table>