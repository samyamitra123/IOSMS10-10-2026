<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();

$dpsc_dies = $_SESSION['user_info']['stake_user'];
$path = '../../../../../readwrite/text_file/';
//Permission --------------------------------------------------------------------------
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	
	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];


$db = new database();
	
$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
$requisition_type=$requisition[0]['code'];
	

 $bill_year=$crypto->decode($_POST['bill_report_year'], 4); 
 $bill_no=$_POST['bill_no']; 

 if($bill_year!='0' && $bill_year!='' && $bill_no!='0' && $bill_no!='')
{
	
	if($logged_user=='EO')
	{
		
		$check_bill= $db->fetch_table("
									SELECT * 
									FROM prd_block_bill_details 
									WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
									AND salary_monthyear like '".$bill_year."%'
									AND bill_no='".$bill_no."' AND requisition_type='".$requisition_type."' 
								");
	
	}
	else if($logged_user=='BDO')
	{
		$check_bill= $db->fetch_table("
									SELECT * 
									FROM prd_block_bill_details 
									WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
									AND salary_monthyear like '".$bill_year."%'
									AND bill_no='".$bill_no."' AND requisition_type='".$requisition_type."' 
								");
	}



$bill_month=substr($check_bill[0]['salary_monthyear'],4,6);
$bill_year=substr($check_bill[0]['salary_monthyear'],0,4); 

}

if(empty($check_bill))
 { 
	 $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Data Insertion Failed.</strong></div>';
	header('Location:text_file_view.php');
	exit(0);
 }
	 else { 
 if($logged_user=='EO') 
 {?>
		<!--<div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
        <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_ps.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
        </div>
    	</div>
    	<br/><br />-->
        
        <div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
        <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_ps.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
        </div>
        </div>
    
    	<br /><br />
        <div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_ps.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
        </div>
        </div>
<? }
else if($logged_user=='BDO')
{ ?>
		<div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
        <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/personal_gp.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
        </div>
    	</div>
    	<br/><br />
        
        <div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
        <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/salarybill_gp.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a>
        </div>
        </div>
    
    	<br />
        <div class="form-group">
        <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/xml_file_gp.php?mo=<?php echo $bill_month; ?>&ye=<?php echo $bill_year; ?>&bill=<?php echo $bill_no; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
        </div>
        </div>
<?

} }?>