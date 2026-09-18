<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';

require '../../../includes/library/cryptography.class.php';

global $db, $crypto;
$db=new database();

$crypto = new cryptography();

$query = "SELECT ps_id from prd_gpf_subscriber_master
          WHERE ps_id != '0' GROUP BY ps_id";

$psSalarySubscriptionStatus = $db->fetch_obj($query); 
$psId = array();

foreach($psSalarySubscriptionStatus as $value)
{
	$psId[] = "'".$value->ps_id."'";  
}
$psId = implode(',', $psId);
$Query = "Select ps_name,ps_code from prd_location_master_panchayat_samiti WHERE ps_code IN (".$psId.")";
$psInfo = $db->fetch_obj($Query); 

$query = "SELECT a.drn_number, b.salary_monthyear, a.ps_id, a.ifms_ref_no, a.total_amount, a.opcode, a.treasury_code from prd_gpf_subscriber_master as a
          LEFT JOIN prd_block_bill_details as b
          ON a.drn_number = b.drn_number
          WHERE a.ps_id IN (".$psId.") AND b.salary_monthyear::integer > '202403'";
$psSalarySubscriptionStatus = $db->fetch_obj($query);
//print_r($psSalarySubscriptionStatus); exit;
$newpsSubscriptionData = array();
$psExtraData = array();
foreach($psSalarySubscriptionStatus as $item)
{
	$newpsSubscriptionData[$item->ps_id][$item->salary_monthyear] = $item;
	$psExtraData[$item->ps_id] = $item;
}
$psData = array();
foreach($psInfo as $item)
{ 
	//print_r($item->ps_code);
	//print_r($psExtraData[$item->ps_code]->opcode); 
	//print_r($psExtraData[$item->ps_code]->treasury_code); exit;
	$item->opcode = $psExtraData[$item->ps_code]->opcode;
	$item->treasury_code = $psExtraData[$item->ps_code]->treasury_code;
	$psData[$item->ps_code] = $item;
}
//print_r($psData); exit;

if (

	  !isset($_SESSION['user_info']['stake_user'])

	|| !isset($_SESSION['user_info']['stake_level'])

	|| !isset($_SESSION['user_info']['flag'])

	|| !isset($_SESSION['user_info']['stake_abbr'])

	



	){

	header('Location: '. $config['base_url'] . "page/login.php");

	exit;

}





if(!isset($_SERVER['HTTP_REFERER']))

{

    header('Location:'.$config['base_url']."page/errordoc.php?id=1");

    exit("Do not paste URL directly");

    

} 

elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 

{

    // substring is not found in string

    header('Location:'. $config['base_url']."page/errordoc.php?id=2");

    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");

}



	

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------
//Page variables

$common['title'] = " NGIPF Legacy PS Report  | PRD | Govt. of West Bengal ";
//---------------------------------- HEADER -----------------------------------------------------------------------------------

require '../../../page/layout/header.php';

//---------------------------------- MENU -------------------------------------------------------------------------------------

require '../../../page/layout/menu.php';


//Page variables

$common['title'] = " GPF Legacy  | PRD | Govt. of West Bengal ";

//---------------------------------- HEADER -----------------------------------------------------------------------------------
//$months = array('202304','202305','202306','202307','202308','202309','202310','202311','202312','202401','202402','202403','202404','202405','202406'); 
$months = array('202404','202405','202406','202407','202408'); 


?>
<div class="emplist">
  <div class="school">
      <div class="table-responsive">
      	<h2>Panchayat Samiti Legacy PF Subscription Report</h2>
<table class="table table-striped table-bordered dataTable no-footer">
	<head>
		<th>Sl</th>
		<th>Ps Name</th>
		<th>Op Code</th>
		<th>Treasury Code</th>
		<?php foreach($months as $month):?>
		<th><?php echo $month;?></th>
	    <?php endforeach;?>
	</head>
	<tbody>
		
			<?php 
            $counter = 1;
			foreach($psData as $key=>$item):?>
			<tr>
			<td><?php echo $counter; ?></td>
			<td><?php echo $item->ps_name;?>(<?php echo $key; ?>)</td>
			<td><?php echo $item->opcode;?></td>
			<td><?php echo $item->treasury_code;?></td>
				<?php foreach($months as $month):?>
				<td><?php echo isset($newpsSubscriptionData[$key][$month])?'Yes':'No';?>
					<p>(IFMS:<?php 
                        echo $newpsSubscriptionData[$key][$month]->ifms_ref_no; 
					?>)</p>
					<p>(Total:<?php 
                        echo $newpsSubscriptionData[$key][$month]->total_amount; 
					?>)</p>					
				</td>
			    <?php endforeach;?>	
			</tr>		
		    <?php $counter++; endforeach; ?>
		
	</tbody>
</table>
    </div>
   </div>
</div>



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------

require 'right_sidebar_dashboard.php';

//----------------------------------- FOOTER ----------------------------------------------------------------------------------

require '../../../page/layout/footer.php';

//----------------------------------------------------------------------------------------------------------------------------

?>