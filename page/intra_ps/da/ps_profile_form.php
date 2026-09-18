<?php

session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("
						SELECT 
							mas.ps_id_pk,
							mas.district_id_fk,
							mas.ps_name,
							mas.ps_code,
							prof.exe_officer_name,
							prof.mobile_no,
							prof.road_name,
							prof.vill_name, 
							prof.ddo_code,
							prof.post_office_name,
							prof.police_station_name, 
							prof.pin_code,
							prof.cotract_no,
							prof.email ,
							prof.treasury_code,
							prof.pl_code_pf,
							prof.t_code_pf
						FROM 
							prd_location_master_panchayat_samiti mas 
						INNER JOIN
							psemp_ps_profile prof
						ON
							mas.ps_id_pk=prof.ps_id_fk
						WHERE
							ps_id_pk='".$_SESSION['location']['ps_id']."'");



$ps_code=$data[0]['ps_code'];
$ps_name=$data[0]['ps_name'];
$district_id=$data[0]['district_id_fk'];

$exe_officer_name=$data[0]['exe_officer_name'];
$mobile_no=$data[0]['mobile_no'];
$road_name=$data[0]['road_name'];
$vill_name=$data[0]['vill_name'];
$post_office_name=$data[0]['post_office_name'];
$police_station_name=$data[0]['police_station_name'];
$pin_code=$data[0]['pin_code'];
$email=$data[0]['email'];
$contact_no=$data[0]['cotract_no'];
$ddo_code=$data[0]['ddo_code'];
$t_code=$data[0]['treasury_code'];
$pl_code_pf=$data[0]['pl_code_pf'];
$t_code_pf=$data[0]['t_code_pf'];



$ps_code=substr($_SESSION['user_info']['stake_user'],0,-1);

?>
<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
<div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      }elseif(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].", ";
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'].", ";
                    } ?></h2><h3>
			<?php   
			    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
			
                     ?></h3>
       </div>


<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">PS PROFILE <?php if(count($data)=='0'){?> SUBMIT <?php } else { ?> UPDATE <?php } ?></h1>
<div class="border"></div>
</br>
<?
 
	   if($_SESSION['msg']){
	echo $_SESSION['msg'];

	 unset($_SESSION['msg']);
	
}
?>  


<?php 
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
}
?>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" class="dashcontenr invisible"> 
<form class="form-horizontal" id="first_form" method="post" action="ps_profile_submit.php" onsubmit="return valid_code();">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="ps_id" id="ps_id" value="<?=$_SESSION['location']['ps_id']?>" />
    <input type="hidden" name="district_id" id="district_id" value="<?=$_SESSION['location']['district_id']?>" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    <div class="row mb-3">
    	<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label" >DISTRICT NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="dise_name" id="dise_name" placeholder="DISTRICT NAME" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $_SESSION['location']['district_name'];?>" readonly>
        </div>
    	<div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">PS NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
			<?
				$db = new database();
				$arr = $db->fetch_table("select ps_name,ps_code from prd_location_master_panchayat_samiti where ps_code='".$ps_code."' ");
            ?>
        	<input type="text" class="form-control upper_case" name="ps_name" id="ps_name" placeholder="PS NAME" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $arr[0]['ps_name']?>" readonly>
        </div>
    	<div class="col-sm-3"></div>
    </div>

   
    
    <div class="row mb-3">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">NAME OF EXECUTIVE OFFICER<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="executive_name" id="executive_name" placeholder="Executive Officer Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $exe_officer_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">MOBILE NO.<span class="star_color">*</span></label>
        <div class="col-sm-3">
			<input type="text" class="form-control upper_case" autocomplete="off" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $mobile_no?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
