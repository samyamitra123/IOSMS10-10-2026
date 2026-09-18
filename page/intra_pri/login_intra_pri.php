<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

//---------------------------- LIBRARY INCLUDE -----------------------------------------------------------------------------------------
session_start();
ob_start();

setcookie(session_name(), session_id(), null, '/', null, null, true);

require '../../includes/library/cryptography.class.php';
//require_once("captcha/simple-php-captcha.php");
//require_once '../../includes/library/xml.class.php';
require_once '../../includes/config/config.php';
//require '../../includes/config/database.config.php';
//require '../../includes/library/database.class.php';


$obj_crpto = new cryptography();

if(isset($_GET['val'])){
	$select_val=$obj_crpto->decode($_GET['val'],3); 
}


//require 'includes/library/session.class.php';
//------------------------------- LOGICAL AREA -----------------------------------------------------------------------------------------
//redirect to dashboard when login session found

/*
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
*/

//$type = simplexml_load_file('stake_level_type_intra_pri.xml');

//------------------------------ PAGE VARIABLES ----------------------------------------------------------------------------------------
$common['title'] = "Login | PRD";

$common['meta']['keyword'] = 'West Bengal Panchayats & Rural Development Department';
$common['meta']['description'] = 'West Panchayats & Rural Development Department';

//---------------------------------- HEADER --------------------------------------------------------------------------------------------
require '../../page/layout/header.php';
//-----------------------------Query Functions-----------------------------------------------------------------------------------------


//---------------------------------- MENU ----------------------------------------------------------------------------------------------
require '../../page/layout/menu.php'; 
//-----------------------------Business Logic-------------------------------------------------------------------------------------------
?>
<script>
			
function open_form(k) { //alert(k);
	//$('#stake').val(k);
	//$('#form_start').show();
	if(k!= ""){
		$("#mob_number").prop('disabled',false);
		$("#pw").prop('disabled',false);
		$('#stake').val(k);
	}
	else if(k== ""){
		$("#mob_number").prop('disabled',true);
		$("#pw").prop('disabled',true);
	}
}		
</script>


<style>

.flex-container {
    display: flex;
}

.flex-child {
    flex: 1;
    
}  

.flex-child:first-child {
    margin-right: 20px;
} 






</style>


<div class="row" id="cont">
     <!--<div class="col-lg-9 col-sm-8" id="sm-pad">--> 
     <div class="content">
    <div class="mainContent float_l">
    <div class="dashboard-main">
   <div class="dashcontenr" id="dashcontenr">
   <div id="sess_msg" style="margin-left: 40%;">
   <?   
  
   if(isset($_SESSION['msg']))
			{
				echo "<strong>".$_SESSION['msg']."</strong>";
				unset($_SESSION['msg']);
			}
			?>
            </div>
    <?php //require '../page/intra_pri/login_intra_pri_form.php';  ?>
	
<div class="flex-container">
	<!--<div class="flex-child" >
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("block");'> Block Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("dprdo");'> DPRDO Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("adm");'> ADM Panchayat Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("aeo");'> AEO Zilla Parishad Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("commissioner");'> Commissioner Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("dm");'> DM Login </a>
		<a class="btn btn-primary" style="margin-top: 1%;" onclick='open_form("hq");'> P&RD Headquater </a>
										
	</div>-->
	
	<div class="col-sm-3" >
		<label for="inputPassword3" class="col-sm-12 control-label" style='padding-left: 6%;'><b>Stake Level:</b></label>
	</div>
	
	<div class="col-sm-3" style='margin-left: -14%;'>
		<select class="form-control" id="level" onchange="open_form(this.value);">
			<option value="">-- Select stake --</option>
			<option value="BLOCK">Block Level User</option>
			<option value="DISTRICT">District Level User</option>
			<option value="STATE">State Level User</option>
		</select>
	</div>
	
	
	<div class="flex-child" id='form_start' style='border: 2px solid #006100; border-radius: 10px; background-color:#87CEFA; margin-left: 4%;'>
	
		
		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title" style="text-align:center">INTRA-PRD LOGIN</h3>
			 	</div>
                	<div class="error" style="color: red;"></div>
                    
                     
			  	<div class="panel-body">
                
                
            <form accept-charset="UTF-8" role="form" action="log_sub_intra_pri.php" method="post" name="login_form" >
				<input type="hidden" name = "stake" id="stake" />	    
				
            <fieldset>
			<div id="error_1" style="color: red;"></div>
			</br>
						 
	
						
			<div class="row mb-3" >
			   <label for="inputPassword3" class="col-sm-6 control-label" style='padding-left: 6%;'><b>Registered Mobile Number:</b></label>
			   <div class="col-sm-6" style="margin-left: -13%;">
				<input class="form-control" id="mob_number" name="mob_number" type="text" Placeholder="Please enter Registered Mobile Number" maxlength="10" autocomplete="off" disabled > 
				 
				</div> 
				 
			</div>
                       
			</br>
			<div id="number_msg" style="color: green;"></div>
			<div class="row mb-3" style='margin-left: 15%;'>
				<!--<label for="inputPassword3" class="col-sm-3 control-label" id="pw_level" name="pw_level" ><b>Secrete PIN:</b></label>-->
				<label for="inputPassword3" class="col-sm-3 control-label" id="pw_level" name="pw_level" ><b>Password:</b></label>
				<div class="col-sm-6">
				<input class="form-control" id="pw" name="pw"  type="password" autocomplete="off" Placeholder="Please enter Password" disabled>
				<!--<a href="" type="button" class="btn btn-sm btn-info btn-block" style="margin-top: 1%; margin-left: 69%; background-color:#1E90FF" id="regeneratePIN" name="regeneratePIN" onClick="sendOTP('forget_PIN');" >Forget PIN</a>-->			 
				</div>             
						 
			</div>
			</br>
			<input type="submit" class="btn btn-lg btn-info btn-block" style="margin-left: 44%; background-color:#1E90FF" value="Submit" id="submit_otp" >
			
                      
						
					</fieldset>
                </form>
                  
                		</div>
        </div>
					
	</div>	
</div>	
    </div></div></div></div>
	
   

 </div></br>
 <?php include('../../page/layout/footer.php'); ?>






