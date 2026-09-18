<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

$db  = new database();
/*echo ("SELECT district_name,district_code, 
count(distinct CASE WHEN '1' then ps.ps_id_pk else null end) as total_ps,
 count(distinct CASE WHEN (ps.ps_status='1') then ps.ps_id_pk else null end) as ps_waiting, 
 count(distinct CASE WHEN (ps.ps_status='2') then ps.ps_id_pk else null end) as ps_finz, 
 count(distinct CASE WHEN (ps.ps_status='3') then ps.ps_id_pk else null end) as ps_rej,
  count(distinct CASE WHEN (ps.ps_status='4') then ps.ps_id_pk else null end) as ps_incomplete, 
  count(distinct CASE WHEN (ps.ps_status='0') then ps.ps_id_pk else null end) as ps_req_not_sent
   from prd_location_master_district dist LEFT JOIN prd_location_master_panchayat_samiti ps ON SUBSTRING (CAST(ps_code AS text),1,4)=CAST(district_code AS text) where district_code<>'3299' and district_code<>'3298' GROUP BY district_name,district_code,district_id_pk order by district_name asc
");die;*/
$arr=$db->fetch_table("SELECT district_name,district_code, 
count(distinct CASE WHEN '1' then ps.ps_id_pk else null end) as total_ps,
 count(distinct CASE WHEN (ps.ps_status='1') then ps.ps_id_pk else null end) as ps_waiting, 
 count(distinct CASE WHEN (ps.ps_status='2') then ps.ps_id_pk else null end) as ps_finz, 
 count(distinct CASE WHEN (ps.ps_status='3') then ps.ps_id_pk else null end) as ps_rej,
  count(distinct CASE WHEN (ps.ps_status='4') then ps.ps_id_pk else null end) as ps_incomplete, 
  count(distinct CASE WHEN (ps.ps_status='0') then ps.ps_id_pk else null end) as ps_req_not_sent
   from prd_location_master_district dist LEFT JOIN prd_location_master_panchayat_samiti ps ON SUBSTRING (CAST(ps_code AS text),1,4)=CAST(district_code AS text)  GROUP BY district_name,district_code,district_id_pk order by district_name asc
");


header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=PS_Profile_Entry_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
  <tr style="border-color:#3E9B96;">
  <td colspan="8" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on PS Profile Entry, Update and Approval<br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
  <tr style="border-color:#3E9B96;">
    <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
    <th scope="col" width="250" align="center" style="border-color:#3E9B96;">DISTRICT NAME</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Total PS</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Approved By EO</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Waiting For Approval At Eo</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">PS Profile Incomplete </th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">PS Profile Rejected By Eo</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Request Not Sent</th>
  </tr>
  <? $cnt=1;$total_ps=0;$finz_ps=0;$wait_ps=0;$incomplt=0;$rej=0;$not_send=0;
    foreach($arr as $key){
	 //if($key['district_code']!='99'){
	if($key['district_code']=='01' || $key['district_code']=='99') 
	continue;
	$total_ps+=$key['total_ps'];
	$finz_ps+=$key['ps_finz'];
	$wait_ps+=$key['ps_waiting'];
	$incomplt+=$key['ps_incomplete'];
	$rej+=$key['ps_rej'];
	$not_send+=$key['ps_req_not_sent'];
	?>
  <tr style="border-color:#3E9B96;">
    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
    <td style="border-color:#3E9B96;"><?=$key['district_name']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_ps']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['ps_finz']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['ps_waiting']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['ps_incomplete']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['ps_rej']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['ps_req_not_sent']?></td>
  </tr>
  <? $cnt++;}  ?>
  <tr style="border-color:#3E9B96;">
  	<td colspan="2" style="text-align:center;font-weight:bold;border-color:#3E9B96;">TOTAL</td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$total_ps ?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$finz_ps?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$wait_ps?></td>
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
