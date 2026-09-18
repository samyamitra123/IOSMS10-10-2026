<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
error_reporting(0);

?>

<?
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require_once '../../includes/library/database.class.php';
//require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

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
require '../../page/layout/header.php';
//---------------------------------- MENU -----------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic-------------------------------------------------------------------

$db= new database();

?>


<script type="text/javascript" src="<?= $config['base_url'] ?>themes/default/js/commonfunc.js" /></script>

<script src="<?= $config['base_url'] ?>themes/default/js/crypto-js.js" type="text/javascript"></script>


<script>
		
		
	function validContact(){
		if(document.getElementById('officer_name').value==''){
		alert("Please Select Officer Name.");
		document.getElementById('officer_name').focus();
		return false;
	}


					
	return true;
}
		
		//--------------------------------------------
		</script>



<div class="content">
<?php require 'common_back_btns_intra_pri.php'; ?>
  <div class="welcome_msg">
	<?php
	//echo 33;die;
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");

		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">RESET PASSWOARD FOR OFFICER LOGIN </h1>
<div class="border"></div>
</br>
<div id="msg"></div>
<?php 
                if(isset($_SESSION['msg'])){
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
					echo '<br><br>';
                }
                ?>
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <form class="form-horizontal" method="post" name="login_form" action="<?= $config['base_url']?>page/intra_pri/password_reset_insert_intra_pri.php" onsubmit="return validContact();">
   
    
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">OFFICER NAME: <span class="star_color">*</span></label>
    <div class="col-sm-3">
 
      
    <select class="form-control"  name="officer_name" id="officer_name" onchange="show_name(this.value)">
		<option value="">-Please Select-</option>
			<?php 
			$reset_officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE higher_authority_code = '".$_SESSION['user_info']['stake_level_code']."' 
			AND higher_authority_stake_user_code = '".$_SESSION['user_info']['stake_user_code']."' ");
			
			foreach($reset_officer_name as $key){ ?>
		<option value="<?= $key['officer_id_const']; ?>"><?= $key['officer_name']; ?></option>
			<?php } ?>
    </select>
      
    </div>
    <div class="col-sm-3"><div id="pass-info" class="float_l col"></div></div>
    </div>
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">OFFICER ID: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="officer_id"  readonly="readonly" id="officer_id" placeholder="OFFICER ID" >
    </div>
    <div class="col-sm-3"><div id="pass-info" class="float_l col"></div></div>
    </div>
    
     <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">OFFICER MOBILE NO :<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="e_mobile" readonly="readonly" id="e_mobile" placeholder="OFFICER MOBILE NO" />
    </div>
    <div class="col-sm-3">
      </div>
    </div>
    
    
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">DEFAULT PASSWORD :<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="d_pin" readonly="readonly" id="d_pin" placeholder="DEFAULT PASSWORD" />
    </div>
    <div class="col-sm-3">
      </div>
    </div>
    
    <div class="row mb-3" style="margin-left: 41%;">
    <div class="col-sm-offset-5 col-sm-7">
      <input type="submit" name="change_gp" value="SUBMIT" class="btn btn-info">
    </div>
  </div>
  
    </form>
    <div class="clear"></div>
    
   
   <div class="clear"></div>    
</div>
</div>
</div>
</div>

<? require '../../page/layout/footer.php'; ?>


<script>

	function show_name(val)
	{
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_officer_name_intra_pri.php?id='+val, function(data){
			var result = $.parseJSON(data);
			$("#officer_id").val(result[0]);
			$("#d_pin").val(result[1]);
			$("#e_mobile").val(result[2]);
		});
	
	}
	</script>
    
 