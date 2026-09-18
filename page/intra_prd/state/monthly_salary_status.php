<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();


/*echo "<pre>";
print_r($_SESSION);
echo "</pre>"; die;*/
			
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
//echo $monthyear; die;
$db = new database();
/*$cnt=$db->fetch_table("Select count(*) as count from ehrms_teacher_salary_save_primary sal 
						   WHERE salary_monthyear='".$monthyear."'
						   AND sal.is_saved='1' AND sal.status_flag='3' AND sal.category_id='1'");*/
if(date('Ym')==$monthyear){
$arr = $db->fetch_table("select dist.district_id_pk,district_name,

count(distinct(block.block_id_pk)) as total_block,

count(distinct case when (sal.status_flag='3' and sal.salary_monthyear='201609' and sal.is_saved='1' AND sal.category_id='1') then block_id_pk else null end) as block_lock,

count(distinct case when (bill.status='1' and bill.salary_monthyear='201609' ) then block_id_pk else null end) as block_bill_yes,

count(distinct case when (CAST(bill.block_code as integer)<>block.block_code)then block.block_code else null end) as block_bill_no  from

prd_location_master_district dist
left join prd_location_master_block block on block.district_id_fk=dist.district_id_pk
left join prd_employee_salary_save sal on CAST(sal.block_code as integer)=block.block_code
left join prd_block_bill_details bill on CAST(bill.block_code as integer)=block.block_code and sal.salary_monthyear='201609'
left join prd_block_bill_details bill1 on CAST(bill1.block_code as integer)<>block.block_code and sal.salary_monthyear='201609'
where sal.is_saved=1 and sal.delete_status=1 and sal.status_flag=3
group by dist.district_name,dist.district_id_pk
order by dist.district_name
");
}else{
$arr = $db->fetch_table("select dist.district_id_pk,district_name,

count(distinct(block.block_id_pk)) as total_block,

count(distinct case when (sal.status_flag='3' and sal.salary_monthyear='201609' and sal.is_saved='1' AND sal.category_id='1') then block_id_pk else null end) as block_lock,

count(distinct case when (bill.status='1' and bill.salary_monthyear='201609' ) then block_id_pk else null end) as block_bill_yes,

count(distinct case when (CAST(bill.block_code as integer)<>block.block_code)then block.block_code else null end) as block_bill_no  from

prd_location_master_district dist
left join prd_location_master_block block on block.district_id_fk=dist.district_id_pk
left join prd_monthly_salary_archive_final sal on CAST(sal.block_code as integer)=block.block_code
left join prd_block_bill_details bill on CAST(bill.block_code as integer)=block.block_code and sal.salary_monthyear='201609'
left join prd_block_bill_details bill1 on CAST(bill1.block_code as integer)<>block.block_code and sal.salary_monthyear='201609'
group by dist.district_name,dist.district_id_pk
order by dist.district_name
");
}


header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Salary_Submission_Status_Report.xls');
header("Content-Transfer-Encoding: binary");
?>

<table border="1" width="60%" style="border-color:#77C4E7">
	<tr style="border-color:#77C4E7">
  <td colspan="6" style="font-weight:bold;font-size:18px;text-align:center;border-color:#77C4E7">District Wise Salary Finalization and Salary Locking For The Month of  <?= GetMonthString($crypto->decode($_GET['mo'],3)).",".$crypto->decode($_GET['ye'],3) ?> <br />(As on <?= date('d-m-Y h:i A');?>)</td>
  </tr>
    <tr class="th_color" style="border-color:#77C4E7">
    <th style="border-color:#77C4E7">SL NO.</th>
    <th style="border-color:#77C4E7">DISTRICT NAME</th>
    <th style="border-color:#77C4E7">TOTAL <br /> BLOCK</th>
    <th style="border-color:#77C4E7">TOTAL <br /> BLOCK <br /> LOCKED</th>
    <th style="border-color:#77C4E7">TOTAL <br /> BLOCK <br /> BILL <br /> GENERATE</th>
    <th style="border-color:#77C4E7">BLOCK <br /> BILL <br /> NOT <br /> GENERATED</th>
    </tr>
<? $cnt=1;$total_block=0;$block_lock=0;$block_bill_yes=0; $block_bill_no=0; 
foreach ($arr as $key) {

if($key['district_id_pk']=='99' || $key['district_id_pk']=='1'){
	continue;
}
?>
<tr style="border-color:#77C4E7">
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $cnt;?></td>
<td style="border-color:#77C4E7"><?= $key['district_name']?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $total_block+=$key['total_block']; echo $key['total_block'];?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $block_lock+=$key['block_lock']; echo $key['block_lock'];?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $block_bill_yes+=$key['block_bill_yes']; echo $key['block_bill_yes'];?></td>
<td style="border-color:#77C4E7;text-align:center;"><? $block_bill_no=$key['block_bill_no'];echo $key['block_bill_no'];?></td>
<!--<td style="border-color:#77C4E7;text-align:center;font-weight:bold;"><? //if($key['total_circle']=$key['lock_circle'] && $key['total_circle']>0){
			//echo 'LOCKED';
		//}else{
			//echo '------';
			 // }?>
</td>-->
</tr>
<? $cnt++; } ?>
<tr style="border-color:#77C4E7">
<td style="border-color:#77C4E7;font-weight:bold;text-align:right;" colspan="2">TOTAL</td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $total_block;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $block_lock;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $block_bill_yes;?></td>
<td style="border-color:#77C4E7;font-weight:bold;text-align:center;"><?= $block_bill_no;?></td>
</tr>
</table>