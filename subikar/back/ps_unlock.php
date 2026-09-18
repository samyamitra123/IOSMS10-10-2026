<?php 
require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';

$db=new database();	

$LoginID = ''; //
// Search from prd_location_master_panchayat_samiti.ps_code='3208007'  Remove PSEO Get ps_id_pk
$Query = ""

//Update Query to Unlock Salary update prd_employee_salary_save SET status_flag=2 WHERE ps_id_fk='132' AND salary_monthyear='202212'

?>