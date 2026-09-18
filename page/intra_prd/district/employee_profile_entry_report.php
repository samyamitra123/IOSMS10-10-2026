<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

$db  = new database();
$arr=$db->fetch_table("SELECT block_name,block_code,
count(distinct CASE WHEN '1' then emp.emp_id_pk else null end) as total_emp,
count(distinct CASE WHEN (em1.emp_status='6') then em1.emp_id_pk else null end) as emp_waiting,
count(distinct CASE WHEN (em1.emp_status='1') then em1.emp_id_pk else null end) as emp_finz,
count(distinct CASE WHEN (em1.emp_status='7') then em1.emp_id_pk else null end) as emp_rej,
count(distinct CASE WHEN (em1.emp_form_status in ('1','2','3','4')) then em1.emp_id_pk else null end) as emp_incomplete,
count(distinct CASE WHEN (em1.emp_status='10' AND em1.emp_form_status not in ('1','2','3','4')) then em1.emp_id_pk else null end) as emp_req_not_sent 
from 
prd_location_master_district dist
LEFT join prd_location_master_block blk ON dist.district_id_pk=blk.district_id_fk
LEFT JOIN prd_location_master_gp gp ON blk.block_id_pk=gp.block_id_fk
LEFT JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
LEFT JOIN prd_employee_master as em1 on em1.gp_id_fk=gp.gp_id_pk 
WHERE district_code='".$_SESSION['location']['district_code']."'
GROUP BY block_name,block_code
order by block_code");


header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Employee_Profile_Entry_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
  <tr style="border-color:#3E9B96;">
  <td colspan="8" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on Employee Profile Entry, Update and Approval<br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
  <tr style="border-color:#3E9B96;">
    <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
    <th scope="col" width="250" align="center" style="border-color:#3E9B96;">BLOCK NAME</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Total Employee</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Approved By BDO</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Waiting For Approval At BDO</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Profile Incomplete </th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Profile Rejected By BDO</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Pending At GP end</th>
  </tr>
  <? $cnt=1;$total_emp=0;$finz_emp=0;$wait_emp=0;$incomplt=0;$rej=0;$not_send=0;
    foreach($arr as $key){
	 //if($key['district_code']!='99'){
	if($key['district_code']=='01' || $key['district_code']=='99') 
	continue;
	$total_emp+=$key['total_emp'];
	$finz_emp+=$key['emp_finz'];
	$wait_emp+=$key['emp_waiting'];
	$incomplt+=$key['emp_incomplete'];
	$rej+=$key['emp_rej'];
	$not_send+=$key['emp_req_not_sent'];
	?>
  <tr style="border-color:#3E9B96;">
    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
    <td style="border-color:#3E9B96;"><?=$key['block_name']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_emp']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_finz']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_waiting']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_incomplete']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_rej']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['emp_req_not_sent']?></td>
  </tr>
  <? $cnt++;}  ?>
  <tr style="border-color:#3E9B96;">
  	<td colspan="2" style="text-align:center;font-weight:bold;border-color:#3E9B96;">TOTAL</td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$total_emp ?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$finz_emp?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$wait_emp?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$incomplt?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$rej?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$not_send?></td>
  </tr>
</table>

<style>
tr,td{
	border-color:#3E9B96;
}
</style>
