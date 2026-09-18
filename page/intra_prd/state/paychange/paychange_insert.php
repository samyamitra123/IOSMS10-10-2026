<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
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
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
//$status='';
//$status=isset($_REQUEST['status'])?$_REQUEST['status']:'';

//$teacher_id_pk=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
//$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';

//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}

include 'paychange_date_select_form.php';

//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>
<script>
function validContact(){
	
	if(document.getElementById('da').value==''){
		alert("Please Enter DA.");
		document.getElementById('da').focus();
		return false;
	}
	
	if(document.getElementById('hra').value==''){
		alert("Please Enter HRA.");
		document.getElementById('hra').focus();
		return false;
	}
	
	if(document.getElementById('ma').value==''){
		alert("Please Enter MA.");
		document.getElementById('ma').focus();
		return false;
	}
	
	if(document.getElementById('conveyance_allowance').value==''){
		alert("Please Enter CONV ALLOW.");
		document.getElementById('conveyance_allowance').focus();
		return false;
	}
	
	
	/*if(document.getElementById('cpf').value=='' ){
		alert("Please Enter CPF.");
		document.getElementById('cpf').focus();
		return false;
	}
	*/
	
	return true;
}
	
</script>
