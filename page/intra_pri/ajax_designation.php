<?php

session_start();
error_reporting(0);

ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:dashboard_intra_pri.php");
}

$cryptoGraph=new cryptography();
$db=new database();
$desig_code=$_GET['desig_code'];
$arr_des=$db->fetch_table("select * from intra_pri_designation_master WHERE designation_code= '".$desig_code."' order by designation");

?>
<?php foreach($arr_des as $key){

	if($key['designation_code'] == 81 || $key['designation_code'] == 82){

		$arr_des_op=$db->fetch_table("select * from intra_pri_designation_master WHERE higher_authority_code= '".$key['designation_code']."' order by designation");
		?>
		<input type="hidden" name="desig" id="desig" value="<?=$key['designation_code']; ?>" />
		<input type="hidden" name="insert_stake" id="insert_stake" value="<?=$key['login_level_stake']?>" />

		<select class="form-control" name="desig1" id="desig1">
			<option value="">-Please Select-</option>

			<?php foreach($arr_des_op as $key1){ ?>
				<option value="<?=$key1['designation_code']; ?>" ><?= $key1['designation']; ?></option>
			<?php } ?>
		</select>
	<?php }
	else{ ?>
		<input type="hidden" name="desig" id="desig" value="<?=$key['designation_code']; ?>" />
		<input type="hidden" name="insert_stake" id="insert_stake" value="<?=$key['login_level_stake']?>" />
		<input type="text" class="form-control" autocomplete="off" name="desig1" id="desig1" placeholder="Designation"  value="<?=$key['designation']; ?>" readonly >

	<? }
} ?>
