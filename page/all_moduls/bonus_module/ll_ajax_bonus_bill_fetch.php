<?
session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$db=new database();
$bonus_cat=$_GET['id'];
$finan_yr=$_GET['finan_yr'];

if($logged_user=='EO')
{
	$arr = $db->fetch_table("select distinct(bill_no), block_bill_pk from prd_block_bill_details 
	INNER JOIN prd_employee_bonus_details ON
	prd_block_bill_details.block_bill_pk=prd_employee_bonus_details.bill_id_fk
	INNER JOIN prd_bonus_type_details ON
	prd_bonus_type_details.bonus_type_id_pk=prd_employee_bonus_details.bonus_type_id_fk
	WHERE prd_bonus_type_details.bonus_category='".$bonus_cat."' AND prd_employee_bonus_details.monthyear='".$finan_yr."' AND prd_employee_bonus_details.ps_id_fk='".$_SESSION['location']['ps_id']."' AND prd_bonus_type_details.delete_status='1' AND prd_bonus_type_details.active_status in (0,1)");

}
else if($logged_user=='BDO')
{
	$arr = $db->fetch_table("select distinct(bill_no), block_bill_pk from prd_block_bill_details
	INNER JOIN prd_employee_bonus_details ON
	prd_block_bill_details.block_bill_pk=prd_employee_bonus_details.bill_id_fk
	INNER JOIN prd_bonus_type_details ON
	prd_bonus_type_details.bonus_type_id_pk=prd_employee_bonus_details.bonus_type_id_fk
	WHERE prd_bonus_type_details.bonus_category='".$bonus_cat."' AND prd_employee_bonus_details.monthyear='".$finan_yr."' AND prd_block_bill_details.block_code='".$_SESSION['user_info']['stake_user']."' AND prd_bonus_type_details.delete_status='1' AND prd_bonus_type_details.active_status in (0,1)");

}
?>
<select class="form-control" name="bonus_name" id="bonus_name" readonly style="cursor:pointer;">
<option value="">-Please Select-</option>
<?php foreach($arr as $key){ $key['code']. '<br />'; ?>
<option value="<?php echo $key['block_bill_pk']; ?>"><?php echo $key['bill_no']; ?></option>
<?php } ?>
</select>