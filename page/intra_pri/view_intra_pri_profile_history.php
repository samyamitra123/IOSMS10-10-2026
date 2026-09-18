<?

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

header("Strict-Transport-Security: max-age=63072000");

session_start();

require '../../includes/config/config.php';
require '../../includes/config/database.config.php';

require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';


/*
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/intra_pri/login_intra_pri.php");
	exit;
} */
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
	padding: 20px;
	
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
/*if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}*/

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "GP PROFILE VIEW| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
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
<? require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
		<?php 
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		//var_dump($_SESSION["user_info"]["stake_user_code"]);
		$officer_detail = $db->fetch_table(" SELECT master.*, desig.designation as designation FROM intra_pri_master as master
											INNER JOIN intra_pri_designation_master as desig ON CAST(master.designation AS character varying) = desig.designation_code 
											WHERE master.higher_authority_code = '".$_SESSION['user_info']['stake_level_code']."' 
											AND higher_authority_stake_user_code = '".$_SESSION['user_info']['stake_user_code']."' ");
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">VIEW SUBMITTED PROFILE</h1>
<div class="border"></div>
</br>
</br>
<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/
if(isset($_SESSION['gp_msg'])){
	echo $_SESSION['gp_msg'];
	unset($_SESSION['gp_msg']);
}
if(isset($_SESSION['unlock_msg'])){
	echo $_SESSION['unlock_msg'];
	unset($_SESSION['unlock_msg']);
}
?>
<div class="emplist" style="width: 99%;">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>SL NO</th>
<th>NAME</th>
<th>DESIGNATION</th>
<th>STATUS</th>
<th>VIEW</th>
<th>ACTION</th>
<!-- <th>Designation</th> -->
</tr>


<?php 
		
	$count=1;	
foreach($officer_detail as $key=>$value){?>
<tr>
	<td><?php echo $count; ?></td>
	<td><?php echo $value['officer_name']; ?></td>
	<td><?php echo $value['designation']; ?></td>
	<td><?php if($value['active_status'] == 1){ echo "Active"; } else { echo "Inactive"; }?></td>
	<td class="view_prof"><a href="" id="<?= $value['mobile_no'];?>" data-bs-toggle="modal" data-bs-target="#bdoprofModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
	
	<td><a href="<?= $config['base_url'] ?>page/intra_pri/edit_profile_form_intra_pri.php?id=<?= $value['mobile_no'];?>"><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" /></a></td>
</tr>
	<!--<td><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" style="opacity:0.5" /></td>-->
<?php $count++;

} ?>

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





<?php require '../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
		$(document).ready(function(){
		$(".view_prof a").click(function() {	
        //var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_user_view_intra_pri.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		});
</script>

<div class="modal fade" id="bdoprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">Profile Details</h4>
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

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<!--<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This BDO Profile For Approval ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_prd/block/sent_block_profile.php?action=approval">YES</a> 
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>-->

<!--<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This BDO Profile For UNLOCK ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_prd/block/unlock_bdo_profile.php">YES</a>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>-->