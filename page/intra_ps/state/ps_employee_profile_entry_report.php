<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//echo 11; die;
$db  = new database();
$arr=$db->fetch_table("SELECT district_name,district_code,

count(distinct CASE WHEN em1.emp_status in ('1','6','7','10') then em1.emp_id_pk else null end) as total_emp,

count(distinct CASE WHEN (em1.emp_status='6') then em1.emp_id_pk else null end) as emp_waiting,
count(distinct CASE WHEN (em1.emp_status='1') then em1.emp_id_pk else null end) as emp_finz,
count(distinct CASE WHEN (em1.emp_status='7') then em1.emp_id_pk else null end) as emp_rej,
count(distinct CASE WHEN (em1.emp_status='10') then em1.emp_id_pk else null end) as emp_req_not_sent 
from 
prd_location_master_district dist
LEFT JOIN prd_location_master_panchayat_samiti ps ON SUBSTRING (CAST(ps_code AS text),1,4)=CAST(district_code AS text)

LEFT JOIN prd_employee_master as em1 on em1.ps_id_fk=ps.ps_id_pk 
GROUP BY district_name,district_code,district_id_pk 
order by district_name asc
");


header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=ps_Employee_Profile_Entry_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
  <tr style="border-color:#3E9B96;">
  <td colspan="8" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on PS Employee Profile Entry, Update and Approval<br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
  <tr style="border-color:#3E9B96;">
    <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
    <th scope="col" width="250" align="center" style="border-color:#3E9B96;">DISTRICT NAME</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Total Employee</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Approved By Eo</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Waiting For Approval At EO</th>
   
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Profile Rejected By EO</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Pending At DA end</th>
  </tr>
  <? $cnt=1;$total_emp=0;$finz_emp=0;$wait_emp=0;$rej=0;$not_send=0;
    foreach($arr as $key){
	 //if($key['district_code']!='99'){
	if($key['district_code']=='01' || $key['district_code']=='99') 
	continue;
	$total_emp+=$key['total_emp'];
	$finz_emp+=$key['emp_finz'];
	$wait_emp+=$key['emp_waiting'];
	//$incomplt+=$key['emp_incomplete'];
	$rej+=$key['emp_rej'];
	$not_send+=$key['emp_req_not_sent'];
	?>
  <tr style="border-color:#3E9B96;">
    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
    <td style="border-color:#3E9B96;"><?=$key['district_name']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_emp']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_finz']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_waiting']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_rej']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_req_not_sent']?></td>
  </tr>
  <? $cnt++;}  ?>
  <tr style="border-color:#3E9B96;">
  	<td colspan="2" style="text-align:center;font-weight:bold;border-color:#3E9B96;">TOTAL</td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$total_emp ?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$finz_emp?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$wait_emp?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$rej?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$not_send?></td>
  </tr>
</table>

<style>
tr,td{
	border-color:#3E9B96;
}
</style>
