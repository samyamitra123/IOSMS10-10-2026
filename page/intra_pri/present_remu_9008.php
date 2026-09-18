
<?php

session_start();
ob_start();
require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
/*if($_SERVER['HTTP_REFERER']==''){
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
*/
 
 if($_POST['val'] == 'A'){
		 
	$period_eng = $_POST['period_eng'];
	$cat_post = $_POST['cat_post'];
	
	$db=new database();
	$arr_remu =$db->fetch_table("select * from intra_pri_9008_remuneration where post_category='".$cat_post."' and period_of_engagement='".$period_eng."'");
	?>
	 <option value="">--Please Select--</option>
	<?php foreach($arr_remu as $key){ ?>
	<option value="<?=$key['present_remuneration']; ?>"><?php echo $key['present_remuneration'] ?></option>

	<?php }
 }
 
 
else if($_POST['val'] == 'B'){
		 
	$app_cas_day_con_worker = $_POST['app_cas_day_con_worker'];
	$gp_ps_zp_B = $_POST['gp_ps_zp_B'];

	$db=new database();
	$arr_remu_B =$db->fetch_table("select * from entry_format_9008 where gp_ps_zp_code='".$gp_ps_zp_B."' and worker_name='".$app_cas_day_con_worker."'");
	echo $arr_remu_B[0]['present_remuneration'];
 }
 
 
 
 else if($_POST['val'] == 'AUTO_CAL'){
		 
	$app_cas_day_con_worker_C = $_POST['app_cas_day_con_worker_C'];
	$gp_ps_zp_C = $_POST['gp_ps_zp_C'];
	$fin_year = $_POST['fin_year'];

	$db=new database();
	$arr_remu_B =$db->fetch_table("select * from entry_format_9008 where gp_ps_zp_code='".$gp_ps_zp_C."' and worker_name='".$app_cas_day_con_worker_C."'");
	echo $arr_remu_B[0]['present_remuneration']*12;
 }
 
 
 
 
else if($_POST['val'] == 'B_worker'){ 
	$gp_ps = $_POST['gp_ps'];
	$db=new database();
	$arr_emp_name =$db->fetch_table("select worker_name from entry_format_9008 where gp_ps_zp_code='".$gp_ps."' ");
	?>
	<option value="">--Please Select--</option>
	<?php foreach($arr_emp_name as $key){ ?>
	<option value="<?=$key['worker_name']; ?>" ><?= $key['worker_name']; ?></option>

<?php } 
}



else if($_POST['val'] == 'B_PS'){ 
	$ps = $_POST['ps'];
	$selected = $_POST['mselected'];

	$db = new database();
		$arr_block = $db->fetch_table("select block_name, block_code from prd_location_master_block WHERE block_code='".$ps."'");
	?>
	<option value="">--Please Select--</option>
	<?php foreach($arr_block as $key){ ?>
	<option value="<?=$key['block_code']; ?>" <?php echo ($selected == $key['block_code'])?'selected':'';?>><?= $key['block_name']; ?></option>

<?php } 
}



else if($_POST['val'] == 'B_GP'){ 
	$ps = $_POST['ps'];
	$selected = $_POST['mselected'];
	$db = new database();
		$arr_gp =$db->fetch_table("select gp.gp_name as gp_name , gp.gp_code as gp_code from prd_location_master_gp as gp
		INNER JOIN prd_location_master_block as block ON gp.block_id_fk = block.block_id_pk where block.block_code = '".$ps."'");
	?>
	<option value="">--Please Select--</option>
	<?php foreach($arr_gp as $key){ ?>
	<option value="<?=$key['gp_code']; ?>"  <?php echo ($selected == $key['gp_code'])?'selected':'';?>><?= $key['gp_name']; ?></option>

<?php } 
}
?>

