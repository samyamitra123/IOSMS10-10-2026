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


function find_block($block_id){
	$db=new database();
	$block=$db->fetch_table("select block_name from prd_location_master_block where block_id_pk='".$block_id."'");
	return $block[0]['block_name'];
}


$db=new database();
$data=$db->fetch_table("select gp_id_pk,gp_code,gp_name,block_id_fk from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'");
$data1=$db->fetch_table("select gram_pradhan_name, mobile_no, road_name,vill_name, post_office, police_station, pin_code,contact_no, email_id from prd_gp_profile where gp_code='".$_SESSION['user_info']['stake_user']."'");
$dise_code=$data[0]['gp_code'];
$gp_name=$data[0]['gp_name'];
$block_code=$data[0]['block_id_fk'];
if(!empty($data1)){
$pradhan_name=$data1[0]['gram_pradhan_name'];
$mobile_no=$data1[0]['mobile_no'];
$road_name=$data1[0]['road_name'];
$vill_name=$data1[0]['vill_name'];
$post_office=$data1[0]['post_office'];
$police_station=$data1[0]['police_station'];
$pin_code=$data1[0]['pin_code'];
$email_id=$data1[0]['email_id'];
$contact_no=$data1[0]['contact_no'];
}
?>
<?php /*<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_prd/gp/pdf_gp_details.php"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>*/ ?>
<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
<td colspan="4" style="text-align:center"><strong>GP PROFILE</strong></td>
</tr>
<tr class="success">
<td><strong>GP CODE :</strong></td>
<td><?php echo $dise_code; ?></td>
<td><strong>GP NAME :</strong></td>
<td><?php echo $gp_name; ?></td>
</tr>
<tr class="warning">
<td><strong>BLOCK NAME :</strong></td>
<td><?php echo find_block($block_code); ?></td>
<td><strong>NAME OF PRADHAN:</strong></td>
<td style="text-transform:uppercase;"><?php echo $pradhan_name; ?></td>
</tr>
<tr class="success">
<td><strong>MOBILE NO. :</strong></td>
<td><?php echo $mobile_no; ?></td>
<td><strong>ROAD NAME:</strong></td>
<td style="text-transform:uppercase;"><?php echo $road_name; ?></td>
</tr>
<tr class="warning">
<td><strong>VILLAGE/TOWN NAME :</strong></td>
<td style="text-transform:uppercase;"><?php echo $vill_name; ?></td>
<td><strong>POST OFFICE:</strong></td>
<td style="text-transform:uppercase;"><?php echo $post_office; ?></td>
</tr>
<tr class="success">
<td><strong>POLICE STATION :</strong></td>
<td style="text-transform:uppercase;"><?php echo $police_station; ?></td>
<td><strong>PIN CODE:</strong></td>
<td><?php echo $pin_code; ?></td>
</tr>
<tr class="warning">
<td><strong>CONTACT NO. :</strong></td>
<td><?php echo $contact_no; ?></td>
<td><strong>EMAIL ID:</strong></td>
<td><?php echo $email_id; ?></td>
</tr>
</table>
</div>