<div class="border"></div>
<h1 class="heading">PS ADDRESS</h1>
<div class="border"></div>
<br/>


    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">ROAD NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="road_name" id="road_name" placeholder="ROAD NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$road_name?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">VILLAGE/TOWN NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="vill_name" id="vill_name" placeholder="VILLEGE/TOWN NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$vill_name?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">POST OFFICE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="post_office" autocomplete="off" id="post_office" placeholder="POST OFFICE"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$post_office_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">POLICE STATION<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="pollice_st" autocomplete="off" id="pollice_st" placeholder="POLICE STATION"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $police_station_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
       
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">PIN CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <input type="text" class="form-control upper_case" name="pin" id="pin" placeholder="PIN CODE" autocomplete="off" maxlength="6"  onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $pin_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">CONTACT NO.</label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="contactno" id="contactno" autocomplete="off" placeholder="CONTACT NUMBER" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $contact_no ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">EMAIL ID<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" name="email" id="email" placeholder="EMAIL ID"  autocomplete="off" onKeyPress="return keyRestrict(event,''0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.'.');" value="<?=$email ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">PL OPERATOR CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="ddo_code" id="ddo_code"  autocomplete="off" placeholder="PL OPERATOR CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $ddo_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
     <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">TREASURY CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="t_code" id="t_code"  autocomplete="off" placeholder="TREASURY CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $t_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">OPERATOR CODE(PF SUBSCRIPTION)<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="pl_code_pf" id="pl_code_pf"  autocomplete="off" placeholder=">OPERATOR CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $pl_code_pf ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
     <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">TREASURY CODE(PF SUBSCRIPTION)<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="t_code_pf" id="t_code_pf"  autocomplete="off" placeholder="TREASURY CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $t_code_pf ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
        <div class="col-sm-offset-5 col-sm-7">
        	<button type="submit" class="btn btn-info">SUBMIT</button>
        </div>
    </div>
  
  
</form>
<div class="clear"></div>
</div>
</div>
</div>
</div>
</div>
<? require '../../../page/layout/footer.php'; ?>
<script>
$(document).ready(function(){
	if($('#form_show').css("visibility")=="hidden"){
			$('#form_show').removeClass("invisible").css('height', 'auto');
		}
	});
		
function valid_code()
{
	if($('#dise_name').val()==''){
		alert('Please Enter Block Name.');
		$('#dise_name').focus();
		return false;
	}
	
	else if($('#ps_name').val()==''){
		alert('Please Enter PS Name.');
		$('#ps_name').focus();
		return false;
	}else if($('#mobile_no').val()==''){
		alert('Please Enter Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}
	else if($('#mobile_no').val()!='' && $('#mobile_no').val().length!='10'){
		alert('Please Enter 10 Digit Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}
	else if($('#executive_name').val()==''){
		alert('Please Enter executive officer name.');
		$('#executive_name').focus();
		return false;
	}
	else if($('#road_name').val()==''){
		alert('Please Enter Road Name.');
		$('#road_name').focus();
		return false;
	}
	else if($('#vill_name').val()==''){
		alert('Please Enter Villege/Town Name.');
		$('#vill_name').focus();
		return false;
	}
	else if($('#post_office').val()==''){
		alert('Please Enter Post Office Name.');
		$('#post_office').focus();
		return false;
	}
	else if($('#pollice_st').val()==''){
		alert('Please Enter Police Station Name.');
		$('#pollice_st').focus();
		return false;
		
	}
	else if($('#pin').val()==''){
		alert('Please Enter Pin Code.');
		$('#pin').focus();
		return false;
	}
	else if($('#pin').val()!='' && $('#pin').val().length!='6'){
		alert('Please Enter 6 Digit Pin Code.');
		$('#pin').focus();
		return false;
	}
	else if($('#ddo_code').val()==''){
		alert('Please Enter PL OPERATOR Code.');
		$('#ddo_code').focus();
		return false;
	
	}
	else if($('#t_code').val()==''){
		alert('Please Enter TREASURY Code.');
		$('#t_code').focus();
		return false;
	
	}
	
	else if($('#pl_code_pf').val()==''){
		alert('Please Enter PL OPERATOR Code PF.');
		$('#pl_code_pf').focus();
		return false;
	
	}
	else if($('#t_code_pf').val()==''){
		alert('Please Enter TREASURY Code PF.');
		$('#t_code_pf').focus();
		return false;
	
	}
/*else if($('#email').val()==''){
		alert('Please Enter Email ID .');
		$('#email').focus();
		return false;
}*/
else if(document.getElementById('email').value!=''){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}
</script>