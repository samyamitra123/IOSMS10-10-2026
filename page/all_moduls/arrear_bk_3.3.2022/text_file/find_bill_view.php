<?php

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

$crypto = new cryptography();

//require 'includes/library/session.class.php';


//echo $_POST['year']; die;
//Functions --------------------------------------------------------------------------------------------------------------------------------------------

//$moye = trim($crypto->decode($_GET['ye'], 4)) . trim($crypto->decode($_GET['mo'], 4));
//$salso = trim($crypto->decode($_GET['ss'], 4));
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

$select_year=$crypto->decode($_POST['year'], 4);

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
$requisition_type=$requisition[0]['code'];




if($logged_user=='EO')
{
	$arr=$db->fetch_table("select bill_no from prd_block_bill_details where ps_id_fk = '".$_SESSION['location']['ps_id']."' and substring(salary_monthyear,1,4) ='" . $select_year . "' AND requisition_type='".$requisition_type."'");

}
else if($logged_user=='BDO')
{
	$arr=$db->fetch_table("select bill_no from prd_block_bill_details where  block_code = '".$_SESSION['user_info']['stake_user']."' and substring(salary_monthyear,1,4) ='" . $select_year . "' AND requisition_type='".$requisition_type."'");
}


	

?>


 <option value="">Please Select</option>
    
	 <? foreach($arr as $key){ ?>
	
      <option value="<?=$key['bill_no'] ?>" ><?= $key['bill_no']; ?></option>
	 <?php } ?>
   <?

?>


