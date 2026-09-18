<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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


if($logged_user=='zpacc')
{
	$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];
}
if($logged_user=='DA')
{
	$stake_clause='ps_id_fk='.$_SESSION['location']['ps_id'];
}
if($logged_user=='GP')
{
	$stake_clause='block_code='.substr($_SESSION['user_info']['stake_user'],0,7);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cryptoGraph=new cryptography();

$db=new database();

$year=$_GET['year'];
$month=$_GET['month'];
$type=$cryptoGraph->decode($_GET['type'],4);
$emp_id=$cryptoGraph->decode($_GET['id'],4);
$ropa_status=$cryptoGraph->decode($_GET['ropa'],4);
	
	if($ropa_status ==''){
		$ropa_status = 0;
	}

if($type=='all')
{
	$payment_file_checking=$db->fetch_table(" SELECT block_bill_pk 
											FROM prd_block_bill_details bill 
											INNER JOIN prd_sftp_benf_upload_response sftp 
											ON bill.block_bill_pk=sftp.bill_id_fk
											WHERE sftp.sftp_benf_response_status='8' AND  AND ropa_status='".$ropa_status."' bill.salary_monthyear='".$year.$month."' 
											AND ".$stake_clause);
}
else
{
	$payment_file_checking=$db->fetch_table(" SELECT archive_final_pk 
											FROM prd_monthly_salary_archive_final
											WHERE emp_id_fk='".$emp_id."' AND salary_monthyear='".$year.$month."' AND payment_status='11' ");
}

if(count($payment_file_checking)>0)
{
	echo 1;
}
else
{
	echo 1;
}



?>
