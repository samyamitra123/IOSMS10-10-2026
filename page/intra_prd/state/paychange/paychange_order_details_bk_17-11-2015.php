<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
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

///------------------------------------------------------------------pdf pdf----------------------------------------------------------- 
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
$common['title'] = "Paychange Order Details| PRD | Govt. of West Bengal ";

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
										select count(*) AS count FROM prd_admin_paychange									
									");
			
	 		
				$order_file_details = $db->fetch_table("
																select entrydate,paychange_fromdate,order_file_name,paychange_id_pk,flag
																FROM prd_admin_paychange
																order by 
																paychange_fromdate DESC
										");
								

//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->
<script type="text/javascript" src="themes/default/js/commonfunc.js"></script>

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
					<? echo $_SESSION['location']['state_name'];
                      ?></h3>
       </div>
       <div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Paychange Order Details</h1>
<div class="border"></div>
</br>
</br>
<?php 
if($_SESSION['msg']){
echo $_SESSION['msg'];
unset ($_SESSION['msg']);
}
?>
<a href="<?= $config['base_url']?>page/intra_prd/state/paychange/paychange_order_insert.php" class="btn btn-info btn-sm">Add New Order</a><br/><br/>

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
	$order_id=$cryptography->encode($key['paychange_id_pk'],3);
	?>
<tr>
<td><?= $cnt;?></td>
<td><?=revdbdate($key['entrydate']); ?></td>
<td><?= revdbdate($key['paychange_fromdate'])?></td>
<td><a href="<?=$config['base_url'] ?>readwrite/upload/upload_paychange/<?php  echo $key['order_file_name']  ?>" target='_blank'><i class="fa fa-download fa-2x"></i></a></td>
<td><a href="paychange_order_edit.php?order_id=<?=$order_id?>"><i class='fa fa-pencil-square-o fa-2x'></i></a></td>
<td style="width: 20%;">
<div class="btn-group" role="group">
<a href="activate_deactivate_paychange_submit.php?id=<?=$cryptography->encode($key['paychange_id_pk'],4)?>&flag=activate" class="btn btn-success" <? if($key['flag']=='t'){ ?> disabled="disabled" <?php } ?>>Activate</a>
<a href="activate_deactivate_paychange_submit.php?id=<?=$cryptography->encode($key['paychange_id_pk'],4)?>&flag=deactivate" class="btn btn-danger" <? if($key['flag']=='f'){ ?> disabled="disabled" <?php } ?>>Deactivate</a>
</div>
</td>
</tr>
 


<? $cnt+=1; }} else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
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
