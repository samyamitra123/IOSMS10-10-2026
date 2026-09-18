<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

/*header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");*/



//---------------------------- LIBRARY INCLUDE -----------------------------------------------------------------------------------------
session_start();
ob_start();
require_once '../includes/config/config.php';
/*if (session_start()) {*/
		//$cookie_name = session_name();
		//$cookie_value = session_id();
		setcookie(session_name(), session_id(), null, '/', null, null, true);
//} update 14.5.2019
/*print ini_get('session.save_path')."<br>"; 
print ini_get('session.use_cookies')."<br>"; 
print ini_get('session.save_handler');*/
/*if(!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookie_name];
} */
require '../includes/library/cryptography.class.php';
$obj_crpto = new cryptography();

if(isset($_GET['val'])){
	$select_val=$obj_crpto->decode($_GET['val'],3); 
}


//require 'includes/library/session.class.php';
//------------------------------- LOGICAL AREA -----------------------------------------------------------------------------------------
//redirect to dashboard when login session found
if(
	   isset($_SESSION['user_info']['stake_user'])
	&& isset($_SESSION['user_info']['stake_level'])
	&& isset($_SESSION['user_info']['flag'])
	){
	header('Location: '. $config['base_url'] . "page/dashboard.php");
	exit;
}

else if( isset($_SESSION['user_info']['change_password_code'])){
	header('Location: '. $config['base_url'] . "page/all_moduls/changepassword/change_password.php");
	exit;
}
//
//$var = $db->fetch_table("SELECT * FROM tbl_admin_login");
//------------------------------ PAGE VARIABLES ----------------------------------------------------------------------------------------
$common['title'] = "Login | PRD";

$common['meta']['keyword'] = 'West Bengal Panchayats & Rural Development Department';
$common['meta']['description'] = 'West Panchayats & Rural Development Department';
//---------------------------------- HEADER --------------------------------------------------------------------------------------------
require '../page/layout/header.php';
//-----------------------------Query Functions-----------------------------------------------------------------------------------------


//---------------------------------- MENU ----------------------------------------------------------------------------------------------
require '../page/layout/menu.php'; 
//-----------------------------Business Logic-------------------------------------------------------------------------------------------
?>
<script>
	$(document).ready(function(){
		//$('#site_stop_modal').modal('show');
		
		
			$('#site_stop_modal').modal('show');
		
		
		});
					
</script>

<div class="row" id="cont">
     <div class="col-lg-9 col-md- col-sm-8" id="sm-pad"> 
     <div class="content">
    <div class="mainContent float_l">
    <div class="dashboard-main">
   <div class="dashcontenr" id="dashcontenr">
   <?php 
   /*echo $_SESSION['test'];
   exit;*/
   ?>
    <?php require '../page/login_form.php';  ?>
    </div></div></div></div></div>
   <div class="clearfix visible-xs"></div>
<!--   <div class="col-lg-3 col-md-4 col-sm-4" id="sm-pad2" style="margin-top: 43px;">
    <?php //include('../page/layout/right_sidebar.php'); ?>
    </div>-->
 </div>
 <?php include('../page/layout/footer.php'); ?>
<!-- 
 <div id="site_stop_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
             <center> <h1 style="color:black;">  <p> Attention!!</p></h1></center>
                <form>
                    <div class="form-group">
  					<h4 style="color:black;"> 
					    <p> 1. Please complete the activities after login and logout fast to make way for others.  <br />

2. Avoid unncessary surfing to dashboard which may take longer processing time.<br />
3. Concurrent users are restricted depending on the traffic conjestion.</br>
4. Avoid unncessary login and logout as it may take some time to re-login depending on the traffic conjestion.</br>
5. Avoid taking reports now which may be taken later.
                    </h4>
                    </div>
                    <div align="right">
                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button> 
                   </div>
                </form>
            </div> 
        </div>
    </div>
</div>
-->





