<?php
//error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
$crypto = new cryptography();

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
/*if(strcmp("http://10.173.22.87:8085/missing/ehrmsdemo/page/intra_ehrms/state/visit_his/ajax_view_page_visit_history.php",$url)==0 || strcmp("http://10.173.22.87:8085/missing/ehrmsdemo/page/intra_ehrms/state/visit_his/ajax_view_page_visit_history.php?",$url)==0){
		header('Location: ../view_page_visit_history.php'); 
}
*///------------------------------------------------------------------pdf pdf----------------------------------------------------------- 
function dbdate($caldate){
				
 		$tmp=explode("-",$caldate);
		return  $tmp;
 }
 
function revdbdate($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 }  

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "PTAX Order Details | PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//-----------------------------QUERY----------------------------------------------------------------------------------
$db = new database();
$cryptography=new cryptography();	
					 $arr2 = $db->fetch_table("
										select count(*) AS count FROM psemp_ptax_order_file									
									");
			
	 		
				$order_file_details = $db->fetch_table("
																select create_date,activation_date,file_name,ptax_orderfile_pk,active_status
																FROM psemp_ptax_order_file
																order by 
																ptax_orderfile_pk DESC
																
										");
								

//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->
<script type="text/javascript" src="themes/default/js/commonfunc.js"></script>
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
     
<div class="content">

	<?php require '../../../common_back_btns.php'; ?>	
   
     <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      </h2><h3>
					<? echo $_SESSION['location']['state_name'];
                      ?></h3>
       </div>
       <div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Ptax Order Details</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(isset($_SESSION['msg'])){
echo $_SESSION['msg'];
unset ($_SESSION['msg']);
}
?>
     <a href="<?= $config['base_url']?>page/intra_ps/state/ptax/ptax_order_insert.php" class="btn btn-info btn-sm">Add New Order</a><br/><br/>
     
     
     <div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Entry Date</th>
<th>Activation Date</th>
<th>Download Link</th>
<th>Edit</th>
<th>Action</th>
</tr>
<? $cnt=1; if(count($order_file_details)){ foreach($order_file_details as $key){
	$order_id=$cryptography->encode($key['ptax_orderfile_pk'],3);
	?>
<tr>
<td><?= $cnt;?></td>
<td><?=revdbdate($key['create_date']); ?></td>
<td><?= revdbdate($key['activation_date'])?></td>
<td>
<a href="<?=$config['base_url'] ?>page/intra_ps/state/ptax/ptax_order_download.php?order_file_name=<?php  echo $key['file_name']  ?>"><i class="fa fa-download fa-2x"></i></a>
</td>
<td><a href="ptax_order_edit.php?order_id=<?=$order_id?>"><i class='fa fa-pencil-square-o fa-2x'></i></a></td>
<td style="width: 20%;">
<div class="btn-group" role="group">
<a href="activate_deactivate_ptax_submit.php?id=<?=$cryptography->encode($key['ptax_orderfile_pk'],4)?>&flag=activate" class="btn btn-success" <? if($key['active_status']=='1'){ ?> disabled="disabled" <?php } ?>>Activate</a>
<a href="activate_deactivate_ptax_submit.php?id=<?=$cryptography->encode($key['ptax_orderfile_pk'],4)?>&flag=deactivate" class="btn btn-danger" <? if($key['active_status']=='0'){ ?> disabled="disabled" <?php } ?>>Deactivate</a>
</div>
</td>
</tr>
 


<? $cnt+=1; }} else { ?>
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
</div>
<div class="clear"></div>

<? require '../../../../page/layout/footer.php'; ?>