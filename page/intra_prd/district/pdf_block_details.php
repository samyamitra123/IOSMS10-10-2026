<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
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

function find_dist($id){
	$db=new database();
	$block=$db->fetch_table("select district_name from prd_location_master_district where district_id_pk='".$id."'");
	return $block[0]['district_name'];
}


$db=new database();
$data=$db->fetch_table("Select block_status,block_name,block_code,district_id_fk from prd_location_master_block where block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$data1=$db->fetch_table("select block_code, bdo_name, mobile_no, road_name, vill_name, 
            post_office, police_station, pin_code, email_id, contact_no, 
            tan_no from prd_block_profile where block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
$block_name=$data[0]['block_name'];
$block_code=$data[0]['block_code'];
$flag=$data[0]['block_status'];
if(!empty($data1)){
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
}

define("_MPDF_TEMP_PATH", '../../../locker/temp/');
include ('../../../includes/third-party/mpdf/mpdf.php');
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
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $block_name .'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					',2);
					
					
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">BDO Profile Details</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;" >DISTRICT NAME :</td>
					<td colspan="3" style="font-weight:bold">'.find_dist($data[0]['district_id_fk']).'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">BLOCK NAME :</td>
					<td colspan="3" style="font-weight:bold">'.$block_name.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">BLOCK CODE :</td>
					<td>'.$block_code.'</td>
					<td class="text_r" style="color: #1F6377;font-weight: bold;font-family: "Arial Narrow";font-style: italic;">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">NAME of BDO :</td>
					<td>'.$bdo_name.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">MOBILE NO. :</td>
					<td>'.$mobile_no.'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">ROAD NAME :</td>
					<td>'.$road_name.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">VILLEGE/TOWN NAME :</td>
					<td>'.$vill_name.'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">POST OFFICE :</td>
					<td>'.$post_office.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">POLICE STATION :</td>
					<td>'.$police_station.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">PIN CODE: </td>
					<td>'.$pin_code.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">CONTACT NO :</td>
					<td>'.$contact_no.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">EMAIL ID: </td>
					<td>'.$email_id.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">TAN NUMBER: </td>
					<td>'.$tan.'</td>
				</tr>
				</table>',2);	
				
/*if($flag=='0'){
$mpdf->WriteHTML('<p style="color: #337AB7;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Not Finalized.</p>',2);	
}
else if($flag=='1'){
$mpdf->WriteHTML('<p style="color: #9B510E;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Waiting For ADMIN Approval.</p>',2);		
}
else if($flag=='2'){
$mpdf->WriteHTML('<p style="color: green;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Finalized By ADMIN.</p>',2);		
}
else if($flag=='3'){
$mpdf->WriteHTML('<p style="color: red;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Rejected By ADMIN.</p>',2);		
}
else if($flag=='4'){
$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">
Profile Incomplete.</p>',2);		
}
*/
				

$mpdf->Output('BLOCK PROFILE OF '.$block_name.'.pdf','D');								
?>