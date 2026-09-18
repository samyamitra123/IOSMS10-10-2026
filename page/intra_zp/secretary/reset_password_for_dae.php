<?php
ob_start();
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once("../../captcha/simple-php-captcha.php");
include_once '../../../includes/library/cryptography.class.php';
//require_once '../../../includes/library/cryptography.class.php';
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
$common['title'] = "Change Password Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -----------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic-------------------------------------------------------------------

$db= new database();
/*$gp=$db->fetch_table("select gp_code,gp_name from prd_location_master_gp gp
inner join prd_location_master_block block on block.block_id_pk=gp.block_id_fk
Where block_code='".$_SESSION['location']['block_code']."' order by gp_code");*/
?>

 <script type="text/javascript" src="../../../themes/default/js/commonfunc.js"></script>
    <script src="../../../themes/default/js/md5.js" type="text/javascript"></script>
	<script src="../../../themes/default/js/sha1.js" type="text/javascript"></script>


<script>
		
		
		function validContact(){
		cap_code=document.getElementById('capcha').value;
		if(cap_code!=''){
		//alert('capta found');
		var cap = CryptoJS.MD5(document.getElementById('capcha').value);
		//alert(cap);
		document.getElementById('capcha').value="***************************";
		document.getElementById('security_code').value=cap;
	}		
			
		//alert(document.getElementById('pass_chk').value);
		if(document.getElementById('dae').value==''){
		alert("Please Insert Dealing Assistant (Establishment) Name.");
		document.getElementById('dae').focus();
		return false;
	}
	
	if(document.getElementById('new_password').value==''){
		alert("Please new password.");
		document.getElementById('new_password').focus();
		return false;
	}
	
	if(document.getElementById('confirm_password').value==''){
		alert("Please Enter Confirm Password.");
		document.getElementById('confirm_password').focus();
		return false;
	}
	
	
	if(document.getElementById('confirm_password').value!=document.getElementById('new_password').value)
	{
	alert("New Password and Confirm Password Does not match.");
		document.getElementById('confirm_password').focus();
		return false;	
	}
	if(document.getElementById('capcha').value==''){
		//alert("Please Enter capcha code.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Captcha Code.</strong></div>'
		document.getElementById('capcha').focus();
		return false;
	}
	
	if(document.getElementById('new_password').value.length < 8){
		//alert("The password should contain minimum eight characters.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>The password should contain minimum eight characters.</strong></div>'
		document.getElementById('new_password').value='';
		document.getElementById('new_password').focus();
		return false;
	}
  if(document.getElementById('pass_chk').value!='yes'){
		//alert("Please Enter Strong Password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Strong Password.</strong></div>'
		document.getElementById('new_password').value='';
		document.getElementById('new_password').focus();
		return false;
	}
	if(document.getElementById('new_password').value!=''){	
	//alert(1);						
		var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.new_password.value));
		//alert(pass);
		document.getElementById('new_password').value=pass;	
		//alert(pass);	
	}
				
	if(document.getElementById('confirm_password').value!=''){
		var pass_nw = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('confirm_password').value));
		document.getElementById('confirm_password').value=pass_nw;
		//alert(pass_nw);
	}
					
	return true;
}
		
		//--------------------------------------------
		</script>
        <script type="text/javascript">
$(document).ready(function() {
	var password1 		= $('#new_password'); //id of first password field
	var password2		= $('#confirm_password'); //id of second password field
	var passwordsInfo 	= $('#pass-info'); //id of indicator element
	passwordStrengthCheck(password1,password2,passwordsInfo); //call password check function
});

function passwordStrengthCheck(password1, password2, passwordsInfo)
{
	//Must contain 5 characters or more
	var WeakPass = /(?=.{5,}).*/; 
	//Must contain lower case letters and at least one digit.
	var MediumPass = /^(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
	//Must contain at least one upper case letter, one lower case letter and one digit.
	var StrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
	//Must contain at least one upper case letter, one lower case letter and one digit.
	var VryStrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=\S*?[^\w\*])\S{5,}$/; 
	
	$(password1).on('keyup', function(e) {
		$('#pass_chk').val('no');
		if(VryStrongPass.test(password1.val()) && password1.val().length > 7)
		{
			passwordsInfo.removeClass().addClass('vrystrongpass').html("Strong!");
			$('#pass_chk').val('yes');
		}	
		else if(StrongPass.test(password1.val()))
		{
			passwordsInfo.removeClass().addClass('strongpass').html("Very Good!Use minimum 8 digit for strong");
			$('#pass_chk').val('no');
			
		}	
		else if(MediumPass.test(password1.val()))
		{
			passwordsInfo.removeClass().addClass('goodpass').html("Good!Enter uppercase letter to make strong");
			$('#pass_chk').val('no');
		}
		else if(WeakPass.test(password1.val()))
    	{
			passwordsInfo.removeClass().addClass('stillweakpass').html("Still Weak! Enter digits to make good password");
			$('#pass_chk').val('no');
    	}
		else
		{
			passwordsInfo.removeClass().addClass('weakpass').html("Very Weak!(Must be 8 or more chars)");
		}
	});
	
	$(password2).on('keyup', function(e) {
		
		if(password1.val() !== password2.val())
		{
			passwordsInfo.removeClass().addClass('weakpass').html("Passwords do not match!");	
		}else{
			passwordsInfo.removeClass().addClass('goodpass').html("Passwords match!");	
		}
			
	});
}
</script> 


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
       
       <?php
	   
	    $data11 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '52'");
	    $count11 = $data11[0][result_count];

	   ?>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
 <?php if($count11<1)
		{
		 	echo '<h1 class="heading"> Create Profile For Dealing Assistant (Establishment) </h1>';	
		}
		else 
		{
			echo '<h1 class="heading">Reset Password For Dealing Assistant (Establishment) </h1>';
		} ?>
