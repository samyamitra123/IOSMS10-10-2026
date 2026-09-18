
<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';     
//require '../../page_visite.php';

$k=strtotime("first day of last month");
$arr = date("Y-m-d",$k);

$month_arr=explode('-',$arr);
$salary_monthyear=$month_arr[0].$month_arr[1];

?>
<style>
#sucess
{
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
#error
{
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

$logged_user=$_SESSION['user_info']['stake_abbr'];

function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return NULL;
	}
	else{
		return $newDate = date("Y-m-d", strtotime($original_date));
	}
}

$monthyear=date('Ym');
	
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
$requisition_type=$requisition[0]['code'];
if($logged_user=='BDO')
{
	$check_ps_bill = $db->fetch_table("
										SELECT count(*) 
										FROM prd_block_bill_details bill
										INNER JOIN prd_employee_arrear arr
										ON bill.block_bill_pk=arr.bill_id_fk
										WHERE bill.block_code = '".$_SESSION['user_info']['stake_user']."'
										AND bill.salary_monthyear ='".$yemo."' AND bill.requisition_type='".$requisition_type."'
									");
	
	$check_ps_bill_exist = $db->fetch_table("
											SELECT count(*) 
											FROM prd_block_bill_details 
											WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
											AND bill_no = '".$_POST['bill']."'
											AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'
											");
			
	$check_new_bill_status=$db->fetch_table("SELECT count(*) FROM prd_employee_arrear WHERE salary_monthyear is null AND bill_id_fk='0' AND block_code='".$_SESSION['user_info']['stake_user']."'");
	
	if($check_ps_bill[0]['count'] == 0  && $check_new_bill_status[0]['count']!=0)
	{
	
			
		$fetch=$db->fetch_table("SELECT now()");  
			
		$insert_arrear_bill = $db->insert("
											INSERT INTO prd_block_bill_details 
												(
												block_code,
												bill_no,
												status,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												requisition_type,
												ps_id_fk
												)
											VALUES
												(
												'".$_SESSION['user_info']['stake_user']."',
												'".$_POST['bill']."',
												'1',
												'".date_frmt_change($_POST['bill_date'])."',
												now(),
												'".$_SESSION['user_agent']['USER_IP']."',
												'".$monthyear."',
												'".$requisition_type."',
												0						
												);
										");
			
		
				
		if($insert_arrear_bill == TRUE )
		{
		
			$update_arrear_salary=$db->update("UPDATE prd_employee_arrear arr
											SET 
											salary_monthyear='".$monthyear."',
											status_flag='3',
											bill_id_fk=bill.block_bill_pk
											FROM
											prd_block_bill_details bill 
											WHERE arr.status_flag=2 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.block_code = '".$_SESSION['user_info']['stake_user']."' AND bill.bill_no='".$_POST['bill']."' AND bill_entry_time='".date_frmt_change($_POST['bill_date'])."' ");
					
			if($update_arrear_salary==TRUE)
			{ ?>
			
				<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
				
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
					</div>
				</div>
				
				<br/>
				
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
					</div>
				</div>
				
				<br />
				
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
					</div>
				</div>
                <input type="hidden" id="new_bill_no" name="new_bill_no"  value="<?php echo $_POST['bill']; ?>"/>
                <input type="hidden" id="new_bill_date" name="new_bill_date"  value="<?php echo $_POST['bill_date']; ?>"/>
	<?php
			}
					
		} 
		else 
		{ ?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
	<?php
		}
		
	}  
	else 
	{ ?>
	
		<?php if($check_ps_bill_exist[0]['count'] == 1)
		{
		?>
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
			</div>
			</div>
			<br/><br />
			
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
			</div>
			</div>
		
			<br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
				<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_gp.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>
			</div>
	<?php 
		}
		else
		{?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is already inserted for this month</strong></div>
	<?php
		}
	 }
}
else if($logged_user=='EO')
{
	
	$check_ps_bill = $db->fetch_table("
										SELECT count(*) 
										FROM prd_block_bill_details bill
										INNER JOIN prd_employee_arrear arr
										ON bill.block_bill_pk=arr.bill_id_fk
										WHERE bill.ps_id_fk = '".$_SESSION['location']['ps_id']."'
										AND bill.salary_monthyear ='".$yemo."' AND bill.requisition_type='".$requisition_type."'
									");
	
	$check_ps_bill_exist = $db->fetch_table("
											SELECT count(*) 
											FROM prd_block_bill_details 
											WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
											AND bill_no = '".$_POST['bill']."'
											AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'
											");
			
	
	$check_new_bill_status=$db->fetch_table("SELECT count(*) FROM prd_employee_arrear WHERE salary_monthyear is null AND bill_id_fk='0' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
	
	if($check_ps_bill[0]['count'] == 0 && $check_new_bill_status[0]['count']!=0)
	{
	
			
		$fetch=$db->fetch_table("SELECT now()");  
			
		$insert_arrear_bill = $db->insert("
											INSERT INTO prd_block_bill_details 
												(
												block_code,
												bill_no,
												status,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												requisition_type,
												ps_id_fk
												)
											VALUES
												(
												'',
												'".$_POST['bill']."',
												'1',
												'".date_frmt_change($_POST['bill_date'])."',
												now(),
												'".$_SESSION['user_agent']['USER_IP']."',
												'".$monthyear."',
												'".$requisition_type."',
												'".$_SESSION['location']['ps_id']."'						
												);
										");
			
		
				
		if($insert_arrear_bill == TRUE )
		{
			
			$update_arrear_salary=$db->update("UPDATE prd_employee_arrear arr
											SET 
											salary_monthyear='".$monthyear."',
											status_flag='3',
											bill_id_fk=bill.block_bill_pk
											FROM
											prd_block_bill_details bill 
											WHERE arr.status_flag=2 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND bill.bill_no='".$_POST['bill']."'  AND bill_entry_time='".date_frmt_change($_POST['bill_date'])."' ");
					
			if($update_arrear_salary==TRUE)
			{ ?>
			
				<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
				
				<!--<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
					</div>
				</div>
				
				<br/>
				-->
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
					</div>
				</div>
				
				<br /><br />
				
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-7">
					<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
					</div>
				</div>
                 <input type="hidden" id="new_bill_no" name="new_bill_no"  value="<?php echo $_POST['bill']; ?>"/>
                <input type="hidden" id="new_bill_date" name="new_bill_date"  value="<?php echo $_POST['bill_date']; ?>"/>
	<?php
			}
					
		} 
		else 
		{ ?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
	<?php
		}
		
	}  
	else 
	{ ?>
	
		<?php if($check_ps_bill_exist[0]['count'] == 1)
		{
		?>
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
			</div>
			</div>
			<br/><br />
			-->
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
			</div>
			</div>
		
			<br /><br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
				<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_ps.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>
			</div>
	<?php 
		}
		else
		{?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is already inserted.</strong></div>
	<?php
		}
	 }
}
?>
