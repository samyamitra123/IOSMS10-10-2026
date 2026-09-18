<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
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
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">ADD EMPLOYEE</h1>
<div class="border"></div>
<br /><br /><br />
<?
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg']."<br/>";
	unset($_SESSION['msg']);
}
?>
<form class="form-horizontal" id="loginForm" method="post" action="add_employee_submit.php" onsubmit="return valid_code();">
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
	<div class="row mb-3" style="margin-left: 14%;">
    <div class="col-sm-2"></div>
    <label for="inputEmail3" class="col-sm-3 col-form-label">Select Type: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select name="type" id="type" class="form-control">
      <option value="">Please Select</option>
      <option value="1">New Employee</option>
      <option value="2">Transferred Employee</option>
       <option value="3">Direct Recruitment under PRI as Departmental Candidate</option>
      </select>
    </div>
    <div class="col-sm-3"></div>
    </div>
    
     <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" class="btn btn-info" name="submit">SUBMIT</button>
    </div>
  </div>
</form>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  

<script>
function valid_code(){
	if($("#type").val()==""){
		alert("Please Select Type");
		$("#type").focus();
		return false;
		
	}
}
</script>