<div class="border"></div>
</br>
<div id="msg"></div>
		<?php 
        if($_SESSION['reset_msg']){
        	echo $_SESSION['reset_msg'];
        	unset($_SESSION['reset_msg']);
        	echo '<br>';
        }
        ?>
         <?php 
	 $time_token=time();
     $_SESSION['security_token']=$time_token;
     $enc_token=md5('369'.$time_token);
	 $cryptography=new cryptography(); 
				
		?>

    <form class="form-horizontal" method="post" name="login_form" action="<?= $config['base_url']?>page/intra_zp/changepassword/reset_password_insert_update.php" onsubmit="return validContact();">
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">DA (Establishment) CODE: <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php
			$db=new database();
			$zp_code=$db->fetch_table("SELECT district_code FROM prd_location_master_district WHERE district_id_pk='".$_SESSION['location']['district_id']."'");
			//$zp_fetch=$db->fetch_table("SELECT stake_user FROM prd_stack_user_login WHERE stake_level_id_fk='52' AND stake_user like '".$zp_code[0]['district_code']."%'");
	 ?>
	
	<input type="text" class="form-control" readonly name="dae_code" id="dae_code" value="<?php echo $_SESSION['location']['district_code'].'ZPDAE'; ?>" />
	
	<input type="hidden" class="form-control" readonly name="dae" id="dae" value="<?php echo $zp_code[0]['district_code']?>" />
    <input type="hidden" class="form-control" readonly name="stake_level_id" id="stake_level_id" value="52" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name = "security_code"  id="security_code" value="" />
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Enter New Password: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="password" class="form-control" name="new_password" id="new_password"  />
      <input type="hidden" name="pass_chk" id="pass_chk" value="">
    </div>
    <div class="col-sm-3"><div id="pass-info" class="float_l col"></div></div>
    </div>
    
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Confirm Password: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="password" class="form-control" name="confirm_password" id="confirm_password"  />
    </div>
    <div class="col-sm-3">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label"><?php echo '<img width ="80" height="40" style="
  border: 1px solid #C9C9C9;margin-left:3%" id="captcha_img" src="'.$config['base_url'].'page/CaptchaSecurityImages.php?width=115&height=40&characters=5" style="border:1px solid #FA6F1B; margin:0 0 0 10px;" alt="CAPTCHA code">'; ?> <span class="star_color">*</span></label>
      <div class="col-sm-3">
      		<input  type="text" maxlength="20" autocomplete="off " name="capcha" id="capcha" class="form-control"  >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>
    <div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
      <input type="submit" name="change_dae" value="SUBMIT" class="btn btn-info">
    </div>
  </div>
  
    </form>
    <div class="clear"></div>
    
    <div class="alert alert-info" style="border:1px solid #1185D9; border-radius:5px;">
       <h2 class="heading" style="text-align: left;">Password Tips:</h2><br />
       <strong><i class="fa fa-info-circle"></i> Password should be strong.</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain minimum eight characters.</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Lower Case character (e.g. a-z).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Upper Case character (e.g. A-Z).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Numeric character (e.g. 0-9).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one special character (e.g.,!@# $%^&*()_+|~-=\`{}[]:";'<>?,./).</strong>
       </div>
   <div class="clear"></div>    
</div>
</div>
</div>
</div>

<? require '../../../page/layout/footer.php'; ?>
<style>
   #pass-info{
	/*width: 97.5%;*/
	height: 25px;
	border: 1px solid #f1f1f1;
	border-radius: 4px;
	color: #829CBD;
	text-align: center;
	font: 12px/25px Arial, Helvetica, sans-serif;
}
#pass-info.weakpass{
	border: 1px solid #FF9191;
	background: #FFC7C7;
	color: #94546E;
	text-shadow: 1px 1px 1px #FFF;
}
#pass-info.stillweakpass {
	border: 1px solid #FBB;
	background: #FDD;
	color: #945870;
	text-shadow: 1px 1px 1px #FFF;
	font-size: 11px;
}
#pass-info.goodpass {
	border: 1px solid #C4EEC8;
	background: #E4FFE4;
	color: #51926E;
	text-shadow: 1px 1px 1px #FFF;
}
#pass-info.strongpass {
	border: 1px solid #6ED66E;
	background: #79F079;
	color: #348F34;
	text-shadow: 1px 1px 1px #FFF;
}
#pass-info.vrystrongpass {
	border: 1px solid #379137;
	background: #48B448;
	color: #CDFFCD;
	text-shadow: 1px 1px 1px #296429;
}
</style>