<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
//header('Location:login.php');
require_once("captcha/simple-php-captcha.php");
require_once '../includes/library/xml.class.php';
require_once '../includes/config/config.php';

require_once '../includes/config/database.config.php';
include_once '../includes/library/database.class.php';
include_once '../includes/library/cryptography.class.php';

error_reporting(0);


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

/*$_SESSION['captcha_code']=$_SESSION['captcha']['code'];
$captcha_code=$obj_crpto->encode($_SESSION['captcha_code'],4);*/
?>
<meta charset="UTF-8">

<link rel="stylesheet" href="css/bootstrap.min.css">

<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<script src="jquery-1.11.2.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(){

        $("#myModal").modal('show');
    });

</script>


<?php
$cryptoGraph=new cryptography();	
 $confirm_msg = $cryptoGraph->decode($_GET['confirm'],7);

if ($confirm_msg == 'success_re_pas')
//if ($count11 < "2")

  {   
 // echo "ffff";
  //die;
  ?>
<div id="myModal" class="modal fade">
    <div class="modal-dialog"><p></p>
        <div class="modal-content">
            <div class="modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                      
                     <h4>System Generated Default Password has been Successfully Changed. Please Login with Username and New Password.</h4>
                    </div>
                    <div align="right">
                    <a href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $cryptoGraph->encode(zp,3); ?>">
                      <button type="button" class="btn btn-success" data-dismiss="">OK</button> </a>
                   </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php }
?>


<script src="<?= $config['base_url'] ?>themes/default/js/md5.js" type="text/javascript"></script>
<script src="<?= $config['base_url'] ?>themes/default/js/sha1.js" type="text/javascript"></script>
<script type="text/javascript">
function validForm(){
	cap_code=document.getElementById('capcha').value;
	if(cap_code!=''){
		//alert('capta found');
		var cap = CryptoJS.MD5(document.getElementById('capcha').value);
		//alert(cap);
		document.getElementById('capcha').value="***************************";
		document.getElementById('security_code').value=cap;
	}	
	/*if(document.getElementById('old_pass').value!=''){
		var old_pass = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('old_pass').value));
		//alert(old_pass);
		document.getElementById('old_pass').value=old_pass;
	}
	if(document.getElementById('old_pass').value==''){
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Old Password.</strong></div>'
		//alert("Please Enter Old Password.");
		document.getElementById('old_pass').focus();
		return false;
	}*/
	if(document.getElementById('new_pass').value==''){
		//alert("Please new password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter New Password.</strong></div>'
		document.getElementById('new_pass').focus();
		alert("Please new password.");
		return false;
	}
	if(document.getElementById('new_pass').value.length < 8){
		alert("The password should contain minimum eight characters.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>The password should contain minimum eight characters.</strong></div>'
		document.getElementById('new_pass').value='';
		document.getElementById('new_pass').focus();
		return false;
	}
	if(document.getElementById('conf_pass').value==''){
		alert("Please Enter Confirm Password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Confirm Password.</strong></div>'
		document.getElementById('conf_pass').focus();
		return false;
	}
	
	if(document.getElementById('capcha').value==''){
		//alert("Please Enter capcha code.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Captcha Code.</strong></div>'
		document.getElementById('capcha').focus();
		return false;
	}
	if(document.getElementById('conf_pass').value!=document.getElementById('new_pass').value)
	{
		//alert("New Password and Confirm Password Does not match.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>New Password and Confirm Password Does not match.</strong></div>'
		document.getElementById('conf_pass').value='';
		document.getElementById('conf_pass').focus();
		return false;	
	}
	if(document.getElementById('pass_chk').value!='yes'){
		//alert("Please Enter Strong Password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Strong Password.</strong></div>'
		document.getElementById('new_pass').value='';
		document.getElementById('new_pass').focus();
		return false;
	}
	/*if(document.getElementById('old_pass').value==document.getElementById('new_pass').value)
	{
		//alert("New Password should not be old password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>New Password should not be old password.</strong></div>'
		document.getElementById('conf_pass').value='';
		document.getElementById('conf_pass').focus();
		return false;	
	}*/
	if(document.getElementById('new_pass').value!=''){
		var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.new_pass.value));
		document.getElementById('new_pass').value=pass;
	}
	if(document.getElementById('conf_pass').value!=''){
		var pass_nw = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('conf_pass').value));
		document.getElementById('conf_pass').value=pass_nw;
	}
					
	/*if(document.getElementById('old_pass').value!=''){
		var old_pass = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('old_pass').value));
		document.getElementById('old_pass').value=old_pass;
	}*/
	
	
	return true;
}
</script>

