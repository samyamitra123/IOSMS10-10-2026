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
	$type=$crypto->decode($_GET['type'],3);
	$db  = new database();

/*function fun_code($val)
	{
		$db = new database();
		$data = $db->fetch_table("SELECT description FROM prd_ifms_response_code_master where code='".$val."';");
		return $data[0]['description'];
	}
	
function fun_sftp_code($val)
{
		$db = new database();
		$data = $db->fetch_table("SELECT description FROM prd_ifms_status_code_master where code='".$val."';");
		return $data[0]['description'];
}
*/

if($stake=='gp')
{

	$condition="block.block_name,bill.drn_number,bill.bill_no,bill.bill_entry_time,bill.response_code,bill_sending_status,sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status ";
	$condition3="left join prd_location_master_block as block 
	on dist.district_id_pk=block.district_id_fk ";
	$condition4="left join prd_block_bill_details as bill on 
	CAST(block.block_code as character varying)=bill.block_code and bill.salary_monthyear='".$yemo."' and bill.requisition_type='1001' ";
	
}
else if($stake=='ps')
{
	$condition="ps.ps_name,pse.mobile_no,pse.treasury_code,bill.drn_number,bill.bill_no,bill.bill_entry_time,bill.response_code,bill.bill_sending_status,sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status ";
	$condition3="left join prd_location_master_panchayat_samiti as ps 
	on dist.district_id_pk=ps.district_id_fk ";
	$condition4="left join prd_block_bill_details as bill 
	on bill.ps_id_fk=ps.ps_id_pk  and bill.salary_monthyear='".$yemo."' and bill.requisition_type='1001' ";
	$condition5="left join psemp_ps_profile as pse 
	on bill.ps_id_fk=pse.ps_id_fk ";
	
}
else if($stake=='zp')
{
	$condition="bill.drn_number,zp.ddo_code,zp.cotract_no,bill.bill_no,bill.bill_entry_time,bill.response_code,bill_sending_status,sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status,bill.zp_emp_type";
	$condition4="left join prd_block_bill_details as bill 
	on dist.district_id_pk=bill.zp_id_fk  and bill.salary_monthyear='".$yemo."' and bill.requisition_type='1001' ";
	$condition5="left join zpemp_zp_profile as zp 
	on bill.zp_id_fk=zp.district_id_fk ";
}
	/*$arr=$db->fetch_table("SELECT dist.district_name,".$condisation."
	from 
	prd_location_master_district as dist
	".$condisation3."
	".$condisation4."
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk
	where dist.district_code<>'3219' and bill.salary_monthyear='".$yemo."' and sftp.active_status='1' and bill.requisition_type='1001'
	order by dist.district_name");*/
	if($type==1)
	{
	$arr=$db->fetch_table("SELECT dist.district_name,".$condition."
	from 
	prd_location_master_district as dist
	".$condition3."
	".$condition4."
	".$condition5."
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk and sftp.active_status='1' 
	where dist.district_code not in('3297','3295','3296','3299')
	order by dist.district_name");
	}
	else if($type==2)
	{


	$arr=$db->fetch_table("SELECT dist.district_name,".$condition."
	from 
	prd_location_master_district as dist
	".$condition3."
	".$condition4."
	".$condition5."
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk and active_status='1'
	where dist.district_code not in('3297','3295','3296','3299') and bill.status='1' 
and (sftp_benf_response_status is null or sftp_benf_response_status not in('5','8') )
 order by dist.district_name");
	}
	
	
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);  
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename=IFMS_Uploaded_Details_Report.xls');
	header("Content-Transfer-Encoding: binary");
	?>
	<table width="150" border="1" style="border-color:#3E9B96;">
    <tr style="border-color:#3E9B96;">
     <td colspan="11"  style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on IFMS Integration Status Details<br />(As on <?= date('d-m-Y h:i A');?>)</td>
    </tr>
	  <tr style="border-color:#3E9B96;">
        <th scope="col" width="45" align="center" style="border-color:#3E9B96;">SL.NO.</th>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;"><?php if($stake=="zp"){?>ZP NAME <? }else{?> DISTRICT NAME <? } ?></th>
       <?php if($stake=="gp" || $stake=="ps")
        {?>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;"><?php if($stake=="gp"){?>BLOCK NAME<? }else if($stake=="ps"){?>PS NAME<?         }?> 
        </th>
        <? } ?>
         <?php if($type=='2')
        {?>
        <th scope="col" width="140" align="center" style="border-color:#3E9B96;"> MOBILE NO</th>
          <th scope="col" width="140" align="center" style="border-color:#3E9B96;">TREASURY CODE</th>
      <? }?>
		<?php if($stake=='zp')
        { ?>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;">Employee Type</th>
        <? }?> 
        <th scope="col" width="140" align="center" style="border-color:#3E9B96;">DRN NO</th>
        <th scope="col" width="100" align="center" style="border-color:#3E9B96;">BILL NO</th>
        <th scope="col" width="100" align="center" style="border-color:#3E9B96;">BILL DATE</th>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;">BILL SUMMARY SENDING </th>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;">BENEFICIARY FILE SENDING </th>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;">PAYMENT MANDATE GENARATE</th>
         <?php if($type=='1')
        {?>
        <th scope="col" width="150" align="center" style="border-color:#3E9B96;">PAYMENT FILE RECEIVE</th>
        <? }?>
	  </tr>
	  <? $cnt=1;if(count($arr)){
		  
		   
		 /* for($i=0;$i<count($arr[0]['district_name']);$i++)
		{
			$emp_id_string.=$arr[$i]['district_name'];
			if($i=count($arr[0]['district_name']))
			{
				$emp_id_string=$emp_id_string.",";
			}
		}*/
		  
		foreach($arr as $key)
		{
		
			if($stake=="gp")
			{
				$stake_name=$key['block_name'];

			}
			else if($stake=="ps")
			{
				$stake_name=$key['ps_name'];
			}
			$district=$key['district_name'];
			
			$last_district_name = false;
			if(!$last_district_name || $district != $last_district_name) {
			$last_district_name = true;
			}
			$last_district_name = $district;
			
		?>
	  <tr style="border-color:#3E9B96;">
		<th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
		<td style="text-align:center;border-color:#3E9B96;"><? if($last_district_name) echo $district;?></td>
        <?php if($stake=="gp" || $stake=="ps")
        	{?>
		<td style="text-align:center;border-color:#3E9B96;"><?=$stake_name ?></td>
          <? }?>
           <?php if($type=='2')
        {?>
        <td style="text-align:center;border-color:#3E9B96;"><?php  if($stake=="ps"){ echo $key['mobile_no'];}else if($stake=='zp'){echo $key['cotract_no'];} ?></td>
          <td style="text-align:center;border-color:#3E9B96;"><?php if($stake=="ps"){ echo $key['treasury_code'];}else if($stake=='zp'){echo $key['ddo_code'];}  ?></td>
          <? }?>
           <?php if($stake=="zp")
			  {?>
            <td style="text-align:center;border-color:#3E9B96;"><?php if($key['zp_emp_type']=='366'){ echo "GOVERNMENT EMPLOYEE";}elseif($key['zp_emp_type']=='367'){echo "GRANT-IN -AID EMPLOYEE";}?></td>
              
           <? }?> 
        <td style="text-align:center;border-color:#3E9B96;"><?='&nbsp;'.$key['drn_number'] ?></td>
        <td style="text-align:center;border-color:#3E9B96;"><?=$key['bill_no'] ?></td>
        <td style="text-align:center;border-color:#3E9B96;"><?=$key['bill_entry_time'] ?></td>
        <td style="text-align:center;border-color:#3E9B96;"><?php if($key['bill_sending_status']=='2'){ echo "YES";}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
        <td style="text-align:center;border-color:#3E9B96;"><?php if($key['sftp_benf_sending_status']=='4' || $key['sftp_benf_sending_status']=='3'){ echo "YES";}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
        <td style="text-align:center;border-color:#3E9B96;"><?php if($key['sftp_benf_response_status']=='5' || $key['sftp_benf_response_status']=='8'){ echo "YES";}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
         <?php if($type=='1')
        {?>
        <td style="text-align:center;border-color:#3E9B96;"><?php if($key['sftp_benf_response_status']=='8'){ echo "YES";}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
        <? }?>
	  </tr>
	  <? $cnt++; }} else {?>
        <tr>
        <td colspan="11" style="color:red;font-weight:bold;text-align:center;">No Data Found</td>
        </tr>
        <? } ?>
	</table>
<style>
tr,td{
	border-color:#3E9B96;
}
</style>
