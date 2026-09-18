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


	function fun_desig($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$desig = @$db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."'");
	return $desig[0]['description'];
	}
	
	function fun_desig_zp($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$desig = @$db->fetch_table("SELECT designation_name FROM zpemp_emp_desig_master where designation_id='".$val."'");
	return $desig[0]['designation_name'];
	}
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
	//echo ("SELECT ps_name FROM prd_location_master_panchayat_samiti where ps_id_pk='".$val."'");die;
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

//echo (" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' "); die;
$pdf_fetch_DT =$db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='5' and district_level='D' ");

//echo $pdf_fetch_DT[0]['pre_district'];
//echo $pdf_fetch_DT[1]['pre_ps_id_fk']; die;
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
 
 
  $memo=rand(000,999).'/'.date('Y').'/'.'Inter District Transfer';
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
<h3 align="center" style="margin:0em 0em 0em;">Directorate Of Panchayats & Rural Development</h3>
<h3 align="center" style="margin:0em 0em 0em;">18/9 DD Block, Sector-I, Salt Lake</h3>
<h3 align="center" style="margin:0em 0em 0em;">Mrittika Bhavan (5th & 7th Floor)</h3>
<h3 align="center" style="margin:0em 0em 0em;">Kolkata-700 064</h4>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td width="370" class="layout"><strong>Memo No. - '.$memo.'</strong></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>
<br>


<table width="100%" style="font-size:10pt;margin:3pt 0pt 0pt 0pt;">
<tr>
	<td width="50" align="justify">To</td>
</tr>',2);



$db=new database();
		$check =$db->fetch_table("select distinct(d.district_id_pk) from prd_location_master_district as d
inner join intra_pri_district_transfer as t on cast(t.pre_district_id_fk as text)=cast(d.district_id_pk as text) or cast(t.transfer_district_id_fk as text)=cast(d.district_id_pk as text) WHERE t.application_id = '".$id."' and t.district_level='D'");

foreach($check as  $val_district){
	$db=new database();
	
	//echo 11; die;
	/*$check_block =$db->fetch_table("select block_id_pk from prd_location_master_block  WHERE block_id_pk = '".$val_block['block_id_pk']."'");*/

	$mpdf->WriteHTML('<tr><td>The District Magistrate & Executive Officer, '.fun_district($val_district['district_id_pk']).' Zilla Parishad</td>
<tr></table>',2);
}


 $mpdf->WriteHTML(

'<table width="100%" style="font-size:10pt;margin:3pt 0pt 0pt 0pt;">
<tr>
	<td width="150"></td>
	<td>Sub: Inter-District Transfer.</td>
</tr>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td>Sir,</td>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I am directed to inform that the proposal for District transfer of the following employees,
have been approved. Necessary order of posting of the employees concerned in a specific
Gram Panchayat/Panchayat Samity/Zilla parishad  in your district may kindly be issued.
&nbsp;&nbsp;
</td>
</table>
<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The concerned employees will lose their seniority relating to past service and name of the employees shall]
be entered in the gradation list of the new district on their joining thereto. Details are as follows :

</td>		   
</tr>
</table>
<table align="center" border="1" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:8pt;" > 


<thead>
<tr>
<th rowspan="2" height="22" align="center" valign="middle"><div align="center"><strong><span class="style2">Sl. No.</span></strong></div></th>
<th rowspan="2" align="center" valign="middle"><div align="center"><strong><span class="style2">Name Of The Employees </span></strong></div></th>
<th rowspan="2" align="center" valign="middle"><div align="center"><strong><span class="style2">Designation </span></strong></div></th>
<th colspan="3" align="center" valign="middle"><div align="center"><strong><span class="style2">Present Place of Posting </span></strong></div></th>
<th align="center" valign="middle"><div align="center"><strong><span class="style2">Place where transferred</span></strong></div></th>
</tr>

</thead>
<tbody>
	<tr>
		<td align="center">District</td>
		<td align="center">Block</td>
		<td align="center">Gram Panchayat</td>
		<td align="center">District</td>
	</tr>
</tbody>





',2);
$count = 1;
 foreach($pdf_fetch_DT as  $val){
	 
	// echo $val['pre_ps_id_fk']; 
	 
	 
	 
	 
	 
$mpdf->WriteHTML('<tr>
	<td width="5%" height="19" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$count.'</span></strong></div></td>
	<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].'</span></strong></div></td>',2);
	
	if($val['pre_gp_id_fk']=='0' && $val['pre_ps_id_fk']=='0' )
	{
	
	$mpdf->WriteHTML('<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_desig_zp($val['desig']).'</span></strong></div></td>',2);
	}
	else
	{
		
		$mpdf->WriteHTML('<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_desig($val['desig']).'</span></strong></div></td>',2);
	}
	
	if($val['pre_gp_id_fk']!='0')
	{
	
	$mpdf->WriteHTML('
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['pre_district_id_fk']).'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_block($val['pre_block_id_fk']).'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_gp($val['transfer_gp_id_fk']).'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['transfer_district_id_fk']).'</span></strong></div></td>
</tr>',2);
	

	}
	else if($val['pre_ps_id_fk']!='0')
	{
		
		$mpdf->WriteHTML('
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['pre_district_id_fk']).'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_ps($val['pre_ps_id_fk']).'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2"></span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['transfer_district_id_fk']).'</span></strong></div></td>
</tr>',2);
		
		
		
		
	}
	else
	{
		$mpdf->WriteHTML('
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['pre_district_id_fk']).' ZP </span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2"></span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2"></span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_district($val['transfer_district_id_fk']).'</span></strong></div></td>
</tr>',2);
	}
$count++; } 

//die;
$mpdf->WriteHTML('
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This transfer has been approved on the strength of recomendation received from concerned District Magistrate. He will be released from his respective district on submission of up to date asset statements and subject to the co ordination that no Departmental Proceedings/ Vigilance Case / Criminal Case is pending against him.
&nbsp;&nbsp;
</td>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This approval stands even if any inter block transfer is effected in the mean time for any reason whatsover.
&nbsp;&nbsp;
</td>
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
    <td width="370" class="layout"><strong>Memo No. - '.$memo.'</strong></td>
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
<li>The District Panchayat & Rural Development Officer,'.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).' with a request to ensure that all norms are folled.</li>',2);

 foreach($pdf_fetch_DT as  $val){


if($val['pre_gp_id_fk']!='' && $val['pre_ps_id_fk']=='0')
	{
		
$mpdf->WriteHTML('<li>The Block Development Officer,________________________ Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);

	}
	else if($val['pre_ps_id_fk']!='')
	{
	
$mpdf->WriteHTML('<li>The Executive officer,___________________________ Panchay Samiti, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);

	}
 }
 
 
 
 $pdf_fetch_DT =$db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='5' and district_level='D' ");

 foreach($pdf_fetch_DT as  $val){
	 
	 if($val['pre_gp_id_fk']!='0' )
	{

$mpdf->WriteHTML('<li>Sri. '.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].' of '.fun_gp($val['pre_gp_id_fk']).' GP under '.fun_block($val['pre_block_id_fk']).' Dev. Block for compliance</li>',2);

	}
	else if($val['pre_ps_id_fk']!='0')
	{
		$mpdf->WriteHTML('<li>Sri. '.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].' of '.fun_ps($val['pre_ps_id_fk']).' Panchayat Samity for compliance</li>',2);

	}
	else
	{
		$mpdf->WriteHTML('<li>Sri. '.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].' of '.fun_district($val['pre_district_id_fk']).' Zill Parishad for compliance</li>',2);
	}
 }


$mpdf->WriteHTML('
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