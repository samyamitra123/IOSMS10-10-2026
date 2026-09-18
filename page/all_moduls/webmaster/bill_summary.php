<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$db=new database();
$post =$_POST;
//print_r($post); 
if(isset($post['search']) && $post['billfor'] != '')
{
	switch($post['billfor'])
	{
		case 'block':
		  $Join = "LEFT JOIN prd_location_master_block as ps ON  CAST(pbbd.block_code as INT) = ps.block_code";
		  $wCondition = "CAST(pbbd.block_code as INT) !=0";
		  $select = "ps.block_name as location";		
		break;
		case 'ps':
		  $Join = "LEFT JOIN prd_location_master_panchayat_samiti as ps ON pbbd.ps_id_fk = ps.ps_id_pk";
		  $wCondition = "pbbd.ps_id_fk !=0";
		  $select = "ps.ps_name as location";
		break;
		case 'zp':
		  $Join = "LEFT JOIN prd_location_master_district as ps ON pbbd.zp_id_fk  = ps.district_id_pk";
		  $wCondition = "pbbd.zp_id_fk !=0";
		  $select = "ps.district_name as location";		
		break;				
	}
	$selectArray = array(
		                   'pbbd.bill_no',
		                   'dice.description',
		                   'pbbd.drn_number',
		                   'psbur.total_beneficiary',
		                   'psbur.total_amount',
		                    $select
	                    );
	$selectArray = implode(', ',$selectArray);
	$Query = "SELECT ".$selectArray." from prd_block_bill_details as pbbd
	          LEFT JOIN prd_sftp_benf_upload_response as psbur ON pbbd.block_bill_pk = psbur.bill_id_fk
	          ".$Join."
	          LEFT JOIN prd_dise_code_master as dice ON pbbd.requisition_type = CAST(dice.code as INT)
	          WHERE ".$wCondition." AND pbbd.salary_monthyear='".date('Ym')."' AND pbbd.bill_sending_status=2";
	//print($Query); exit;
	$psSalaryBill = $db->fetch_obj($Query);
}
//print_r($psSalaryBill); exit;

//redirect to login page when login session not found
/*if (
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
}*/
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------


?>
<!-- Common Back Button --->
<div class="content">
    <div style="padding:10px">
        <?php require '../../common_back_btns.php'; ?>
    </div>
    <div class="mainContent float_l" style="min-height: 400px;">
        <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
        <h3>
        <? echo $_SESSION['location']['state_name'];;
        ?></h3>
        </div>
            
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        	<form name="Bill Search" method="POST">
        	<select name="billfor" required>
        		<option>SELECT Bill FOR</option>
        		<option value="block">BLOCK</option>
        		<option value="ps">PS</option>
        		<option value="zp">DISTRICT</option>
        	</select>
        	<input type="submit" name="search" value="Search">
            </form>
            <code>
            	<?php echo json_encode($psSalaryBill); ?>
            </code>
        </div>
    </div>    
</div>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>