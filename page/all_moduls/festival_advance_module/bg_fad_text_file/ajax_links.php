
<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';     
//require '../../page_visite.php';


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 $k=strtotime("first day of last month");
  $arr = date("Y-m-d",$k); 


$current_month_first_date=date("Y-m-01");
$next_month_date =date("Y-m-d", strtotime("$current_month_first_date +1 month")); 
$after_ten_month_date=date("Y-m-d", strtotime("$current_month_first_date +10 month"));

$next_monthyear_of_current_month=substr($next_month_date,0,4).substr($next_month_date,5,2);
$next_ten_monthyear_of_current_month=substr($after_ten_month_date,0,4).substr($after_ten_month_date,5,2);

$month_arr=explode('-',$arr);
$salary_monthyear=$month_arr[0].$month_arr[1];

?>
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
</style>

<?php

$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return NULL;
	}
	else{
		return $newDate = date("Y-m-d", strtotime($original_date));
	}
}

	//$monthyear = $crypto->decode($_REQUEST['bill_report_year'],4).$crypto->decode($_REQUEST['bill_report_month'],4);
	$monthyear=date('Ym');
	
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='429'");
$requisition_type=$requisition[0]['code'];

$all_emp_id=$crypto->decode($_POST['all_emp'],4);

if($logged_user=='zpddo')
{

	$check_bonus_bill = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_duplicate_bill_no_check=$db->fetch_table("
				SELECT count(*) FROM prd_block_bill_details 
				WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
				AND bill_no = '".$_POST['bill']."'
				AND salary_monthyear = '".$monthyear."'
				AND status='1' 
			");		
			
	if($check_duplicate_bill_no_check[0]['count']=='0')		
	{
		
				
		$fetch=$db->fetch_table("SELECT now()");  
		
		pg_query('BEGIN');
		$insert_bonus_bill = $db->insert("
											INSERT INTO prd_block_bill_details (
											bill_no,
											status,
											block_code,
											bill_entry_time,
											bill_update_time,
											ip_addres,
											salary_monthyear,
											ps_id_fk,
											requisition_type,
											zp_id_fk
											)
											VALUES(
											'".$_POST['bill']."',
											'1',
											'0',
											'".date_frmt_change($_POST['bill_date'])."',
											now(),
											'".$_SESSION['user_agent']['USER_IP']."',
											'".$monthyear."',
											'0',
											'".$requisition_type."',
											'".$_SESSION['location']['district_id']."'
											);
											");
		
		
		$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
											WHERE bill_no='".$_POST['bill']."'
											AND zp_id_fk='".$_SESSION['location']['district_id']."'
											AND requisition_type='".$requisition_type."'
											AND salary_monthyear='".$monthyear."'
											");
		
		/*$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details fad SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											FROM prd_employee_master emp
											WHERE fad.festival_advance_status=4
											AND emp.zp_id_fk='".$_SESSION['location']['district_id']."'");*/
											
		$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											WHERE festival_advance_status=4
											AND emp_id_fk in (".$all_emp_id.")");
		
		
		$fad_instalment_details_update=$db->update(" UPDATE prd_festival_advance_entry_sal sal
													SET deduction_start_monthyear='".$next_monthyear_of_current_month."',
														deduction_end_monthyear='".$next_ten_monthyear_of_current_month."' 
													FROM prd_festival_advance_employee_details fad
													WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk
													AND fad.bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."' ");
		
		
		if($insert_bonus_bill == TRUE && $fad_details_update == TRUE && $fad_instalment_details_update == TRUE)
		{
			pg_query('COMMIT');
			?>
			<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
			
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/personal.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personal Details</a>
			</div>
			</div>
			<br/>-->
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/salarybill.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
			</div>
			</div>
			<br /><br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/xml_file.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>
			</div>-->
		<?php
		
		} 
		else 
		{ 
			pg_query('ROLLBACK');?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
		<?php
		} 
	}
	else
	{
	 ?>
		<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Please Change Bill number. This Bill number has already been used for this month.</strong></div>
	<?php
	}
}
else if($logged_user=='EO')
{

	$check_bonus_bill = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_duplicate_bill_no_check=$db->fetch_table("
				SELECT count(*) FROM prd_block_bill_details 
				WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
				AND bill_no = '".$_POST['bill']."'
				AND salary_monthyear = '".$monthyear."'
				AND status='1' 
			");		
			
	if($check_duplicate_bill_no_check[0]['count']=='0')		
	{
		
				
		$fetch=$db->fetch_table("SELECT now()");  
		
		pg_query('BEGIN');
		$insert_bonus_bill = $db->insert("
											INSERT INTO prd_block_bill_details (
											bill_no,
											status,
											block_code,
											bill_entry_time,
											bill_update_time,
											ip_addres,
											salary_monthyear,
											ps_id_fk,
											requisition_type,
											zp_id_fk
											)
											VALUES(
											'".$_POST['bill']."',
											'1',
											'0',
											'".date_frmt_change($_POST['bill_date'])."',
											now(),
											'".$_SESSION['user_agent']['USER_IP']."',
											'".$monthyear."',
											'".$_SESSION['location']['ps_id']."',
											'".$requisition_type."',
											0
											);
											");
		
		
		$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
											WHERE bill_no='".$_POST['bill']."'
											AND ps_id_fk='".$_SESSION['location']['ps_id']."'
											AND requisition_type='".$requisition_type."'
											AND salary_monthyear='".$monthyear."'
											");
		
		
		/*$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details fad SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											FROM prd_employee_master emp
											WHERE fad.festival_advance_status=4
											AND emp.ps_id_fk='".$_SESSION['location']['ps_id']."'");*/
											
		$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											WHERE festival_advance_status=4
											AND emp_id_fk in (".$all_emp_id.")");
		
		
		$fad_instalment_details_update=$db->update(" UPDATE prd_festival_advance_entry_sal sal
													SET deduction_start_monthyear='".$next_monthyear_of_current_month."',
														deduction_end_monthyear='".$next_ten_monthyear_of_current_month."' 
													FROM prd_festival_advance_employee_details fad
													WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk
													AND fad.bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."' ");
		
		if($insert_bonus_bill == TRUE && $fad_details_update == TRUE && $fad_instalment_details_update == TRUE)
		{
			pg_query('COMMIT');
			?>
			<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
			
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/bonus_module/bg_bonus_text_file/personal.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
			</div>
			</div>-->
			<br/>
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/salarybill_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
			</div>
			</div>
			<br /><br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/xml_file_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>
			</div>
		<?php
		
		} 
		else 
		{ 
			pg_query('ROLLBACK');?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
		<?php
		} 
	}
	else
	{
	 ?>
		<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Please Change Bill number. This Bill number has already been used for this month.</strong></div>
	<?php
	}
}
else if($logged_user=='BDO')
{

	$check_bonus_bill = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	");
	
	$check_duplicate_bill_no_check=$db->fetch_table("
				SELECT count(*) FROM prd_block_bill_details 
				WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
				AND bill_no = '".$_POST['bill']."'
				AND salary_monthyear = '".$monthyear."'
				AND status='1' 
			");		
			
	if($check_duplicate_bill_no_check[0]['count']=='0')		
	{
		
				
		$fetch=$db->fetch_table("SELECT now()");  
		
		pg_query('BEGIN');
		
		$insert_bonus_bill = $db->insert("
											INSERT INTO prd_block_bill_details (
											bill_no,
											status,
											block_code,
											bill_entry_time,
											bill_update_time,
											ip_addres,
											salary_monthyear,
											ps_id_fk,
											requisition_type,
											zp_id_fk
											)
											VALUES(
											'".$_POST['bill']."',
											'1',
											'".$_SESSION['user_info']['stake_user']."',
											'".date_frmt_change($_POST['bill_date'])."',
											now(),
											'".$_SESSION['user_agent']['USER_IP']."',
											'".$monthyear."',
											'0',
											'".$requisition_type."',
											0
											);
											");
		
		
		$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
											WHERE bill_no='".$_POST['bill']."'
											AND block_code = '".$_SESSION['user_info']['stake_user']."'
											AND requisition_type='".$requisition_type."'
											AND salary_monthyear='".$monthyear."'
											");
		
		/*$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details fad SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											FROM prd_employee_master emp
											INNER JOIN prd_location_master_gp gp
											ON emp.gp_id_fk=gp.gp_id_pk
											WHERE fad.festival_advance_status=4
											AND gp.block_id_fk='".$_SESSION['location']['block_id']."'");*/
											
		$fad_details_update=$db->update("UPDATE prd_festival_advance_employee_details SET 
											festival_advance_status=5,
											bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."'
											WHERE festival_advance_status=4
											AND emp_id_fk in (".$all_emp_id.")");
		
		
		$fad_instalment_details_update=$db->update(" UPDATE prd_festival_advance_entry_sal sal
													SET deduction_start_monthyear='".$next_monthyear_of_current_month."',
														deduction_end_monthyear='".$next_ten_monthyear_of_current_month."' 
													FROM prd_festival_advance_employee_details fad
													WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk
													AND fad.bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."' ");
		
		
		if($insert_bonus_bill == TRUE && $fad_details_update == TRUE && $fad_instalment_details_update == TRUE)
		{
			pg_query('COMMIT');
			?>
			<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
			
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/personal_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
			</div>
			</div>-->
			<br/>
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/salarybill_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
			</div>
			</div>
            <br/>
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/xml_file_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>
			</div>
		<?php
		
		} 
		else 
		{ 
			pg_query('ROLLBACK');?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
		<?php
		} 
	}
	else
	{
	 ?>
		<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Please Change Bill number. This Bill number has already been used for this month.</strong></div>
	<?php
	}
}
?>
