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
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}
else if($_GET['confirm'] == 'fails'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data Alresdy Exists...</strong></div>';
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
<h1 class="heading">IFMS Master Data Report</h1>
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
<form class="form-horizontal" id="ifms" name="ifms" method="post" action="ifms_master_data_download_first_join.php" onsubmit="return valid_code();">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Year Of Report<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control upper_case" name="year" id="year">
      <option value="">-Please Select-</option>
						<?php
							
							for($i=2013;$i<=date('Y');$i++)
							{
								
						?>
                        <option value="<?php echo $i?>"><?php echo $i; ?></option>
                  		 <?php
							}
						?>
      </select>
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Month Of Report</label>
    <div class="col-sm-3">
      <select class="form-control upper_case" name="month" id="month">
      <option value="">-Please Select-</option>
      <option value="01">January</option>
      <option value="02">February</option>
      <option value="03">March</option>
      <option value="04">April</option>
      <option value="05">May</option>
      <option value="06">June</option>
      <option value="07">July</option>
      <option value="08">August</option>
      <option value="09">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
      </select>
    </div>
    <div class="col-sm-3"></div>
    </div>
    
    
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
		
function valid_code(){
	if($('#year').val()==''){
		alert('Please Select Year Of Report.');
		$('#year').focus();
		return false;
	}
}
</script>