<?
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';     
//require '../../page_visite.php';


error_reporting(0);


$cryptoGraph=new cryptography();
$db=new database();

$month=$cryptoGraph->decode($_GET['month'],4);
$year=$cryptoGraph->decode($_GET['year'],4);
//$bill_number=$db->fetch_table("select municipality_bill_pk,bill_no FROM mad_municipality_bill_details where salary_monthyear='".$year.$month."' ORDER BY municipality_bill_pk ASC");
/*$bill_number=$db->fetch_table("select municipality_bill_pk,bill_no FROM mad_municipality_bill_details where salary_monthyear='".$year.$month."' AND requisition_type = '1003' ORDER BY municipality_bill_pk ASC");*/
$logged_user=$_SESSION['user_info']['stake_abbr']; 
$bill_number=$db->fetch_table("SELECT * FROM prd_block_bill_details
		WHERE  zp_id_fk='".$_SESSION['location']['district_id']."'
		AND salary_monthyear ='".$year.$month."'
		ORDER BY bill_serial_no ASC");
//print_r($bill_number);


?>
<option value="">--Please Select--</option>
<?php
foreach($bill_number as $bills)
{
?>

    
    <option value="<?=$bills['bill_serial_no']?>"><?php echo $bills['bill_serial_no']; ?></option>
<?php	
}
//$arr=$db->fetch_table("select payscale_code,payscale_range from mad_dise_payscale_master where payband_id_fk='".$id."'");
?>
<?php @pg_close($con); ?>