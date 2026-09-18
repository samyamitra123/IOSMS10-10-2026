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
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>ZP Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("
						SELECT 
							mas.district_id_pk,
							prof.aeo_name,
							prof.secretary_name,
							prof.fc_cao_name,
							prof.accountant_name,
							prof.district_id_fk,
							prof.road_name,
							prof.vill_name, 
							prof.post_office_name,
							prof.police_station_name, 
							prof.pin_code,
							prof.cotract_no,
							prof.email,
							prof.tan_no,
							prof.gst_no,
							prof.pl_code ,
							prof.pan_no,
							prof.ddo_code
						FROM 
							prd_location_master_district mas 
						INNER JOIN
							zpemp_zp_profile prof
						ON
							mas.district_id_pk=prof.district_id_fk
						WHERE
							district_id_pk='".$_SESSION[location][district_id]."'");



//$ps_code=$data[0]['ps_code'];
//$ps_name=$data[0]['ps_name'];
$district_id= strtoupper($data[0]['district_id_fk']);

$aeo_name=$data[0]['aeo_name'];
$secretary_name=$data[0]['secretary_name'];
$fc_cao_name=$data[0]['fc_cao_name'];
$accountant_name=$data[0]['accountant_name'];
//$mobile_no=$data[0]['mobile_no'];
$road_name=$data[0]['road_name'];
$vill_name=$data[0]['vill_name'];
$post_office_name=$data[0]['post_office_name'];
$police_station_name=$data[0]['police_station_name'];
$pin_code=$data[0]['pin_code'];
$email=$data[0]['email'];
$contact_no=$data[0]['cotract_no'];
$tan_no=$data[0]['tan_no'];
$gst_no=$data[0]['gst_no'];
$pl_code=$data[0]['pl_code'];
$ddo_code=$data[0]['ddo_code'];
$pan_no=$data[0]['pan_no'];



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
<h1 class="heading">ZP PROFILE <?php if(count($data)=='0'){?> SUBMIT <?php } else { ?> UPDATE <?php } ?></h1>
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
<form class="form-horizontal" id="first_form" method="post" action="zp_profile_submit.php" onsubmit="return valid_code();">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="ps_id" id="ps_id" value="<?=$_SESSION['location']['ps_id']?>" />
    <input type="hidden" name="district_id" id="district_id" value="<?=$_SESSION['location']['district_id']?>" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    <div class="form-group">
    	<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label" >DISTRICT NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="dise_name" id="dise_name" placeholder="DISTRICT NAME" maxlength="10" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $_SESSION['location']['district_name'];?>" readonly>
        </div>
    	<div class="col-sm-3"></div>
    </div>
    
    
    <!--<div class="form-group">
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
    </div>-->

   
    
   <!-- <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">AEO NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="executive_name" id="executive_name" placeholder="Executive Officer Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $exe_officer_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>-->
<!--BY ND ON 30_01_2018-->    
    <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">AEO NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="aeo_name" id="aeo_name" placeholder="Aeo Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $aeo_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
      <!--<div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">SECRETARY NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="secretary_name" id="secretary_name" placeholder="Secretary Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $secretary_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
      <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">FC & CAO NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="fc_cao_name" id="fc_cao_name" placeholder="FC & CAO Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $fc_cao_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
      <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">ACCOUNTANT NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="accountant_name" id="accountant_name" placeholder="Accountant Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?= $accountant_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>-->
    
    <!--END OF BY ND ON 30_01_2018-->  
    
   <!-- <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">MOBILE NO.<span class="star_color">*</span></label>
        <div class="col-sm-3">
			<input type="text" class="form-control upper_case" autocomplete="off" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $mobile_no?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>-->
    
    <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">TAN NUMBER<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="tan_no" id="tan_no" placeholder="Tan Number"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $tan_no ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    <div class="form-group">
    <div class="col-sm-2"></div>
            <label for="inputPassword3" class="col-sm-3 control-label">PAN NO </label>
            <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="pan_no"  name="pan_no" placeholder="PAN NO" autocomplete="off"  value="<?=$pan_no ?>" maxlength="10">
             </div>
        <div class="col-sm-3"></div>
    </div>
   
         <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">GST NUMBER</label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="gst_no" id="gst_no" placeholder="GST Number"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $gst_no ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    <div class="form-group">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">LF OPERATOR CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="pl_code" id="pl_code" placeholder="PL Operator Code"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $pl_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
   <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">TREASURY CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" maxlength="3" name="ddo_code" id="ddo_code"  autocomplete="off" placeholder="DDO CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $ddo_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
<div class="border"></div>
<h1 class="heading">ZP ADDRESS</h1>
<div class="border"></div>
<br/>


    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">ROAD NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="road_name" id="road_name" placeholder="ROAD NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$road_name?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">VILLAGE/TOWN NAME<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="vill_name" id="vill_name" placeholder="VILLEGE/TOWN NAME"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$vill_name?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">POST OFFICE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="post_office" autocomplete="off" id="post_office" placeholder="POST OFFICE"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?=$post_office_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">POLICE STATION<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="pollice_st" autocomplete="off" id="pollice_st" placeholder="POLICE STATION"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $police_station_name ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
       
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">PIN CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <input type="text" class="form-control upper_case" name="pin" id="pin" placeholder="PIN CODE" autocomplete="off" maxlength="6"  onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $pin_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">CONTACT NO.</label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="contactno" id="contactno" autocomplete="off" placeholder="CONTACT NUMBER" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789.');" value="<?= $contact_no ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">EMAIL ID<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" name="email" id="email" placeholder="EMAIL ID"  autocomplete="off" onKeyPress="return keyRestrict(event,''0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.'.');" value="<?=$email ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>
    <!--<div class="form-group">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">TREASURY OPERATOR CODE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" name="ddo_code" id="ddo_code"  autocomplete="off" placeholder="TREASURY OPERATOR CODE" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?= $ddo_code ?>">
        </div>
        <div class="col-sm-3"></div>
    </div>-->
    
    
    <div class="form-group">
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
	
	/*else if($('#ps_name').val()==''){
		alert('Please Enter PS Name.');
		$('#ps_name').focus();
		return false;
	}*/
	/*else if($('#mobile_no').val()==''){
		alert('Please Enter Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}
	else if($('#mobile_no').val()!='' && $('#mobile_no').val().length!='10'){
		alert('Please Enter 10 Digit Mobile Number.');
		$('#mobile_no').focus();
		return false;
	}*/
	else if($('#aeo_name').val()==''){
		alert('Please Enter executive AEO name.');
		$('#executive_name').focus();
		return false;
	}
	/*else if($('#secretary_name').val()==''){
		alert('Please Enter executive Secretary name.');
		$('#executive_name').focus();
		return false;
	}
	else if($('#fc_cao_name').val()==''){
		alert('Please Enter executive FA & CO name.');
		$('#executive_name').focus();
		return false;
	}
	else if($('#accountant_name').val()==''){
		alert('Please Enter executive Accountant name.');
		$('#executive_name').focus();
		return false;
	}*/
	else if($('#tan_no').val()==''){
		alert('Please Enter Tan Number.');
		$('#tan_no').focus();
		return false;
	}
	else if($('#pl_code').val()==''){
		alert('Please Enter PL Operator Code.');
		$('#pl_code').focus();
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
	/*else if($('#ddo_code').val()==''){
		alert('Please Enter DDO Code.');
		$('#ddo_code').focus();
		return false;
	
	}*/
	/*else if($('#contactno').val()==''){
		alert('Please Enter Contact Number.');
		$('#mobile_no').focus();
		return false;
	}*/
else if($('#email').val()==''){
		alert('Please Enter Email ID .');
		$('#email').focus();
		return false;
}
else if(document.getElementById('email').value!=''){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}
</script>