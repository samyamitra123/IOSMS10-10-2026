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
							ps.ps_id_fk,
							ps.exe_officer_name, 
							ps.mobile_no, 
							ps.road_name ,
							ps.ddo_code,
							pm.ps_name, 
							ps.post_office_name,
							ps.police_station_name, 
							ps.pin_code, 
							ps.cotract_no, 
							ps.email, 
							ps.last_upd_time, 
							ps.ip_address,
							ps.vill_name,
							pm.ps_status, 
							psu.reason,
							psu.sl_no,
							ps.treasury_code 
						FROM 
							prd_location_master_panchayat_samiti pm 
						INNER JOIN 
							psemp_ps_profile ps 
						ON 
							pm.ps_id_pk=ps.ps_id_fk 
						INNER JOIN 
							psemp_ps_profile_update_status psu 
						ON 
							pm.ps_id_pk=psu.ps_id_fk
	      				WHERE 
							ps.ps_id_fk='".$_SESSION['location']['ps_id']."' 
		   				ORDER BY 
							psu.sl_no DESC LIMIT 1" );
    		
$ps_name=$query[0]['ps_name']; 
$executive_officer_name=$query[0]['exe_officer_name'];
$mobile_no=$query[0]['mobile_no'];
$road_name=$query[0]['road_name'];
$vill_name=$query[0]['vill_name'];
$post_office=$query[0]['post_office_name'];
$police_station=$query[0]['police_station_name'];	
$pin=$query[0]['pin_code'];
$contact_no=$query[0]['cotract_no'];
$email_id=$query[0]['email'];
$reject_reason=$query[0]['reason'];
$ddo_code=$query[0]['ddo_code'];
$t_code=$query[0]['treasury_code'];
					
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
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $_SESSION['location']['ps_name'] .'</h3>
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
					<td colspan="3" style="font-weight:bold">'.$_SESSION['location']['district_name'].'</td>
				<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;"> PS NAME :</td>
					<td colspan="3" style="font-weight:bold">'.$ps_name.'</td>
				</tr>
				 <tr>
				    <td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;"> NAME OF EXECUTIVE OFFICER:</td>
					<td style="text-transform:uppercase;">'.$executive_officer_name.'</td>
					<td>&nbsp;</td><td>&nbsp;
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">MOBILE NO. :</td>
					
					<td colspan="3">'.$mobile_no.'</td>
		
				</tr>
					</table>
	<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PS ADDRESS:</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
					<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">ROAD NAME :</td>
					<td style="text-transform:uppercase;">'.$road_name.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">VILLAGE/TOWN NAME :</td>
					<td style="text-transform:uppercase;">'.$vill_name.'</td>
				    </tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">POST OFFICE :</td>
					<td style="text-transform:uppercase;">'.$post_office.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">POLICE STATION :</td>
					<td style="text-transform:uppercase;">'.$police_station.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">PIN CODE :</td>
					<td>'.$pin.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">CONTACT NO :</td>
					<td>'.$contact_no.'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">EMAIL ID :</td>
					<td>'.$email_id.'</td>
					
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">PL OPERATOR CODE :</td>
					<td style="text-transform:uppercase;">'.$ddo_code.'</td>
				</tr>
				<tr>		
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">TREASURY CODE :</td>
					<td style="text-transform:uppercase;">'.$t_code.'</td>
					<td></td>
					<td></td>
				</tr>
				</table>');	
				
if($query[0]['ps_status']=='1')
{
	$mpdf->WriteHTML('<p style="color:#8a6d3b;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Waiting For Approval</p>');	
}
else if($query[0]['ps_status']=='2')
{
	$mpdf->WriteHTML('<p style="color: #3C763D;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Approved</p>');		
}
else if($query[0]['ps_status']=='3')
{
	$mpdf->WriteHTML('<p style="color:#A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Profile Rejected [ Reject Reason : '.$reject_reason.']</p>');		
}
else if($query[0]['ps_status']=='7')
{
	$mpdf->WriteHTML('<p style="color:#a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Waiting For Unlock</p>');		
}
else if($query[0]['ps_status']=='8')
{
	$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Profile Unlocked For Edit</p>');		
}
else if($query[0]['ps_status']=='0' )
{
	$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Profile Not Sent</p>');		
}
else if(!$query)
{
	$mpdf->WriteHTML('<p style="color: red;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Profile Not Submitted</p>');
}

$mpdf->Output('PS PROFILE OF '.$_SESSION['location']['ps_name'].'.pdf','D');								
?>
					
					





		
			
	
