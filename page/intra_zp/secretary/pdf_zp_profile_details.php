<?

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$db = new database();

							
							$query=$db->fetch_table("SELECT 
							zp.district_id_fk,
							zp.aeo_name,
							zp.secretary_name,
							zp.fc_cao_name,
							zp.accountant_name,
							zp.road_name ,
							zp.post_office_name,
							zp.police_station_name, 
							zp.pin_code, 
							zp.cotract_no, 
							zp.email, 
							zp.last_upd_time, 
							zp.ip_address,
							zp.vill_name,
							zp.tan_no,
							zp.gst_no,
							zp.pl_code,
							zp.ddo_code,
							zp.pan_no,
							dt.district_name,
							dt.zp_status, 
							dt.zp_unlock_status, 
							zpu.reason,
							zpu.sl_no,
							zpu.sec_present_status,
							zpu.aeo_present_status 
						FROM 
							prd_location_master_district dt 
						INNER JOIN 
							zpemp_zp_profile zp 
						ON 
							dt.district_id_pk=zp.district_id_fk 
						LEFT JOIN 
							zpemp_zp_profile_update_status zpu 
						ON 
							dt.district_id_pk=zpu.district_id_fk
	      				WHERE 
							zp.district_id_fk='".$_SESSION['location']['district_id']."' 
		   				ORDER BY 
							zpu.sl_no DESC LIMIT 1" );
							
							/*$query=$db->fetch_table("SELECT  
							zp.district_id_fk,
							pm.zp_status,
							zp.aeo_name, 
							zp.secretary_name, 
							zp.fc_cao_name ,
							pm.district_name,
							zp.accountant_name,  
							zp.road_name,
							zp.post_office_name, 
							zp.police_station_name, 
							zp.pin_code, 
							zp.cotract_no, 
							zp.email, 
							zp.ip_address,
							zp.vill_name,
							psu.reason,
							psu.sl_no
						FROM 
							prd_location_master_district pm 		
						INNER JOIN 
							zpemp_zp_profile zp 
						ON 
							pm.district_id_pk=zp.district_id_fk 
						LEFT JOIN
							zpemp_zp_profile_update_status psu
						ON
							pm.district_id_pk=psu.district_id_fk
						
	     				WHERE 
							zp.district_id_fk='".$_SESSION['location']['district_id']."' 
						ORDER BY 
							psu.sl_no DESC LIMIT 1
		   				");*/
							

    		
$district_name=$query[0]['district_name']; 
$aeo_name=$query[0]['aeo_name'];
$secretary_name=$query[0]['secretary_name'];
$fc_cao_name=$query[0]['fc_cao_name'];
$accountant_name=$query[0]['accountant_name'];
$road_name=$query[0]['road_name'];
$vill_name=$query[0]['vill_name'];
$post_office=$query[0]['post_office_name'];
$police_station=$query[0]['police_station_name'];	
$pin=$query[0]['pin_code'];
$contact_no=$query[0]['cotract_no'];
$email_id=$query[0]['email'];
$reject_reason=$query[0]['reason'];
$tan_no=$query[0]['tan_no'];
$gst_no=$query[0]['gst_no'];
$pl_code=$query[0]['pl_code'];
$ddo_code=$query[0]['ddo_code'];
$pan_no=$query[0]['pan_no'];
					
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
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $_SESSION['location']['district_name'] .'</h3>
							</td>
			                      <td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					');
					
					
$mpdf->WriteHTML('<div class="segment">
	<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PROFILE DETAILS:</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
						<td colspan="3" style="font-weight:bold">'.$_SESSION['location']['district_name'].'</td>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;"> DISTRICT NAME :</td>
					<td style="font-weight:bold">'.$_SESSION['location']['district_name'].'</td>
					<td>&nbsp;</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">AEO NAME :</td>
					<td style="text-transform:uppercase;">'.$aeo_name.'</td>
				</tr>
				 <tr>
				    <td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">TAN NUMBER :</td>
					<td style="text-transform:uppercase;">'.$tan_no.'</td>
					<td>&nbsp;</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">PAN NUMBER :</td>
					<td style="text-transform:uppercase;">'.$pan_no.'</td>
		
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">GST NUMBER :</td>
					<td style="text-transform:uppercase;">'.$gst_no.'</td>
                   <td>&nbsp;</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">LF OPERATOR CODE :</td>
					<td style="text-transform:uppercase;">'.$pl_code.'</td>
					
				</tr>
				<tr>
					
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">TREASURY CODE :</td>
					<td style="text-transform:uppercase;">'.$ddo_code.'</td>
				</tr>
				
					</table>
	<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">ZP ADDRESS:</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
					<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">ROAD NAME :</td>
					<td style="text-transform:uppercase;">'.$road_name.'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">VILLAGE/TOWN NAME :</td>
					<td style="text-transform:uppercase;">'.$vill_name.'</td>
				    </tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">POST OFFICE :</td>
					<td style="text-transform:uppercase;">'.$post_office.'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">POLICE STATION :</td>
					<td style="text-transform:uppercase;">'.$police_station.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">PIN CODE :</td>
					<td>'.$pin.'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">CONTACT NO :</td>
					<td>'.$b=(($contact_no==0) ?$contact_no='':$contact_no).'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">EMAIL ID :</td>
					<td>'.$email_id.'</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;"></td>
					<td style="text-transform:uppercase;"></td>
							
				</tr>
				</table>');	
				
if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='0')
{
	$mpdf->WriteHTML('<p style="color:#3c763d;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Approved</p>');	
}
else if($query[0]['zp_status']=='2')
{
	$mpdf->WriteHTML('<p style="color: #8a6d3b;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Waiting For Approval</p>');		
}
else if($query[0]['zp_status']=='3')
{
	$mpdf->WriteHTML('<p style="color:#a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Profile Rejected By Secretary [ Reject Reason : '.$reject_reason.']</p>');		
}
else if($query[0]['zp_status']=='4')
{
	$mpdf->WriteHTML('<p style="color:#8a6d3b;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Forwarded To AEO</p>');		
}
else if($query[0]['zp_status']=='5')
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Profile Rejected By AEO</p>');		
}

/*else if($query[0]['zp_status']=='6')
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Waiting For Unlock</p>');		
}*/
else if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='1')
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Waiting For Unlock</p>');		
}
else if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='2')
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Unlock Request Forwarded To AEO</p>');		
}
else if($query[0]['zp_status']=='7')
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Profile Unlocked For Edit</p>');		
}


else if($query[0]['zp_status']=='0' )
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Profile Not Sent</p>');		
}
else if(!$query)
{
	$mpdf->WriteHTML('<p style="color: #a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">STATUS : Profile Not Submitted</p>');
}


$mpdf->Output('ZP PROFILE OF '.$_SESSION['location']['district_name'].'.pdf','D');								
?>
					
					





		
			
	
