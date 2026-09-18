<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

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
/*header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");*/
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>GP Profile submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("select gp_id_pk,gp_code,gp_name,block_id_fk from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'");
$data1=$db->fetch_table("select gram_pradhan_name, mobile_no, road_name,vill_name, post_office, police_station, pin_code,contact_no, email_id from prd_gp_profile where gp_code='".$_SESSION['user_info']['stake_user']."'");
$dise_code=$data[0]['gp_code'];
$gp_name=$data[0]['gp_name'];
$block_code=$data[0]['block_id_fk'];
if(!empty($data1)){
$pradhan_name=$data1[0]['gram_pradhan_name'];
$mobile_no=$data1[0]['mobile_no'];
$road_name=$data1[0]['road_name'];
$vill_name=$data1[0]['vill_name'];
$post_office=$data1[0]['post_office'];
$pollice_st=$data1[0]['police_station'];
/*$pin_code=$data1[0]['pin_code'];
$email_id=$data1[0]['email_id'];
$contact_no=$data1[0]['contact_no'];*/
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
<h1 class="heading">GP PROFILE update</h1>
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
<form class="form-horizontal" id="first_form" method="post" action="gp_profile_submit.php" onsubmit="return valid_code();">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?=$teacher_id_pk?>" />
   		<input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<?=$emp_id_pk?>" />
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 col-form-label" >GP CODE<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" name="dise_code" id="dise_code" placeholder="GP CODE" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $dise_code?>" readonly="readonly">
		</div>
		<div class="col-sm-3"></div>
    </div>
     <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 col-form-label">GP NAME<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" name="gp_name" id="gp_name" placeholder="GP NAME" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz.');" value="<?= $gp_name?>" readonly="readonly">
		</div>
		<div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 col-form-label">BLOCK<span class="star_color">*</span></label>
		<div class="col-sm-3">
		<? 
		$db=new database();
		$arr=$db->fetch_table("select block_name,block_id_pk from prd_location_master_block order by block_name")
		?>
		  <SELECT class="form-control upper_case" name="block" id="block" disabled="disabled">
		  <option>---------Please Select----------</option>
		  <?
		  foreach($arr as $key){
		  ?>
		  <option val="<?=$key['block_id_pk'] ?>" <? if($block_code==$key['block_id_pk']) { echo "selected" ;} ?>><?= $key['block_name']; ?></option>
		  <? } ?>
		  </SELECT>
		</div>
		<div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 col-form-label">NAME OF PRADHAN<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" name="pradhan_name" id="pradhan_name" placeholder="Gram Pradhan Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $pradhan_name ?>">
		</div>
		<div class="col-sm-3"></div>
    </div>
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 col-form-label">MOBILE NO.<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $mobile_no?>" >
		</div>
		<div class="col-sm-3"></div>
    </div>
<div class="border"></div>
<h1 class="heading">GP ADDRESS</h1>
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
		<label for="inputPassword3" class="col-sm-3 col-form-label">VILLAGE/TOWN NAME<span class="star_color">*</span></label>
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
		<label for="inputPassword3" class="col-sm-3 col-form-label">CONTACT NO.</label>
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
	else if($('#pradhan_name').val()==''){
		alert('Please Enter Gram Pradhan Name.');
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
	else if(document.getElementById('email').value!=0){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}
</script>