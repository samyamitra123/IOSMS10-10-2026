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
if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}


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
$arr=$db->fetch_table("
						SELECT gp_id_pk,gp_code,gp_name,flag
						from prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%'  GROUP BY gp_name,gp_id_pk,gp_code,gp_id_pk,flag order by gp_name

");
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
        <div class="col-sm-12" style=" width:98%;">
<h1 class="heading">Gram Panchayet LIST</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(isset($_SESSION['gp_msg1'])){
	echo $_SESSION['gp_msg1'];
	unset($_SESSION['gp_msg1']);
}
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
<th>Status</th>
<th>View</th>
</tr>
<? $cnt=1;
if(count($arr)){ foreach($arr as $item){ 
if($item['flag']=='0'){
	$status='<p class="text-primary" style="font-weight:bold">PROFILE NOT SENT</p>';
}
else if($item['flag']=='1'){
	$status='<p class="text-warning" style="font-weight:bold">WAITING FOR APPROVAL</p>';
}
else if($item['flag']=='2') {
	$status='<p class="text-success" style="font-weight:bold">APPROVED</p>';
}
else if($item['flag']=='3') {
	$status='<p class="text-danger" style="font-weight:bold">PROFILE REJECTED</p>';
}
else{
	$status='<p class="text-danger" style="font-weight:bold">PROFILE INCOMPLETE</p>';
}

?>
<input type="hidden" name="gp_status" id="gp_status" value="<?= $item['flag']?>"  />
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['gp_code']?></td>
<td><?= $status?></td>
<td class="view_gp"><a href="" id="<?= $cryptoGraph->encode($item['gp_code'],4);?>" data-bs-toggle="modal" data-bs-target="#gpprfModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
</tr>
<? $cnt+=1; } ?>
<? } else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
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

<script>
		$(document).ready(function(){
		$(".view_gp a").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_gp_view.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		});
</script>

<div class="modal fade" id="gpprfModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">GP Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>    
      </div>
    </div>
  </div>
</div>
