<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$db=new database();
$cryptoGraph=new cryptography();

if(!empty($_REQUEST['id']) && !empty($_REQUEST['type'])){
	if($cryptoGraph->decode($_REQUEST['flag'],4)=='enable'){
		$enable = $db->update("
					UPDATE prd_upload
					SET
						flag = 1
					WHERE upload_id_pk = ". $cryptoGraph->decode($_REQUEST['id'],4).";
					
				");
	}
	
	if($cryptoGraph->decode($_REQUEST['flag'],4)=='disable'){
		$disable = $db->update("
					UPDATE prd_upload
					SET
						flag = 2
					WHERE upload_id_pk = ". $cryptoGraph->decode($_REQUEST['id'],4).";
					
				");
	}
}

if($enable){
	$_SESSION['message']='<div class="alert alert-success" style="text-align:center"><strong>Status Enabled Successfully...</strong></div>';
	header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_notice.php");
	exit;
}
else if($disable){
	$_SESSION['message']='<div class="alert alert-success" style="text-align:center"><strong>Status Disabled Successfully...</strong></div>';
	header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_notice.php");
	exit;
}
else{
	$_SESSION['message']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Status Updation Fails...</strong></div>';
	header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_notice.php");
	exit;	
}