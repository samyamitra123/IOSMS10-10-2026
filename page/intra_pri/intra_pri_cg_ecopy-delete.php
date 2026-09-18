<?php
session_start();
ob_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../../includes/library/myvalidation.class.php';
$crypto=new cryptography();
$db=new database();

$delete_id=(isset($_POST['delete_id']))?$crypto->decode($_POST['delete_id'],4):''; 
$delete_f=(isset($_POST['delete_f']))?$crypto->decode($_POST['delete_f'],4):''; 
$empId=(isset($_POST['empId']))?$crypto->decode($_POST['empId'],4):''; 
	 if($delete_id=="delete")
			{
				$query=$db->update("UPDATE intra_pri_file_upload SET 
			status='0'
			WHERE 
			emp_id_const='".$empId."' and flag='".$delete_f."' and status='1'");
				
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>DOCUMENT SUCCESSFULLY REMOVED </strong></div>';
					header('Location:view_intra_pri_service.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> DOCUMENT REMOVED FAILED.</strong></div>';
					header('Location:view_intra_pri_service.php');
				}
			}
?>