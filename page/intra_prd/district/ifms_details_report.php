<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$crypto = new cryptography();
$yemo=$crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
$db  = new database();

$arr=$db->fetch_table("select dist.district_name, block.block_name, block.block_code, bill.ifms_reference_no, bill.bill_no,bill.ifms_uploaded_date from 
prd_location_master_district as dist inner join prd_location_master_block as block on dist.district_id_pk=block.district_id_fk 
inner join prd_block_bill_details as bill on block.block_code=CAST(bill.block_code as integer )where bill.salary_monthyear='".$yemo."' and dist.district_code='".$_SESSION['user_info']['stake_user']."'
order by dist.district_name");





	$dist_name=$arr[0]['district_name'];
	$block_name=$arr[0]['block_name'];
	$bill_no=$arr[0]['bill_no'];
	$block_code=$arr[0]['block_code'];
	$upload_date=$arr[0]['ifms_uploaded_date'];
	$ref_no= $arr[0]['ifms_reference_no'];
	
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=IFMS_Uploaded_Details_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
  <tr style="border-color:#3E9B96;">
  <td colspan="6" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on IFMS Uploaded Details<br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
  <tr style="border-color:#3E9B96;">
    <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
    <th scope="col" width="200" align="center" style="border-color:#3E9B96;">BLOCK Name</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">BLOCK Code</th>
    <th scope="col" width="100" align="center" style="border-color:#3E9B96;">Bill No</th>
    <th scope="col" width="300" align="center" style="border-color:#3E9B96;">IFMS Reference No</th>
    <th scope="col" width="300" align="center" style="border-color:#3E9B96;">IFMS Uploaded Date </th>
  </tr>
  <? $cnt=1;
    foreach($arr as $key){
	 //if($key['district_code']!='99'){
	/*if($key['district_code']=='01') 
	continue;
	$total_block+=$key['total_block'];
	$finz_block+=$key['block_finz'];
	$wait_block+=$key['block_waiting'];
	$incomplt+=$key['block_incomplete'];
	$rej+=$key['block_rej'];
	$not_send+=$key['block_req_not_sent'];*/
	?>
  <tr style="border-color:#3E9B96;">
    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
   <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_name']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_code']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?=$key['bill_no']?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?php if($key['ifms_reference_no']!=''){ echo '&nbsp;'.$key['ifms_reference_no'];}else{ echo "Data Not Inserted"; } ?></td>
    <td style="text-align:center;border-color:#3E9B96;"><?php if($key['ifms_uploaded_date']!=''){ echo$key['ifms_uploaded_date'];}else{ echo "Data Not Inserted"; }?></td>
  </tr>
  <? $cnt++;}  ?>
  <!--<tr style="border-color:#3E9B96;">
  	<td colspan="2" style="text-align:center;font-weight:bold;border-color:#3E9B96;">TOTAL</td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$total_block ?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$finz_block?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$wait_block?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$incomplt?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$rej?></td>
    <td style="text-align:center;font-weight:bold;border-color:#3E9B96;"><?=$not_send?></td>
  </tr>-->
</table>

<style>
tr,td{
	border-color:#3E9B96;
}
</style>
