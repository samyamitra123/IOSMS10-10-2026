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
$rep_monthyr=$crypto->decode($_GET['rep_year'],3). $crypto->decode($_GET['rep_month'],3);
$district_id=$_GET['district_id'];


$db  = new database();


$arr=$db->fetch_table("SELECT 
											block.block_id_pk,
											block.block_name,
											count(gp_id_pk) as total_gp,
											bill.block_bill_pk,
											bill.bill_no,
											bill.bill_entry_time
										FROM
											prd_location_master_block AS block
										INNER JOIN
											prd_location_master_gp gp
										ON
											block.block_id_pk=gp.block_id_fk
										LEFT JOIN
											(SELECT block_bill_pk,block_code,bill_no,bill_entry_time FROM prd_block_bill_details WHERE salary_monthyear='".$rep_monthyr."' AND status='1' AND requisition_type='1001') AS bill
										ON
											CAST(block.block_code as character varying)=bill.block_code
										WHERE block.district_id_fk='".$district_id."'
										GROUP BY 
											block.block_id_pk,block.block_name,bill.block_bill_pk,bill.bill_no,bill.bill_entry_time");


	
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Bill_Details_Report.xls');
header("Content-Transfer-Encoding: binary");
?>
<table width="200" border="1" style="border-color:#3E9B96;">
    <tr style="border-color:#3E9B96;">
    	<td colspan="4" style="font-weight:bold;font-size:18px;text-align:center;border-color:#3E9B96;">Report on Bill Details of <?php echo $_SESSION['location']['district_name']; ?><br />(As on <?= date('d-m-Y h:i A');?>)</td>
    </tr>
    <tr style="border-color:#3E9B96;">
        <th scope="col" width="50" align="center" style="border-color:#3E9B96;">SL.NO.</th>
        <th scope="col" width="200" align="center" style="border-color:#3E9B96;">BLOCK NAME</th>
        <th scope="col" width="200" align="center" style="border-color:#3E9B96;">TOTAL GP</th>
        <th scope="col" width="300" align="center" style="border-color:#3E9B96;">BILL GENERATED </th>
    </tr>
    <? $cnt=1;
    foreach($arr as $key)
	{
    
    ?>
        <tr style="border-color:#3E9B96;">
            <th scope="row" style="border-color:#3E9B96;"><?=$cnt;?></th>
            <td style="text-align:center;border-color:#3E9B96;"><?=$key['block_name']?></td>
            <td style="text-align:center;border-color:#3E9B96;"><?=$key['total_gp']?></td>
            <td style="text-align:center;border-color:#3E9B96;"><?php if($key['block_bill_pk']!=''){ echo "YES";}else{ echo "NO";} ?></td>
        </tr>
        <? $cnt++;
	}  ?>

</table>

<style>
tr,td{
	border-color:#3E9B96;
}
</style>
