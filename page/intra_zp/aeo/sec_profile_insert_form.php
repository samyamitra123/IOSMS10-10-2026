<?php
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once '../../../includes/library/database.class.php';
require_once("../../captcha/simple-php-captcha.php");
require_once '../../../includes/library/xml.class.php';
include_once '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

$_SESSION['location']['block_code'];

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
if (
    !(($_SESSION['privilege']['01'] == TRUE) 
  || ($_SESSION['privilege']['0101'] == TRUE)
  || ($_SESSION['privilege']['0102'] == TRUE)
  || ($_SESSION['privilege']['0103'] == TRUE)
  || ($_SESSION['privilege']['0104'] == TRUE)
  || ($_SESSION['privilege']['07'] == TRUE)
  || ($_SESSION['privilege']['0701'] == TRUE)
  || ($_SESSION['privilege']['0702'] == TRUE)
  || ($_SESSION['privilege']['0703'] == TRUE)
  || ($_SESSION['privilege']['56'] == TRUE)
  || ($_SESSION['privilege']['5601'] == TRUE)
  || ($_SESSION['privilege']['5602'] == TRUE)
  || ($_SESSION['privilege']['5603'] == TRUE)
  || ($_SESSION['privilege']['59'] == TRUE)
  || ($_SESSION['privilege']['5901'] == TRUE)
  || ($_SESSION['privilege']['5902'] == TRUE)
  || ($_SESSION['privilege']['5903'] == TRUE)
  || ($_SESSION['privilege']['5904'] == TRUE))
  ){
  header('Location: '. $config['base_url'] . "page/dashboard.php");
  exit;
}

error_reporting(0);
/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Dashboard | WBULBHRMS | Govt. of West Bengal";


require '../../municipality_admin/header.php';
require '../../municipality_admin/common.php';
require '../../municipality_admin/menu.php';

$type = simplexml_load_file('xml/login/stake_level_type.xml');
$stake = simplexml_load_file('xml/login/stake_level.xml');

// for new DEO CODE
$db = new database();
$cryptography=new cryptography();
//echo $old_pass."<br>";exit;
$new_pass=sha1($new_pass);
$block_code = $_SESSION['location']['block_code'];
$dist_name = $_SESSION['location']['district_name'];
$block_name = $_SESSION['location']['block_name'];
//$db = new database();

$data10 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM mad_location_master_deo where block_id_fk ='".$_SESSION ['location']['block_id_pk']."'");

// echo $_SESSION ['location']['block_id_pk'];
$count10 = $data10[0][result_count];
//die;

$arr = $db->fetch_table("select municipality_code, block_id_pk,block_code from mad_location_master_municipality where block_code='".$block_code."'");

$m_code = $arr[0]['municipality_code'];
$block1_code = $arr[0]['block_id_pk'];
 $block_login_back_code = $arr[0]['block_code'];
 
$arr1 = $db->fetch_table("select max(gp_code) as entry_rec_tchcd, max(deo_code) as entry_rec_tchcd1 from mad_location_master_deo where block_id_fk='".$block1_code."'");

if($count10<"1")
{
 $block12_code = $arr1[0]['entry_rec_tchcd'];
 $block121_code = $arr1[0]['entry_rec_tchcd1'];
 $deo1_code =$m_code.'01';
 $gp_code_max = $block_login_back_code.'001';
	
}
else
{
	//die;
 $block12_code = $arr1[0]['entry_rec_tchcd'];
 $block121_code = $arr1[0]['entry_rec_tchcd1'];
 $deo1_code = $block121_code + '1';
 $gp_code_max = $block12_code + '1';
}

$_SESSION['deo2_code'] = $deo1_code;
$cryptoGraph=new cryptography();	
$confirm_msg = $cryptoGraph->decode($_GET['confirm'],7);

