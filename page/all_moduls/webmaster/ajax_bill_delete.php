<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once'../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';     
require_once '../../all_function/fun_store/zp_ps_gp_function.php';
include( '../../all_function/fun_store/ifms_functions.php');
$db = new database();
$drn_number= $_GET['drn_no'];
$rowid = $_GET['rowid'];
if($drn_number != '')
{
		$Query = "SELECT * from prd_block_bill_details WHERE drn_number='".$drn_number."'";
		$blockBillDetails = $db->fetch_table($Query);
		//print_r($blockBillDetails); exit;
		if(count($blockBillDetails) > 0)
		  {
				$blockBillDetails = $blockBillDetails[0];
				//print_r($blockBillDetails);
				$Query = "update prd_block_bill_details SET status=0 WHERE drn_number='".$drn_number."'";
				$db->update($Query);
				//$k=strtotime("first day of last month");
				$arr = date("Y-m-d"); 
				$month_arr=explode('-',$arr);
				$salary_monthyear=$month_arr[0].$month_arr[1];

				$Where = array();
				if($blockBillDetails['block_code'] != '')
				  $Where[] = "block_code='".$blockBillDetails['block_code']."'";
				elseif($blockBillDetails['ps_id_fk'] != '')
				  $Where[] = "ps_id_fk='".$blockBillDetails['ps_id_fk']."'";
				elseif($blockBillDetails['zp_id_fk'] != '')
				  $Where[] = "zp_id_fk='".$blockBillDetails['zp_id_fk']."'";

				
		    //print_r($Where);
		   // exit;
				if(count($Where) > 1)
				{
					$Where[] = "salary_monthyear='".$salary_monthyear."'";
					$Query ="SELECT * from prd_monthly_salary_archive_final WHERE ".implode(' AND ',$Where);
					$FinalBillDetails = $db->fetch_table($Query);
					//print_r($FinalBillDetails); 
					$Query = "UPDATE prd_monthly_salary_archive_final SET delete_status=0 WHERE ".implode(' AND ',$Where);
					$db->update($Query);
					$Query = "UPDATE prd_sftp_benf_upload_response SET active_status=0 WHERE bill_id_fk='".$blockBillDetails['block_bill_pk']."'";
					$db->update($Query);

					$Query = "UPDATE prd_employee_salary_save SET status_flag=0, delete_status=0, is_saved=0 WHERE ".implode(' AND ',$Where);
					$db->update($Query);

					echo "Bill Deleted Sucessfully For Block ID: ".$blockBillDetails['block_code'];
				}
			}
			else
			{
				 echo "Bill Already Deleted";
			}
}
if($rowid != '')
  {
  	$Query = "DELETE FROM prd_employee_salary_save WHERE oid =".$rowid;
  	$db->update($Query);
  	//echo $Query;
  }			


?>
