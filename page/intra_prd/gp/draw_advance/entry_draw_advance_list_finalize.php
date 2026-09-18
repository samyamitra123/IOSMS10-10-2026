<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';

/*error_reporting(0);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}


$cryptoGraph=new cryptography();

$gp_id=$cryptoGraph->decode($_REQUEST['gp_id'],4);
						
						$db = new database();	
				
						$query_update=$db->update("UPDATE prd_adavance_pay set
											status_flag='2'
										 WHERE
											 gp_id_fk='$gp_id'");				 
						
				
				if($query_update){
				echo '<div class="alert alert-success" style="text-align:center"><strong>You have successfully finalize the list.</strong></div>';exit;
				//$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Data Has Been Succesfully Inserted.</strong></div>';
					
				}
				else{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Finalization Fails.</strong></div>';exit;
					
				}
						
					


?>