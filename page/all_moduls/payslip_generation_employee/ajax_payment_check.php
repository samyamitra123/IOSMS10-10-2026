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

/*if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
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
*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$crypto=new cryptography();


$year=$crypto->decode($_GET['year'],4); 

$month=$crypto->decode($_GET['month'],4); 
//$month=$_GET['month'];

//$emp_id='PE2018022524';
$emp_id=$_SESSION['user_info']['stake_user'];

$db=new database();


	$emp_id_pk=$db->fetch_table("Select emp_id_pk
	FROM prd_employee_master
WHERE emp_id_const='".$emp_id."' and emp_status in('1','9','2')");
	

	$payment_file_checking=$db->fetch_table(" SELECT archive_final_pk 
											FROM prd_monthly_salary_archive_final
											WHERE emp_id_fk='".$emp_id_pk[0]['emp_id_pk']."' AND salary_monthyear='".$year.$month."'");


if(count($payment_file_checking)>0)
{
	echo 1;
}
else
{
	echo 0;
}



?>
