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

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryptoGraph=new cryptography();

//error_reporting(0);
		$yemo=date("Y").date("m");
		$ifms_upload_date=$_REQUEST['ifms_upload_date'];
		$ifms_ref_no=$_REQUEST['ifms_ref_no'];
		
		
		$db=new database();
		
		$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
		$requisition_type=$requisition[0]['code'];

		$insrt_contact=$db->update("update prd_block_bill_details set 
		ifms_uploaded_date='".$ifms_upload_date."', 
		ifms_reference_no='".$ifms_ref_no."',
		ifms_status='1'
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
							AND salary_monthyear ='".$yemo."'
							AND status='1'
							 AND requisition_type='".$requisition_type."'"
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