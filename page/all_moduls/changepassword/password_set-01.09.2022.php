<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

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
//---------------------------------- MENU -----------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic-------------------------------------------------------------------

$db= new database();
  $crypto = new cryptography();

/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";
*/
if($_SESSION['user_info']['stake_abbr']=='EO')
{
	$db= new database();
	$gp=$db->fetch_table("select ps_id_pk,ps_code,ps_name from prd_location_master_panchayat_samiti 
Where ps_code='".$_SESSION['location']['block_code']."' order by ps_code");
}
else
{
	$gp=$db->fetch_table("select gp_id_pk,gp_code,gp_name from prd_location_master_gp gp
inner join prd_location_master_block block on block.block_id_pk=gp.block_id_fk
Where block_code='".$_SESSION['location']['block_code']."' order by gp_code");
}

?>


<script type="text/javascript" src="<?= $config['base_url'] ?>themes/default/js/commonfunc.js" /></script>

<script src="<?= $config['base_url'] ?>themes/default/js/crypto-js.js" type="text/javascript"></script>


<script>
		
		
		function validContact(){
			
		//alert(document.getElementById('pass_chk').value);
		if(document.getElementById('gp').value==''){
		alert("Please Select GP Name.");
		document.getElementById('gp').focus();
		return false;
	}


					
	return true;
}
		
		//--------------------------------------------
		</script>



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
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">USEER SET PASSWOARD FOR EMPLOYEE LOGIN </h1>
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
    <form class="form-horizontal" method="post" name="login_form" action="<?= $config['base_url']?>page/all_moduls/changepassword/password_set_insert.php" onsubmit="return validContact();">
        <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("set",4); ?>" />
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    
    <?php if($_SESSION['user_info']['stake_abbr']=='EO')
{?>


 <label for="inputPassword3" class="col-sm-3 control-label">PS Name: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control upper_case" name="gp" id="gp "onchange="show_gp(this.value)">
      <option value="">-Please Select-</option>
						<?php
							
							foreach($gp as $key)
							{
								
						?>
                        <option value="<?php echo $key['ps_id_pk']?>"><?php echo $key['ps_name']; ?></option>
                  		 <?php
							}
						?>
      </select>
      </div>
<?php }
else
{?>


    
    <label for="inputPassword3" class="col-sm-3 control-label">GP Name: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control upper_case" name="gp" id="gp "onchange="show_gp(this.value)">
      <option value="">-Please Select-</option>
						<?php
							
							foreach($gp as $key)
							{
								
						?>
                        <option value="<?php echo $key['gp_id_pk']?>"><?php echo $key['gp_name']; ?></option>
                  		 <?php
							}
						?>
      </select>
      
    
    </div>
     <?php }?>
    <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">EMPLOYEE NAME: <span class="star_color">*</span></label>
    <div class="col-sm-3">
 
      
      <select class="form-control"  name="employee_name" id="employee_name" onchange="show_name(this.value)">
    <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['emp_id_const']. '<br />'; ?>
<option value="<?= $key['emp_id_const']; ?>"<? if($block_id_fk==$key['emp_first_name']){ echo "selected";}?>><?= $key['emp_first_name']; ?></option>
<? } ?>
    </select>
      
    </div>
    <div class="col-sm-3"><div id="pass-info" class="float_l col"></div></div>
    </div>
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">EMPLOYEE ID: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      
      <input type="text" class="form-control" name="employee_id"  readonly="readonly"id="employee_id">
       
     
    </div>
    <div class="col-sm-3"><div id="pass-info" class="float_l col"></div></div>
    </div>
    
     <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">EMPLOYEE MOBILE NO :<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="e_mobile" readonly="readonly" id="e_mobile"  />
    </div>
    <div class="col-sm-3">
      </div>
    </div>
    
    
    
    <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">DEFAULT PASSWORD :<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="d_pin" readonly="readonly" id="d_pin"  />
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

<script>
function show_gp(val)
	{
		//alert(val);
		//return false;
		$.post('<?= $config['base_url'] ?>page/all_moduls/changepassword/ajax_details.php?id='+val, function(data){
			
			//alert(data);
			
			
		$("#employee_name").html(data);	
		});
	
	}
	
	function show_name(val)
	{
		//alert(val);
		//return false;
		$.post('<?= $config['base_url'] ?>page/all_moduls/changepassword/ajax_employee_name.php?id='+val, function(data){
			
			//alert(data);
			
			//return false;
			 var result = $.parseJSON(data);
			
			
		$("#employee_id").val(result[0]);
		$("#d_pin").val(result[1]);
		$("#e_mobile").val(result[2]);
		});
	
	}
	</script>
    
 