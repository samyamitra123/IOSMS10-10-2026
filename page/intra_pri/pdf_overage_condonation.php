<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");

session_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require_once '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
$crypto = new cryptography();
//require 'includes/library/session.class.php';



function code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function code_block($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_id_pk='".$val."'");
		return $arr[0]['block_name'];																															
	}
	
	function code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
		return $arr[0]['district_name'];																															
	}
	
	

$id = $crypto->decode($_GET['id'],4);
$db=new database();
$insert_pk_pdf = $db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_master WHERE application_id = '".$id."' ");
//var_dump($insert_pk_pdf); die;

$proposal = $insert_pk_pdf[0]['proposal'];
$application_id = $insert_pk_pdf[0]['application_id'];
$name= $insert_pk_pdf[0]['emp_first_name']." ". $insert_pk_pdf[0]['emp_second_name']." ". $insert_pk_pdf[0]['emp_last_name'];
$emp_id = $insert_pk_pdf[0]['emp_id_const'];
$dob = date("d-m-Y",strtotime($insert_pk_pdf[0]['emp_dob']));

if(isset($insert_pk_pdf[0]['emp_sex']) && $insert_pk_pdf[0]['emp_sex'] == 91){ $emp_sex = "Male"; }
else if(isset($insert_pk_pdf[0]['emp_sex']) && $insert_pk_pdf[0]['emp_sex'] == 92){ $emp_sex = "Female"; }
else if(isset($insert_pk_pdf[0]['emp_sex']) && $insert_pk_pdf[0]['emp_sex'] == 93){ $emp_sex = "Others"; }

//$arr_des_pdf = $db->fetch_table("select code,description from prd_dise_code_master where code='".$insert_pk_pdf[0]['emp_desig']."' order by code");
$arr_des = $db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where 
	='".$insert_pk_pdf[0]['emp_desig']."' order by designation_id");
//$design = $arr_des_pdf[0]['description'];
$design = $arr_des[0]['designation_name'];

$gp_id_fk = $insert_pk_pdf[0]['gp_id_fk'];
$ps_id_fk = $insert_pk_pdf[0]['ps_id_fk'];
$zp_id_fk = $insert_pk_pdf[0]['zp_id_fk'];

if($gp_id_fk != 0){
	$heading = 'GP';
	$gp_ps_zp = code_gp($gp_id_fk);
}

else if($ps_id_fk != 0){
	$heading = 'PS';
	$gp_ps_zp = code_ps($PS_id_fk);
}

else if($zp_id_fk != 0){
	$heading = 'ZP';
	$gp_ps_zp = code_district($zp_id_fk);
}

$emp_first_memo_no = $insert_pk_pdf[0]['emp_first_memo_no'];
$emp_first_join_date = date("d-m-Y",strtotime($insert_pk_pdf[0]['emp_first_join_date']));
$appoinment_authority = $insert_pk_pdf[0]['appoinment_authority'];
$notice_date = date("d-m-Y",strtotime($insert_pk_pdf[0]['notice_date']));

define("_MPDF_TEMP_PATH", '../../locker/temp/');
include ('../../includes/third-party/mpdf/mpdf.php');
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
							
								<div class="logo" style="text-align: center;"><img width="30" src="../../themes/default/image/ashoka.jpg" /></div>
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $_SESSION['location']['gp_name'] .'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					',2);
	


$mpdf->WriteHTML('<div class="table-responsive">
			<table width="100%" class="table" style="font-size:11px;">
				<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
					<td colspan="4" style="text-align:center"><strong> EMPLOYEE DETAIL </strong></td>
				</tr>
				<tr class="success">
					<td><strong>PROPOSAL NAME:</strong></td>
					<td>'.$proposal.'</td>
				</tr></br>
				<tr class="success">
					<td><strong>APPLICATION ID:</strong></td>
					<td>'.$application_id.' </td>
				</tr></br>
				<tr class="warning">
					<td><strong>EMPLOYEE ID :</strong></td>
					<td>'.$emp_id.' </td>
					<td><strong>EMPLOYEE NAME :</strong></td>
					<td style="text-transform:uppercase;"> '.$name.'</td>
				</tr></br>
				<tr class="success">
					<td><strong>DATE OF BIRTH :</strong></td>
					<td> '.$dob.'</td>
					<td><strong>SEX :</strong></td>
					<td style="text-transform:uppercase;"> '.$emp_sex.'</td>
				</tr></br>
				<tr class="warning">
					<td><strong>DESIGNATION :</strong></td>
					<td style="text-transform:uppercase;"> '.$design.'</td>
					<td><strong>Name of '.$heading.' Posted: </strong></td>
					<td style="text-transform:uppercase;">'.$gp_ps_zp.'</td>
    
				</tr></br>
				<tr class="success">
					<td><strong>FIRST APPOINMENT ORDER NO. :</strong></td>
					<td style="text-transform:uppercase;">'.$emp_first_memo_no.'</td>
					<td><strong>DATE OF JOINING IN THE FIRST POSTING:</strong></td>
					<td>'.$emp_first_join_date.'</td>
				</tr></br>
				<tr class="warning">
					<td><strong>NAME OF APPOINTING AUTHORITY :</strong></td>
					<td>'.$appoinment_authority.'</td>
					<td><strong>DATE OF EMPLOYMENT NOTIFICATION/</br> EMPLOYMENT EXCHANGE CALL LETTER DATE:</strong></td>
					<td>'.$notice_date.'</td>
				</tr></br>
			</table>
</div>',2);	
					
	
			

$mpdf->Output('OAC PDF OF '.$name.'.pdf','D');								
?>