<script type="text/javascript">
$(document).ready(function() {
	var password1 		= $('#new_pass'); //id of first password field
	var password2		= $('#conf_pass'); //id of second password field
	var passwordsInfo 	= $('#pass-info'); //id of indicator element
	passwordStrengthCheck(password1,password2,passwordsInfo); //call password check function
	
	if($('#form_show').css("visibility")=="hidden"){
		$('#form_show').removeClass("invisible").css('height', 'auto');
	}
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
<!--<div class="container" style="padding-top:20px;">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">-->
        <div class="content">
        <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
       
    	<?php
		if($_SESSION['password_stake_level']=="50")
		{ ?>
        <h3 class="heading" style="text-align:center;">WELCOME: AEO, <?php echo $_SESSION['district_name']; ?></h3>
        
        <?php } ?>
        <h4 class="heading" style="text-align:center;">Please Change The System Generated Default Password</h3>
<div class="border"></div><br/>
 <?php
         if($_SESSION['msg']){
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
					echo '<br><br>';
                }
				else
				{
					//echo '<div class="alert alert-success" style="text-align:center; padding: 6px;"><strong>* OTP Has Been Generated And Sent To Your Mobile Number XXXXXXXX'.substr($mobile_number,-2,2).'.</strong></div>';
				}
                ?>
			  	
                 <form class="form-horizontal" action="<?= $config['base_url'] ?>page/aeo_change_password_update_submit.php" name="login_form" id="aeo_change_password_form" method="post" enctype="multipart/form-data" onsubmit="return validForm();">
                 <div id="msg"></div>
                 
                     <?php 
					 $time_token=time();
					 $_SESSION['security_token']=$time_token;
					 $enc_token=md5('369'.$time_token);
					 $cryptography=new cryptography(); 
			
					?>
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                <input type="hidden" name = "security_code"  id="security_code" value="" />
       
             
               <noscript>Please Enable JavaScript In your Browser</noscript>
                <? if($error_msg){?>
              	<div class="float_l col_line"><?php echo $error_msg;?></div>
                    <div class="clear"></div> 
                <? }?>   
                 <?php
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
	$msg2=$cryptography->decode($_GET['msg'],3);
	?>
    	<div title="Message" id="msgs" align="left" style="color:#093;"><?php echo $msg2;?></div>
<?php
          }
      else{
	$msg2="";
          }
?>
      
      
      <div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label" >New Password <span class="star_color">*</span></label>
      <div class="col-sm-3">
      		<input  type="password" maxlength="20" autocomplete="off " name="new_pass" placeholder="NEW PASSWORD" id="new_pass" class="form-control" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*');" >
            <input type="hidden" name="pass_chk" id="pass_chk" value="">
  	  </div>
  	  <div class="col-sm-3">
      <div id="pass-info" class="float_l col"></div>
      </div>
  	  </div>
      <div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label" style= " ">Confirm Password <span class="star_color">*</span></label>
      <div class="col-sm-3">
      		<input  type="password" maxlength="20" autocomplete="off " name="conf_pass" placeholder="CONFIRM PASSWORD"  id="conf_pass" class="form-control" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*');" >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>
      
      <!--Commented for OTP by nirupam on 15-05_2017-->
      
      <!--<div class="form-group">
      <div class="col-sm-3"></div>
      		<label for="inputPassword3" class="col-sm-2 control-label" style="color:#E5F4F4">OTP <span class="star_color">*</span></label>
          <div class="col-sm-4">
      		<input  type="text" maxlength="20" autocomplete="off " name="otp"  id="otp" placeholder="OTP"  class="form-control"  >
          </div>
           
 
  	  <div class="col-sm-3"></div>
  	  </div>-->
        <!--End of Commented for OTP by nirupam on 15-05_2017-->
        
      <div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label"><?php echo '<img width ="80" height="40" style="
  border: 1px solid #C9C9C9;margin-left:3%" id="captcha_img" src="'.$config['base_url'].'page/CaptchaSecurityImages.php?width=115&height=40&characters=5" style="border:1px solid #FA6F1B; margin:0 0 0 10px;" alt="CAPTCHA code">'; ?> <span class="star_color">*</span></label>
      <div class="col-sm-3">
      		<input  type="text" maxlength="20" autocomplete="off " name="capcha" id="capcha" class="form-control" placeholder="CAPTCHA"  >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>
      <div class="form-group">
    <div class="col-sm-offset-5 col-sm-5">
      <button type="submit" name="forgetpassword_submit" class="btn btn-info">Update password</button>
    </div>
  </div>
      
      	</form>
			 
            
	</div>
    
	
        
     <br/>
        <div class="clear"></div>
       <div class="alert alert-info" style="border:1px solid #1185D9; border-radius:5px; margin-left:10px; margin-right:30px;">
       <h3 class="heading" style="text-align: left;">Password Tips:</h3><br />
       <strong><i class="fa fa-info-circle"></i> Password should be strong.</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should not be old password.</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain minimum eight characters.</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Lower Case character (e.g. a-z).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Upper Case character (e.g. A-Z).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one Numeric character (e.g. 0-9).</strong>
       <br /><br />
       <strong><i class="fa fa-info-circle"></i> Password should contain one special character (e.g.,!@# $%^&*).</strong>
       </div>
       <?php include('layout/footer.php'); ?>
</div>

<div>



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