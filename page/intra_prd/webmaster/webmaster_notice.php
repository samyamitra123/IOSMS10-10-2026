<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
error_reporting(0);
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
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
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>
<?
$db=new database();
$arr=$db->fetch_table("SELECT upload_id_pk, date, ip, page_title, description, meta_keyword,meta_description,flag, upload_category_id_fk FROM prd_upload where
												prd_upload.flag in (1,2);");
?>
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
<? require '../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
               
  </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Notice/News & Events</h1>
<div class="border"></div>
</br>
</br>
<?php 
if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}
if(!empty($_SESSION['message'])){
	echo $_SESSION['message'];
	echo "<br/>";
	unset($_SESSION['message']);
}
?>
<a href="<?= $config['base_url']?>page/intra_prd/webmaster/webmaster_add_new_notice.php" class="btn btn-info btn-sm">Upload Notice / News And Events</a><br/><br/>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Type</th>
<th>Title</th>
<th>Status</th>
<th>Edit</th>
<th>Delete</th>
<th  style="width: 21%;">Action</th>
</tr>
<? $cnt=1; 
if(!empty($arr)){
foreach($arr as $item){
if($item['upload_category_id_fk']=='1')
 $type="NOTICE";
if($item['upload_category_id_fk']=='2')
 $type="NEWS & EVENTS";
if($item['flag']=='1')
 $status="ENABLED";
if($item['flag']=='2')
 $status="DISABLED";
?>
<tr>
<td><?= $cnt ?></td>
<td><?= $type ?></td>
<td><?= $item['page_title'] ?></td>
<td><?= $status ?></td>
<td><a  href="<?= $config['base_url'] ?>page/intra_prd/webmaster/webmaster_add_new_notice.php?type=<?php echo $cryptoGraph->encode($item['upload_category_id_fk'],4); ?>&edit=<?php echo $cryptoGraph->encode($item['upload_id_pk'],4); ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/webmaster/webmaster_delete.php?type=<?php echo $cryptoGraph->encode($item['upload_category_id_fk'],4); ?>&id=<?php echo $cryptoGraph->encode($item['upload_id_pk'],4); ?>"><i class="fa fa-trash-o fa-2x"></i></a></td>
<td class="btn-group col-sm-2">
<? if($item['flag']=='1'){ ?>
<a class="btn btn-success btn-sm disabled">Enable</a>
<? } else { ?>
<a class="btn btn-success btn-sm" href="<?= $config['base_url'] ?>page/intra_prd/webmaster/webmaster_enable_disable.php?type=<?php echo $cryptoGraph->encode($item['upload_category_id_fk'],4); ?>&id=<?php echo $cryptoGraph->encode($item['upload_id_pk'],4); ?>&flag=<?= $cryptoGraph->encode('enable',4) ?>">Enable</a>
<? } if($item['flag']=='2') { ?>
<a class="btn btn-danger btn-sm disabled" >Disable</a>
<? } else { ?>
<a class="btn btn-danger btn-sm" href="<?= $config['base_url'] ?>page/intra_prd/webmaster/webmaster_enable_disable.php?type=<?php echo $cryptoGraph->encode($item['upload_category_id_fk'],4); ?>&id=<?php echo $cryptoGraph->encode($item['upload_id_pk'],4); ?>&flag=<?= $cryptoGraph->encode('disable',4) ?>">Disable</a>
<? } ?>
</td>
</tr>
<? $cnt++;} } else { ?>
<tr>
<td colspan="7" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>
