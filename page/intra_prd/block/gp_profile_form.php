<?php
session_start();
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//echo $_SERVER['HTTP_REFERER'];exit;
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
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
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>GP Profile submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'fails'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>GP Code Already Exists...</strong></div>';
}
else if($_GET['confirm'] == 'fails_mob'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Mobile Number Alresdy Exists...</strong></div>';
}
else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("select block_id_pk,block_code from prd_location_master_block where block_code='".$_SESSION['location']['block_code']."'");

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
					<? echo $_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">GP PROFILE ENTRY</h1>
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
<form class="form-horizontal" id="gp_form" name="gp_form" method="post" action="gp_profile_submit.php" onsubmit="return valid_code();">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?=$teacher_id_pk?>" />
   		<input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<?=$emp_id_pk?>" />
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <input type="hidden" name="block" value="<?=$data[0]['block_id_pk']; ?>" />
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">GP Code<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="dise_code" id="dise_code" placeholder="GP CODE" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $dise_code?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
     <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">GP Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="gp_name" id="gp_name" placeholder="GP NAME" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $gp_name?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name Of Gram Pradhan</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="pradhan_name" id="pradhan_name" placeholder="Gram Pradhan Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $pradhan_name ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Mobile No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $mobile_no?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
<!--<div class="border"></div>
<h1 class="heading">GP ADDRESS</h1>
<div class="border"></div><br/>
<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">ROAD NAME</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="road_name" id="road_name" placeholder="ROAD NAME"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $road_name?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">VILLEGE/TOWN NAME</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="vill_name" id="vill_name" placeholder="VILLEGE/TOWN NAME"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $vill_name?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">POST OFFICE</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="post_office" id="post_office" placeholder="POST OFFICE"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $post_office ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">POLICE STATION</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="pollice_st" id="pollice_st" placeholder="POLICE STATION"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $police_station ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">PIN CODE</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="pin" id="pin" placeholder="PIN CODE" maxlength="6"  onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $pin_code ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">EMAIL ID</label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="email" id="email" placeholder="EMAIL ID"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $email_id ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>-->
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
	else if($('#dise_code').val()!='' && $('#dise_code').val().length!='10'){
		alert('Please Enter 10 Digit GP Code.');
		$('#dise_code').focus();
		return false;
	   }
	else if($('#gp_name').val()==''){
		alert('Please Enter GP Name.');
		$('#gp_name').focus();
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
}
</script>