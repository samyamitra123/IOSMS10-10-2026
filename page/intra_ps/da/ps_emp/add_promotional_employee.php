<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Edited Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Employee Profile Edited Fails...</strong></div>';
}
?>
<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 5px 10px;
}

</style>

<?php
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>
<script>
$(document).ready(function(e) {
    $("#view").click(function(){
	var link=$("#emp_id").val();
	var len=link.length;
	//alert(len);
	if(link==""){
		alert('Please Enter Employee ID');
		$("#emp_id").focus();
	}
	else if(len!='12'){
		alert('Employee ID Must Be 12 Digit Long.');
		$("#emp_id").focus();
	}
	else{
	$.post('<?= $config['base_url'] ?>page/intra_ps/da/ps_emp/ajax_promosational_emp.php?id='+link,function(data){
		//alert(data);
		$("#transfer").html(data);
	});
	}
	});
});
</script>

<div class="content">
<? require '../../../../page/common_back_btns.php'; ?>
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
			<?php if($_SESSION['user_info']['stake_abbr']=='GP'){ ?>
			<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
			}else{
			    
			    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
			}
                     ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">PROMOTIONALLY RECRUITED EMPLOYEE </h1>
<div class="border"></div>
<br/><br/><br/>
<?php 
if($msg){
echo $msg;
echo "<br/>";
}
?>
<form class="form-horizontal">
	<div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputEmail3" class="col-sm-3 control-label">Employee ID: <span class="star_color">*</span></label>
		<div class="col-sm-3"> 
		  <input type="text" name="emp_id" id="emp_id" class="form-control upper_case" maxlength="12" placeholder="Enter Employee ID" value="" />
		</div>
		<div class="col-sm-3">
		<a id="view" class="btn btn-info">ADD</a>
		</div>
    </div>
</form>
<div id="transfer"></div>
<?php 
if(!empty($_SESSION['sent_msg'])){
echo $_SESSION['sent_msg'];
unset($_SESSION['sent_msg']);
}
?>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>





<? require '../../../../page/layout/footer.php'; ?>



