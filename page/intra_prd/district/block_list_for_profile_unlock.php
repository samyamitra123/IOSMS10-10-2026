<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "District List| PRD | Govt. of West Bengal ";

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
$district_id_pk=$db->fetch_table('select district_id_pk from prd_location_master_district 
							where district_code='.$_SESSION['location']['district_code'].'');
 $id=$district_id_pk[0]['district_id_pk'];
						 
$arr=$db->fetch_table("select block_code,block_name,block_status
FROM prd_location_master_block 
WHERE district_id_fk='".$id."'
order by block_code");
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
      <h2>WELCOME: <?php echo $_SESSION['user_info']['stake_abbr']; ?>
     </h2> <h3>
					<? if(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].",".$_SESSION['location']['state_name'];
                      }
                      ?></h3>
      
    </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">BLOCK LIST FOR PROFILE UNLOCK</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(isset($_SESSION['unlock_msg'])){
	echo $_SESSION['unlock_msg'];
	unset($_SESSION['unlock_msg']);
}
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Block Name</th>
<th>Block Code</th>
<th>Status</th>
<th>Unlock</th>
</tr>
<? $cnt=1;
if(count($arr)){ foreach($arr as $item){ 
if($item['block_status']=='0'){
	$status='<p class="text-primary" style="font-weight:bold">PROFILE NOT SENT</p>';
}
else if($item['block_status']=='1'){
	$status='<p class="text-warning" style="font-weight:bold">WAITING FOR FINALIZATION</p>';
}
else if($item['block_status']=='2') {
	$status='<p class="text-success" style="font-weight:bold">APPROVED</p>';
}
else if($item['block_status']=='3') {
	$status='<p class="text-danger" style="font-weight:bold">PROFILE REJECTED</p>';
}
else if($item['block_status']=='5') {
	$status='<p class="text-warning" style="font-weight:bold">WAITING FOR PROFILE UNLOCK</p>';
}
else if($item['block_status']=='6') {
	$status='<p class="text-success" style="font-weight:bold">PROFILE UNLOCKED</p>';
}
else{
	$status='<p class="text-danger" style="font-weight:bold">PROFILE INCOMPLETE</p>';
}
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['block_name']?></td>
<td><?= $item['block_code']?></td>
<td><?= $status?></td>
<? if($item['block_status']=='5'){ ?>
<td class="view"><a class="btn btn-primary" href="<?= $config['base_url'] ?>page/intra_prd/district/unlock_bdo_profile.php?id=<?= $cryptoGraph->encode($item['block_code'],4);?>"><i class="fa fa-lock"></i></a></td>
<? }else if($item['block_status']=='6'){ ?>
<td><a class="btn btn-primary disabled"><i class="fa fa-unlock-alt"></i></a></td>
<? } else{ ?>
<td class="view"><a class="btn btn-primary disabled"><i class="fa fa-lock"></i></a></td>
<? } ?>
</tr>
<? $cnt+=1; } ?>
<? } else { ?>
<tr>
<td colspan="4" style="color:red;font-weight:bold">No Data Found</td>
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
