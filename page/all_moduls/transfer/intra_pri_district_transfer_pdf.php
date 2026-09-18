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
//require '../../page_visite.php';
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
//var_dump($_SESSION['user_info']['stake_level_code']); die;


	function fun_gp($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$gp = @$db->fetch_table("SELECT gp_name FROM prd_location_master_gp where gp_id_pk='".$val."'");
	return $gp[0]['gp_name'];
	}
	
	function fun_ps($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$ps = @$db->fetch_table("SELECT ps_name FROM prd_location_master_panchayat_samiti where ps_id_pk='".$val."'");
	return $ps[0]['ps_name'];
	}
	
	function fun_block($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$block = @$db->fetch_table("SELECT block_name FROM prd_location_master_block where block_id_pk='".$val."'");
	return $block[0]['block_name'];
	}
	
	function fun_district($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$district = @$db->fetch_table("SELECT district_name FROM prd_location_master_district where district_id_pk='".$val."'");
	return $district[0]['district_name'];
	}
$crypto = new cryptography();
//$cryptoGraph=new cryptography();
$db=new database();
$id = $crypto->decode($_GET['id'],4);
//var_dump($id); die;


$pdf_fetch_DT =$db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' ");
$officer_name =$db->fetch_table(" SELECT * FROM intra_pri_master WHERE designation = '".$_SESSION['user_info']['stake_level_code']."' ");
$officer_desig =$db->fetch_table(" SELECT * FROM intra_pri_designation_master WHERE designation_code = '".$_SESSION['user_info']['stake_level_code']."' ");
	   
//var_dump($overage_pdf_fetch); die;	
/*
 $emp_name = $pdf_fetch_DT[0]['emp_name'];   
 $present_posting = $pdf_fetch_DT[0]['present_posting'];   
 $new_posting = $pdf_fetch_DT[0]['new_posting'];   
 $district = $pdf_fetch_DT[0]['district'];   
 $emp_dob = $pdf_fetch_DT[0]['emp_dob'];   
 $category = $pdf_fetch_DT[0]['category'];
 $condon_days = $pdf_fetch_DT[0]['condon_days'];
 $upper_age_limit = $pdf_fetch_DT[0]['upper_age_limit'];
 $pri_type = $pdf_fetch_DT[0]['pri_type'];
 $cc_1_memo_no = $pdf_fetch_DT[0]['cc_1_memo_no'];
 $emp_sex = $pdf_fetch_DT[0]['emp_sex'];
 
 if($emp_sex =='91'){ $he_she = 'he'; $his_her = 'his';} else if($emp_sex =='92'){ $he_she = 'she';  $his_her ='her'; }
*/
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
<h1 align="center"><img src="img/Pnrd_logo.png" style="float:center;width:60pt;height:80pt"></h1>
<h2 align="center" style="margin:0em 0em 0em;">Government of West Bengal</h2>
<h4 align="center" style="margin:0em 0em 0em;">OFFICE OF THE DISTRICT MAGISTRATE & COLLECTOR, '.strtoupper(fun_district($pdf_fetch_DT[0]['pre_district'])).'</h4>
<h4 align="center" style="margin:0em 0em 0em;">P&RD Section</h4>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td width="370" class="layout"><strong>Memo No. - </strong></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>
<br>


<table style="font-size:10pt;margin:3pt 0pt 0pt 0pt;">
<tr>
<td width="50" align="justify">Order</td>
</tr>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
           The following PRI employees now posted at different Gram Panchayats/ Panchayat Samiti as detailed in column nos. 4&5 are hereby transferred and posted at Gram Panchayats/ Panchayat Samiti of this district as detailed in column nos. 6&7. </span></span></td>		   
</tr>
</table>
<table align="center" border="1" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:8pt;" > 
<tr>
<td width="5%" height="22" align="center" valign="middle"><div align="center"><strong><span class="style2">Sl. No.</span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name Of The Employees </span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">Designation </span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name of the Present Gram Panchayat</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name of the Present Block</span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">Transferred to Gram Panchayat</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">Transferred to Block</span></strong></div></td>
</tr>
<tr>
<td width="5%" height="22" align="center" valign="middle"><div align="center"><strong><span class="style2">(1)</span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">(2) </span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">(3) </span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">(4)</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">(5)</span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">(6)</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">(7)</span></strong></div></td>
</tr>',2);
$count = 1;
 foreach($pdf_fetch_DT as $key => $val){
$mpdf->WriteHTML('<tr>
	<td width="5%" height="19" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$count.'</span></strong></div></td>
	<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['emp_name'].'</span></strong></div></td>
	<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['designation'].'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['pre_gp'].'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['pre_block'].'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['new_gp'].'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['new_block'].'</span></strong></div></td>
</tr>',2);
$count++; } 
$mpdf->WriteHTML('
</table>

<table style="margin-top:4%;font-size:9pt"> 
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
<td align="center"><p>'.$officer_name[0]['officer_name'].' </p>
  <p>'.$officer_desig[0]['designation'].' to the Govt. of West Bengal <br>
    Panchayats & Rural Development Department</p></td>
</tr>
</table>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td width="370" class="layout"><strong>Memo No. - </strong></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>

<table>
<tr>
<td>Copy forwarded for information and necessary action to:</td>
</tr>
</table>
<ol style="font-size:14px">
<li>The Sub-Divisional Officer,___________________________ Sub-Division, '.$pdf_fetch_DT[0]['pre_district'].'</li>
<li>The Block Development Officer,________________________ Dev. Block, '.$pdf_fetch_DT[0]['pre_district'].'</li>
<li>The Treasury Officer,_______________________________ Treasury, '.$pdf_fetch_DT[0]['pre_district'].'</li>
<li>The Prodhan,_______________________ Gram Panchayat under __________________________ Dev. Block, '.$pdf_fetch_DT[0]['pre_district'].'</li>
<li>Sri. ______________________________ (__________________________) of ____________________________ GP under ___________________________Dev. Block for compliance.</li>
</ol>
<table style="margin-top:14%;font-size:9pt"> 
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


$mpdf->Output('order_district_transfer'.'.pdf','I');


$mpdf->SetWatermarkImage('./img/biswa_bangla_1.png',1,'',array(10,65));
$mpdf->showWatermarkImage = true;
$mpdf->watermarkImageAlpha = 0.15;




?>