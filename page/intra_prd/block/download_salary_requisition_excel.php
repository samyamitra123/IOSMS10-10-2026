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

$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -----------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic-------------------------------------------------------------------


?>
		<script>
			$(document).ready(function(){
			  $("#year,#month").change(function () {
				 // alert($("#month option:selected").val());
			    if(
			    	$("#month option:selected").val() != "" &&
			    	$("#year option:selected").val() != "" 
			    	){
			    		mo = $("#month option:selected").val();
			    		ye = $("#year option:selected").val();
						$.get("<?=$config['base_url']?>page/intra_prd/block/salary_excel.php?mo="+mo+"&ye="+ye,function(data,status){
							//alert(data);
						if(data==0)
						{
						
							//alert('1111');
							$('#show_box').html('<div class="alert alert-danger" style="text-align:center;width:23%;margin-left:43%"><strong>NO DATA FOUND</strong></div>');
					  //alert("Data: " + data + "\nStatus: " + status);
						}
						else
						{
						//alert(2);
							$('#show_box').html('<a class="btn btn-warning btn-sm" href="<?= $config['base_url']?>page/intra_prd/block/download_excel_for_salary_requisition.php?mo='+mo+'&ye='+ye+'" style="margin-left:43%"><i class="fa fa-cloud-download fa-lg"></i> Download</a>');
					  //alert("Data: " + data + "\nStatus: " + status);
						}
					});
				}
			  });
			});
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
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%;">
<h1 class="heading">Download EXCEL FOR SALARY REQUISITION FOR ROPA 2009</h1>
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
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <form class="form-horizontal">
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">Select Year</label>
		<div class="col-sm-3">
		  <select class="form-control upper_case" name="year" id="year">
		  <option value="">-Please Select-</option>
							<?php
								
								for($i=2015;$i<=date('Y');$i++)
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
    
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">Select Month</label>
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
    <div id="show_box"></div>
<div class="clear"></div>
</form>
</div>
</div>
</div>
</div>

<? require '../../../page/layout/footer.php'; ?>

<script>
function valid_code(){
	if($('#year').val()==''){
		alert('Please Select Year Of Report.');
		$('#year').focus();
		return false;
	}
	if($('#month').val()==''){
		alert('Please Select Month Of Report.');
		$('#month').focus();
		return false;
	}
}
</script>
