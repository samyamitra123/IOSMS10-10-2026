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

$stake=$crypto->decode($_GET['stk'],3);
$yemo=$crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
$db  = new database();

if($stake=='gp')
{
	$condition="count(distinct CASE WHEN (block.block_status in ('0','1','2','3','4','5','6')) 
	then block.block_code else null end) as total_block,";
	$condition2="count(distinct CASE WHEN  (bill.status='1' and salary_monthyear='".$yemo."' and requisition_type='1001') then bill.block_bill_pk  else null end) as total_bill_generate,";
	$condition3="left join prd_location_master_block as block 
	on dist.district_id_pk=block.district_id_fk ";
	$condition4="left join prd_block_bill_details as bill on 
	CAST(block.block_code as character varying)=bill.block_code";
	$condition5="count(distinct CASE WHEN  (bill.ifms_status='1' and salary_monthyear='".$yemo."' ) then bill.block_bill_pk  else null end) as total_ifms_up";
	$zp_emp_type=" ";	
	$where_condition=" ";	
}
else if($stake=='ps')
{
	$condition="count(distinct CASE WHEN  (ps.ps_status in ('0','1','2','3','7','8','9')) then ps.ps_id_pk  else null end) as total_ps,";
	$condition2="count(distinct CASE WHEN  (bill.status='1' and salary_monthyear='".$yemo."' and requisition_type='1001') then bill.block_bill_pk  else null end) as total_bill_generate,";
	$condition3="left join prd_location_master_panchayat_samiti as ps 
	on dist.district_id_pk=ps.district_id_fk ";
	$condition4="left join prd_block_bill_details as bill 
	on bill.ps_id_fk=ps.ps_id_pk ";
	$condition5="count(distinct CASE WHEN  (bill.ifms_status='1' and salary_monthyear='".$yemo."' and requisition_type='1001') then bill.block_bill_pk  else null end) as total_ifms_up";
	$zp_emp_type=" ";	
	$where_condition=" ";	
}
else if($stake=='zp')
{	
	$condition=" ";
	//$condition="count(distinct CASE WHEN  (dist.zp_status in ('1','2','3','4','5','7')) then dist.district_id_pk  else null end) as total_zp,";
	$condition2="count(distinct CASE WHEN  (bill.status='1' and salary_monthyear='".$yemo."' and requisition_type='1001'and (bill.zp_emp_type='367' or bill.zp_emp_type='366' )) then bill.block_bill_pk  else null end) as total_bill_generate,";
	$condition3=" ";
	$condition4="left join prd_block_bill_details as bill 
	on bill.zp_id_fk=dist.district_id_pk ";
	$condition5="count(distinct CASE WHEN  (bill.ifms_status='1' and salary_monthyear='".$yemo."' and requisition_type='1001' and (bill.zp_emp_type='367' or bill.zp_emp_type='366' )) then bill.block_bill_pk  else null end) as total_ifms_up";
	$zp_emp_type="and (bill.zp_emp_type='367' or bill.zp_emp_type='366' )";	
	$where_condition=" and (dist.zp_status in ('1','2','3','4','5','7'))";	
	
}
if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
{




	$arr=$db->fetch_table("SELECT dist.district_name,".$condition."".$condition2."
	count(distinct CASE WHEN (bill.bill_sending_status='2' and bill.status='1' and bill.salary_monthyear='".$yemo."' and requisition_type='1001'".$zp_emp_type.")
	then bill.block_bill_pk else null end) as total_bill_sending,
	count(distinct CASE WHEN (sftp.sftp_benf_sending_status in('3','4') and bill.salary_monthyear='".$yemo."' and requisition_type='1001' and sftp.active_status='1' ".$zp_emp_type.")
	then sftp.sftp_benf_id_pk else null end) as total_ben_sending,
	count(distinct CASE WHEN (sftp.sftp_benf_response_status in('5','8') and bill.salary_monthyear='".$yemo."' and requisition_type='1001' and sftp.active_status='1'".$zp_emp_type." )
	then sftp.sftp_benf_id_pk else null end) as total_payment_genarate,
	
	count(distinct CASE WHEN (sftp.sftp_benf_response_status in('8') and bill.salary_monthyear='".$yemo."' and sftp.active_status='1'".$zp_emp_type." )
	then sftp.sftp_benf_id_pk else null end) as total_payment_file_genarate
	from 
	prd_location_master_district as dist
	".$condition3."
	".$condition4."
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk
	where dist.district_code not in('3297','3295','3296','3299','3298')".$where_condition."
	group by dist.district_name order by dist.district_name");
}
else
{
	$arr=$db->fetch_table("SELECT dist.district_name,".$condition."".$condition2." ".$condition5."
	from 
	prd_location_master_district as dist
	".$condition3."
	".$condition4."
	where dist.district_code not in('3297','3295','3296','3299','3298')
	group by dist.district_name order by dist.district_name");
	
}
	/*$dist_name=$arr[0]['district_name'];
	$total_block=$arr[0]['total_block'];
	$total_bill_generate=$arr[0]['total_bill_generate'];
	$total_ifms_up=$arr[0]['total_bill_sending'];
	$total_ben_sending=$arr[0]['total_ben_sending'];
	$total_ifms_up_prev=$arr[0]['total_ifms_up'];
	$total_payment_genarate=$arr[0]['total_payment_genarate'];
	$total_payment_file_genarate=$arr[0]['total_payment_file_genarate']; */
	
 $all_total_bill_generate=$all_total_ifms_up=$all_total_ben_sending=$all_total_ifms_up_prev=$all_payment_genarate=$all_payment_file_genarate=0;
		
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);  
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename=IFMS_Uploaded_Details_Report.xls');
	header("Content-Transfer-Encoding: binary");
	?>
    <table width="200" border="1" style="border-color:#3E9B96;">
        <tr  style="border-color:#3E9B96;">
     <td <?php if(($stake=='gp') || ($stake=='ps')){?> colspan="8" <? }else if($stake=='zp'){?> colspan="7"<?php }?> style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on IFMS Uploaded Details<br />(As on <?= date('d-m-Y h:i A');?>)
            </td>
        </tr>
        <tr style="border-color:#3E9B96;">
                <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
                <th scope="col" width="300" align="center" style="border-color:#3E9B96;">DISTRICT NAME</th>
                <?php if(($stake=='gp') || ($stake=='ps')){?>
                <th scope="col" width="200" align="center" style="border-color:#3E9B96;"><?php  if($stake=="gp"){?>TOTAL BLOCK<? }else if($stake=="ps"){?>TOTAL PS<? }?> </th>
                <? }?>
                <th scope="col" width="100" align="center" style="border-color:#3E9B96;">TOTAL BILL GENERATE</th>
                <th scope="col" width="300" align="center" style="border-color:#3E9B96;"><?php if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp')){?> TOTAL BILL FILE SENDING<?php }else{?>IFMS UPLOADED<? } ?></th>
            <?php if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
			{?>
                <th scope="col" width="100" align="center" style="border-color:#3E9B96;">TOTAL BENEFICIARY FILE SENDING</th>
                <th scope="col" width="100" align="center" style="border-color:#3E9B96;">TOTAL PAYMENT MANDET GENERATE</th>
                <th scope="col" width="100" align="center" style="border-color:#3E9B96;">TOTAL PAYMENT FILE RECIVE</th>
            <? }?>
        </tr>
        <? $cnt=1;
		$all_total_block = "";
        foreach($arr as $key)
        {
				
			$all_total_bill_generate+=$key['total_bill_generate'];
			if(!empty($key['total_bill_sending'])){
				$all_total_ifms_up+=$key['total_bill_sending'];
			}
			else{
				$all_total_ifms_up += 0;
			}
			
			if(!empty($key['total_ben_sending'])){
				$all_total_ben_sending+=$key['total_ben_sending'];
			}
			else{
				$all_total_ben_sending += 0;
			}
			
			if(!empty($key['total_ifms_up'])){
				$all_total_ifms_up_prev+=$key['total_ifms_up'];
			}
			else{
				$all_total_ifms_up_prev += 0;
			}
			
			//$all_total_ifms_genarate+=$key['total_ifms_genarate'];
			if(!empty($key['total_payment_genarate'])){
				$all_payment_genarate+=$key['total_payment_genarate'];
			}
			else{
				$all_payment_genarate +=0;
			}
			
			if(!empty($key['total_payment_file_genarate'])){
				$all_payment_file_genarate+=$key['total_payment_file_genarate'];
			}
			else{
				$all_payment_file_genarate +=0;
			}
			
			$all_total_block += 0;
			if($stake=="gp")
			{
				$total_stake=$key['total_block'];
				$all_total_block+=$key['total_block'];
			}
			else if($stake=="ps")
			{
				$total_stake=$key['total_ps'];
				$all_total_block+=$key['total_ps'];
			}
			//var_dump($all_total_block); 
		 ?>
			<tr style="border-color:#3E9B96;">
                    <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$key['district_name']?></td>
                    <?php if(($stake=='gp') || ($stake=='ps')){?>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$total_stake ?></td>
                    <? }?>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_bill_generate']?></td>
                <?php if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
                { ?>
                	<td style="text-align:center;border-color:#3E9B96;"><?='&nbsp;'.$key['total_bill_sending']?></td>
             <? }else
				{?>
               		<td style="text-align:center;border-color:#3E9B96;"><?='&nbsp;'.$key['total_ifms_up']?></td>
           <?php }?>
                <?php if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
				{?>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_ben_sending']?></td>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_payment_genarate']?></td>
                    <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_payment_file_genarate']?></td>
                    
              <? }?>
			</tr>
			<? $cnt++;
		}  ?>
            <tr style="text-align:center;font-weight:bold;">
                    <td colspan="2" style="text-align:right;">TOTAL</td>
                     <?php if($stake=='gp' || $stake=='ps'){?>
                    <td><?= $all_total_block?></td>
                    <? } ?>
                    <td><?= $all_total_bill_generate?></td>
                <?php
                if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
                {?>
                    <td><?= $all_total_ifms_up?></td>
             <? }else
                { ?>
                    <td><?= $all_total_ifms_up_prev?></td>
             <? } 
                if(($yemo>201808 && $stake=='ps') || ($yemo>201810 && $stake=='gp') || ($yemo>201809 && $stake=='zp'))
                {?>
                    <td><?= $all_total_ben_sending?></td>
                    <td><?= $all_payment_genarate?></td>
                    <td><?=$all_payment_file_genarate?></td>
             <? }?>
            </tr>
       </table>

	
<style>
tr,td{
	border-color:#3E9B96;
}
</style>