if($confirm_msg == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>DEO Profile Has Been Created Successfully.</strong></div>';
}
else if($confirm_msg == 'fails')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>DEO Profile Has Not Been Created. Please Try Again...</strong></div>';
}
?>

  <style>

		.page_title
		{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
   
   </style>
   
	<style>
       #pass-info{
        /*width: 97.5%;*/
        height: 25px;
        /*border: 1px solid #f1f1f1;*/
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
<div class="page-content">
    <div class="content">
        <div class="page-title">
   
            <h1 class="heading" style="text-align:center;">DEO PROFILE INSERT</h1>
            <div class="border"></div>
            </br>
           
               <?php 

                if($_SESSION['msg'])
				{
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

                    <div class="row" id="cont">
                       <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad" style="text-align:center;"> 
                            <form class="form-horizontal" action="<?= $config['base_url'] ?>page/intra_mad/chairman/deoprofile_insert_submit.php" name="login_form" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validForm();">
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
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;">User ID <span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                        <input  type="text" maxlength="20" autocomplete="off " name="deo_id" placeholder="DEO ID" id="deo_id" class="form-control" style="background-color:#D3D3D3;"  value="<?php echo $deo1_code ;?>" readonly="readonly" >
                                      
                                  </div>
                                  <div class="col-sm-3">
                                  
                                  </div>
                                  </div>
                                  
                                  <div class="form-group">
                                  <div class="col-sm-3"></div>
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;">Name <span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                        <input  type="text" maxlength="50" autocomplete="off " name="ddo_name" placeholder="DEO Name" id="ddo_name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" class="form-control"  >
                                      
                                  </div>
                                  <div class="col-sm-3">
                                  
                                  </div>
                                  </div>
                                  <div class="form-group">
                                  <div class="col-sm-3"></div>
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;">Email Id<span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                         <input type="text" class="form-control" autocomplete="off" name="ddo_email" placeholder="Email Id" id="ddo_email" value="" onKeyPress="return keyRestrict(event,'0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.');">
                                  </div>
                                  <div class="col-sm-3"></div>
                                  </div>
                                  
                                  <div class="form-group">
                                  <div class="col-sm-3"></div>
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;">Mobile No.<span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                         <input type="text" class="form-control" autocomplete="off" placeholder="Mobile No." name="ddo_mobile" id="ddo_mobile" maxlength="10" value="" onKeyPress="return keyRestrict(event,'0123456789');">
                                  </div>
                                  <div class="col-sm-3"></div>
                                  </div>
                                  
                                  
                                  
                                  <div class="form-group">
                                  <div class="col-sm-3"></div>
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;"> New Password <span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                        <input  type="password" maxlength="20" autocomplete="off " name="new_pass" placeholder="Password" id="new_pass" class="form-control" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*');"  >
                                        <input type="hidden" name="pass_chk" id="pass_chk" value="">
                                  </div>
                                  <div class="col-sm-3">
                                  <div id="pass-info" class="float_l col"></div>
                                  </div>
                                  </div>
                                  
                                  <div class="form-group">
                                  <div class="col-sm-3"></div>
                                        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align:left;">Confirm Password <span class="star_color">*</span></label>
                                  <div class="col-sm-4">
                                        <input  type="password" maxlength="20" autocomplete="off " name="conf_pass" placeholder="Confirm Password"  id="conf_pass" class="form-control" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*');"  >
                                  </div>
                                  <div class="col-sm-3"></div>
                                  </div>
                                  <br/>
                                  
                                  <div class="form-group">
                                <div class="col-sm-offset-5 col-sm-1">
                                  <button type="submit" name="ddoprofile_submit" class="btn btn-info">SUBMIT</button>
                                </div>
                              </div>
                                  
                                    </form>
                        </div>
                        
                    </div>
           

      </div>
               <div class="clear"></div>
    
    <div class="alert alert-info" style="border:1px solid #1185D9; border-radius:5px; text-align:left;">
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
       <strong><i class="fa fa-info-circle"></i> Password should contain one Special character (e.g.,!@# $%^&*).</strong>
       </div>
   <div class="clear"></div> 
    </div>
  </div>
  
</div>
<? require '../../municipality_admin/footer.php'; ?>


<script type="text/javascript" src="<?= $config['base_url'] ?>themes/default/js/commonfunc.js" /></script>
<script src="<?= $config['base_url'] ?>themes/default/js/md5.js" type="text/javascript"></script>
<script src="<?= $config['base_url'] ?>themes/default/js/sha1.js" type="text/javascript"></script>


<script type="text/javascript">
function validForm(){
	
	if(document.getElementById('ddo_name').value==''){
		//alert("Please new password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter DEO Name.</strong></div>'
		document.getElementById('ddo_name').focus();
		return false;
	}
	if(document.getElementById('ddo_email').value==''){
		//alert("Please new password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter A Valid Email Id.</strong></div>'
		document.getElementById('ddo_email').focus();
		return false;
	}
	
	if(document.getElementById('ddo_email').value!=0){
		if(document.getElementById('ddo_email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('ddo_email').focus();			
				return false;
		}
	}
	if(document.getElementById('ddo_mobile').value == 0){
		alert("Please Enter Mobile Number.");
		document.getElementById('ddo_mobile').focus();
		return false;
	}
	if(document.getElementById('ddo_mobile').value != 0 && document.getElementById('ddo_mobile').value.length !=10){
		alert("Please Enter 10 Digit Mobile Number.");
		document.getElementById('ddo_mobile').focus();
		return false;
	}
	 if((document.getElementById('ddo_mobile').value.charAt(0)!="9") && (document.getElementById('ddo_mobile').value.charAt(0)!="8") && (document.getElementById('ddo_mobile').value.charAt(0)!="7"))
           {
                alert("Mobile Number Should Start With 9, 8 or 7 ");
				document.getElementById('ddo_mobile').focus();
                return false
           }
	
	// check password
	
	if(document.getElementById('new_pass').value==''){
		//alert("Please new password.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter New Password.</strong></div>'
		document.getElementById('new_pass').focus();
		return false;
	}
	if(document.getElementById('new_pass').value.length < 8){
		//alert("The password should contain minimum eight characters.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>The Password Should Contain Minimum Eight Characters.</strong></div>'
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
	
	
	if(document.getElementById('conf_pass').value!=document.getElementById('new_pass').value)
	{
		//alert("New Password and Confirm Password Does not match.");
		document.getElementById('msg').innerHTML='<div class="alert alert-danger" style="text-align:center;"><strong>New Password And Confirm Password Does Not Match.</strong></div>'
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
	
	if(document.getElementById('new_pass').value!=''){
		//var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.new_pass.value));
		var pass = CryptoJS.MD5(document.login_form.new_pass.value);
		document.getElementById('new_pass').value=pass;
	}
	if(document.getElementById('conf_pass').value!=''){
		//var pass_nw = CryptoJS.SHA1(CryptoJS.MD5(document.getElementById('conf_pass').value));
		var pass_nw = CryptoJS.MD5(document.getElementById('conf_pass').value);
		document.getElementById('conf_pass').value=pass_nw;
	}
	
	
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



<!-- Favicon icon -->
<link rel="shortcut icon" href="<?php echo $config['base_url']; ?>themes/default/img/fav_icon.png">
<link href="<?php echo $config['base_url']; ?>themes/default/css/allinone_carousel.css" rel="stylesheet" type="text/css">
<!--<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css">-->
<!--<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css">-->
<link href="<?php echo $config['base_url']; ?>themes/default/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css">
<!--<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>-->

<!--<script src="<?php //echo $config['base_url']; ?>themes/default/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
--><script src="<?php echo $config['base_url']; ?>themes/default/js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/allinone_carousel.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-migrate-3.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/commonfunc.js" type="text/javascript"></script>

<!----- new development-------------------->
<script src="<?php echo $config['base_url']; ?>themes/default/js/crypto-js.js" type="text/javascript"></script>

<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-ui.min-1.12.1.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-ui-1.12.1.js" type="text/javascript"></script>

<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui.min-1.12.1.css" rel="stylesheet" type="text/css">
<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui.structure.min-1.12.1.css" rel="stylesheet" type="text/css">
<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui.structure-1.12.1.css" rel="stylesheet" type="text/css">
<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui.theme.min-1.12.1.css" rel="stylesheet" type="text/css">
<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui.theme-1.12.1.css" rel="stylesheet" type="text/css"><strong></strong>






