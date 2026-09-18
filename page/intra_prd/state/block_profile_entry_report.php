<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

$db  = new database();
$arr=$db->fetch_table("SELECT district_name,district_code,
count(distinct CASE WHEN '1' then em1.block_id_pk else null end) as total_block,
count(distinct CASE WHEN (em1.block_status='1') then em1.block_id_pk else null end) as block_waiting,
count(distinct CASE WHEN (em1.block_status='2') then em1.block_id_pk else null end) as block_finz,
count(distinct CASE WHEN (em1.block_status='3') then em1.block_id_pk else null end) as block_rej,
count(distinct CASE WHEN (em1.block_status='4') then em1.block_id_pk else null end) as block_incomplete,
count(distinct CASE WHEN (em1.block_status='0') then em1.block_id_pk else null end) as block_req_not_sent 
from prd_location_master_district dist 
LEFT JOIN prd_location_master_block em1 ON em1.district_id_fk=dist.district_id_pk where district_code<>'3299'
GROUP BY district_name,district_code,district_id_pk order by district_code
");

header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Block_Profile_Entry_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
  <tr style="border-color:#3E9B96;">
  <td colspan="8" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on BLOCK Profile Entry, Update and Approval<br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
  <tr style="border-color:#3E9B96;">
    <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
    <th scope="col" width="250" align="center" style="border-color:#3E9B96;">DISTRICT NAME</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Total BLOCK</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Approved BLOCK</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Waiting For Approval</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">BLOCK Profile Incomplete </th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Rejected BLOCK Profile</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Request Not Sent</th>
  </tr>
  <? $cnt=1;$total_emp=0;$finz_emp=0;$wait_emp=0;$incomplt=0;$rej=0;$not_send=0;
    foreach($arr as $key){
	 //if($key['district_code']!='99'){
	if($key['district_code']=='01') 
	continue;
	$total_block+=$key['total_block'];
	$finz_block+=$key['block_finz'];
	$wait_block+=$key['block_waiting'];
	$incomplt+=$key['block_incomplete'];
	$rej+=$key['block_rej'];
	$not_send+=$key['block_req_not_sent'];
	?>
  <tr style="border-color:#3E9B96;">
    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
    <td style="border-color:#3E9B96;"><?=$key['district_name']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_block']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_finz']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_waiting']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_incomplete']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_rej']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_req_not_sent']?></td>
  </tr>
  <? $cnt++;}  ?>
  <tr style="border-color:#3E9B96;">
  	<td colspan="2" style="text-align:center;font-weight:bold;border-color:#3E9B96;">TOTAL</td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$total_block ?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$finz_block?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$wait_block?></td>
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
