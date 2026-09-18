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


function find_dist($id){
	$db=new database();
	$block=$db->fetch_table("select district_name from prd_location_master_district where district_id_pk='".$id."'");
	return $block[0]['district_name'];
}


$db=new database();
$data=$db->fetch_table("Select block_status,block_code,block_name,district_id_fk FROM prd_location_master_block where block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$data1=$db->fetch_table("select block_code, bdo_name, mobile_no, road_name, vill_name, 
            post_office, police_station, pin_code, email_id, contact_no, 
            tan_no from prd_block_profile where block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$block_name=$data[0]['block_name'];
$block_code=$data[0]['block_code'];
$flag=$data[0]['block_status'];

$bdo_name=$data1[0]['bdo_name'];
$mobile_no=$data1[0]['mobile_no'];
$road_name=$data1[0]['road_name'];
$vill_name=$data1[0]['vill_name'];
$post_office=$data1[0]['post_office'];
$police_station=$data1[0]['police_station'];
$tan=$data1[0]['tan_no'];
if($data1[0]['pin_code']==0){
$pin_code='';
}else{
$pin_code=$data1[0]['pin_code'];
}
$email_id=$data1[0]['email_id'];
if($data1[0]['contact_no']=='0'){
$contact_no='';	
}else{
$contact_no=$data1[0]['contact_no'];

}
?>
<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_prd/district/pdf_block_details.php?id=<?=$_REQUEST['id']?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>
<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
<td colspan="4" style="text-align:center"><strong>BDO PROFILE</strong></td>
</tr>
<tr class="success">
<td><strong>DISTRICT NAME :</strong></td>
<td><?php echo find_dist($data[0]['district_id_fk']); ?></td>
<td><strong>BLOCK CODE :</strong></td>
<td><?php echo $block_code; ?></td>
</tr>
<tr class="warning">
<td><strong>BLOCK NAME :</strong></td>
<td><?php echo $block_name; ?></td>
<td><strong>NAME of BDO:</strong></td>
<td><?php echo $bdo_name; ?></td>
</tr>
<tr class="success">
<td><strong>MOBILE NO. :</strong></td>
<td><?php echo $mobile_no; ?></td>
<td><strong>ROAD NAME:</strong></td>
<td><?php echo $road_name; ?></td>
</tr>
<tr class="warning">
<td><strong>VILLEGE/TOWN NAME :</strong></td>
<td><?php echo $vill_name; ?></td>
<td><strong>POST OFFICE:</strong></td>
<td><?php echo $post_office; ?></td>
</tr>
<tr class="success">
<td><strong>POLICE STATION :</strong></td>
<td><?php echo $police_station; ?></td>
<td><strong>PIN CODE:</strong></td>
<td><?php echo $pin_code; ?></td>
</tr>
<tr class="warning">
<td><strong>CONTACT NO. :</strong></td>
<td><?php echo $contact_no; ?></td>
<td><strong>EMAIL ID:</strong></td>
<td><?php echo $email_id; ?></td>
</tr>
<tr class="success">
<td><strong>TAN Number :</strong></td>
<td><?php echo $tan; ?></td>
<td></td>
<td></td>
</tr>
</table>
</div>
<? if($flag=='1'){ ?>
<div>
<a class="btn btn-success" style="margin-left:43%" href="<?= $config['base_url']?>page/intra_prd/district/bdo_profile_finalize.php?id=<?=$_REQUEST['id']?>&action=<?= $cryptoGraph->encode('approve',4)?>">Approve</a>
<a class="btn btn-danger" href="<?= $config['base_url']?>page/intra_prd/district/bdo_profile_finalize.php?id=<?=$_REQUEST['id']?>&action=<?= $cryptoGraph->encode('reject',4)?>">Reject</a>  
</div>
<? } ?>