<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';


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
$arr=$db->fetch_table("select 
				gp_id_pk, gp_code,gp_name,count(emp.emp_id_pk) as total
			from 
				prd_location_master_gp gp,
				prd_employee_master emp
			where 
			gp.gp_id_pk=emp.gp_id_fk AND 
			emp.bank_upd_status in(1,2,3) AND CAST(gp_code AS text) like '".$_SESSION['location']['block_code']."%' 
			group by gp_id_pk,gp_code,gp_name
			order by 
			gp_name");

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
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">GP List For Update Bank Details</h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 
if(!empty($msg)){
echo "<br/>";
echo $msg;
echo "<br/>";
}
if(isset($_SESSION['bank_msg'])){
	echo "<br/>";
	echo $_SESSION['bank_msg'];
	echo "<br/>";
	unset($_SESSION['bank_msg']);
}
?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>GP Code</th>
<th>GP Name</th>
<th>Total Request</th>
<th>View</th>
</tr>
<?php $cnt=1;$total=0; if(count($arr)){ foreach($arr as $item){ 
$total+=$item['total'];
?>

<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_code'] ?> </td>
<td><?= $item['gp_name']?></td>
<td><?= $item['total'] ?> </td>
<td><a href="<?= $config['base_url'] ?>page/intra_prd/block/update_bank_details.php?gp_id=<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td></tr>
<?php $cnt++;} ?>
<tr>
<td></td>
<td></td>
<td style="font-weight:bold;">Total</td>
<td><?= $total; ?></td>
<td></td>
</tr>
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
</div>
<div class="clear"></div>

<? require '../../../page/layout/footer.php'; ?>