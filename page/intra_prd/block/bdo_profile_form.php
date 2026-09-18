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
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}

$db=new database();
$data1=$db->fetch_table("select block_code, bdo_name, mobile_no, road_name, vill_name, 
            post_office, police_station, pin_code, email_id, contact_no, 
            tan_no from prd_block_profile where block_code='".$_SESSION['location']['block_code']."'");
			
			$data=$db->fetch_table("select operator_code_pf,t_code_pf from prd_dise_admin where block_code='".$_SESSION['location']['block_code']."'");
			
$operator_code_pf=$data[0]['operator_code_pf'];
$t_code_pf=$data[0]['t_code_pf'];
if(!empty($data1)){
$bdo_name=$data1[0]['bdo_name'];
$mobile_no=$data1[0]['mobile_no'];
$road_name=$data1[0]['road_name'];
$vill_name=$data1[0]['vill_name'];
$post_office=$data1[0]['post_office'];
$pollice_st=$data1[0]['police_station'];
$tan=$data1[0]['tan_no'];
if($data1[0]['pin_code']==0){
$pin='';
}else{
$pin=$data1[0]['pin_code'];
}
$email=$data1[0]['email_id'];
if($data1[0]['contact_no']=='0'){
$contact_no='';	
}else{
$contact_no=$data1[0]['contact_no'];
}
}
?>

<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
  <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">BDO PROFILE FORM</h1>
<div class="border"></div>
</br>
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
<form class="form-horizontal" id="first_form" method="post" action="bdo_profile_submit.php" onsubmit="return valid_code();">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        
   <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">District Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="dise_code" id="dise_code" placeholder="DISTRICT NAME" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $_SESSION['location']['district_name']?>" readonly="readonly">
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Block Code<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="dise_code" id="dise_code" placeholder="BLOCK CODE" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $_SESSION['location']['block_code']?>" readonly="readonly">
    </div>
    <div class="col-sm-3"></div>
    </div>
     <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Block Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="gp_name" id="gp_name" placeholder="BLOCK NAME" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $_SESSION['location']['block_name']?>" readonly="readonly">
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name Of BDO<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="bdo_name" id="bdo_name" placeholder="BDO Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $bdo_name ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Mobile No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $mobile_no?>" >
    </div>
    <div class="col-sm-3"></div>
    </div>
<div class="border"></div>
<h1 class="heading">BLOCK ADDRESS</h1>
<div class="border"></div><br/>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">ROAD NAME<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="road_name" id="road_name" placeholder="ROAD NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $road_name?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">VILLEGE/TOWN NAME<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="vill_name" id="vill_name" placeholder="VILLEGE/TOWN NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $vill_name?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">POST OFFICE<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="post_office" id="post_office" placeholder="POST OFFICE"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $post_office ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">POLICE STATION<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="pollice_st" id="pollice_st" placeholder="POLICE STATION"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $pollice_st ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">PIN CODE</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="pin" id="pin" placeholder="PIN CODE" maxlength="6"  onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $pin ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Contact No.</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="contactno" id="contactno" placeholder="CONTACT NUMBER" maxlength="12"  onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $contact_no ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">EMAIL ID</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="email" id="email" placeholder="EMAIL ID"  onKeyPress="return keyRestrict(event,''0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.'.');" value="<?= $email ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">OPERATOR CODE(PF SUBSCRIPTION)<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="operator_code_pf" id="operator_code_pf"  autocomplete="off" placeholder="OPERATOR CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $operator_code_pf ?>">
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
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">TAN NO.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="tan" id="tan" placeholder="TAN NUMBER"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ .');" value="<?= $tan ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3" style="padding-left: 47%; margin-top: 4%;">
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
		
function valid_code(){
	if($('#dise_code').val()==''){
		alert('Please Enter GP Code.');
		$('#dise_code').focus();
		return false;
	}
	else if($('#bdo_name').val()==''){
		alert('Please Enter BDO Name.');
		$('#bdo_name').focus();
		return false;
	}
	else if($('#mobile_no').val()==''){
		alert('Please Enter Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}
	else if($('#mobile_no').val()!='' && $('#mobile_no').val().length!='10'){
		alert('Please Enter 10 Digit Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}
	else if($('#bdo_name').val()==''){
		alert('Please Enter BDO Name.');
		$('#pradhan_name').focus();
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
	else if($('#tan').val()==''){
		alert('Please Enter TAN Number.');
		$('#tan').focus();
		return false;
	}
	else if($('#t_code_pf').val()==''){
		alert('Please Enter TREASURY CODE(PF SUBSCRIPTION).');
		$('#t_code_pf').focus();
		return false;
	}
	else if($('#operator_code_pf').val()==''){
		alert('Please Enter OPERATOR CODE(PF SUBSCRIPTION).');
		$('#operator_code_pf').focus();
		return false;
	}
	
	
	
	else if(document.getElementById('email').value!=0){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}
</script>