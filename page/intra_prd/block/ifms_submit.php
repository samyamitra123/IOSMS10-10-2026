<?
ob_start();
session_start();
//echo 11; die;
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryptoGraph=new cryptography();

//error_reporting(0);
		$yemo=date("Y").date("m");
		$ifms_upload_date=$_REQUEST['ifms_upload_date'];
		$ifms_ref_no=$_REQUEST['ifms_ref_no'];
		
		if($validator->blank_select($ifms_upload_date) == FALSE || $ifms_upload_date == '0001-01-01')
			{
			$error_message='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid IFMS Uploaded date.</strong></div>';
			include 'text_file.php';
			exit;
			}
			else if($validator->blank_select($ifms_ref_no) == FALSE)
			{
			$error_message='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFMS Reference No.</strong></div>';
			include 'text_file.php';
			exit;
			}
		
		$db=new database();
		/*echo "update prd_block_bill_details set 
		ifms_uploaded_date='".$ifms_upload_date."', 
		ifms_reference_no='".$ifms_ref_no."',
		ifms_status='1'
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
							AND salary_monthyear ='".$yemo."'
							AND status='1'"; die;*/
							
							//echo "select ifms_reference_no from prd_block_bill_details where status='1' and salary_monthyear='".date('Ym')."'"; die;
		$ifms_ref_check=$db->fetch_table("select ifms_reference_no from prd_block_bill_details where status='1' and salary_monthyear='".date('Ym')."'");
		
		if($validator->blank_select($ifms_ref_no) == TRUE)
			{
				
				foreach($ifms_ref_check as $key){
					
					if($ifms_ref_no == $key['ifms_reference_no'])
					{
						
						$error_message='<div class="alert alert-danger" style="text-align:center"><strong>IFMS Reference No already exists. Please Enter valid IFMS Reference No.</strong></div>';
						include 'text_file.php';
						exit;
					}
				
			  	}
			}		
		
			//echo 11; die;
		$insrt_contact=$db->update("update prd_block_bill_details set 
		ifms_uploaded_date='".$ifms_upload_date."', 
		ifms_reference_no='".$ifms_ref_no."',
		ifms_status='1'
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
							AND salary_monthyear ='".$yemo."'
							AND status='1'"
							 );
		//print_r($insrt_contact);
		//exit;
		
if($insrt_contact){
	
	header('Location:text_file.php?confirm=success');
}
else{
	header('Location:text_file.php?confirm=false');
}
		
?>