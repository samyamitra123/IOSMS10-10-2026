<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
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
//$arr=$db->fetch_table("select emp_id_const,emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk from prd_employee_master_ngipf where emp_status in('1') AND gp_id_fk='".$cryptoGraph->decode($_REQUEST['id'],4)."' order by emp_id_const");
$gp_id =$cryptoGraph->decode($_REQUEST['id'],4);
$Query = "select  
                 pem.emp_first_name,
                 pem.emp_second_name,
                 pem.emp_last_name,
                 pem.emp_desig,
                 pem.emp_status,
                 pem.emp_id_pk,
                 pem.emp_system_code,
                 pem.emp_id_const,
                 pem.gp_id_fk,
                 pgrm.pfaccno,
                 pgrm.status
          from prd_employee_master as pem
          LEFT JOIN prd_gpf_request_master as pgrm on pem.emp_id_const= pgrm.emp_id_const
          where emp_status in('6','1','9') AND gp_id_fk='".$gp_id."' 
          order by emp_first_name";
$arr=$db->fetch_table($Query);
$gp_name=$db->fetch_table("select gp_name,gp_code from prd_location_master_gp where gp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."'");

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}




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
<h1 class="heading">EDIT EMPLOYEE LIST FOR <?= $gp_name[0]['gp_name'].' ('.$gp_name[0]['gp_code'].')'?></h1>
<div class="border"></div>
</br>
</br>
<?php 
if(!empty($msg)){
echo $msg;
echo "<br/>";
echo "<br/>";
}
?>
<div class="msg"></div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee ID</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Status</th>
<th>Pf Acc</th>
<th>NGIPF Status</th>
<th>Action</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
if($item['emp_status']=='1') {
	$status='<span style="color:green;font-weight:bold">FINALIZED</span>';
}
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_id_const']?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $status; ?></td>
<td><?= $item['pfaccno']; ?></td>
<td>
	  <?php 
        if($item['status']==0)
       	 {echo "Submit";}
       	elseif($item['status']==2)
       	  {	echo "Pending"; }
       	elseif($item['status']==1)
       		{ echo "Success"; }
       	elseif($item['status']==3)
       		{ echo "Failed Update"; }       	
       	else
       		{ echo "Not Submitted"; }
	  ?>	

</td>
<!--<td class="view"><a href="<?= $config['base_url']?>page/intra_prd/block/edit_emp/edit_employee.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>&gp_id=<?=$_REQUEST['id']; ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>-->
<td>

	<!--<a href="<?= $config['base_url']?>page/api/gpf/index_gpf.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" type="button" class="btn btn-outline-primary" >SEND API</a>-->

<?php if($item['status']==2): ?>
		<span class="btn btn-outline-primary disable">SEND API</span>
		<a href="<?= $config['base_url']?>page/api/gpf/show_gpf.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" type="button" class="btn btn-outline-primary" >Show Data</a>
	<?php elseif(($item['status']==1 || $item['status']==3) && $item['pfaccno']!='') : ?>
	<a href="<?= $config['base_url']?>page/api/gpf/show_gpf.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" type="button" class="btn btn-outline-primary" >Show Data</a>	
	<a href="<?= $config['base_url']?>page/api/gpf/updateapi.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" type="button" class="btn btn-outline-primary" >SEND API</a>
  <?php else: ?>
	<a href="<?= $config['base_url']?>page/api/gpf/index_gpf.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" type="button" class="btn btn-outline-primary" >SEND API</a>
<?php endif; ?>

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



