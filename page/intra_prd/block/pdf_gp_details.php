<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//error_reporting(0);


session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
$cryptoGraph = new cryptography();
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
function find_block($block_id){
	$db=new database();
	$block=$db->fetch_table("select block_name from prd_location_master_block where block_id_pk='".$block_id."'");
	return $block[0]['block_name'];
}


$db=new database();
$data=$db->fetch_table("select gp_id_pk,gp_code,gp_name,block_id_fk,flag from prd_location_master_gp where gp_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$data1=$db->fetch_table("select gram_pradhan_name, mobile_no, road_name,vill_name, post_office, police_station, pin_code,contact_no, email_id from prd_gp_profile where gp_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$dise_code=$data[0]['gp_code'];
$gp_name=$data[0]['gp_name'];
$block_code=$data[0]['block_id_fk'];
$flag=$data[0]['flag'];
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

//echo "fadggg1";exit;

define("_MPDF_TEMP_PATH", '../../../locker/temp/');

include ('../../../includes/third-party/mpdf/mpdf.php');
#include $_SERVER['DOCUMENT_ROOT'] . '/includes/third-party/mpdf/mpdf.php';
//echo "fadggg1";exit;

$stylesheet='';

$mpdf=new mPDF();
$mpdf->WriteHTML($stylesheet,1);

//Header and footer
$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');

//PDF header
$mpdf->WriteHTML('
					
					
					<table>
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
							
								<div class="logo" style="text-align: center;"><img width="30" src="../../../themes/default/image/ashoka.jpg" /></div>
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $gp_name .'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					',2);
				
					
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">GP Profile Details</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;" >GP CODE :</td>
					<td colspan="3" style="font-weight:bold">'.$dise_code.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">GP NAME :</td>
					<td colspan="3" style="font-weight:bold">'.$gp_name.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">BLOCK NAME :</td>
					<td>'.find_block($block_code).'</td>
					<td class="text_r" style="color: #1F6377;font-weight: bold;font-family: "Arial Narrow";font-style: italic;">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">NAME OF PRADHAN :</td>
					<td>'.(strtoupper($pradhan_name)).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">MOBILE NO. :</td>
					<td>'.$mobile_no.'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">ROAD NAME :</td>
					<td>'.(strtoupper($road_name)).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">VILLAGE/TOWN NAME :</td>
					<td>'.(strtoupper($vill_name)).'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">POST OFFICE :</td>
					<td>'.(strtoupper($post_office)).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">POLICE STATION :</td>
					<td>'.(strtoupper($police_station)).'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">PIN CODE: :</td>
					<td>'.$pin_code.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">CONTACT NO :</td>
					<td>'.$contact_no.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">EMAIL ID: :</td>
					<td>'.$email_id.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;"></td>
					<td></td>
				</tr>
				</table>',2);	
	
				
if($flag=='0'){
$mpdf->WriteHTML('<p style="color: #337AB7;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Not Finalized.</p>',2);	
}
else if($flag=='1'){
$mpdf->WriteHTML('<p style="color: #9B510E;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Waiting For BDO Approval.</p>',2);		
}
else if($flag=='2'){
$mpdf->WriteHTML('<p style="color: green;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Finalized By BDO.</p>',2);		
}
else if($flag=='3'){
$mpdf->WriteHTML('<p style="color: red;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Rejected By BDO.</p>',2);		
}
else if($flag=='4'){
$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">
Profile Incomplete.</p>',2);		
}

				

$mpdf->Output('GP PROFILE OF '.$gp_name.'.pdf','D');								
?>