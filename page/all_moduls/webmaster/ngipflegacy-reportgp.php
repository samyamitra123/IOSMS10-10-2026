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

$query = "SELECT gp_id from prd_gpf_subscriber_master
          WHERE gp_id != '0' GROUP BY gp_id";

$gpSalarySubscriptionStatus = $db->fetch_obj($query); 
//print_r($gpSalarySubscriptionStatus); exit;
$gp_id = array();

foreach($gpSalarySubscriptionStatus as $value)
{
	$gpId[] = "'".$value->gp_id."'";  
}
$gpId = implode(',', $gpId);

$Query = "Select block_name,block_code from prd_location_master_block WHERE block_code IN (".$gpId.")";
$psInfo = $db->fetch_obj($Query); 
$psData = array();
foreach($psInfo as $item)
{
	$psData[$item->block_code] = $item->block_name;
}
//print_r($psData); exit;
$query = "SELECT a.drn_number, b.salary_monthyear, a.gp_id from prd_gpf_subscriber_master as a
          LEFT JOIN prd_block_bill_details as b
          ON a.drn_number = b.drn_number
          WHERE a.gp_id IN (".$gpId.") ";
$psSalarySubscriptionStatus = $db->fetch_obj($query);
$newpsSubscriptionData = array();
foreach($psSalarySubscriptionStatus as $item)
{
	$newpsSubscriptionData[$item->gp_id][$item->salary_monthyear] = $item;
}

//print_r($newpsSubscriptionData);

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
$months = array('202304','202305','202306','202307','202308','202309','202310','202311','202312','202401','202402','202403','202404','202405','202406'); 


?>
<div class="emplist">
  <div class="school">
      <div class="table-responsive">
      	<h2>Gram Panchayat Legacy PF Subscription Report</h2>
<table class="table table-striped table-bordered dataTable no-footer">
	<head>
		<th>Sl</th>
		<th>Block Name</th>
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
			<td><?php echo $item;?>(<?php echo $key; ?>)</td>
				<?php foreach($months as $month):?>
				<td><?php echo isset($newpsSubscriptionData[$key][$month])?'Yes':'No';?></td>
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