<?php
//echo 88; die;
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';

$crypto = new cryptography();
//$cryptoGraph=new cryptography();
$db=new database();
$id = $crypto->decode($_GET['id'],4);
//var_dump($id); die;
$CG_pdf_fetch =$db->fetch_table(" SELECT * FROM intra_pri_cg_order as ipco 
                                  LEFT JOIN intra_pri_cg_profile_master as ipcpm 
                                  ON ipco.application_id = ipcpm.application_id 
                                  WHERE ipcpm.application_id = '".$id."' ");
$CG_pdf_fetch = $CG_pdf_fetch[0];
$empInfo = $db->fetch_table(" SELECT * FROM prd_employee_master 
                                  WHERE emp_id_const = '".$CG_pdf_fetch['emp_id_const']."' ");
$empInfo = $empInfo[0];
//print_r($empInfo); exit;
$officer_name =$db->fetch_table(" SELECT * FROM intra_pri_master WHERE designation = '".$_SESSION['user_info']['stake_level_code']."' ");
$officer_desig =$db->fetch_table(" SELECT * FROM intra_pri_designation_master WHERE designation_code = '".$_SESSION['user_info']['stake_level_code']."' ");
//print_r($CG_pdf_fetch); exit;
if($CG_pdf_fetch['gp_id_fk'] != 0)
{
   $Query = "SELECT plmd.district_id_pk, plmd.district_name, plmb.block_name, plmg.gp_name from prd_location_master_gp as plmg 
             LEFT JOIN prd_location_master_block as plmb 
             ON plmg.block_id_fk = plmb.block_id_pk
             LEFT JOIN prd_location_master_district as plmd 
             ON plmb.district_id_fk = plmd.district_id_pk 
             WHERE plmg.gp_id_pk=".$CG_pdf_fetch['gp_id_fk'];
   $DistrictData = $db->fetch_table($Query);
   //print_r($DistrictData); exit;
   $DistrictData = $DistrictData[0];
   $to = 'The District Magistrate, '.strtoupper($DistrictData['district_name']);
   $pri_type = 'GP';
   $empDesign = $db->fetch_table("select code,description from prd_dise_code_master where code='".$CG_pdf_fetch['emp_desig']."'");
   $empDesign = $empDesign[0];
   $posting = $DistrictData['gp_name'].' Gram Panchayat, under '.$DistrictData['block_name'].' Panchayat Samiti, '.$DistrictData['district_name'].' District';
   //$empGroup = $db->fetch_table("select code,description from prd_dise_code_master where code='".$CG_pdf_fetch['emp_group']."'");
   $empGroup = $CG_pdf_fetch['incumbent_post'];
}
// echo "Please wait...";
//print_r($CG_pdf_fetch); 
//exit;
if($CG_pdf_fetch['ps_id_fk'] != 0)
{
   $Query = "SELECT plmd.district_id_pk, plmd.district_name, plmb.ps_name from prd_location_master_panchayat_samiti as plmb 
             LEFT JOIN prd_location_master_district as plmd 
             ON plmb.district_id_fk = plmd.district_id_pk 
             WHERE plmb.ps_id_pk=".$CG_pdf_fetch['ps_id_fk'];
   $DistrictData = $db->fetch_table($Query);
   //print_r($DistrictData); exit;
   $DistrictData = $DistrictData[0];
   $to = 'The District Magistrate, '.strtoupper($DistrictData['district_name']);
   $pri_type = 'PS';
   $empDesign = $db->fetch_table("select code,description from prd_dise_code_master where code='".$CG_pdf_fetch['emp_desig']."'");
   $empDesign = $empDesign[0];
   //print_r($empDesign); exit; 
   $posting = $DistrictData['ps_name'].' Panchayat Samiti, '.$DistrictData['district_name'].' District';
   //$empGroup = $db->fetch_table("select code,description from prd_dise_code_master where code='".$CG_pdf_fetch['emp_group']."'");
   $empGroup = $CG_pdf_fetch['incumbent_post'];
}
if($CG_pdf_fetch['zp_id_fk'] != 0 && $CG_pdf_fetch['ps_id_fk'] == 0)
{
   $Query = "SELECT district_name from prd_location_master_district 
             WHERE district_id_pk=".$CG_pdf_fetch['zp_id_fk'];
   $DistrictData = $db->fetch_table($Query);
   //print_r($DistrictData); exit;
   $DistrictData = $DistrictData[0];
   $to = 'The District Magistrate, '.strtoupper($DistrictData['district_name']);
   $pri_type = 'ZP';
   $empDesign = $db->fetch_table("select grade_code_fk as code,designation_name as description from zpemp_emp_desig_master where designation_id='".$CG_pdf_fetch['emp_desig']."'");
   $empDesign = $empDesign[0];

   $posting = $DistrictData['district_name'].' District';
   //$empGroup = $db->fetch_table("select code,description from prd_dise_code_master where code='".$CG_pdf_fetch['emp_group']."'");
   $empGroup = $CG_pdf_fetch['incumbent_post'];
}
$appNo = explode('/',$CG_pdf_fetch['application_id']);
//print_r($appNo); exit;
$orderNo = 'CG/'.($appNo[3] + $CG_pdf_fetch['cg_order_id_pk']).'/'.date('Y');
//print_r($CG_pdf_fetch); exit;
//var_dump($overage_pdf_fetch); die;	
 $presentDist = $db->fetch_table("SELECT district_name from prd_location_master_district WHERE district_id_pk =".$CG_pdf_fetch['present_district']);
 //print("SELECT dist_name from prd_location_master_district WHERE district_id_pk =".$CG_pdf_fetch['present_district']); exit;
 $presentDist = $presentDist[0];
 $emp_name = $CG_pdf_fetch['emp_name'];   
 $candidateAddress =   $CG_pdf_fetch['present_house_no']."".$CG_pdf_fetch['present_street']
                       .', Vill-'.$CG_pdf_fetch['present_town_vill']
                       .', P.O.-'.$CG_pdf_fetch['present_post_office']
                       .', P.S.-'.$CG_pdf_fetch['present_police_station']
                       .', DIST.-'.$presentDist['district_name']
                       .', PIN-'.$CG_pdf_fetch['present_pin'];
                       

 $pri_type = $pri_type;
 $cc_1_memo_no = $CG_pdf_fetch['memo_no_ec'];
 $emp_sex = $CG_pdf_fetch['applicant_sex'];
 
 if($emp_sex =='91'){ $he_she = 'he'; $his_her = 'his'; $shrismt = 'Shri ';} else if($emp_sex =='92'){ $he_she = 'she';  $his_her ='her'; $shrismt = 'Smt ';}
if($empInfo['emp_religion'] == 162)
{
   $shrismt = '';
}
//print_r($CG_pdf_fetch); exit;
 $relation = ($CG_pdf_fetch['relation_employee'] == 1)?'S/o':'W/o';

 $relation = ($CG_pdf_fetch['relation_employee'] == 3)?'D/o':$relation;

 //var_dump($he_she); die;
	    //------------------------------------------------------------------------------------------------------	
	    define("_MPDF_TEMP_PATH", '../../locker/temp/');
	    include ('../../includes/third-party/mpdf/mpdf.php');
	    $stylesheet ='';
	    $mpdf=new mPDF();
	     

	    $mpdf->WriteHTML($stylesheet,1);
		

	    //Header and footer
	    $mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
 
	    //PDF header
	    $mpdf->WriteHTML('
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        
</head>
<body>
<h1 align="center"><img src="img/Pnrd_logo.png" style="float:center;width:60pt;height:70pt"></h1>
<h2 align="center" style="margin:0em 0em 0em;">Government of West Bengal</h2>
<h4 align="center" style="margin:0em 0em 0em;">Panchayats & Rural Development Department</h4>
<h4 align="center" style="margin:0em 0em 0em;">Joint Administrative Building, HC-7, Sector – III, Bidhan Nagar, Kolkata-700106</h4>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td class="layout" width="370">Order No: '.$orderNo.'</td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>
<br>
<table style="font-size:10pt;">
<tr>
<td width="50">From</td>
<td> : </td>
<td>'.$officer_desig[0]['designation'].' to the Govt. of West Bengal, Panchayats & Rural Development Department</td>
</tr>
</table>
<table style="font-size:10pt;">
<tr>
<td width="50">To</td>
<td> : </td>
<td>'.$to.'</td>
</tr>
</table>
<table border="0" width="100%" style="border-collapse: collapse; margin:0pt 0pt 0pt 0pt; font-size:9pt;">
    <tr>
        <td width="50">Sub</td>
        <td> : </td>
        <td style="text-align:left;">Approval for appointment on compassionate ground in die-in-harness category in favour of  <span style="font-weight: bold;">'.$shrismt.$CG_pdf_fetch['incumbent'].',</span> '.$relation.' Late '.$emp_name.' , Ex - '.$empDesign['description'].' of  '.$posting.'</td>
    </tr>
</table>

<table style="font-size:10pt;margin:3pt 0pt 0pt 0pt;">
<tr>
<td width="50">Ref.</td>
<td> : </td>
<td>'.$CG_pdf_fetch['application_id'].'</td>
</tr>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:9pt;" > 
<tr>
<td align="justify">
           With reference to the above mentioned subject , I have been  directed  to inform that the competent authority of this Dept. 
           as per Para (5) of the  G.O. No. 251-Emp. Dt.03/12/2013 of  Labour Dept. Govt. of  West Bengal and this Dept.’s 
           Notification No. 4097/PN dt. 29.9.2014  has been pleased to accord approval in f/o  
           <span style="font-weight: bold;">'.$shrismt.$CG_pdf_fetch['incumbent'].',</span> '.$relation.' Late '.$emp_name.' , Ex - '.$empDesign['description'].' , of '.$posting.' for his appointment to the post of a '.$empGroup.' vacancy  under appropriate  Exempted Category  in  PRI set up  in your District, 
               according to availability of the same as per para ( 9) of the 251-Emp. Dt.03/12/2013 of Labour Department, 
               Govt. of West Bengal.
               <br> <br>
               You are ,therefore, requested to take necessary action for issuance of appointment  
               letter in f/o <span style="font-weight: bold;">'.$shrismt.$CG_pdf_fetch['incumbent'].',</span> '.$relation.' Late '.$emp_name.', '.$candidateAddress.', as aforesaid, after observing   due formalities  viz. 
               Police verification and Medical Examination of 
               '.$shrismt.$CG_pdf_fetch['applicant_name'].'.  
               Requirement of fund in this connection, if any, may be intimated to the department in due course.</td>
</tr>
</table>

<table style="margin-top:3%;font-size:8pt"> 
<tr>
<td width="20"></td>
<td align="center"> </td>
<td width="400"></td>
<td align="center">.......................................................................................</td>
</tr>
<tr>
<td width="20"></td>
<td align="center"><strong></strong></td>
<td width="400"></td>
<td align="center">'.$officer_desig[0]['designation'].' to the Govt. of West Bengal <br>
Panchayats & Rural Development Department</td>
</tr>
</table>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td class="layout" width="370"></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>

<table>
<tr>
<td>Copy forwarded for information and necessary action to:-</td>
</tr>
</table>
<ol style="font-size:12px">
<li>The Commissioner, P & R D, West Bengal.</li>
<li>P.S. to Hon’ble M.I.C of this Department</li>
<li>A.E.O., Zilla Parishad, '. ucfirst($DistrictData['district_name']).'</li> 
<li>The D. P. R. D. O., '. ucfirst($DistrictData['district_name']).' - He/She is requested to serve the copy of this order to '.$shrismt.$CG_pdf_fetch['incumbent'].'.</li>
<li>Sr. P.S. to Secretary of this Department.</li>
<li>Joint Secretary, PRI Cell of this Department.</li>
<li>Head Assistant, PRI cell of this Department.</li>
<li>'.$shrismt.$CG_pdf_fetch['incumbent'].' , '.$relation.' Late '.$emp_name.', '.$candidateAddress.'</li>
</ol>
<table style="margin:10pt 0pt 0pt 0pt;font-size:9pt"> 
<tr>
<td width="20"></td>
<td align="center"> </td>
<td width="400"></td>
<td align="center">.......................................................................................</td>
</tr>
<tr>
<td width="20"></td>
<td align="center"><strong></strong></td>
<td width="400"></td>
<td align="center">'.$officer_desig[0]['designation'].' to the Govt. of West Bengal <br>
Panchayats & Rural Development Department</td>
</tr>
</table>
</body>
</html>',2);


$mpdf->Output('abc'.'.pdf','I');


$mpdf->SetWatermarkImage('./img/biswa_bangla_1.png',1,'',array(10,65));
$mpdf->showWatermarkImage = true;
$mpdf->watermarkImageAlpha = 0.15;




?>