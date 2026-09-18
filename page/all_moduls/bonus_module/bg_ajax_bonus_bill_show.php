<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';


if($_SERVER['HTTP_REFERER']==''){
header("Location:../../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '.$config['base_url']."page/login.php");
exit;
}



if(!isset($_SERVER['HTTP_REFERER']))
{
	header('Location:'.$config['base_url']."page/error.php?id=1");
	exit("Do not paste URL directly");

} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
// substring is not found in string
header('Location:'. $config['base_url']."page/error.php?id=2");
exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

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


$cryp = new cryptography();

$emp_id_pk=$cryp->decode($_GET['id'],4); 

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


$db = new database();

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

$desig_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	");


function fun_desig($dcode, $code_desig)
{
	foreach ($code_desig as $key) 
	{
		if($key['designation_id'] == $dcode)
		{
			return $key['designation_name'];
		}
	}
}

error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$year=$_GET['year'];
$bonus_bill_no=$_GET['bonus_bill_no'];
$bon_cat=$_GET['bon_cat'];
$bon_name=$_GET['bon_name'];

if($logged_user=='zpddo')
{

	/*$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											WHERE monthyear='".$year.$month."' AND emp_bon.delete_status='1' AND bon_type.active_status!='0' 
											AND bon_type.zp_id_fk='".$_SESSION['location']['district_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.bonus_name='".$bon_name."'");*/
}
else if($logged_user=='EO')
{
	
	$bonus_bill_details_fetch=$db->fetch_table(" SELECT 
											distinct(bill.bill_no),
											bill.salary_monthyear,
											bill.bill_entry_time
											FROM prd_block_bill_details bill 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											INNER JOIN prd_bonus_type_details bon_type ON
											bon_type.bonus_type_id_pk= emp_bon.bonus_type_id_fk 
											WHERE emp_bon.monthyear='".$year."' 
											AND bill.ps_id_fk='".$_SESSION['location']['ps_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.bonus_name='".$bon_name."'
											AND bill.block_bill_pk='".$bonus_bill_no."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)");
}
else if($logged_user=='BDO')
{
	$bonus_bill_details_fetch=$db->fetch_table(" SELECT 
											distinct(bill.bill_no),
											bill.salary_monthyear,
											bill.bill_entry_time
											FROM prd_block_bill_details bill 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											INNER JOIN prd_bonus_type_details bon_type ON
											bon_type.bonus_type_id_pk= emp_bon.bonus_type_id_fk 
											WHERE emp_bon.monthyear='".$year."' 
											AND bill.block_code='".$_SESSION['user_info']['stake_user']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.bonus_name='".$bon_name."'
											AND bill.block_bill_pk='".$bonus_bill_no."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)");
}								


$bill_number=$bonus_bill_details_fetch[0]['bill_no'];
$bill_month=substr($bonus_bill_details_fetch[0]['salary_monthyear'],4,2);
$bill_year=substr($bonus_bill_details_fetch[0]['salary_monthyear'],0,4);

?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<?php if(count($bonus_bill_details_fetch)>0)
{ ?>

    <div class="school" style="height:150px; color:#D3E796;">
    
        <div class="form-group" >
            <div class="col-sm-2"></div>
            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm; color:#0070A3;">Bill Number:</label>
            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm;color:#0070A3;"><?php echo $bill_number; ?></label>
        
           
            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm;color:#0070A3;">Bill Date:</label>
            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm;color:#0070A3;"><?php echo $bonus_bill_details_fetch[0]['bill_entry_time']; ?></label>
        </div>
        <br /><br />
        <?php if($logged_user=='EO')
		{ ?>
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/bonus_module/bg_bonus_text_file/salarybill_ps.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_number; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
            </div>
             
        </div>
        <br /><br />
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/bonus_module/bg_bonus_text_file/xml_file_ps.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_number; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
            </div>
            
        </div>
        <?php }
		else if($logged_user=='BDO')
		{ ?>
        	<div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/bonus_module/bg_bonus_text_file/salarybill_gp.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_number; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
            </div>
             
        </div>
        <br /><br />
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/bonus_module/bg_bonus_text_file/xml_file_gp.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_number; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
            </div>
            
        </div>
        <?php } ?>
			
        
    </div>
       
               
    <div class="clear"></div>

<?
}
else
{
	echo "<div class='school'><center><table class='table-responsive'><tr><td colspan='23' style='color:#F00; font-size:18px'><strong>No data found</strong></td></tr></table></center></div>";
}
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  
