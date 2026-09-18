<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$db=new database();
$monthYear = date('Ym',strtotime('-1 month'));
$Query = "SELECT emp_id_fk, oid from prd_employee_salary_save WHERE salary_monthyear='".$monthYear."' order by oid asc";

$SalaryLastData = $db->fetch_table($Query);
$NewData = array();
foreach($SalaryLastData as $slData)
{
	$NewData[$slData['emp_id_fk']] = "'".$slData['oid']."'";
}
	$Query = "UPDATE prd_employee_salary_save SET status_flag='0', delete_status='0' WHERE salary_monthyear='".$monthYear."'";
	$db->update($Query);
    $Query = "UPDATE prd_employee_salary_save SET status_flag='4', delete_status='1', is_saved=1 WHERE oid IN (".implode(', ',$NewData).")";
	$db->update($Query);

	
$Query = "SELECT emp_id_fk, archive_final_pk from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthYear."' order by archive_final_pk asc";

$SalaryLastData = $db->fetch_table($Query);
$NewData = array();
foreach($SalaryLastData as $slData)
{
	$NewData[$slData['emp_id_fk']] = "'".$slData['archive_final_pk']."'";
}
	$Query = "UPDATE prd_monthly_salary_archive_final SET status_flag='0', delete_status='0' WHERE salary_monthyear='".$monthYear."'";
	$db->update($Query);

	$Query = "UPDATE prd_monthly_salary_archive_final SET status_flag='4', delete_status='1', is_saved=1 WHERE archive_final_pk IN (".implode(', ',$NewData).")";
    $db->update($Query);	    		

print_r("Success");
?>