<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

require '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
include_once '../../includes/library/database.class.php';
include_once '../../includes/library/cryptography.class.php';
require_once("../captcha/simple-php-captcha.php");
require_once '../../includes/library/xml.class.php';





require '../../page/layout/header.php';
require '../../page/layout/menu.php';

/*$_SESSION['captcha'] = simple_php_captcha( array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'abcdefghjkmnprstuvwxyz23456789',
	'min_font_size' => 20,
	'max_font_size' => 25,
	'color' => '#222',
	'angle_min' => 0,
	'angle_max' => 0,
	'shadow' => true,
	'shadow_color' => '#FFF',
	'shadow_offset_x' => -1,
	'shadow_offset_y' => 1
));*/
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
//$type = simplexml_load_file('../../xml/login/stake_level_type.xml');
//$stake = simplexml_load_file('../../xml/login/stake_level.xml');


?>

<script type="text/javascript" src="<?= $config['base_url'] ?>themes/default/js/commonfunc.js" /></script>

<script src="<?= $config['base_url'] ?>themes/default/js/crypto-js.js" type="text/javascript"></script>


<script type="text/javascript">
function validForm(){
	/*cap_code=document.getElementById('capcha').value;
	if(cap_code!=''){ //alert(cap_code);
		//alert('capta found');
		var cap = CryptoJS.MD5(document.getElementById('capcha').value);
		alert(cap);
		document.getElementById('capcha').value="***************************";
		document.getElementById('security_code').value=cap;
	}	
	 if(document.getElementById('old_pass').value!=''){
		var old_pass = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('old_pass').value));
		//alert(old_pass);
		document.getElementById('old_pass').value=old_pass;
	}
	if(document.getElementById('old_pass').value==''){
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Old Password.</strong></div>'
		//alert("Please Enter Old Password.");
		document.getElementById('old_pass').focus();
		return false;
	} */
	if(document.getElementById('new_pass').value==''){
		//alert("Please new password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter New Password.</strong></div>'
		document.getElementById('new_pass').focus();
		return false;
	}
	if(document.getElementById('new_pass').value.length < 8){
		//alert("The password should contain minimum eight characters.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>The password should contain minimum eight characters.</strong></div>'
		document.getElementById('new_pass').value='';
		document.getElementById('new_pass').focus();
		return false;
	}
	if(document.getElementById('conf_pass').value==''){
		//alert("Please Enter Confirm Password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Confirm Password.</strong></div>'
		document.getElementById('conf_pass').focus();
		return false;
	}
	/*
	if(document.getElementById('capcha').value==''){
		//alert("Please Enter capcha code.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Captcha Code.</strong></div>'
		document.getElementById('capcha').focus();
		return false;
	} */
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
	/* if(document.getElementById('old_pass').value==document.getElementById('new_pass').value)
	{
		//alert("New Password should not be old password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>New Password should not be old password.</strong></div>'
		document.getElementById('conf_pass').value='';
		document.getElementById('conf_pass').focus();
		return false;	
	} */
	if(document.getElementById('new_pass').value!=''){
		//var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.new_pass.value));
		var pass = CryptoJS.SHA256(document.login_form.new_pass.value);
		document.getElementById('new_pass').value=pass;
	}
	if(document.getElementById('conf_pass').value!=''){
		//var pass_nw = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('conf_pass').value));
		var pass_nw = CryptoJS.SHA256(document.getElementById('conf_pass').value);
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
<!--CONTENT START-->
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
          <?php require 'common_back_btns_intra_pri.php'; ?>
   
	<div class="welcome_msg">
		<?php 
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		
		//var_dump($officer_name);
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>

          <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>
   
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
   
    <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Change Password</h1>
<h3 class="heading" style="color:red">For Security Purpose Please Change The Password</h3>
<div class="border"></div>
       
  
	   <br /><br />
       <?php 
                if(isset($_SESSION['msg'])){
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
					echo '<br><br>';
                }
                ?>
                <div id="msg"></div>
     
     <?php 
	 $time_token=time();
     $_SESSION['security_token']=$time_token;
     $enc_token=md5('369'.$time_token);
	 $cryptography=new cryptography(); 
				
				?>

     
        <form class="form-horizontal" action="<?= $config['base_url'] ?>page/intra_pri/changepassword_intra_pri_update.php" name="login_form" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validForm();">
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                <input type="hidden" name = "id"  id="id" value="<?=$cryptography->encode($id,4)?>" />
       
              <div class="child3">
               <noscript>Please Enable JavaScript In your Browser</noscript>
                <? if(isset($error_msg)){?>
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
      <!--<div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label">Old Password <span class="star_color">*</span></label>
      <div class="col-sm-4">
      		<input  type="password" maxlength="20" autocomplete="off " name="old_pass"  id="old_pass" class="form-control"  >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>-->
      
     <div class="row mb-3">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label">New Password <span class="star_color">*</span></label>
      <div class="col-sm-4">
      		<input  type="password" maxlength="20" autocomplete="off " name="new_pass" id="new_pass" placeholder="New Password" class="form-control"  >
            <input type="hidden" name="pass_chk" id="pass_chk" value="">
  	  </div>
  	  <div class="col-sm-3">
      <div id="pass-info" class="float_l col"></div>
      </div>
  	  </div>
      <div class="row mb-3">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label">Confirm Password <span class="star_color">*</span></label>
      <div class="col-sm-4">
      		<input  type="password" maxlength="20" autocomplete="off " name="conf_pass"  id="conf_pass" placeholder="Confirm Password" class="form-control"  >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>
      <!--<div class="form-group">
      <div class="col-sm-3"></div>
     		<label for="inputPassword3" class="col-sm-2 control-label"><?php echo '<img width ="80" height="40" style="
  border: 1px solid #C9C9C9;margin-left:3%" id="captcha_img" src="'.$config['base_url'].'page/CaptchaSecurityImages.php?width=115&height=40&characters=5" style="border:1px solid #FA6F1B; margin:0 0 0 10px;" alt="CAPTCHA code">'; ?> <span class="star_color">*</span></label>-->
      <!--<div class="col-sm-4">
      		<input  type="text" maxlength="20" autocomplete="off " name="capcha" id="capcha" class="form-control"  >
  	  </div>
  	  <div class="col-sm-3"></div>
  	  </div>-->
       <div class="row mb-3" style="margin-left: 41%;">
    <div class="col-sm-offset-5 col-sm-5">
      <button type="submit" name="changepassword_submit" class="btn btn-info">Update password</button>
    </div>
  </div>
      
      	</form>
        <div class="clear"></div>
       <div class="alert alert-info" style="border:1px solid #1185D9; border-radius:5px;">
       <h2 class="heading" style="text-align: left;">Password Tips:</h2><br />
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
       <strong><i class="fa fa-info-circle"></i> Password should contain one special character (e.g.,!@# $%^&*()_+|~-=\`{}[]:";'<>?,./).</strong>
       </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="clear"></div>
