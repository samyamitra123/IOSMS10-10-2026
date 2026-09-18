<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}

$db=new database();
$arr=$db->fetch_table(  "
select gp_id_pk,gp_name,gp_code from prd_location_master_gp gp inner join prd_location_master_block block on gp.block_id_fk=block.block_id_pk WHERE block.block_code='".$_SESSION['location']['block_code']."' except SELECT 
	DISTINCT(adv.gp_id_fk),
	gp.gp_name,
	gp.gp_code
	from prd_location_master_gp gp 
	left join prd_adavance_pay adv on gp.gp_id_pk=adv.gp_id_fk 
	WHERE adv.block_code='".$_SESSION['location']['block_code']."'  
	AND adv.advance_monthyear='".date('Ym')."' and adv.status_flag in ('2','3')
						
");
//$count=count($arr_school);
?>

<div class="school">
<div class="table-responsive">
<a class="btn btn-info btn-sm" id="final" style="float: right;" href="advance_salary_finalize.php">Show Finalized GP</a>
<div class="text-warning" style="font-family:'MS Serif', 'New York', serif; font-size:18px; font-weight:400; ">
<strong>Total <?php echo $count; ?> GP Not Finalized.</strong>
</div>

<!--<a class="btn btn-info btn-sm" style="float: right;margin-right: 1%;">Lock GP</a>-->
<table width="100%" cols="4">
<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
</tr>
<? $cnt=1;$total_emp=0;$total_finz=0;$total_waiting=0;$total_incomplete=0;$total_rej=0;$total_req_not_sent=0; 
if(count($arr)){ 



foreach($arr as $item){ 

if($item['status_flag'] == 2){
 $status= "<div style='color:#080;'>FINALIZED</div>";
 } elseif($item['status_flag'] == 3){
  $status= "<div style='color:#F00;'>LOCKED</div>";
 }
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['gp_code']?></td>
<?php  $cnt++;} ?>
</tr>

<? } else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found </td>
</tr>
<? } ?>
</table>
</div>
</div>


