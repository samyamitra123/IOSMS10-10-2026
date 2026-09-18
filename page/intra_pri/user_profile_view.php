<?php

session_start();

//error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require_once '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

/*
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}


*/

?>

<style>
.info {
  background-color: #e7f3fe;
  border-left: 6px solid #2196F3;
}
</style>
<div class="content">
<? require 'common_back_btns_intra_pri.php'; ?>

	<div class="welcome_msg">
		<?php 
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		
		//var_dump($officer_name);
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>


<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">USER PROFILE </h1>
<div class="border"></div>
</br>
<?
 
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
//header('Location:dashboard_intra_pri.php');	
} 
/*
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
}*/

 function fun_desig($dsig)
		    { 
			
			//echo $dsig;
			    $db = new database();
			    /*echo("select description,code 
			    from prd_dise_code_master where code='".$dsig."'");die;*/
				
				//echo 122;
				
			    $data = $db->fetch_table("select designation
			    from intra_pri_designation_master where designation_code='".$dsig."'");
			    return $data[0]['designation'];
		    }
			
			
			 function dateshow($dateval)
    {	
    
    //echo $dateval;
    $date=substr($dateval,0,10);
    //return $date;
    $datearr=explode('-',$date);
    $dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
    return $dob=='--'?'':$dob;
    }
?>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" class="dashcontenr invisible"> 
<form class="form-horizontal" id="first_form" method="post" action="user_profile_submit.php" onsubmit="return valid_code();">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
   
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

	

    
    
    
   
   
    
    <div class="row mb-3">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">NAME OF OFFICER<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" autocomplete="off" name="executive_name" id="executive_name" value="<?=$officer_name[0]['officer_name'];?>" placeholder=" Officer Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">SEX<span class="star_color">*</span></label>
    <div class="col-sm-3">
   				<?php
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
				?>
     
      
      <select class="form-control" name="drpSex" id="drpSex">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['code']. '<br />';?>
    <option value="<?= $key['code']; ?>" <? if($officer_name[0]['sex']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
      
        </div>
        <div class="col-sm-3"></div>
    </div>
    
 
    <div class="row mb-3" id="designation_div" >
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">DESIGNATION<span class="star_color">*</span></label>
		<div class="col-sm-3" id="desig_block">
			<input type="text" class="form-control" autocomplete="off" name="desig1" id="desig1"  value="<?=fun_desig($officer_name[0]['designation']);?>"placeholder="Designation" readonly >	
				
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">MOBILE NO.<span class="star_color">*</span></label>
        <div class="col-sm-3">
			<input type="text" class="form-control" autocomplete="off" name="mobile_no"  readonly id="mobile_no" value="<?=$officer_name[0]['mobile_no'];?>" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    

   <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">EMAIL ID:<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" name="email" id="email" placeholder="EMAIL ID"  value="<?=$officer_name[0]['email_id'];?>" autocomplete="off" onKeyPress="return keyRestrict(event,''0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.'.');" >
        </div>
        <div class="col-sm-3"></div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">DATE OF JOINING IN PRESENT POST: <span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" autocomplete="off" name="date_join" id="date_join" value="<?=$officer_name[0]['date_of_join_prsnt_post']=='0001-01-01' ? '' : dateshow($officer_name[0]['date_of_join_prsnt_post']);?>" placeholder="DATE OF JOINING IN PRESENT POST"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" >
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
<? require '../layout/footer.php'; ?>
<script>

$(function() {
	$( "#date_join" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy' 
	});
});


	
$(document).ready(function(){
	if($('#form_show').css("visibility")=="hidden"){
			$('#form_show').removeClass("invisible").css('height', 'auto');
		}
	});
		
function valid_code()
{
	if($('#designation_code').val()==''){
		alert('Please Select Stake.');
		$('#designation_code').focus();
		return false;
	}
	
	
	else if($('#executive_name').val()==''){
		alert('Please Enter executive officer name.');
		$('#executive_name').focus();
		return false;
	}
	if($('#drpSex').val()==''){
		alert('Please Select SEX.');
		$('#dise_name').focus();
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
	

else if(document.getElementById('email').value!=''){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}



function open_block(k) { 
		//alert(k);
	if(k== 34){
		$('#block_div').show();
		
	}
	else
	{
		$('#block_div').hide();
		
	}
	
	//$('#desig').val(k);
	$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_designation.php?desig_code='+k, function(data){
		//alert(data); 
			$("#desig_block").html(data);	
		});
}

function open_assign(){ 

if(document.getElementById('master_cell_code').checked==true)
		{
			$('#sub_assign').show();
			
			
		}
		else
		{
			
			$('#sub_assign').hide();
			
			
		}
		
}

</script>