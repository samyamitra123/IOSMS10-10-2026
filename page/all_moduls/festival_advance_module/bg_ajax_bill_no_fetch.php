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

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='429'");
$requisition_type=$requisition[0]['code'];

$year=$_GET['year'];
if($year!='')
{
	if($logged_user=='EO')
	{
		$arr = $db->fetch_table("select bill_no,block_bill_pk from prd_block_bill_details where substr(salary_monthyear,1,4)='".$year."' AND ps_id_fk='".$_SESSION['location']['ps_id']."' AND status='1' AND requisition_type='".$requisition_type."'");
	}
	else if($logged_user=='BDO')
	{
		$arr = $db->fetch_table("select bill_no,block_bill_pk from prd_block_bill_details where substr(salary_monthyear,1,4)='".$year."' AND block_code='".$_SESSION['user_info']['stake_user']."' AND status='1' AND requisition_type='".$requisition_type."'");
	}
	else if($logged_user=='zpacc')
	{
		$arr = $db->fetch_table("select bill_no,block_bill_pk from prd_block_bill_details where substr(salary_monthyear,1,4)='".$year."' AND zp_id_fk='".$_SESSION['location']['district_id']."' AND status='1' AND requisition_type='".$requisition_type."'");
	}
	if(count($arr)>0)
	{
		echo '<option value="">-Please Select-</option>';
		foreach($arr as $key)
		{ 
			$key['block_bill_pk']. '<br />'; ?>
			<option value="<?php echo $key['block_bill_pk']; ?>"><?php echo $key['bill_no']; ?></option>
		<?php 
		}
	}
	else
	{
		echo '<option value="">-Please Select-</option>';
	}
}
else
{
	echo '<option value="">-Please Select-</option>';
}
?>
