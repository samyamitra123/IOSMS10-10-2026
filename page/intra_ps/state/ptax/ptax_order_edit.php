<?php
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
include_once '../../../../includes/library/database.class.php';
include_once '../../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';


if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])


	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

function dbdate($caldate){
 					$tmp=explode("-",$caldate);
					$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];		
  return  $redate;
 }

	
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy these two lines to every page

header("X-Frame-Options: deny");
header("X-Content-Type-Options: nosniff");

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");


//body of the form
include 'ptax_order_edit_form.php';

?>

<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
