<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
$_SERVER['HTTP_REFERER']=$_SESSION['http_referer'];
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
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

if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Edited Successfully...</strong></div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div class="alert alert-danger" style="text-align:center"><strong>Employee Profile Edited Fails...</strong></div>';
	}
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

$db=new database();
$arr=$db->fetch_table("select emp_id_const,emp_first_name,emp_second_name,emp_last_name from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($_REQUEST['emp_id_pk'],4)."'");

$name=$arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name'];
$gp_name=$db->fetch_table("select gp_name,gp_code from prd_location_master_gp where gp_id_pk='".$cryptoGraph->decode($_REQUEST['gp_id'],4)."'");
?>

<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>

<style>
h1 {
display: block;
font-size: 2em;
-webkit-margin-before: 0.67em;
-webkit-margin-after: 0.67em;
-webkit-margin-start: 0px;
-webkit-margin-end: 0px;
font-weight: bold;
}
</style>
<div class="content">
<? require '../../../../page/common_back_btns.php'; ?>
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
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EDIT EMPLOYEE </h1>
<div class="border"></div>
</br>
<?php 
if(!empty($msg)){
echo $msg;
echo "<br/>";
}
?>
<div class="msg"></div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th colspan="7" style="font-size: 25px;"><?= $gp_name[0]['gp_name'].' ('.$gp_name[0]['gp_code'].')'?></th>
</tr>
<tr>
<th>EMPLOYEE ID</th>
<th>EMPLOYEE NAME</th>
<th>PRIMARY DETAILS</th>
<th>PROFESSIONAL DETAILS</th>
<th>SALARY DETAILS</th>
<th>PERSONAL DETAILS</th>
<th>CONTACT DETAILS</th>
</tr>
<tr>
<td><?= $arr[0]['emp_id_const'];?></td>
<td><?= $name;?></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/edit_emp/profile_entry_basic_edit.php?emp_id_pk=<?=$_REQUEST['emp_id_pk']?>&gp_id=<?=$_REQUEST['gp_id'] ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/edit_emp/profile_entry_prof.php?emp_id_pk=<?=$_REQUEST['emp_id_pk']?>&gp_id=<?=$_REQUEST['gp_id'] ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/edit_emp/profile_entry_sal.php?emp_id_pk=<?=$_REQUEST['emp_id_pk']?>&gp_id=<?=$_REQUEST['gp_id'] ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/edit_emp/profile_entry_per.php?emp_id_pk=<?=$_REQUEST['emp_id_pk']?>&gp_id=<?=$_REQUEST['gp_id'] ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/edit_emp/profile_entry_contact.php?emp_id_pk=<?=$_REQUEST['emp_id_pk']?>&gp_id=<?=$_REQUEST['gp_id'] ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
</tr>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>





<? require '../../../../page/layout/footer.php'; ?>



